<?php namespace TrevorWP\CPT\Get_Involved;

/**
 * Advocacy: Letter
 */
class Letter extends Get_Involved_Object {
	/* Post Types */
	const POST_TYPE = self::POST_TYPE_PREFIX . 'letter';

	/** @inheritDoc */
	static function register_post_type(): void {
		# Post Type
		register_post_type(
			self::POST_TYPE,
			array(
				'labels'        => array(
					'name'          => 'Federal Priorities',
					'singular_name' => 'Federal Priority',
					'add_new'       => 'Add New Federal Priority',
				),
				'public'        => true,
				'hierarchical'  => false,
				'show_in_rest'  => true,
				'supports'      => array(
					'title',
					'editor',
					'revisions',
					'custom-fields',
					'excerpt',
				),
				'has_archive'   => true,
				'rewrite'       => array(
					'slug'       => self::PERMALINK_LETTER,
					'with_front' => false,
					'feeds'      => false,
				),
				'template'      => array(
					array(
						'core/columns',
						array(),
						array(
							array( 'core/column' ),
						),
					),
					array(
						'trevor/bottom-list',
						array(
							'title' => 'This letter would:',
						),
					),
				),
				'template_lock' => 'insert',
			)
		);
	}
}
