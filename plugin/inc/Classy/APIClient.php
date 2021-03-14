<?php namespace TrevorWP\Classy;

use TrevorWP\Main;
use TrevorWP\Util\Log;

/**
 * Classy APIClient
 *
 * @link https://github.com/classy-org/classy-org-wp/blob/master/ClassyAPIClient.php
 */
class APIClient {
	const CLASSY_API_BASEURL = 'https://api.classy.org';
	const CLASSY_API_VERSION = '2.0';

	/**
	 * @var APIClient
	 */
	public static $instance;

	/**
	 * @var string
	 */
	private $client_id;

	/**
	 * @var string
	 */
	private $client_secret;

	/**
	 * @return array [$client_id, $client_secret]
	 */
	public static function get_credentials(): array {
		return [
			get_option( Main::OPTION_KEY_CLASSY_CLIENT_ID, '' ),
			get_option( Main::OPTION_KEY_CLASSY_CLIENT_SECRET, '' ),
		];
	}

	/**
	 * @param string $client_id
	 * @param string $client_secret
	 */
	public static function set_credentials( string $client_id, string $client_secret ): void {
		update_option( Main::OPTION_KEY_CLASSY_CLIENT_ID, $client_id, false );
		update_option( Main::OPTION_KEY_CLASSY_CLIENT_SECRET, $client_secret, false );
	}

	/**
	 * Private constructor, use factory.
	 *
	 * @param $client_id string API client ID
	 * @param $client_secret string API client secret
	 */
	public function __construct( string $client_id, string $client_secret ) {
		$this->client_id     = $client_id;
		$this->client_secret = $client_secret;
	}

	/**
	 * Factory method for API singleton.
	 *
	 * @return APIClient
	 */
	public static function get_instance(): ?APIClient {
		if ( empty( self::$instance ) ) {
			list( $client_id, $client_secret ) = self::get_credentials();

			if ( empty( $client_id ) || empty( $client_secret ) ) {
				Log::alert( 'Classy API credentials are missing.' );

				return null;
			}

			self::$instance = new APIClient( $client_id, $client_secret );
		}

		return self::$instance;
	}

	/**
	 * Create an access token using key and secret.
	 *
	 * @param bool $use_cache
	 *
	 * @return mixed
	 */
	public function get_access_token( bool $use_cache = true ): ?string {
		$cache_key = Main::CACHE_GROUP_PREFIX . 'ACCESS_TOKEN_' . $this->client_id;
		$token     = $use_cache ? get_transient( $cache_key ) : null;

		if ( empty( $token ) ) {
			$result = wp_remote_post(
				$url = 'https://api.classy.org/oauth2/auth?' . http_build_query( [
						'client_id'     => $this->client_id,
						'client_secret' => $this->client_secret,
						'grant_type'    => 'client_credentials'
					] )
			);

			if ( is_wp_error( $result ) ) {
				if ( $use_cache ) {
					delete_transient( $cache_key );
				}
				$token = null;
				// TODO: LOG
			} else {
				$token = json_decode( $result['body'], true );
				if ( $use_cache ) {
					set_transient( $cache_key, $token['access_token'], ( $token['expires_in'] - 60 ) );
				}
				$token = $token['access_token'];
				// TODO: LOG
			}
		}

		return $token;
	}

	/**
	 * Make an API request to Classy API.
	 *
	 * @param string $url Endpoint URL (e.g. /campaigns/999999)
	 * @param string $method HTTP Method (e.g. 'GET', 'POST', 'PUT')
	 * @param array $params URL Parameters to be sent as '?key1=value1&key2=value2'
	 *
	 * @return string Content response
	 */
	public function request( string $url, $method = 'GET', $params = [] ): ?string {
		$url = self::CLASSY_API_BASEURL
		       . '/' . self::CLASSY_API_VERSION
		       . '/' . $url
		       . '?' . http_build_query( $params );

		$token = $this->get_access_token();

		$options = [
			'http' => [
				'method' => $method,
				'header' => "Content-Type: text/plain\r\n"
				            . "Authorization: Bearer $token\r\n"
			]
		];

		$context = stream_context_create( $options );

		return @file_get_contents( $url, false, $context );
	}
}
