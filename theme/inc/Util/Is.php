<?php namespace TrevorWP\Theme\Util;

use TrevorWP\CPT;

/**
 * Collection of boolean returning functions.
 */
class Is {
	/**
	 * Tells whether the current page is related with the support center.
	 *
	 * @return bool
	 */
	public static function support(): bool {
		if ( is_post_type_archive( CPT\Support::POST_TYPE ) ) {
			return true;
		}

		if ( is_singular( CPT\Support::POST_TYPE ) ) {
			return true;
		}

		if ( is_tax( CPT\Support::TAXONOMY_CATEGORY ) || is_tax( CPT\Support::TAXONOMY_TAG ) ) {
			return true;
		}

		// TODO: Check search
		// TODO: Use static cache?

		return false;
	}
}
