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

		# Resource Center LP
		if ( $wp_query->get( CPT\RC\Guide::QV_RESOURCES_LP ) ) {
			return true;
		}

		# Blog
		if ( is_singular( CPT\RC\Post::POST_TYPE ) ) {
			return $wp_query->get( CPT\RC\Post::QV_BLOG );
		}

		if ( is_singular( CPT\Post::POST_TYPE ) && $wp_query->get( CPT\RC\Post::QV_BLOG ) ) {
			return true;
		}

		# Any RC Post
		if ( is_singular( CPT\RC\RC_Object::$PUBLIC_POST_TYPES ) ) {
			return true;
		}

		# RC Taxonomies
		if ( is_tax( CPT\RC\RC_Object::TAXONOMY_CATEGORY ) || is_tax( CPT\RC\RC_Object::TAXONOMY_TAG ) ) {
			return true;
		}

		// TODO: Check search
		// TODO: Use static cache

		return false;
	}
}
