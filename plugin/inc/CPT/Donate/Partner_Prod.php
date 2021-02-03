<?php namespace TrevorWP\CPT\Donate;

/**
 * Partner Product
 */
class Partner_Prod extends Donate_Object {
	const POST_TYPE = self::POST_TYPE_PREFIX . 'prtnr_prod';

	/** @inheritDoc */
	static function register_post_type(): void {
		# Post Type
		register_post_type( self::POST_TYPE, [
			'labels'              => [
				'name'          => 'Products',
				'singular_name' => 'Product',
				'add_new'       => 'Add New Product'
			],
			'public'              => false,
			'hierarchical'        => false,
			'exclude_from_search' => true,
			'show_in_rest'        => true,
			'show_ui'             => true,
			'supports'            => [
				'title',
				'thumbnail',
				'excerpt',
			],
			'has_archive'         => false,
			'rewrite'             => false,
		] );
	}
}
