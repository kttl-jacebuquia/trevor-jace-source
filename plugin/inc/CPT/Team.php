<?php namespace TrevorWP\CPT;


use TrevorWP\Main;

class Team {
	const POST_TYPE = Main::POST_TYPE_PREFIX . 'team';

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
			'public'              => false,
			'hierarchical'        => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'show_ui'             => true,
			'show_in_rest'        => true,
			'supports'            => [
				'title',
				'editor',
				'thumbnail',
			],
			'has_archive'         => false,
			'rewrite'             => false,
		] );
	}
}
