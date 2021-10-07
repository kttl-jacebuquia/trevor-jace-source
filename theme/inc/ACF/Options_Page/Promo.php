<?php namespace TrevorWP\Theme\ACF\Options_Page;

use TrevorWP\CPT\Promo_Popup as CPT_PROMO;
use TrevorWP\Theme\ACF\Field_Group\Promo_Popup as FG_PROMO;
use TrevorWP\Theme\Util\Is;

class Promo extends A_Options_Page {
	const FIELD_SUPPORT             = 'promo_support';
	const FIELD_SUPPORT_TOGGLE      = 'promo_support_toggle';
	const FIELD_ORGANIZATION        = 'promo_organization';
	const FIELD_ORGANIZATION_TOGGLE = 'promo_organization_toggle';

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
		$support             = static::gen_field_key( static::FIELD_SUPPORT );
		$support_toggle      = static::gen_field_key( static::FIELD_SUPPORT_TOGGLE );
		$organization        = static::gen_field_key( static::FIELD_ORGANIZATION );
		$organization_toggle = static::gen_field_key( static::FIELD_ORGANIZATION_TOGGLE );

		return array_merge(
			array(
				static::FIELD_SUPPORT             => array(
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
					'wrapper'    => array(
						'width' => '50',
					),
				),
				static::FIELD_SUPPORT_TOGGLE      => array(
					'key'         => $support_toggle,
					'name'        => static::FIELD_SUPPORT_TOGGLE,
					'label'       => 'Show Promo?',
					'type'        => 'true_false',
					'ui'          => 1,
					'ui_on_text'  => 'Yes',
					'ui_off_text' => 'No',
					'wrapper'     => array(
						'width' => '50',
					),
				),
				static::FIELD_ORGANIZATION        => array(
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
					'wrapper'    => array(
						'width' => '50',
					),
				),
				static::FIELD_ORGANIZATION_TOGGLE => array(
					'key'         => $organization_toggle,
					'name'        => static::FIELD_ORGANIZATION_TOGGLE,
					'label'       => 'Show Promo?',
					'type'        => 'true_false',
					'ui'          => 1,
					'ui_on_text'  => 'Yes',
					'ui_off_text' => 'No',
					'wrapper'     => array(
						'width' => '50',
					),
				),
			),
		);
	}

	public static function get_promo_popup() {
		$is_support  = Is::rc();
		$promo_field = $is_support ? static::FIELD_SUPPORT : static::FIELD_ORGANIZATION;
		$promo_popup = static::get_option( $promo_field );

		$promo_state = $is_support ? static::FIELD_SUPPORT_TOGGLE : static::FIELD_ORGANIZATION_TOGGLE;
		$promo_state = static::get_option( $promo_state );

		$data = array(
			'promo' => $promo_popup,
			'state' => $promo_state,
		);

		return $data;
	}

	public static function render(): string {
		$promo_popup = self::get_promo_popup();

		return FG_PROMO::render( $promo_popup['promo'] );
	}
}
