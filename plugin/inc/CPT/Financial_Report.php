<?php namespace TrevorWP\CPT;

use TrevorWP\Main;

class Financial_Report {
	const POST_TYPE = Main::POST_TYPE_PREFIX . 'fin_report';

	/**
	 * @see \TrevorWP\Util\Hooks::register_all()
	 */
	public static function construct(): void {
		add_action( 'init', [ self::class, 'init' ], 10, 0 );
	}

	/**
	 * Fires after WordPress has finished loading but before any headers are sent.
	 *
	 * @link https://developer.wordpress.org/reference/hooks/init/
	 */
	public static function init(): void {
		register_post_type( self::POST_TYPE, [
			'public'              => true,
			'hierarchical'        => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => true,
			'show_ui'             => true,
			'show_in_rest'        => true,
			'has_archive'         => true,
			'rewrite'             => [
				'slug'       => 'financial-reports',
				'with_front' => false,
			],
			'supports'            => [
				'title',
				'custom-fields',
			],
			'labels'              => [
				'name'          => 'Financial Reports',
				'singular_name' => 'Financial Report',
			],
		] );
	}
}
