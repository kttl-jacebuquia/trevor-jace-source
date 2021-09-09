<?php namespace TrevorWP\Theme\Util;

use TrevorWP\CPT\RC\RC_Object;
use TrevorWP\CPT\Get_Involved\Get_Involved_Object;
use TrevorWP\Theme\ACF\Options_Page\Header;
use TrevorWP\Theme\Customizer\Search;

class Tools {
	/**
	 * @return string|null
	 */
	public static function get_body_gradient_type(): ?string {
		$type = null;

		if ( get_query_var( Get_Involved_Object::QV_VOLUNTEER ) ) {
			return null;
		}

		if ( is_page() || is_404() ) {
			return null;
		}

		$is_rc = Is::rc();
		if ( $is_rc ) {
			$type = 'rc';

			if ( get_query_var( RC_Object::QV_GET_HELP ) ) {
				$type = 'get_help';
			} elseif ( get_query_var( RC_Object::QV_TREVORSPACE ) ) {
				$type = 'trevorspace';
			}
		} elseif ( get_query_var( Search::QV_SEARCH ) ) {
			$type = 'search';
		} else {
			$type = 'default';
		}

		return $type;
	}

	/**
	 * @return string
	 */
	public static function get_relative_home_url(): string {
		$header = Header::get_header();

		$find_support = $header['find_support_link']['url'];
		$explore_trevor = $header['explore_trevor_link']['url'];

		return trailingslashit( Is::rc() ? $find_support : $explore_trevor );
	}
}
