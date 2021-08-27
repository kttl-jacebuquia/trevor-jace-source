<?php namespace TrevorWP\CPT\Donate;

/**
 * Product Partner
 */
class Prod_Partner extends Donate_Object {
	const POST_TYPE = self::POST_TYPE_PREFIX . 'prod_prtnr';

	/** @inheritDoc */
	static function register_post_type(): void {
		# Post Type
		register_post_type(
			self::POST_TYPE,
			array(
				'labels'              => array(
					'name'          => 'Product Partners',
					'singular_name' => 'Product Partner',
					'add_new'       => 'Add New Product Partner',
				),
				'public'              => true,
				'hierarchical'        => false,
				'exclude_from_search' => true,
				'show_in_rest'        => true,
				'show_ui'             => true,
				'supports'            => array(
					'title',
					'thumbnail',
					'excerpt',
				),
				'has_archive'         => true,
				'rewrite'             => array(
					'slug'       => self::PERMALINK_PROD_PARTNERS,
					'with_front' => false,
					'feeds'      => false,
				),
				'menu_icon'           => 'dashicons-layout',
			)
		);
	}
}
