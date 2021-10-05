<?php namespace TrevorWP\Theme\ACF\Options_Page;

use TrevorWP\CPT\Promo_Popup as CPT_PROMO;
use TrevorWP\Theme\ACF\Field_Group\Promo_Popup as FG_PROMO;
use TrevorWP\Theme\Util\Is;

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

	public static function get_promo_popup() {
		$is_support  = Is::rc();
		$promo_field = $is_support ? static::FIELD_SUPPORT : static::FIELD_ORGANIZATION;
		$promo_popup = static::get_option( $promo_field );

		return $promo_popup;
	}

	public static function render(): string {
		$promo_popup = self::get_promo_popup();

		return FG_PROMO::render( $promo_popup );
	}
}
