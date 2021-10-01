<?php namespace TrevorWP\CPT;

use TrevorWP\Main;

class Promo_Popup {
	const POST_TYPE = Main::POST_TYPE_PREFIX . 'promo_popup';

	/**
	 * @see \TrevorWP\Util\Hooks::register_all()
	 */
	public static function construct(): void {
		add_action( 'init', array( self::class, 'init' ), 1, 0 );
	}

	/**
	 * Fires after WordPress has finished loading but before any headers are sent.
	 *
	 * @link https://developer.wordpress.org/reference/hooks/init/
	 */
	public static function init(): void {
		register_post_type(
			self::POST_TYPE,
			array(
				'public'              => false,
				'hierarchical'        => false,
				'exclude_from_search' => true,
				'publicly_queryable'  => false,
				'show_ui'             => true,
				'show_in_rest'        => false,
				'has_archive'         => false,
				'capability_type'     => 'post',
				'supports'            => array(
					'title',
					'custom-fields',
				),
				'labels'              => array(
					'name'          => 'Promo Popups',
					'singular_name' => 'Promo Popup',
				),
				'menu_icon'           => 'dashicons-layout',
			)
		);
	}
}
