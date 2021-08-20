<?php namespace TrevorWP\CPT\Donate;

/**
 * Fundraiser Story
 */
class Fundraiser_Stories extends Donate_Object {
	/* Post Types */
	const POST_TYPE = self::POST_TYPE_PREFIX . 'fund_story';

	/** @inheritDoc */
	static function register_post_type(): void {
		# Register Post Type
		register_post_type(
			self::POST_TYPE,
			array(
				'labels'       => array(
					'name'          => 'Fundraiser Stories',
					'singular_name' => 'Fundraiser Story',
					'add_new'       => 'Add New',
				),
				'description'  => 'Fundraiser success stories.',
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
				'rewrite'      => array(
					'slug'       => self::PERMALINK_FUND_STORY,
					'with_front' => false,
					'feeds'      => false,
					'pages'      => false,
				),
				'menu_icon'    => 'dashicons-layout',
			)
		);
	}
}
