<?php namespace TrevorWP\Theme\Helper;

use TrevorWP\Meta\Post;

/**
 * Content Length Helper
 */
class Content_Length {
	/* Options */
	const OPTION_AUTO   = 'auto';
	const OPTION_SHORT  = 'short';
	const OPTION_MEDIUM = 'medium';
	const OPTION_LONG   = 'long';
	const OPTION_HIDDEN = 'hidden';

	/* Settings */
	const SETTINGS = array(
		self::OPTION_AUTO   => array( 'name' => 'Auto' ),
		self::OPTION_SHORT  => array( 'name' => 'Short' ),
		self::OPTION_MEDIUM => array( 'name' => 'Medium' ),
		self::OPTION_LONG   => array( 'name' => 'Long' ),
		self::OPTION_HIDDEN => array( 'name' => 'Hidden' ),
	);

	/* Defaults */
	const DEFAULT_OPTION     = self::OPTION_AUTO;
	const DEFAULT_LEN_VALUES = array(
		self::OPTION_MEDIUM => 500,
		self::OPTION_LONG   => 1000,
	);

	/**
	 * @param \WP_Post $post
	 *
	 * @return int
	 */
	public static function get_post_word_count( \WP_Post $post ): int {
		$content = do_blocks( $post->post_content );
		$content = wp_strip_all_tags( $content );

		return str_word_count( $content );
	}

	/**
	 * @param \WP_Post $post
	 *
	 * @return string|null
	 */
	public static function post( \WP_Post $post ): ?string {
		if ( ! empty( Post::$KEYS_BY_POST_TYPE[ Post::KEY_LENGTH_IND ] ) ) {
			if ( ! in_array( $post->post_type, Post::$KEYS_BY_POST_TYPE[ Post::KEY_LENGTH_IND ] ) ) {
				return null;
			}
		}

		$val = \TrevorWP\Meta\Post::get_content_length_indicator( $post->ID );

		if ( ! array_key_exists( $val, self::SETTINGS ) ) {
			$val = self::DEFAULT_OPTION;
		}

		if ( $val == self::OPTION_HIDDEN ) {
			return null;
		}

		if ( $val != self::OPTION_AUTO ) {
			return self::SETTINGS[ $val ]['name'];
		}

		$wc = self::get_post_word_count( $post );
		foreach ( self::DEFAULT_LEN_VALUES as $key => $val ) {
			if ( $wc > $val ) {
				return self::SETTINGS[ $key ]['name'];
			}
		}

		return self::SETTINGS[ self::OPTION_SHORT ]['name'];
	}
}
