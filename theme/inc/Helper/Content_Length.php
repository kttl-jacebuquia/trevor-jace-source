<?php namespace TrevorWP\Theme\Helper;

/**
 * Content Length Helper
 */
class Content_Length {
	/* Options */
	const OPTION_AUTO = 'auto';
	const OPTION_SHORT = 'short';
	const OPTION_MEDIUM = 'medium';
	const OPTION_LONG = 'long';
	const OPTION_HIDDEN = 'hidden';

	/* Settings */
	const SETTINGS = [
		self::OPTION_AUTO   => [ 'name' => 'Auto' ],
		self::OPTION_SHORT  => [ 'name' => 'Short' ],
		self::OPTION_MEDIUM => [ 'name' => 'Medium' ],
		self::OPTION_LONG   => [ 'name' => 'Long' ],
		self::OPTION_HIDDEN => [ 'name' => 'Hidden' ],
	];

	/* Defaults */
	const DEFAULT_OPTION = self::OPTION_AUTO;
	const DEFAULT_LEN_VALUES = [
		self::OPTION_MEDIUM => 500,
		self::OPTION_LONG   => 1000,
	];

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
		if ( ! in_array( $post->post_type, \TrevorWP\Meta\Post::$KEYS_BY_POST_TYPE ) ) {
			return null;
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
		foreach ( array_reverse( array_keys( self::DEFAULT_LEN_VALUES ) ) as $key ) {
			$max = \TrevorWP\Theme\Customizer\Posts::get_val( $key );
			if ( $wc > $max ) {
				return self::SETTINGS[ $key ]['name'];
			}
		}

		return self::SETTINGS[ self::OPTION_SHORT ]['name'];
	}
}
