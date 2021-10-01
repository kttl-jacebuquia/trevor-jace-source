<?php namespace TrevorWP\Theme\ACF\Options_Page;

use TrevorWP\CPT\Promo_Popup as CPT_PROMO;

class Promo extends A_Options_Page {
	const FIELD_SUPPORT      = 'promo_support';
	const FIELD_ORGANIZATION = 'promo_organization';

	/** @inheritDoc */
	protected static function prepare_page_register_args(): array {
		return array_merge(
			parent::prepare_page_register_args(),
			array(
				'page_title'  => 'Promo Popup',
				'parent_slug' => 'general-settings',
			)
		);
	}

	/** @inheritDoc */
	protected static function prepare_fields(): array {
		$support      = static::gen_field_key( static::FIELD_SUPPORT );
		$organization = static::gen_field_key( static::FIELD_ORGANIZATION );

		return array_merge(
			array(
				static::FIELD_SUPPORT      => array(
					'key'        => $support,
					'name'       => static::FIELD_SUPPORT,
					'label'      => 'Support',
					'type'       => 'post_object',
					'required'   => 1,
					'post_type'  => array( CPT_PROMO::POST_TYPE ),
					'taxonomy'   => '',
					'allow_null' => 0,
					'multiple'   => 0,
					'ui'         => 1,
				),
				static::FIELD_ORGANIZATION => array(
					'key'        => $organization,
					'name'       => static::FIELD_ORGANIZATION,
					'label'      => 'Organization',
					'type'       => 'post_object',
					'required'   => 1,
					'post_type'  => array( CPT_PROMO::POST_TYPE ),
					'taxonomy'   => '',
					'allow_null' => 0,
					'multiple'   => 0,
					'ui'         => 1,
				),
			),
		);
	}
}
