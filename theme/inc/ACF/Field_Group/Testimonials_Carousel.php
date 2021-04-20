<?php namespace TrevorWP\Theme\ACF\Field_Group;

use TrevorWP\Theme\Helper;

class Testimonials_Carousel extends A_Field_Group implements I_Block {
	const FIELD_DATA = 'data';
	const FIELD_DATA_IMG = 'img';
	const FIELD_DATA_QUOTE = 'quote';
	const FIELD_DATA_CITE = 'cite';

	/** @inheritDoc */
	protected static function prepare_register_args(): array {
		$data          = static::gen_field_key( static::FIELD_DATA );
		$data_img      = static::gen_field_key( static::FIELD_DATA_IMG );
		$data_title    = static::gen_field_key( static::FIELD_DATA_QUOTE );
		$data_subtitle = static::gen_field_key( static::FIELD_DATA_CITE );

		return [
			'title'  => 'Testimonials Carousel',
			'fields' => [
				static::FIELD_DATA => [
					'key'        => $data,
					'name'       => static::FIELD_DATA,
					'label'      => 'Data',
					'type'       => 'repeater',
					'required'   => 1,
					'layout'     => 'table',
					'sub_fields' => [
						static::FIELD_DATA_IMG   => [
							'key'           => $data_img,
							'name'          => static::FIELD_DATA_IMG,
							'label'         => 'Image',
							'type'          => 'image',
							'required'      => 1,
							'return_format' => 'array',
							'preview_size'  => 'medium',
							'library'       => 'all',
						],
						static::FIELD_DATA_QUOTE => [
							'key'      => $data_title,
							'name'     => static::FIELD_DATA_QUOTE,
							'label'    => 'Quote',
							'type'     => 'textarea',
							'required' => 1,
						],
						static::FIELD_DATA_CITE  => [
							'key'   => $data_subtitle,
							'name'  => static::FIELD_DATA_CITE,
							'label' => 'Cite',
							'type'  => 'textarea',
						],
					],
				]
			]
		];
	}

	/** @inheritDoc */
	public static function get_block_args(): array {
		return array_merge( parent::get_block_args(), [
			'name'       => static::get_key(),
			'title'      => 'Testimonials Carousel',
			'post_types' => [ 'page' ],
		] );
	}

	/** @inheritDoc */
	public static function render_block( $block, $content = '', $is_preview = false, $post_id = 0 ): void {
		$data = [];
		foreach ( (array) static::get_val( static::FIELD_DATA ) as $entry ) {
			$data[] = [
				'img'   => $entry[ self::FIELD_DATA_IMG ],
				'cite'  => $entry[ self::FIELD_DATA_CITE ],
				'quote' => $entry[ self::FIELD_DATA_QUOTE ],
			];
		}

		echo Helper\Carousel::testimonials( $data );
	}
}