<?php namespace TrevorWP\Util;

use TrevorWP\Admin;
use TrevorWP\Options;
use TrevorWP\Exception;
use TrevorWP\Exception\Unauthorized;

class Google_API {
	/**
	 * @var \Google_Client
	 */
	protected static $_client;

	/**
	 * Returns the path of the Google OAuth configuration file.
	 *
	 * @return string
	 * @throws Exception\Internal If the config file is missing.
	 */
	public static function get_config_file(): string {
		$env = Tools::get_env();

		if ( $env == 'test' ) {
			$env = 'dev';
		}

		$name = "g_oauth-{$env}.json";
		$path = path_join( TREVOR_PRIVATE_DATA_DIR, $name );
		if ( ! file_exists( $path ) ) {
			throw new Exception\Internal( 'Google OAuth Client Id file is missing.' );
		}

		return $path;
	}

	/**
	 * @return string
	 */
	public static function get_return_url(): string {
		return admin_url( 'admin-ajax.php?action=trevor-g-return' );
	}

	/**
	 * @return false|mixed|void
	 */
	public static function get_token() {
		return get_option( Options\Google::KEY_ACCESS_KEY );
	}

	/**
	 * @return bool
	 */
	public static function has_token(): bool {
		$token = self::get_token();

		return is_array( $token ) && ! empty( $token );
	}

	/**
	 * @param bool $validate_token
	 *
	 * @return \Google_Client
	 * @throws Exception\Internal
	 * @throws \Google_Exception
	 */
	public static function get_client( $validate_token = true ): \Google_Client {
		if ( empty( self::$_client ) ) {
			$has_token = self::has_token();
			if ( ! $has_token && $validate_token ) {
				throw new Exception\Internal( 'Google access token is missing.' );
			}

			self::$_client = new \Google_Client();
			self::$_client->setAuthConfig( self::get_config_file() );
			self::$_client->addScope( \Google_Service_Analytics::ANALYTICS_READONLY );

			if ( $has_token ) {
				self::$_client->setAccessToken( self::get_token() );
			}

			# Renew if necessary
			if ( $validate_token && $has_token && self::$_client->isAccessTokenExpired() ) {
				self::set_token( self::$_client->fetchAccessTokenWithRefreshToken(), false );
				Log::info( 'Google access token is updated using the refresh token.' );
			}
		}

		return self::$_client;
	}

	/**
	 * @param array $access_token
	 * @param bool $log_audit
	 *
	 * @return bool
	 * @throws Exception\Internal
	 */
	public static function set_token( array $access_token, bool $log_audit = true ): bool {
		$result = update_option( Options\Google::KEY_ACCESS_KEY, $access_token, false );

		if ( ! $result ) {
			throw new Exception\Internal( 'Could not set the google access token.' );
		}

		# Clear cache
		if ( $has_client = ! is_null( self::$_client ) ) {
			self::$_client = null;
			self::get_client( true );
		}

		# Audit log
		if ( $log_audit ) {
			Log::audit( 'Google access token is set.' );
		}

		return $result;
	}

	/**
	 * @throws Exception\Internal
	 * @throws Unauthorized
	 * @throws \Google_Exception
	 *
	 * @link https://developer.wordpress.org/reference/hooks/wp_ajax_action/
	 * @see Hooks::register_all()
	 */
	public static function handle_return_page(): void {
		if ( ! current_user_can( 'update_core' ) ) {
			throw new Unauthorized();
		}

		$client = Google_API::get_client( false );

		# Redirect URL
		$client->setRedirectUri( Google_API::get_return_url() );

		# Force to get refresh token
		$client->setAccessType( 'offline' );
		$client->setApprovalPrompt( 'force' );

		# Redirect to google
		if ( empty( $_GET['code'] ) ) {
			wp_redirect( $client->createAuthUrl() );
			die();
		}

		$token = $client->fetchAccessTokenWithAuthCode( $_GET['code'] );
		Google_API::set_token( $token, true );

		$page_slug = Admin\Google::MENU_SLUG;
		wp_redirect( admin_url( "admin.php?page={$page_slug}&auth_success=1" ) );
		die();
	}
}
