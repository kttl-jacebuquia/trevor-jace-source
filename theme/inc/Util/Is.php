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
		global $wp_query;

		if ( is_post_type_archive( CPT\Support_Resource::POST_TYPE ) ) {
			return true;
		}

		if ( is_singular( CPT\Support_Resource::POST_TYPE ) ) {
			return true;
		}

		if ( is_singular( CPT\Support_Post::POST_TYPE ) ) {
			return $wp_query->get( CPT\Support_Post::QV_BLOG );
		}

		if ( is_singular( CPT\Post::POST_TYPE ) && $wp_query->get( CPT\Support_Post::QV_BLOG ) ) {
			return true;
		}

		if ( is_tax( CPT\Support_Resource::TAXONOMY_CATEGORY ) || is_tax( CPT\Support_Resource::TAXONOMY_TAG ) ) {
			return true;
		}

		// TODO: Check search
		// TODO: Use static cache?

		return false;
	}
}
