<?php namespace TrevorWP\Theme\ACF\Options_Page;

use TrevorWP\Theme\ACF\Field_Group;

class Page_Circulation_Options extends A_Options_Page {
	const FIELD_CARDS = 'cards';
	const FIELD_CARD = 'card';

	/** @inheritDoc */
	protected static function prepare_fields(): array {
		$cards = static::gen_field_key( static::FIELD_CARDS );
		$card  = static::gen_field_key( static::FIELD_CARD );

		return [
			static::FIELD_CARDS => [
				'key'          => $cards,
				'name'         => $cards, // name = key
				'label'        => 'Cards',
				'type'         => 'repeater',
				'layout'       => 'row',
				'button_label' => 'New Card',
				'sub_fields'   => [
					static::FIELD_CARD => Field_Group\Page_Circulation_Card::clone( [
						'key'   => $card,
						'name'  => static::FIELD_CARD,
						'label' => 'Card',
					] )
				]
			]
		];
	}

	/**
	 * Returns all the cards.
	 *
	 * @return array
	 */
	public static function get_cards(): array {
		static $cards;

		if ( is_null( $cards ) ) {
			$cards = [];

			if ( static::have_rows( static::FIELD_CARDS ) ) {
				while ( static::have_rows( static::FIELD_CARDS ) ) {
					the_row();
					$card = [];
					foreach ( Field_Group\Page_Circulation_Card::get_all_fields() as $field_name ) {
						$card[ $field_name ] = get_sub_field( $field_name );
						//fixme: can't get cloned sub fields in array
					}

					$cards[ get_sub_field( Field_Group\Page_Circulation_Card::FIELD_CARD_KEY ) ] = $card;
				}
			}
		}

		return $cards;
	}
}
