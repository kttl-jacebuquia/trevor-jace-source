<?php namespace TrevorWP\CPT\RC;

/**
 * Resource Center: Article
 */
class Article extends RC_Object {
	/* Post Types */
	const POST_TYPE = self::POST_TYPE_PREFIX . 'article';

	/** @inheritDoc */
	public static function register_post_type(): void {
		# Post Type
		register_post_type(
			self::POST_TYPE,
			array(
				'labels'       => array(
					'name'          => 'Articles',
					'singular_name' => 'Article',
					'add_new'       => 'Add New Article',
				),
				'public'       => true,
				'hierarchical' => false,
				'show_in_rest' => true,
				'supports'     => array(
					'title',
					'editor',
					'revisions',
					'author',
					'thumbnail',
					'custom-fields',
					'excerpt',
				),
				'has_archive'  => false,
				'rewrite'      => false,
			)
		);
	}
}
