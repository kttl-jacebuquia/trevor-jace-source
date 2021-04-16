<?php namespace TrevorWP\Theme\Util;

use TrevorWP\CPT\Org\Org_Object;
use TrevorWP\CPT\RC\RC_Object;
use TrevorWP\CPT\Get_Involved\Get_Involved_Object;
use TrevorWP\Theme\Single_Page;
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

		if ( is_page() ) {
			return null;
		}

		$is_rc = Is::rc();
		if ( $is_rc ) {
			$type = 'rc';

			if ( get_query_var( RC_Object::QV_GET_HELP ) ) {
				$type = 'get_help';
			} else if ( get_query_var( RC_Object::QV_TREVORSPACE ) ) {
				$type = 'trevorspace';
			}
		} else if ( get_query_var( Search::QV_SEARCH ) ) {
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
		return trailingslashit( home_url( Is::rc() ? \TrevorWP\CPT\RC\RC_Object::PERMALINK_BASE : Org_Object::PERMALINK_ORG_LP ) );
	}
}
