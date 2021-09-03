<?php namespace TrevorWP\CPT\Get_Involved;

use TrevorWP\CPT\RC\RC_Object;
use TrevorWP\Main;
use TrevorWP\Util\Tools;

/**
 * Abstract Get Involved Object
 */
abstract class Get_Involved_Object {
	/* Flags */
	const IS_PUBLIC = true;

	/* Post Types */
	const POST_TYPE_PREFIX = Main::POST_TYPE_PREFIX . 'gi_';

	/* Taxonomies */
	const TAXONOMY_PREFIX       = self::POST_TYPE_PREFIX;
	const TAXONOMY_PARTNER_TIER = self::TAXONOMY_PREFIX . 'partner_tier';
	const TAXONOMY_GRANT_TIER   = self::TAXONOMY_PREFIX . 'grant_tier';

	/* Query Vars */
	const QV_BASE                 = Main::QV_PREFIX . 'gi';
	const QV_ADVOCACY             = self::QV_BASE . '_advocacy';
	const QV_ECT                  = self::QV_BASE . '_ect'; // Ending Conversion Therapy
	const QV_VOLUNTEER            = self::QV_BASE . '_volunteer';
	const QV_BILL                 = self::QV_BASE . '_bill';
	const QV_LETTER               = self::QV_BASE . '_letter';
	const QV_PARTNER_W_US         = self::QV_BASE . 'partner_w_us';
	const QV_CORP_PARTNERSHIPS    = self::QV_BASE . 'corp_partnerships';
	const QV_INSTITUTIONAL_GRANTS = self::QV_BASE . 'institutional_grants';
	const _QV_ALL                 = array(
		self::QV_BASE,
		self::QV_BILL,
		self::QV_LETTER,
	);

	/* Permalinks */
	const PERMALINK_ADVOCACY             = 'advocacy';
	const PERMALINK_ECT                  = 'ending-conversion-therapy';
	const PERMALINK_VOLUNTEER            = 'volunteer';
	const PERMALINK_PARTNER_W_US         = 'partner-with-us';
	const PERMALINK_CORP_PARTNERSHIPS    = 'corporate-partnerships';
	const PERMALINK_INSTITUTIONAL_GRANTS = 'institutional-grants';
	const PERMALINK_BILL                 = self::PERMALINK_ADVOCACY . '/bill';
	const PERMALINK_LETTER               = self::PERMALINK_ADVOCACY . '/letter';

	/* Collections */
	const _ALL_ = array(
		Bill::class,
		Letter::class,
		Partner::class,
		Grant::class,
	);

	/* Misc */
	const LOGO_SIZES = array(
		'text'   => array( 'name' => 'Text' ),
		'normal' => array( 'name' => 'Normal' ),
		'big'    => array( 'name' => 'Big' ),
	);

	/**
	 * @var string[]
	 */
	static $ALL_POST_TYPES = array();

	/**
	 * @var string[]
	 */
	static $PUBLIC_POST_TYPES = array();

	/**
	 * @see construct()
	 */
	abstract static function register_post_type(): void;

	/**
	 * @see \TrevorWP\Util\Hooks::register_all()
	 */
	final public static function construct(): void {
		# Init All
		/** @var RC_Object $cls */
		foreach ( self::_ALL_ as $cls ) {
			# Fill post types
			self::$ALL_POST_TYPES[] = $cls::POST_TYPE;

			if ( $cls::IS_PUBLIC ) {
				self::$PUBLIC_POST_TYPES[] = $cls::POST_TYPE;
			}
		}

		add_action( 'init', array( self::class, 'init' ), 10, 0 );
		add_filter( 'query_vars', array( self::class, 'query_vars' ), PHP_INT_MAX, 1 );
		add_filter( 'body_class', array( self::class, 'body_class' ), 10, 1 );
		add_action( 'pre_get_posts', array( self::class, 'pre_get_posts' ), 10, 1 );
	}

	/**
	 * Fires after WordPress has finished loading but before any headers are sent.
	 *
	 * @link https://developer.wordpress.org/reference/hooks/init/
	 *
	 * @see construct()
	 */
	public static function init(): void {
		# Post Types
		foreach ( self::_ALL_ as $cls ) {
			/** @var RC_Object $cls */
			$cls::register_post_type();
		}

		# Taxonomies
		## Partner Tier
		register_taxonomy(
			self::TAXONOMY_PARTNER_TIER,
			array( Partner::POST_TYPE ),
			array(
				'public'            => false,
				'hierarchical'      => false,
				'show_ui'           => true,
				'show_in_rest'      => true,
				'show_tagcloud'     => false,
				'show_admin_column' => true,
				'labels'            => Tools::gen_tax_labels( 'Partner Tier' ),
			)
		);

		## Grant Tier
		register_taxonomy(
			self::TAXONOMY_GRANT_TIER,
			array( Grant::POST_TYPE ),
			array(
				'public'            => false,
				'hierarchical'      => false,
				'show_ui'           => true,
				'show_in_rest'      => true,
				'show_tagcloud'     => false,
				'show_admin_column' => true,
				'labels'            => Tools::gen_tax_labels( 'Grant Tier' ),
			)
		);
	}

	/**
	 * Filters rewrite rules used for individual permastructs.
	 *
	 * @param array $vars
	 *
	 * @return array
	 * @see construct()
	 *
	 * @link https://developer.wordpress.org/reference/hooks/query_vars/
	 */
	public static function query_vars( array $vars ): array {
		return array_merge( $vars, self::_QV_ALL );
	}

	/**
	 * Filters the list of CSS body class names for the current post or page.
	 *
	 * @param array $classes An array of body class names.
	 *
	 * @return array
	 *
	 * @link https://developer.wordpress.org/reference/hooks/body_class/
	 */
	public static function body_class( array $classes ): array {
		foreach ( self::_QV_ALL as $qv ) {
			if ( get_query_var( $qv ) ) {
				$classes[] = 'is-' . substr( $qv, strlen( Main::QV_PREFIX ) );
			}
		}

		return $classes;
	}

	/**
	 * Fires after the query variable object is created, but before the actual query is run.
	 *
	 * @param \WP_Query $query
	 *
	 * @link https://developer.wordpress.org/reference/hooks/pre_get_posts/
	 */
	public static function pre_get_posts( \WP_Query $query ): void {
		if ( ! is_admin() && is_post_type_archive( Bill::POST_TYPE ) ) {
			# Set per page
			set_query_var( 'posts_per_archive_page', 12 );
		} elseif ( ! is_admin() && is_post_type_archive( Letter::POST_TYPE ) ) {
			# Set per page
			set_query_var( 'posts_per_archive_page', 12 );
		}
	}
}
