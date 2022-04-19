<?php namespace TrevorWP\Theme\ACF\Field_Group;

use TrevorWP\CPT\Page_ReCirculation;

class Page_Circulation_Card extends A_Basic_Section {
	const FIELD_TYPE   = 'card_type';
	const FIELD_BUTTON = 'card_button';

	const TYPE_TREVORSPACE = 'trevorspace';
	const TYPE_RC          = 'rc';
	const TYPE_GET_HELP    = 'get_help';
	const TYPE_DONATION    = 'donation';
	const TYPE_FUNDRAISER  = 'fundraiser';
	const TYPE_COUNSELOR   = 'counselor';
	const TYPE_ADVOCATE    = 'advocate';

	/** @inheritdoc */
	public static function _get_fields(): array {
		$type   = static::gen_field_key( static::FIELD_TYPE );
		$button = static::gen_field_key( static::FIELD_BUTTON );

		$return = array_merge(
			static::_gen_tab_field( 'General' ),
			array(
				static::FIELD_TYPE => array(
					'key'           => $type,
					'name'          => static::FIELD_TYPE,
					'label'         => 'Type',
					'type'          => 'select',
					'required'      => true,
					'default_value' => static::TYPE_TREVORSPACE,
					'choices'       => array(
						static::TYPE_TREVORSPACE => 'TrevorSpace',
						static::TYPE_RC          => 'Resources Center',
						static::TYPE_GET_HELP    => 'Get Help',
						static::TYPE_DONATION    => 'Donation',
						static::TYPE_FUNDRAISER  => 'Fundraiser',
						static::TYPE_COUNSELOR   => 'Counselor',
						static::TYPE_ADVOCATE    => 'Advocate',
					),
					'ui'            => 1,
				),
			),
			parent::_get_fields(),
			static::_gen_tab_field( 'Button' ),
			array(
				static::FIELD_BUTTON => array(
					'key'           => $button,
					'label'         => 'Button',
					'name'          => static::FIELD_BUTTON,
					'type'          => 'link',
					'required'      => true,
					'return_format' => 'array',
				),
			),
		);

		$return[ static::FIELD_TITLE ]['required'] = true;
		unset( $return['tab_buttons'] );
		unset( $return[ static::FIELD_BUTTONS ] );

		return $return;
	}

	/** @inheritdoc */
	public static function prepare_register_args(): array {
		return array_merge(
			parent::prepare_register_args(),
			array(
				'title'    => 'Page Circulation Card',
				'location' => array(
					array(
						array(
							'param'    => 'post_type',
							'operator' => '==',
							'value'    => Page_ReCirculation::POST_TYPE,
						),
					),
				),
			)
		);
	}
}
