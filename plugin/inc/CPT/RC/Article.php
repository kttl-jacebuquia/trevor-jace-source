<?php namespace TrevorWP\CPT\RC;

/**
 * Resource Center: Article
 */
class Article extends RC_Object {
	/* Post Types */
	const POST_TYPE = self::POST_TYPE_PREFIX . 'article';

	/** @inheritDoc */
	public static function init(): void {
		# Post Type
		register_post_type( self::POST_TYPE, [
			'labels'       => [
				'name'          => 'Articles',
				'singular_name' => 'Article',
				'add_new'       => 'Add New Article'
			],
			'public'       => true,
			'hierarchical' => false,
			'show_in_rest' => true,
			'supports'     => [
				'title',
				'editor',
				'revisions',
				'author',
				'thumbnail'
			],
			'has_archive'  => false,
			'rewrite'      => false,
		] );
	}
}
