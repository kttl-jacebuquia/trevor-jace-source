<?php namespace TrevorWP\Classy;

use TrevorWP\Main;

/**
 * Classy APIClient
 *
 * @link https://github.com/classy-org/classy-org-wp/blob/master/ClassyAPIClient.php
 */
class APIClient {
	const CLASSY_API_BASEURL = 'https://api.classy.org';
	const CLASSY_API_VERSION = '2.0';

	private static $instance;
	private $clientId;
	private $clientSecret;

	/**
	 * Private constructor, use factory.
	 *
	 * @param $clientId string API client ID
	 * @param $clientSecret string API client secret
	 */
	private function __construct( string $clientId, string $clientSecret ) {
		$this->clientId     = $clientId;
		$this->clientSecret = $clientSecret;
	}

	/**
	 * Factory method for API singleton.
	 *
	 * @param $clientId string API client ID
	 * @param $clientSecret string API client secret
	 *
	 * @return APIClient
	 */
	public static function getInstance( string $clientId, string $clientSecret ): APIClient {
		if ( ( self::$instance instanceof APIClient ) === false ) {
			self::$instance = new APIClient( $clientId, $clientSecret );
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
	public function getAccessToken( bool $use_cache = true ) {
		$cacheKey = Main::CACHE_GROUP_PREFIX . 'ACCESS_TOKEN_' . $this->clientId;
		$token    = $use_cache ? get_transient( $cacheKey ) : null;

		if ( empty( $token ) ) {
			$result = wp_remote_post(
				$url = 'https://api.classy.org/oauth2/auth?' . http_build_query( [
						'client_id'     => $this->clientId,
						'client_secret' => $this->clientSecret,
						'grant_type'    => 'client_credentials'
					] )
			);

			if ( is_wp_error( $result ) ) {
				if ( $use_cache ) {
					delete_transient( $cacheKey );
				}
				$token = false;
			} else {
				$token = json_decode( $result['body'], true );
				if ( $use_cache ) {
					set_transient( $cacheKey, $token['access_token'], ( $token['expires_in'] - 60 ) );
				}
				$token = $token['access_token'];
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
	public function request( string $url, $method = 'GET', $params = array() ) {
		$url = self::CLASSY_API_BASEURL
		       . '/' . self::CLASSY_API_VERSION
		       . '/' . $url
		       . '?' . http_build_query( $params );

		$token = $this->getAccessToken();

		$options = array(
			'http' => array(
				'method' => $method,
				'header' => "Content-Type: text/plain\r\n"
				            . "Authorization: Bearer $token\r\n"
			)
		);

		$context  = stream_context_create( $options );
		$response = file_get_contents( $url, false, $context );

		// FIXME: Error handle

		return $response;
	}
}
