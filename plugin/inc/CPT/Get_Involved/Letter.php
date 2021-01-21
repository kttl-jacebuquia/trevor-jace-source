<?php namespace TrevorWP\CPT\Get_Involved;


class Letter extends Get_Involved_Object {
	/* Post Types */
	const POST_TYPE = self::POST_TYPE_PREFIX . 'letter';

	/** @inheritDoc */
	static function register_post_type(): void {
		# Post Type
		register_post_type( self::POST_TYPE, [
			'labels'       => [
				'name'          => 'Letters',
				'singular_name' => 'Letter',
				'add_new'       => 'Add New Letter'
			],
			'public'       => true,
			'hierarchical' => false,
			'show_in_rest' => true,
			'supports'     => [
				'title',
				'editor',
				'revisions',
				'custom-fields',
				'excerpt',
			],
			'has_archive'  => false,
			'rewrite'      => false,
		] );
	}
}
