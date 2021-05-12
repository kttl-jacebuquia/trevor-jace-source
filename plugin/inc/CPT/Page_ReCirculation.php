<?php namespace TrevorWP\CPT;

use TrevorWP\Main;

class Page_ReCirculation {
	const POST_TYPE = Main::POST_TYPE_PREFIX . 'page_recirc';

	/**
	 * @see \TrevorWP\Util\Hooks::register_all()
	 */
	public static function construct(): void {
		add_action( 'init', array( self::class, 'init' ), 10, 0 );
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
				'supports'            => array(
					'custom-fields',
				),
				'labels'              => array(
					'name'          => 'Page ReCirculation Cards',
					'singular_name' => 'Page ReCirculation Card',
				),
			)
		);
	}
}
