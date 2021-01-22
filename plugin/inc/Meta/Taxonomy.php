<?php namespace TrevorWP\Meta;

use TrevorWP\CPT\Get_Involved\Get_Involved_Object;
use TrevorWP\Main;

class Taxonomy {
	const KEY_POPULARITY_RANK_PREFIX = Main::META_KEY_PREFIX . 'popularity_rank_';

	const PARTNER_TIER_PREFIX = Main::META_KEY_PREFIX . 'partner_tier_';
	const KEY_PARTNER_TIER_NAME = self::PARTNER_TIER_PREFIX . 'name';
	const KEY_PARTNER_TIER_VALUE = self::PARTNER_TIER_PREFIX . 'value';
	const KEY_PARTNER_TIER_LOGO_SIZE = self::PARTNER_TIER_PREFIX . 'logo-size';

	/**
	 * @param int $term_id
	 *
	 * @return string
	 */
	public static function get_partner_tier_logo_size( int $term_id ): string {
		$size = (string) get_term_meta( $term_id, self::KEY_PARTNER_TIER_LOGO_SIZE, true );

		if ( ! array_key_exists( $size, Get_Involved_Object::LOGO_SIZES ) ) {
			$size = 'text';
		}

		return $size;
	}
}
