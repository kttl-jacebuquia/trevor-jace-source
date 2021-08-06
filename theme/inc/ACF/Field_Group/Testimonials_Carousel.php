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
	const FIELD_BG_CLR         = 'bg_color';

	/** @inheritDoc */
	protected static function prepare_register_args(): array {
		$text_alignment = static::gen_field_key( static::FIELD_TEXT_ALIGNMENT );
		$image_type     = static::gen_field_key( static::FIELD_IMAGE_POSITION );
		$image_position = static::gen_field_key( static::FIELD_IMAGE_TYPE );
		$data           = static::gen_field_key( static::FIELD_DATA );
		$data_img       = static::gen_field_key( static::FIELD_DATA_IMG );
		$data_title     = static::gen_field_key( static::FIELD_DATA_QUOTE );
		$data_subtitle  = static::gen_field_key( static::FIELD_DATA_CITE );
		$bg_color       = static::gen_field_key( static::FIELD_BG_CLR );

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
				static::FIELD_BG_CLR         => Field\Color::gen_args(
					$bg_color,
					static::FIELD_BG_CLR,
					array(
						'label'         => 'BG Color',
						'default_value' => 'gray-light',
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
			'bg_color'       => static::get_val( static::FIELD_BG_CLR ) ?? 'gray-light',
		);

		echo Helper\Carousel::testimonials( $data, $options );
	}
}
