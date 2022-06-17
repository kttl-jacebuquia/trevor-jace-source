<?php

namespace TrevorWP\Theme\Ajax;

use TrevorWP\Classy\Content;

/**
 * Classy API
 */
class Classy {


	public static function construct() {
		/**
		 * Ajax for Lever
		 */
		add_action( 'wp_ajax_nopriv_classy', array( self::class, 'classy' ) );
		add_action( 'wp_ajax_classy', array( self::class, 'classy' ) );
	}

	/**
	 * Retrives campaign data from Classy API
	 */
	public static function classy() {
		$data = array();

		switch ( $_GET['type'] ) {
			case 'events':
				$data = Content::get_events_data();
				break;
		}

		wp_die(
			json_encode(
				array(
					'status' => 'SUCCESS',
					'data'   => $data,
				)
			),
			200
		);
	}
}
