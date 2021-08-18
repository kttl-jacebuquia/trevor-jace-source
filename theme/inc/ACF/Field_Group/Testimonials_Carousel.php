<?php namespace TrevorWP\Theme\ACF\Field_Group;

use TrevorWP\Theme\ACF\Field;
use TrevorWP\Theme\Helper;

class Testimonials_Carousel extends A_Field_Group implements I_Block {
	const FIELD_TEXT_ALIGNMENT = 'text_alignment';
	const FIELD_IMAGE_POSITION = 'image_position';
	const FIELD_IMAGE_TYPE     = 'image_type';
	const FIELD_DATA           = 'data';
	const FIELD_DATA_IMG       = 'img';
	const FIELD_DATA_QUOTE     = 'quote';
	const FIELD_DATA_CITE      = 'cite';
	const FIELD_BOX_BG_CLR     = 'box_bg_color';
	const FIELD_BOXED          = 'boxed';
	const FIELD_OUTER_BG_CLR   = 'outer_bg_color';
	const FIELD_TEXT_COLOR     = 'text_color';

	/** @inheritDoc */
	protected static function prepare_register_args(): array {
		$text_alignment = static::gen_field_key( static::FIELD_TEXT_ALIGNMENT );
		$image_type     = static::gen_field_key( static::FIELD_IMAGE_POSITION );
		$image_position = static::gen_field_key( static::FIELD_IMAGE_TYPE );
		$data           = static::gen_field_key( static::FIELD_DATA );
		$data_img       = static::gen_field_key( static::FIELD_DATA_IMG );
		$data_title     = static::gen_field_key( static::FIELD_DATA_QUOTE );
		$data_subtitle  = static::gen_field_key( static::FIELD_DATA_CITE );
		$box_bg_color   = static::gen_field_key( static::FIELD_BOX_BG_CLR );
		$outer_bg_color = static::gen_field_key( static::FIELD_OUTER_BG_CLR );
		$boxed          = static::gen_field_key( static::FIELD_BOXED );
		$text_color     = static::gen_field_key( static::FIELD_TEXT_COLOR );

		return array(
			'title'  => 'Testimonials Carousel',
			'fields' => array(
				static::FIELD_IMAGE_POSITION => array(
					'key'           => $image_position,
					'name'          => static::FIELD_IMAGE_POSITION,
					'label'         => 'Image Position (Desktop)',
					'type'          => 'button_group',
					'choices'       => array(
						'left'  => 'Left',
						'right' => 'Right',
					),
					'default_value' => 'left',
				),
				static::FIELD_IMAGE_TYPE     => array(
					'key'           => $image_type,
					'name'          => static::FIELD_IMAGE_TYPE,
					'label'         => 'Image Type',
					'type'          => 'button_group',
					'choices'       => array(
						'contained' => 'Contained',
						'cover'     => 'Cover',
					),
					'default_value' => 'contained',
				),
				static::FIELD_TEXT_ALIGNMENT => array(
					'key'           => $text_alignment,
					'name'          => static::FIELD_TEXT_ALIGNMENT,
					'label'         => 'Text Alignment',
					'type'          => 'button_group',
					'choices'       => array(
						'left'   => 'Left',
						'center' => 'Center',
						'right'  => 'Right',
					),
					'default_value' => 'center',
				),
				static::FIELD_DATA           => array(
					'key'        => $data,
					'name'       => static::FIELD_DATA,
					'label'      => 'Data',
					'type'       => 'repeater',
					'required'   => true,
					'layout'     => 'table',
					'sub_fields' => array(
						static::FIELD_DATA_IMG   => array(
							'key'           => $data_img,
							'name'          => static::FIELD_DATA_IMG,
							'label'         => 'Image',
							'type'          => 'image',
							'required'      => true,
							'return_format' => 'array',
							'preview_size'  => 'medium',
							'library'       => 'all',
						),
						static::FIELD_DATA_QUOTE => array(
							'key'      => $data_title,
							'name'     => static::FIELD_DATA_QUOTE,
							'label'    => 'Quote',
							'type'     => 'textarea',
							'required' => true,
						),
						static::FIELD_DATA_CITE  => array(
							'key'   => $data_subtitle,
							'name'  => static::FIELD_DATA_CITE,
							'label' => 'Cite',
							'type'  => 'textarea',
						),
					),
				),
				static::FIELD_BOXED          => array(
					'key'           => $boxed,
					'name'          => static::FIELD_BOXED,
					'label'         => 'Layout',
					'type'          => 'button_group',
					'choices'       => array(
						'default' => 'Default',
						'boxed'   => 'Boxed',
					),
					'default_value' => 'default',
				),
				static::FIELD_BOX_BG_CLR     => Field\Color::gen_args(
					$box_bg_color,
					static::FIELD_BOX_BG_CLR,
					array(
						'label'             => 'Box BG Color',
						'default_value'     => 'gray-light',
						'wrapper'           => array(
							'width' => '50%',
						),
						'conditional_logic' => array(
							array(
								array(
									'field'    => $boxed,
									'operator' => '==',
									'value'    => 'boxed',
								),
							),
						),
					),
				),
				static::FIELD_OUTER_BG_CLR   => Field\Color::gen_args(
					$outer_bg_color,
					static::FIELD_OUTER_BG_CLR,
					array(
						'label'         => 'Outer BG Color',
						'default_value' => 'white',
						'wrapper'       => array(
							'width' => '50%',
						),
					),
				),
				static::FIELD_TEXT_COLOR   => Field\Color::gen_args(
					$text_color,
					static::FIELD_TEXT_COLOR,
					array(
						'label'         => 'Text Color',
						'default_value' => 'teal-dark',
						'wrapper'       => array(
							'width' => '50%',
						),
					),
				),
			),
		);
	}

	/** @inheritDoc */
	public static function get_block_args(): array {
		return array_merge(
			parent::get_block_args(),
			array(
				'name'       => static::get_key(),
				'title'      => 'Testimonials Carousel',
				'post_types' => array( 'page' ),
			)
		);
	}

	/** @inheritDoc */
	public static function render_block( $block, $content = '', $is_preview = false, $post_id = 0 ): void {
		$data = array();
		foreach ( (array) static::get_val( static::FIELD_DATA ) as $entry ) {
			$data[] = array(
				'img'   => $entry[ self::FIELD_DATA_IMG ],
				'cite'  => $entry[ self::FIELD_DATA_CITE ],
				'quote' => $entry[ self::FIELD_DATA_QUOTE ],
			);
		}

		$options = array(
			'text_alignment' => static::get_val( static::FIELD_TEXT_ALIGNMENT ) ?? 'center',
			'image_position' => static::get_val( static::FIELD_IMAGE_POSITION ) ?? 'left',
			'image_type'     => static::get_val( static::FIELD_IMAGE_TYPE ) ?? 'contained',
			'boxed'          => static::get_val( static::FIELD_BOXED ) ?? 'boxed',
			'box_bg_color'   => 'boxed' === static::get_val( static::FIELD_BOXED ) ? static::get_val( static::FIELD_BOX_BG_CLR ) : 'transparent',
			'outer_bg_color' => static::get_val( static::FIELD_OUTER_BG_CLR ) ?? 'white',
			'text_color'     => static::get_val( static::FIELD_TEXT_COLOR ) ?? 'teal-dark',
		);

		echo Helper\Carousel::testimonials( $data, $options );
	}
}
