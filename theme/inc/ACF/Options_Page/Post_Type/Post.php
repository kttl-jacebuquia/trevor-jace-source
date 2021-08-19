<?php

namespace TrevorWP\Theme\ACF\Options_Page\Post_Type;

class Post extends A_Post_Type {
	const POST_TYPE = \TrevorWP\CPT\Post::POST_TYPE;
	const SLUG      = \TrevorWP\CPT\Post::PERMALINK_BASE;

	/** @inheritdoc */
	protected static function prepare_page_register_args(): array {
		return array_merge(
			parent::prepare_page_register_args(),
			array(
				'parent_slug' => 'edit.php',
			)
		);
	}

	protected static function prepare_fields(): array {
		$num_words = static::gen_field_key( static::FIELD_NUM_WORDS );

		return array_merge(
			parent::prepare_fields(),
			array(
				static::FIELD_NUM_WORDS => array(
					'key'           => $num_words,
					'name'          => static::FIELD_NUM_WORDS,
					'label'         => 'Max. Number of Words in Card Description',
					'type'          => 'number',
					'default_value' => 100,
					'wrapper'       => array(
						'width' => '50',
					),
				),
			)
		);
	}
}
