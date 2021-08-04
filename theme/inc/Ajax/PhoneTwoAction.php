<?php namespace TrevorWP\Theme\Ajax;

/**
 * Phone2Action API
 */
class PhoneTwoAction {

	public static function construct() {
		/**
		 * Ajax for Phone2Action
		 */
		add_action( 'wp_ajax_nopriv_phone2action', array( self::class, 'phone_two_action' ) );
		add_action( 'wp_ajax_phone2action', array( self::class, 'phone_two_action' ) );
	}

	public static function phone_two_action() {
		$api_settings = array(
			'app_id'      => 'PxnnCt3eVLxht+4c27vWn6uJLDbxP6TME',
			'api_key'     => 'HJnyHDKWG-6Lhs94aitnZoEqvR8pyrYzn',
			'campaign_id' => '26523',
		);

		if ( empty( $_POST['fullname'] ) ) {
			wp_die( json_encode( array( 'status' => 'MISSING_FULLNAME' ) ), 400 );
		}

		if ( empty( $_POST['email'] ) ) {
			wp_die( json_encode( array( 'status' => 'MISSING_EMAIL' ) ), 400 );
		}

		if ( empty( $_POST['zipcode'] ) ) {
			wp_die( json_encode( array( 'status' => 'MISSING_ZIPCODE' ) ), 400 );
		}

		if ( ! empty( $_POST['sms_notif'] ) && empty( $_POST['phone'] ) ) {
			wp_die( json_encode( array( 'status' => 'MISSING_PHONE_NUMBER' ) ), 400 );
		}

		$params      = $_POST;
		$sms_notif   = 0;
		$email_notif = 0;

		# Get firstname & lastname
		$name = explode( ' ', $params['fullname'] );

		$lastname = end( $name );
		array_pop( $name );
		$firstname = implode( ' ', $name );

		if ( '1' === $params['sms_notif'] ) {
			$sms_notif = 1;
		}

		if ( '1' === $params['email_notif'] ) {
			$email_notif = 1;
		}

		/*
			Phone2Action: https://docs.phone2action.com/
			Create an Advocate api reference: https://api.phone2action.com/2.0/advocates

			Postman:
			POST: https://api.phone2action.com/2.0/advocates
		*/
		$url = 'https://api.phone2action.com/2.0/advocates';

		$headers = array(
			'Authorization: Basic ' . base64_encode( "{$api_settings['app_id']}:{$api_settings['api_key']}" ),
			'Content-type: application/json',
		);

		$payload = array(
			'campaigns'         => array(
				$api_settings['campaign_id'],
			),
			'firstname'         => $firstname,
			'lastname'          => $lastname,
			'email'             => $params['email'],
			'phone'             => $params['phone'],
			'zip5'              => $params['zipcode'],
			'smsOptin'          => $sms_notif,
			'smsOptinConfirmed' => $sms_notif,
			'emailOptin'        => $email_notif,
		);

		# Create advocate
		$response = self::curl( $url, $headers, json_encode( $payload ) );
		$response = json_decode( $response, true );

		if ( ! empty( $response['error'] ) ) {
			wp_die( json_encode( array( 'status' => $response['error'] ) ), 400 );
		}

		wp_die( json_encode( array( 'status' => 'SUCCESS' ) ), 400 );
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
