<?php namespace TrevorWP\CPT;

use TrevorWP\Main;
use TrevorWP\Util\Tools;

class Team {
	const POST_TYPE = Main::POST_TYPE_PREFIX . 'team';

	const TAXONOMY_GROUP = self::POST_TYPE . '_group';

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
			'has_archive'         => false,
			'rewrite'             => false,
			'supports'            => [
				'title',
				'editor',
				'thumbnail',
				'custom-fields',
			],
			'labels'              => [
				'name'          => 'Team',
				'singular_name' => 'Team Member',
			],
		] );

		# Taxonomies
		register_taxonomy( self::TAXONOMY_GROUP, [ self::POST_TYPE ], [
			'public'            => false,
			'hierarchical'      => false,
			'show_ui'           => true,
			'show_in_rest'      => true,
			'show_tagcloud'     => false,
			'show_admin_column' => true,
			'labels'            => Tools::gen_tax_labels( 'Group' ),
		] );
	}
}
