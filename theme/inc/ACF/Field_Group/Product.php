<?php namespace TrevorWP\Theme\ACF\Field_Group;

class Product extends A_Field_Group {
	const FIELD_PRODUCT_START_DATE = 'product_start_date';
	const FIELD_PRODUCT_END_DATE   = 'product_end_date';
	const FIELD_PRODUCT_IMAGE      = 'product_image';
	const FIELD_PRODUCT_PARTNER    = 'product_partner';
	const FIELD_PRODUCT_URL        = 'product_url';

	/**
	 * @inheritDoc
	 */
	protected static function prepare_register_args(): array {
		$product_start_date = static::gen_field_key( static::FIELD_PRODUCT_START_DATE );
		$product_end_date   = static::gen_field_key( static::FIELD_PRODUCT_END_DATE );
		$product_image      = static::gen_field_key( static::FIELD_PRODUCT_IMAGE );
		$product_url        = static::gen_field_key( static::FIELD_PRODUCT_URL );
		$product_partner    = static::gen_field_key( static::FIELD_PRODUCT_PARTNER );

		return array(
			'title'    => 'Product Details',
			'fields'   => array(
				static::FIELD_PRODUCT_START_DATE => array(
					'key'            => $product_start_date,
					'name'           => static::FIELD_PRODUCT_START_DATE,
					'label'          => 'Start Date',
					'type'           => 'date_picker',
					'required'       => true,
					'wrapper'        => array(
						'width' => '50%',
					),
					'display_format' => 'M j, Y',
					'return_format'  => 'M j, Y',
					'first_day'      => 0,
				),
				static::FIELD_PRODUCT_END_DATE   => array(
					'key'            => $product_end_date,
					'name'           => static::FIELD_PRODUCT_END_DATE,
					'label'          => 'End Date',
					'type'           => 'date_picker',
					'required'       => true,
					'wrapper'        => array(
						'width' => '50%',
					),
					'display_format' => 'M j, Y',
					'return_format'  => 'M j, Y',
					'first_day'      => 0,
				),
				static::FIELD_PRODUCT_PARTNER    => array(
					'key'           => $product_partner,
					'name'          => static::FIELD_PRODUCT_PARTNER,
					'label'         => 'Partner',
					'type'          => 'post_object',
					'required'      => true,
					'wrapper'       => array(
						'width' => '50%',
					),
					'post_type'     => array(
						0 => \TrevorWP\CPT\Donate\Prod_Partner::POST_TYPE,
					),
					'taxonomy'      => '',
					'allow_null'    => 0,
					'multiple'      => 0,
					'return_format' => 'object',
					'ui'            => 1,
				),
				static::FIELD_PRODUCT_IMAGE      => array(
					'key'           => $product_image,
					'name'          => static::FIELD_PRODUCT_IMAGE,
					'label'         => 'Image',
					'type'          => 'image',
					'wrapper'       => array(
						'width' => '50%',
					),
					'return_format' => 'array',
					'preview_size'  => 'thumbnail',
					'library'       => 'all',
				),
				static::FIELD_PRODUCT_URL        => array(
					'key'      => $product_url,
					'name'     => static::FIELD_PRODUCT_URL,
					'label'    => 'URL',
					'type'     => 'url',
					'required' => true,
				),
			),
			'location' => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => \TrevorWP\CPT\Donate\Partner_Prod::POST_TYPE,
					),
				),
			),
		);
	}
}
