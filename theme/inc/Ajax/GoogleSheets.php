<?php namespace TrevorWP\Theme\Ajax;

use TrevorWP\Theme\ACF\Options_Page\API_Keys;

/**
 * Google Sheets API
 */
class GoogleSheets {

	public static function construct() {
		/**
		 * Ajax for google_sheets
		 */
		add_action( 'wp_ajax_nopriv_google_sheets', array( self::class, 'google_sheets' ) );
		add_action( 'wp_ajax_google_sheets', array( self::class, 'google_sheets' ) );
	}

	public static function google_sheets( $a ) {
		$range           = $_GET['range'];
		$api_key         = API_Keys::get_option( API_Keys::FIELD_GOOGLE_SHEETS_API_KEY );
		$spreadsheet_key = API_Keys::get_option( API_Keys::FIELD_GOOGLE_SHEETS_SHEET_KEY );

		$url = implode(
			'',
			array(
				'https://sheets.googleapis.com/v4/spreadsheets/',
				$spreadsheet_key,
				"/values/'",
				$range,
				"'/?alt=json&majorDimension=COLUMNS&valueRenderOption=UNFORMATTED_VALUE&dateTimeRenderOption=FORMATTED_STRING&key=",
				$api_key,
			),
		);

		$headers = array(
			'origin: https://cdpn.io',
			'referer: https://cdpn.io/',
		);

		if ( empty( $range ) ) {
			wp_die( json_encode( array( 'status' => 'MISSING_RANGE' ) ), 400 );
		}

		# Create advocate
		$response = self::curl( $url, $headers );
		$response = json_decode( $response, true );

		if ( ! empty( $response['error'] ) ) {
			wp_die( json_encode( array( 'status' => $response['error'] ) ), 400 );
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
		$response = curl_exec( $ch );
		curl_close( $ch );

		return $response;
	}
}
