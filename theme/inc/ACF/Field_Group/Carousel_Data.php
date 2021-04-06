<?php namespace TrevorWP\Theme\ACF\Field_Group;


class Carousel_Data extends A_Field_Group {
	const FIELD_TYPE = 'type';
	const FIELD_DATA = 'data';
	const FIELD_DATA_IMG = 'data_img';
	const FIELD_DATA_TITLE = 'data_tile';
	const FIELD_DATA_SUBTITLE = 'data_subtitle';
	const FIELD_POSTS = 'posts';

	/** @inheritDoc */
	protected static function prepare_register_args(): array {
		$type          = static::gen_field_key( static::FIELD_TYPE );
		$data          = static::gen_field_key( static::FIELD_DATA );
		$data_img      = static::gen_field_key( static::FIELD_DATA_IMG );
		$data_title    = static::gen_field_key( static::FIELD_DATA_TITLE );
		$data_subtitle = static::gen_field_key( static::FIELD_DATA_SUBTITLE );
		$data_posts    = static::gen_field_key( static::FIELD_POSTS );

		return [
			'title'  => 'Carousel Data',
			'fields' => [
				static::FIELD_TYPE => [
					'key'           => $type,
					'name'          => static::FIELD_TYPE,
					'label'         => 'Type',
					'type'          => 'select',
					'choices'       => [
						'custom' => 'custom',
						'posts'  => 'posts',
					],
					'default_value' => 'custom',
					'return_format' => 'value',
				],
				static::FIELD_DATA => [
					'key'               => $data,
					'name'              => static::FIELD_DATA,
					'label'             => 'Data',
					'type'              => 'repeater',
					'required'          => 1,
					'layout'            => 'table',
					'conditional_logic' => [
						[
							[
								'field'    => $type,
								'operator' => '==',
								'value'    => 'custom',
							],
						],
					],
					'sub_fields'        => [
						static::FIELD_DATA_IMG      => [
							'key'           => $data_img,
							'name'          => static::FIELD_DATA_IMG,
							'label'         => 'Image',
							'type'          => 'image',
							'required'      => 1,
							'return_format' => 'array',
							'preview_size'  => 'medium',
							'library'       => 'all',
						],
						static::FIELD_DATA_TITLE    => [
							'key'   => $data_title,
							'name'  => static::FIELD_DATA_TITLE,
							'label' => 'Title',
							'type'  => 'text',
						],
						static::FIELD_DATA_SUBTITLE => [
							'key'   => $data_subtitle,
							'name'  => static::FIELD_DATA_SUBTITLE,
							'label' => 'Subtitle',
							'type'  => 'textarea',
						],
					],
				],
				[
					'key'               => $data_posts,
					'label'             => 'Posts',
					'name'              => 'posts',
					'type'              => 'relationship',
					'required'          => 1,
					'return_format'     => 'object',
					'conditional_logic' => [
						[
							[
								'field'    => $type,
								'operator' => '==',
								'value'    => 'posts',
							],
						],
					],
					'filters'           => [
						0 => 'search',
						1 => 'post_type',
						2 => 'taxonomy',
					],
					'elements'          => [
						0 => 'featured_image',
					],
				],
			]
		];
	}
}
