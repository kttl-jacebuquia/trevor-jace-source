<?php namespace TrevorWP\Theme\Util;

use TrevorWP\CPT\RC\RC_Object;
use TrevorWP\CPT\Get_Involved\Get_Involved_Object;

class Tools {
	/**
	 * @return string|null
	 */
	public static function get_body_gradient_type(): ?string {
		$type = null;

		if ( get_query_var( Get_Involved_Object::QV_VOLUNTEER ) ) {
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
		} else {
			$type = 'default';
		}

		return $type;
	}
}
