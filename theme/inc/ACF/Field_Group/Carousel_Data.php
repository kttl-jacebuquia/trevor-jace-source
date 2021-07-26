<?php namespace TrevorWP\Theme\ACF\Field_Group;

class Carousel_Data extends A_Field_Group {
	const FIELD_TYPE          = 'type';
	const FIELD_DATA          = 'data';
	const FIELD_DATA_IMG      = 'data_img';
	const FIELD_DATA_TITLE    = 'data_title';
	const FIELD_DATA_SUBTITLE = 'data_subtitle';
	const FIELD_DATA_CTA      = 'data_cta';
	const FIELD_POSTS         = 'posts';

	/** @inheritDoc */
	protected static function prepare_register_args(): array {
		$type          = static::gen_field_key( static::FIELD_TYPE );
		$data          = static::gen_field_key( static::FIELD_DATA );
		$data_img      = static::gen_field_key( static::FIELD_DATA_IMG );
		$data_title    = static::gen_field_key( static::FIELD_DATA_TITLE );
		$data_subtitle = static::gen_field_key( static::FIELD_DATA_SUBTITLE );
		$data_posts    = static::gen_field_key( static::FIELD_POSTS );
		$data_cta      = static::gen_field_key( static::FIELD_DATA_CTA );

		return array(
			'title'  => 'Carousel Data',
			'fields' => array(
				static::FIELD_TYPE  => array(
					'key'           => $type,
					'name'          => static::FIELD_TYPE,
					'label'         => 'Type',
					'type'          => 'select',
					'choices'       => array(
						'custom' => 'custom',
						'posts'  => 'posts',
					),
					'default_value' => 'custom',
					'return_format' => 'value',
				),
				static::FIELD_DATA  => array(
					'key'               => $data,
					'name'              => static::FIELD_DATA,
					'label'             => 'Data',
					'type'              => 'repeater',
					'required'          => true,
					'layout'            => 'table',
					'max'               => 4,
					'conditional_logic' => array(
						array(
							array(
								'field'    => $type,
								'operator' => '==',
								'value'    => 'custom',
							),
						),
					),
					'sub_fields'        => array(
						static::FIELD_DATA_IMG      => array(
							'key'           => $data_img,
							'name'          => static::FIELD_DATA_IMG,
							'label'         => 'Image',
							'type'          => 'image',
							'required'      => true,
							'return_format' => 'array',
							'preview_size'  => 'medium',
							'library'       => 'all',
						),
						static::FIELD_DATA_TITLE    => array(
							'key'   => $data_title,
							'name'  => static::FIELD_DATA_TITLE,
							'label' => 'Title',
							'type'  => 'text',
						),
						static::FIELD_DATA_SUBTITLE => array(
							'key'   => $data_subtitle,
							'name'  => static::FIELD_DATA_SUBTITLE,
							'label' => 'Subtitle',
							'type'  => 'textarea',
						),
						static::FIELD_DATA_CTA      => array(
							'key'   => $data_cta,
							'name'  => static::FIELD_DATA_CTA,
							'label' => 'CTA',
							'type'  => 'link',
						),
					),
				),
				static::FIELD_POSTS => array(
					'key'               => $data_posts,
					'name'              => static::FIELD_POSTS,
					'label'             => 'Posts',
					'type'              => 'relationship',
					'required'          => true,
					'return_format'     => 'object',
					'conditional_logic' => array(
						array(
							array(
								'field'    => $type,
								'operator' => '==',
								'value'    => 'posts',
							),
						),
					),
					'filters'           => array(
						0 => 'search',
						1 => 'post_type',
						2 => 'taxonomy',
					),
					'elements'          => array(
						0 => 'featured_image',
					),
				),
			),
		);
	}
}
