<?php namespace TrevorWP\CPT\Get_Involved;

/**
 * Institutional Grant
 */
class Grant extends Get_Involved_Object {
	/* Flags */
	const IS_PUBLIC = false;

	const POST_TYPE = self::POST_TYPE_PREFIX . 'grant';

	/** @inheritDoc */
	static function register_post_type(): void {
		# Post Type
		register_post_type(
			self::POST_TYPE,
			array(
				'labels'       => array(
					'name'          => 'Grants',
					'singular_name' => 'Grant',
					'add_new'       => 'Add New Grant',
				),
				'public'       => true,
				'hierarchical' => false,
				'show_in_rest' => true,
				'supports'     => array(
					'title',
				),
				'has_archive'  => false,
				'rewrite'      => false,
			)
		);
	}
}
