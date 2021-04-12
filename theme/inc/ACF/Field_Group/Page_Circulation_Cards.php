<?php namespace TrevorWP\Theme\ACF\Field_Group;


class Page_Circulation_Cards extends A_Basic_Section {
	const FIELD_CARDS = 'cards';
	const FIELD_CARD_KEY = 'card_key';
	const FIELD_CARD_BG = 'card_bg';

	protected static function _get_fields(): array {
		$cards    = static::gen_field_key( static::FIELD_CARDS );
		$card_key = static::gen_field_key( static::FIELD_CARD_KEY );
		$card_bg  = static::gen_field_key( static::FIELD_CARD_BG );

		return [
			static::FIELD_CARDS => [
				'key'           => $cards,
				'name'          => static::FIELD_CARDS,
				'label'         => 'Cards',
				'type'          => 'repeater',
				'layout'        => 'row',
				'return_format' => 'array',
				'collapsed'     => $card_key,
				'sub_fields'    => array_merge(
					static::_gen_tab_field( 'General' ),
					[
						static::FIELD_CARD_KEY => [
							'key'      => $card_key,
							'name'     => static::FIELD_CARD_KEY,
							'label'    => 'Unique Identifier',
							'type'     => 'text',
							'required' => 1,
						],
					],
					parent::_get_fields(),
					static::_gen_tab_field( 'Background' ),
					[
						static::FIELD_CARD_BG => [
							'key'     => $card_bg,
							'name'    => static::FIELD_CARD_BG,
							'label'   => 'Type',
							'type'    => 'select',
							'choices' => [
								''
							]
						],
					],
				)
			]
		];
	}
}
