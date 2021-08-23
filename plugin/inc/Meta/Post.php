<?php namespace TrevorWP\Meta;

use TrevorWP\Main;
use TrevorWP\Theme;
use TrevorWP\CPT;
use TrevorWP\CPT\Page_ReCirculation;
use TrevorWP\Util\Tools;

/**
 * Post Meta
 */
class Post {
	# Popularity
	const KEY_VIEW_COUNT_SHORT = Main::META_KEY_PREFIX . 'uniq_views_short';
	const KEY_VIEW_COUNT_LONG  = Main::META_KEY_PREFIX . 'uniq_views_long';
	const KEY_POPULARITY_RANK  = Main::META_KEY_PREFIX . 'popularity_rank';
	const KEY_AVG_VISITS       = Main::META_KEY_PREFIX . 'avg_visits';

	# Images
	const KEY_IMAGE_SQUARE     = Main::META_KEY_PREFIX . 'image_square';
	const KEY_IMAGE_HORIZONTAL = Main::META_KEY_PREFIX . 'image_horizontal';

	# File
	const KEY_FILE = Main::META_KEY_PREFIX . 'file';

	# Header
	const KEY_HEADER_TYPE       = Main::META_KEY_PREFIX . 'header_type';
	const KEY_HEADER_BG_CLR     = Main::META_KEY_PREFIX . 'header_bg_clr';
	const KEY_HEADER_SNOW_SHARE = Main::META_KEY_PREFIX . 'show_share';
	const KEY_HEADER_SHOW_DATE  = Main::META_KEY_PREFIX . 'show_date';

	# Length
	const KEY_LENGTH_IND = Main::META_KEY_PREFIX . 'length_ind';

	# RC External
	const KEY_RC_EXTERNAL_URL = Main::META_KEY_PREFIX . 'rc_ext_url';

	# Highlights
	const KEY_HIGHLIGHTS = Main::META_KEY_PREFIX . 'highlights';

	# Main Category
	const KEY_MAIN_CATEGORY = Main::META_KEY_PREFIX . 'main_category';

	# Recirculation Cards
	const KEY_RECIRCULATION_CARDS = Main::META_KEY_PREFIX . 'recirculation_cards';

	# Partner
	const PARTNER_URL = Main::META_KEY_PREFIX . 'partner_url';

	# Product Partner
	const STORE_IMG = self::KEY_FILE;
	const STORE_URL = Main::META_KEY_PREFIX . 'store_url';
	const ITEM_NAME = Main::META_KEY_PREFIX . 'item_name';

	# Products
	const PROD_ITEM_IMG   = self::KEY_FILE;
	const PROD_ITEM_URL   = Main::META_KEY_PREFIX . 'prod_item_url';
	const PROD_PARTNER_ID = Main::META_KEY_PREFIX . 'prod_partner_id';

	# Bill
	const KEY_BILL_ID = Main::META_KEY_PREFIX . 'bill_id';

	# Team
	const KEY_PRONOUNS = Main::META_KEY_PREFIX . 'pronouns';

	public static $KEYS_BY_POST_TYPE = array();
	public static $ARGS_BY_KEY       = array();

	/**
	 * Registers all registered post meta.
	 */
	public static function register_all(): void {
		global $wp_meta_keys;
		$rc_ppt       = CPT\RC\RC_Object::$PUBLIC_POST_TYPES;
		$article_kind = array_merge( $rc_ppt, array( CPT\Post::POST_TYPE ) );
		$ppt          = Tools::get_public_post_types();

		$default = array(
			'show_in_rest'  => true,
			'single'        => true,
			'type'          => 'string',
			'auth_callback' => array( self::class, 'auth_check_editors' ),
			'post_types'    => $ppt,
		);

		// Posts
		foreach (
			array(
				self::KEY_HEADER_TYPE         => array(
					'sanitize_callback' => array( self::class, 'sanitize_post_header_types' ),
					'default'           => Theme\Helper\Post_Header::DEFAULT_TYPE,
					'post_types'        => $article_kind,
				),
				self::KEY_HEADER_BG_CLR       => array(
					'default'    => Theme\Helper\Post_Header::DEFAULT_BG_COLOR,
					'post_types' => $article_kind,
				),
				self::KEY_HEADER_SNOW_SHARE   => array(
					'type'       => 'boolean',
					'default'    => true,
					'post_types' => $article_kind,
				),
				self::KEY_HEADER_SHOW_DATE    => array(
					'type'       => 'boolean',
					'default'    => true,
					'post_types' => $article_kind,
				),
				self::KEY_LENGTH_IND          => array(
					'default'    => Theme\Helper\Content_Length::DEFAULT_OPTION,
					'post_types' => $article_kind,
				),
				self::KEY_IMAGE_SQUARE        => array(
					'post_types' => $article_kind,
				),
				self::KEY_IMAGE_HORIZONTAL    => array(
					'post_types' => $article_kind,
				),
				self::KEY_HIGHLIGHTS          => array(
					'type'         => 'array',
					'default'      => array(),
					'show_in_rest' => false,
				),
				self::KEY_MAIN_CATEGORY       => array(
					'type'       => 'integer',
					'default'    => 0,
					'post_types' => $article_kind,
				),
				self::KEY_RECIRCULATION_CARDS => array(
					'default'      => array(),
					'type'         => 'array',
					'show_in_rest' => array(
						'schema' => array(
							'type'  => 'array',
							'items' => array( 'type' => 'string' ),
						),
					),
					'post_types'   => $article_kind,
				),
				self::KEY_FILE                => array(
					'post_types' => array(
						CPT\RC\Guide::POST_TYPE,
						/* These two sharing the same key */
						CPT\Donate\Prod_Partner::POST_TYPE,
						CPT\Donate\Partner_Prod::POST_TYPE,
					),
				),
				self::KEY_BILL_ID             => array(
					'post_types' => array(
						CPT\Get_Involved\Bill::POST_TYPE,
					),
				),
				self::STORE_URL               => array(
					'post_types' => array(
						CPT\Donate\Prod_Partner::POST_TYPE,
					),
				),
				self::ITEM_NAME               => array(
					'post_types' => array(
						CPT\Donate\Prod_Partner::POST_TYPE,
					),
				),
				self::PROD_ITEM_URL           => array(
					'post_types' => array(
						CPT\Donate\Partner_Prod::POST_TYPE,
					),
				),
				self::PROD_PARTNER_ID         => array(
					'post_types' => array(
						CPT\Donate\Partner_Prod::POST_TYPE,
					),
				),
				self::PARTNER_URL             => array(
					'post_types' => array(
						CPT\Get_Involved\Partner::POST_TYPE,
						CPT\Get_Involved\Grant::POST_TYPE,
					),
				),
				self::KEY_PRONOUNS            => array(
					'post_types' => array(
						CPT\Team::POST_TYPE,
					),
				),
			) as $meta_key => $args
		) {
			$args = array_merge( $default, $args );

			self::$ARGS_BY_KEY[ $meta_key ]       = $args;
			self::$KEYS_BY_POST_TYPE[ $meta_key ] = $args['post_types'];

			foreach ( $args['post_types'] as $post_type ) {
				register_post_meta( $post_type, $meta_key, $args );
			}
		}

		# Set defaults
		$wp_meta_keys['post'][ CPT\Post::POST_TYPE ][ static::KEY_HEADER_BG_CLR ]['default']    = Theme\Helper\Post_Header::CLR_BLUE_GREEN;
		$wp_meta_keys['post'][ CPT\Post::POST_TYPE ][ static::KEY_HEADER_TYPE ]['default']      = Theme\Helper\Post_Header::TYPE_SPLIT;
		$wp_meta_keys['post'][ CPT\RC\Post::POST_TYPE ][ static::KEY_HEADER_BG_CLR ]['default'] = Theme\Helper\Post_Header::CLR_INDIGO;
		$wp_meta_keys['post'][ CPT\RC\Post::POST_TYPE ][ static::KEY_HEADER_TYPE ]['default']   = Theme\Helper\Post_Header::TYPE_SPLIT;
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
		$config = array(
			'metaKeys'           => array(),
			'metaKeysByPostType' => Post::$KEYS_BY_POST_TYPE,
		);

		# Collect Meta Keys
		foreach ( ( new \ReflectionClass( self::class ) )->getConstants() as $constant => $key ) {
			if ( strpos( $constant, 'KEY_' ) === 0 ) {
				$config['metaKeys'][ $constant ] = $key;
			}
		}

		if ( in_array( $post->post_type, $ppt ) ) {
			# Header types
			$config[ self::KEY_HEADER_TYPE ] = array(
				'types' => Theme\Helper\Post_Header::SETTINGS,
			);

			# Header Colors
			$config[ self::KEY_HEADER_BG_CLR ] = array(
				'colors' => Theme\Helper\Post_Header::BG_COLORS,
			);

			# Content Length
			$config[ self::KEY_LENGTH_IND ] = array(
				'settings' => Theme\Helper\Content_Length::SETTINGS,
			);

			# ReCirculation Cards
			$config[ self::KEY_RECIRCULATION_CARDS ] = array(
				'settings' => self::get_recirculation_posts(),
			);
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
			if ( ! empty( $terms = wp_get_object_terms( $post->ID, $tax, array( 'number' => 1 ) ) ) ) {
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
	 * @return bool
	 */
	public static function can_show_date_box( int $post_id ): bool {
		return ! empty( get_post_meta( $post_id, self::KEY_HEADER_SHOW_DATE, true ) );
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

	/**
	 * @param int $post_id
	 *
	 * @return int|null
	 */
	public static function get_file_id( int $post_id ): ?int {
		$val = get_post_meta( $post_id, self::KEY_FILE, true );

		if ( ! empty( $val ) && is_numeric( $val ) ) {
			return (int) $val;
		}

		return null;
	}

	/**
	 * @param int $post_id
	 *
	 * @return array
	 */
	public static function get_recirculation_cards( int $post_id ): array {
		$posts = get_post_meta( $post_id, self::KEY_RECIRCULATION_CARDS, true );
		if ( empty( $posts ) || ! is_array( $posts ) ) {
			return array();
		}

		return $posts;
	}

	/**
	 * @param int $post_id
	 *
	 * @return string|null
	 */
	public static function get_bill_id( int $post_id ): ?string {
		return get_post_meta( $post_id, self::KEY_BILL_ID, true );
	}

	/**
	 * @param int $post_id
	 *
	 * @return string|null
	 */
	public static function get_partner_url( int $post_id ): ?string {
		return get_post_meta( $post_id, self::PARTNER_URL, true );
	}

	/**
	 * @param int $post_id
	 *
	 * @return string|null
	 */
	public static function get_store_url( int $post_id ): ?string {
		return get_post_meta( $post_id, self::STORE_URL, true );
	}

	/**
	 * @param int $post_id
	 *
	 * @return string|null
	 */
	public static function get_store_img_id( int $post_id ): ?string {
		return get_post_meta( $post_id, self::STORE_IMG, true );
	}

	/**
	 * @param int $post_id
	 *
	 * @return string|null
	 */
	public static function get_item_name( int $post_id ): ?string {
		return get_post_meta( $post_id, self::ITEM_NAME, true );
	}

	/**
	 * @param int $post_id
	 *
	 * @return int|null
	 */
	public static function get_partner_id( int $post_id ): int {
		return (int) get_post_meta( $post_id, self::PROD_PARTNER_ID, true );
	}

	/**
	 * @param int $post_id
	 *
	 * @return string|null
	 */
	public static function get_item_url( int $post_id ): ?string {
		return get_post_meta( $post_id, self::PROD_ITEM_URL, true );
	}

	/**
	 * @param int $post_id
	 *
	 * @return string|null
	 */
	public static function get_item_img_id( int $post_id ): ?string {
		return get_post_meta( $post_id, self::PROD_ITEM_IMG, true );
	}

	/**
	 * @param int $post_id
	 *
	 * @return string|null
	 */
	public static function get_pronounces( int $post_id ): ?string {
		return get_post_meta( $post_id, self::KEY_PRONOUNS, true );
	}

	public static function get_recirculation_posts() {
		$settings = array();

		$args = array(
			'numberposts' => -1,
			'post_status' => 'publish',
			'post_type'   => Page_ReCirculation::POST_TYPE,
		);

		$posts = get_posts( $args );

		foreach ( $posts as $post ) {
			$settings[ $post->ID ] = array(
				'name' => $post->post_title,
			);
		}

		return $settings;
	}
}
