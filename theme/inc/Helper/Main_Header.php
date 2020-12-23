<?php namespace TrevorWP\Theme\Helper;

/**
 * Main Header Helper
 */
class Main_Header {
	const DEFAULT_TEXT_COLOR = Post_Header::BG_CLR_2_TXT_CLR[ Post_Header::DEFAULT_BG_COLOR ];

	/**
	 * @return string
	 */
	public static function get_text_color(): string {
		if ( is_singular( \TrevorWP\Util\Tools::get_public_post_types() ) && ( $queried_object = get_queried_object() ) instanceof \WP_Post ) {
			/** @var \WP_Post $queried_object */
			if ( \TrevorWP\Theme\Helper\Post_Header::supports_bg_color( $queried_object ) ) {
				list( , $txt_color ) = \TrevorWP\Theme\Helper\Post_Header::get_bg_color( $queried_object );

				return $txt_color;
			}
		}

		return self::DEFAULT_TEXT_COLOR;
	}
}
