<?php namespace TrevorWP\CPT;

use TrevorWP\Main;

class Financial_Report {
	const POST_TYPE = Main::POST_TYPE_PREFIX . 'fin_report';

	/**
	 * @see \TrevorWP\Util\Hooks::register_all()
	 */
	public static function construct(): void {
		add_action( 'init', array( self::class, 'init' ), 10, 0 );
		add_action( 'template_redirect', array( self::class, 'template_redirect' ), 10, 0 );
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
				'publicly_queryable'  => true,
				'show_ui'             => true,
				'show_in_rest'        => false,
				'has_archive'         => true,
				'rewrite'             => array(
					'slug'       => 'financial-reports',
					'with_front' => false,
				),
				'supports'            => array(
					'title',
					'custom-fields',
				),
				'labels'              => array(
					'name'          => 'Financial Reports',
					'singular_name' => 'Financial Report',
				),
			)
		);
	}

	/**
	 * Fires before determining which template to load.
	 *
	 * @link https://developer.wordpress.org/reference/hooks/template_redirect/
	 */
	public static function template_redirect(): void {
		if ( is_singular( static::POST_TYPE ) ) {
			wp_redirect( home_url(), 301 );
			exit;
		}
	}
}
