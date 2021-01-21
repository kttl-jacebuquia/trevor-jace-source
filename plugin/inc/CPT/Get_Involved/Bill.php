<?php namespace TrevorWP\CPT\Get_Involved;


class Bill extends Get_Involved_Object {
	/* Post Types */
	const POST_TYPE = self::POST_TYPE_PREFIX . 'bill';

	/** @inheritDoc */
	static function register_post_type(): void {
		# Post Type
		register_post_type( self::POST_TYPE, [
			'labels'       => [
				'name'          => 'Bills',
				'singular_name' => 'Bill',
				'add_new'       => 'Add New Bill'
			],
			'public'       => true,
			'hierarchical' => false,
			'show_in_rest' => true,
			'supports'     => [
				'title',
				'editor',
				'custom-fields',
				'excerpt',
			],
			'has_archive'  => false,
			'rewrite'      => false,
		] );
	}
}
