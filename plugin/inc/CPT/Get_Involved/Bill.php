<?php namespace TrevorWP\CPT\Get_Involved;

/**
 * Advocacy: Bill
 */
class Bill extends Get_Involved_Object {
	/* Post Types */
	const POST_TYPE = self::POST_TYPE_PREFIX . 'bill';

	/** @inheritDoc */
	static function register_post_type(): void {
		# Post Type
		register_post_type( self::POST_TYPE, [
			'labels'        => [
				'name'          => 'Bills',
				'singular_name' => 'Bill',
				'add_new'       => 'Add New Bill'
			],
			'public'        => true,
			'hierarchical'  => false,
			'show_in_rest'  => true,
			'supports'      => [
				'title',
				'editor',
				'custom-fields',
				'excerpt',
			],
			'has_archive'   => true,
			'rewrite'       => [
				'slug'       => self::PERMALINK_BILL,
				'with_front' => false,
				'feeds'      => false,
			],
			'template'      => [
				[
					'core/columns',
					[],
					[
						[ 'core/column' ]
					]
				],
				[
					'trevor/bottom-list',
					[
						'title' => 'This bill would:',
					]
				]
			],
			'template_lock' => 'insert',
		] );
	}
}
