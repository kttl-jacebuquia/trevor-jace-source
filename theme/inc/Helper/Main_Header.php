<?php namespace TrevorWP\Theme\Helper;

use TrevorWP\Theme\Util\Is;

/**
 * Main Header Helper
 */
class Main_Header {
	const DEFAULT_TEXT_COLOR = Post_Header::BG_CLR_2_TXT_CLR[ Post_Header::DEFAULT_BG_COLOR ];

	/**
	 * @return string
	 * // TODO: Maybe we should use css variables for this?
	 */
	public static function get_text_color(): string {
		if ( is_singular( \TrevorWP\Util\Tools::get_public_post_types() ) && ( $queried_object = get_queried_object() ) instanceof \WP_Post ) {
			/** @var \WP_Post $queried_object */
			if ( \TrevorWP\Theme\Helper\Post_Header::supports_bg_color( $queried_object ) ) {
				list( , $txt_color ) = \TrevorWP\Theme\Helper\Post_Header::get_bg_color( $queried_object );

				return $txt_color;
			}
		} elseif ( Is::trevorspace() || Is::get_help() ) {
			return Post_Header::CLR_INDIGO;
		}

		return self::DEFAULT_TEXT_COLOR;
	}
}
