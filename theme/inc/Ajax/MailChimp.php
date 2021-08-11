<?php namespace TrevorWP\Theme\Ajax;

use TrevorWP\Theme\ACF\Options_Page\API_Keys;

/**
 * MailChimp API
 */
class MailChimp {

	public static function construct() {
		/**
		 * Ajax for MailChimp
		 */
		add_action( 'wp_ajax_nopriv_mailchimp', array( self::class, 'mailchimp' ) );
		add_action( 'wp_ajax_mailchimp', array( self::class, 'mailchimp' ) );
	}

	public static function mailchimp() {
		$api_settings = API_Keys::get_mailchimp();

		if ( empty( $_POST['email'] ) ) {
			wp_die( json_encode( array( 'status' => 'MISSING_EMAIL' ) ), 400 );
		}

		if ( ! filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ) {
			wp_die( json_encode( array( 'status' => 'INVALID_EMAIL' ) ), 400 );
		}

		$params = $_POST;

		/*
			MailChimp: https://mailchimp.com/developer/marketing/api/
			Add Member to List reference: https://${dc}.api.mailchimp.com/3.0/lists/{list_id}/members

			Postman:
			POST: https://${dc}.api.mailchimp.com/3.0/lists/{list_id}/members
		*/
		$url = "https://{$api_settings['data_center']}.api.mailchimp.com/3.0/lists/{$api_settings['list_id']}/members";

		$headers = array(
			'Authorization: Basic ' . base64_encode( "user_id:{$api_settings['api_key']}" ),
			'Content-type: application/json',
		);

		$payload = array(
			'email_address' => $params['email'],
			'status'        => 'subscribed',
		);

		# Add member to list
		$response = self::curl( $url, $headers, json_encode( $payload ) );
		$response = json_decode( $response, true );

		if ( ! empty( $response['status'] && in_array($response['status'], array( 400, 401, 403, 429, 500 )) ) ) {
			wp_die( json_encode( array( 'status' => $response['detail'] ) ), 400 );
		}

		wp_die( json_encode( array( 'status' => 'SUCCESS' ) ), 200 );
	}

	/**
	 * Perform a curl
	 *
	 * @param string $url
	 * @param array $headers
	 * @param array $payload
	 *
	 * @return string $response
	 */
	public static function curl( $url, $headers, $payload = array() ) {
		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $url );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
		curl_setopt( $ch, CURLOPT_POST, 1 );
		$response = curl_exec( $ch );
		curl_close( $ch );

		return $response;
	}
}
