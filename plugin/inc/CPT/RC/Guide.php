<?php namespace TrevorWP\CPT\RC;

/**
 * Resource Center: Guide
 */
class Guide extends RC_Object {
	/* Post Types */
	const POST_TYPE = self::POST_TYPE_PREFIX . 'guide';

	/** @inheritDoc */
	public static function register_post_type(): void {
		# Post Type
		register_post_type( self::POST_TYPE, [
			'labels'       => [
				'name'          => 'Guides',
				'singular_name' => 'Guide',
				'add_new'       => 'Add New Guide'
			],
			'public'       => true,
			'hierarchical' => false,
			'show_in_rest' => true,
			'supports'     => [
				'title',
				'editor',
				'revisions',
				'author',
				'thumbnail',
				'custom-fields',
				'excerpt',
			],
			'has_archive'  => false,
			'rewrite'      => false,
		] );
	}
}
