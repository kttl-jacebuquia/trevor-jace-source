<?php namespace TrevorWP\Jobs;

use TrevorWP\Main;
use TrevorWP\Theme\ACF\Options_Page\Site_Banners;
use TrevorWP\Util\Log;

class Long_Wait {
	/**
	 * @return bool|null
	 */
	public static function update(): ?bool {
		$url  = Site_Banners::get_option( Site_Banners::FIELD_LONG_WAIT_URL );
		$resp = wp_remote_get( $url );

		if ( 200 !== $resp['response']['code'] ) {
			Log::alert( 'Counselor wait endpoint returned an error.', compact( 'resp' ) );

			return null;
		}

		$resp = json_decode( $resp['body'], true );
		if ( json_last_error() ) {
			// JSON error
			Log::alert(
				'Counselor wait response JSON decode error.',
				array(
					'error' => json_last_error_msg(),
					'body'  => $resp['body'],
				)
			);

			return null;
		}

		update_option( Main::OPTION_KEY_COUNSELOR_LONG_WAIT, $val = empty( $resp['isLongWait'] ) ? 0 : 1 );

		return boolval( $val );
	}
}
