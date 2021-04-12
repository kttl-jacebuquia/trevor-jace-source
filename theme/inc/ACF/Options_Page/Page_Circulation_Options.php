<?php namespace TrevorWP\Theme\ACF\Options_Page;

use TrevorWP\Theme\ACF\Field_Group;
use TrevorWP\Util\Tools;

class Page_Circulation_Options extends A_Options_Page {
	const FIELD_CARDS = 'cards';

	/** @inheritDoc */
	protected static function prepare_fields(): array {
		$cards = static::gen_field_key( static::FIELD_CARDS );

		return [
			static::FIELD_CARDS => Field_Group\Page_Circulation_Cards::clone( [
				'key'         => $cards,
				'name'        => $cards,
				'prefix_name' => 1,
			] )
		];
	}

	/**
	 * Returns all the cards.
	 *
	 * @return array
	 */
	public static function get_cards(): array {
		return Tools::index_by(
			(array) static::get_option( static::FIELD_CARDS . '_' . Field_Group\Page_Circulation_Cards::FIELD_CARDS ),
			Field_Group\Page_Circulation_Cards::FIELD_CARD_KEY
		);
	}
}
