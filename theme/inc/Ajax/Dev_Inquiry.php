<?php namespace TrevorWP\Theme\Ajax;

/**
 * Dev Inquiry AJAX Endpoint
 */
class Dev_Inquiry {
	const ACTION = 'dev_inquiry';

	public static function construct() {
		/**
		 * Ajax for dev inquiry
		 */
		add_action( 'wp_ajax_nopriv_' . static::ACTION, array( self::class, static::ACTION ) );
		add_action( 'wp_ajax_' . static::ACTION, array( self::class, static::ACTION ) );
	}

	public static function dev_inquiry() {
		$url = 'https://trevor.tfaforms.net/rest/responses/processor';

		$headers = array(
			'content-type' => 'x-www-form-urlencoded',
		);

		# Send form contents
		list( $info, $response ) = self::curl( $url, $headers, http_build_query( $_POST ) );
		$response                = wp_strip_all_tags( $response, true );

		if ( 200 !== $info['http_code'] ) {
			wp_die( json_encode( array( 'status' => $response ) ), 400 );
		}

		wp_die(
			json_encode(
				array(
					'status' => 'SUCCESS',
					'data'   => $response,
				),
			),
			200,
		);
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
		curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
		curl_setopt( $ch, CURLOPT_POST, 1 );
		$response = curl_exec( $ch );
		$info     = curl_getinfo( $ch );
		curl_close( $ch );

		return array(
			$info,
			$response,
		);
	}
}
