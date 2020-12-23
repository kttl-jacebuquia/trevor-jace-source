<?php namespace TrevorWP\Meta;

use TrevorWP\Main;
use TrevorWP\Theme;
use TrevorWP\Util\Tools;

/**
 * Post Meta
 */
class Post {
	# Popularity
	const KEY_VIEW_COUNT_SHORT = Main::META_KEY_PREFIX . 'uniq_views_short';
	const KEY_VIEW_COUNT_LONG = Main::META_KEY_PREFIX . 'uniq_views_long';
	const KEY_POPULARITY_RANK = Main::META_KEY_PREFIX . 'popularity_rank';
	const KEY_AVG_VISITS = Main::META_KEY_PREFIX . 'avg_visits';

	# Images
	const KEY_IMAGE_SQUARE = Main::META_KEY_PREFIX . 'image_square';
	const KEY_IMAGE_HORIZONTAL = Main::META_KEY_PREFIX . 'image_horizontal';

	# Header
	const KEY_HEADER_TYPE = Main::META_KEY_PREFIX . 'header_type';
	const KEY_HEADER_BG_CLR = Main::META_KEY_PREFIX . 'header_bg_clr';
	const KEY_HEADER_SNOW_SHARE = Main::META_KEY_PREFIX . 'show_share';

	# Length
	const KEY_LENGTH_IND = Main::META_KEY_PREFIX . 'length_ind';

	# RC External
	const KEY_RC_EXTERNAL_URL = Main::META_KEY_PREFIX . 'rc_ext_url';

	# Highlights
	const KEY_HIGHLIGHTS = Main::META_KEY_PREFIX . 'highlights';

	# Main Category
	const KEY_MAIN_CATEGORY = Main::META_KEY_PREFIX . 'main_category';

	/**
	 * Registers all registered post meta.
	 */
	public static function register_all(): void {
		$default = [
			'show_in_rest'  => true,
			'single'        => true,
			'type'          => 'string',
			'auth_callback' => [ self::class, 'auth_check_editors' ]
		];

		// Posts
		foreach (
			[
				self::KEY_HEADER_TYPE       => [
					'sanitize_callback' => [ self::class, 'sanitize_post_header_types' ],
					'default'           => Theme\Helper\Post_Header::DEFAULT_TYPE,
					'object_subtype'
				],
				self::KEY_HEADER_BG_CLR     => [ 'default' => Theme\Helper\Post_Header::DEFAULT_BG_COLOR ],
				self::KEY_HEADER_SNOW_SHARE => [ 'type' => 'boolean', 'default' => true ],
				self::KEY_LENGTH_IND        => [ 'default' => Theme\Helper\Content_Length::DEFAULT_OPTION ],
				self::KEY_IMAGE_SQUARE      => [],
				self::KEY_IMAGE_HORIZONTAL  => [],
				self::KEY_HIGHLIGHTS        => [ 'type' => 'array', 'default' => [], 'show_in_rest' => false ],
				self::KEY_MAIN_CATEGORY     => [ 'type' => 'integer', 'default' => 0 ]
			] as $meta_key => $args
		) {
			foreach ( Tools::get_public_post_types() as $post_type ) {
				register_post_meta( $post_type, $meta_key, array_merge( $default, $args ) );
			}
		}
	}

	/**
	 * A cb to check whether user can edit posts.
	 *
	 * @return bool
	 */
	public static function auth_check_editors(): bool {
		return current_user_can( 'edit_posts' );
	}

	/**
	 * Sanitizes post header type value.
	 *
	 * @param string $val
	 *
	 * @return string
	 */
	public static function sanitize_post_header_types( string $val ): string {
		return in_array( $val, Theme\Helper\Post_Header::ALL_TYPES )
			? $val
			: Theme\Helper\Post_Header::DEFAULT_TYPE;
	}

	/**
	 * @param int $post_id
	 *
	 * @return string|null
	 */
	public static function get_content_length_indicator( int $post_id ): ?string {
		return get_post_meta( $post_id, self::KEY_LENGTH_IND, true );
	}

	/**
	 * @param \WP_Post $post
	 *
	 * @return array
	 */
	public static function get_editor_config( \WP_Post $post ): array {
		$ppt    = Tools::get_public_post_types();
		$config = [
			'metaKeys' => []
		];

		# Collect Meta Keys
		foreach ( ( new \ReflectionClass( self::class ) )->getConstants() as $constant => $key ) {
			if ( strpos( $constant, 'KEY_' ) === 0 ) {
				$config['metaKeys'][ $constant ] = $key;
			}
		}

		if ( in_array( $post->post_type, $ppt ) ) {
			# Header types
			$config[ self::KEY_HEADER_TYPE ] = [
				'types' => Theme\Helper\Post_Header::SETTINGS,
			];

			# Header Colors
			$config[ self::KEY_HEADER_BG_CLR ] = [
				'colors'  => Theme\Helper\Post_Header::BG_COLORS,
				'default' => Theme\Helper\Post_Header::DEFAULT_BG_COLOR,
			];

			# Content Length
			$config[ self::KEY_LENGTH_IND ] = [
				'settings' => Theme\Helper\Content_Length::SETTINGS,
			];
		}

		return $config;
	}

	/**
	 * @param int $post_id
	 *
	 * @return int
	 */
	public static function get_main_category_id( int $post_id ): int {
		return (int) get_post_meta( $post_id, self::KEY_MAIN_CATEGORY, true );
	}

	/**
	 * @param \WP_Post $post
	 *
	 * @return \WP_Term|null
	 */
	public static function get_main_category( \WP_Post $post ): ?\WP_Term {
		# Get category taxonomy
		$tax = Tools::get_post_category_tax( $post );
		if ( ! $tax ) {
			return null;
		}

		$term_id = self::get_main_category_id( $post->ID );

		# Fallback
		$main_cat = null;
		if ( ! $term_id || empty( $main_cat = get_term( $term_id, $tax ) ) ) {
			if ( ! empty( $terms = wp_get_object_terms( $post->ID, $tax, [ 'number' => 1 ] ) ) ) {
				$main_cat = reset( $terms );
			}
		}

		return $main_cat;
	}

	/**
	 * @param int $post_id
	 *
	 * @return bool
	 */
	public static function can_show_share_box( int $post_id ): bool {
		return ! empty( get_post_meta( $post_id, self::KEY_HEADER_SNOW_SHARE, true ) );
	}

	/**
	 * @param int $post_id
	 *
	 * @return array|null
	 */
	public static function get_highlights( int $post_id ): ?array {
		$highlights = get_post_meta( $post_id, self::KEY_HIGHLIGHTS, true );
		if ( ! empty( $highlights ) && is_array( $highlights ) ) {
			return $highlights;
		}

		return null;
	}

	/**
	 * @param int $post_id
	 *
	 * @return int
	 */
	public static function get_vertical_img_id( int $post_id ): int {
		return (int) get_post_thumbnail_id( $post_id );
	}

	/**
	 * @param int $post_id
	 *
	 * @return int
	 */
	public static function get_horizontal_img_id( int $post_id ): int {
		return (int) get_post_meta( $post_id, self::KEY_IMAGE_HORIZONTAL, true );
	}

	/**
	 * @param int $post_id
	 *
	 * @return int
	 */
	public static function get_square_img_id( int $post_id ): int {
		return (int) get_post_meta( $post_id, self::KEY_IMAGE_SQUARE, true );
	}
}
