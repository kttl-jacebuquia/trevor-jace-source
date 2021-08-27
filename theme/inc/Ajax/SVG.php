<?php namespace TrevorWP\Theme\Ajax;

/**
 * SVG API
 */
class SVG {

	public static function construct() {
		/**
		 * Ajax for SVG
		 */
		add_action( 'wp_ajax_svg', array( self::class, 'svg' ) );
	}

	public static function svg() {
		$url           = '';
		$attachment_id = isset( $_REQUEST['attachmentID'] ) ? $_REQUEST['attachmentID'] : '';

		if ( ! empty( $attachment_id ) ) {
			$url = wp_get_attachment_url( $attachment_id );
		}

		echo $url;
		wp_die();
	}
}
