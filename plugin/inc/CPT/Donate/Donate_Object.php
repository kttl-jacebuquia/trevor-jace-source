<?php namespace TrevorWP\CPT\Donate;

use TrevorWP\Main;
use TrevorWP\Meta\Post;

abstract class Donate_Object {
	/* Post Types */
	const POST_TYPE_PREFIX = Main::POST_TYPE_PREFIX;

	/* Query Vars */
	const QV_DONATE = Main::QV_PREFIX . 'donate';
	const QV_FUNDRAISE = Main::QV_PREFIX . 'fundraise';
	const QV_PROD_PARTNERSHIPS = Main::QV_PREFIX . 'prod_partnerships';

	const _QV_ALL = [
		self::QV_DONATE,
		self::QV_FUNDRAISE,
		self::QV_PROD_PARTNERSHIPS,
	];

	/* Permalinks */
	const PERMALINK_DONATE = 'donate';
	const PERMALINK_FUNDRAISE = 'fundraise';
	const PERMALINK_PROD_PARTNERS = 'shop-products';
	const PERMALINK_PROD_PARTNERSHIPS = 'product-partnerships';
	const PERMALINK_FUND_STORY = 'fundraise/success-stories';

	/**
	 * @var string[]
	 */
	static array $ALL_POST_TYPES = [
		Prod_Partner::POST_TYPE,
		Partner_Prod::POST_TYPE,
		Fundraiser_Stories::PERMALINK_FUND_STORY,
	];

	/**
	 * @see construct()
	 */
	abstract static function register_post_type(): void;

	/**
	 * @see \TrevorWP\Util\Hooks::register_all()
	 */
	final public static function construct(): void {
		add_action( 'init', [ self::class, 'init' ], 10, 0 );
		add_filter( 'query_vars', [ self::class, 'query_vars' ], PHP_INT_MAX, 1 );
		add_filter( 'body_class', [ self::class, 'body_class' ], 10, 1 );

		add_action( 'template_redirect', [ self::class, 'template_redirect' ], 10, 0 );
	}

	public static function init(): void {
		# Post Types
		Prod_Partner::register_post_type();
		Partner_Prod::register_post_type();
		Fundraiser_Stories::register_post_type();

		# Rewrites
		## Single Pages
		foreach (
			[
				[ self::PERMALINK_DONATE, self::QV_DONATE ],
				[ self::PERMALINK_FUNDRAISE, self::QV_FUNDRAISE ],
				[ self::PERMALINK_PROD_PARTNERSHIPS, self::QV_PROD_PARTNERSHIPS ],
			] as list(
			$regex, $qv
		)
		) {
			add_rewrite_rule( $regex . '/?$', 'index.php?' . http_build_query( [
					$qv => 1,
				] ), 'top' );
		}
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
	 * Fires before determining which template to load.
	 *
	 * @link https://developer.wordpress.org/reference/hooks/template_redirect/
	 */
	public static function template_redirect(): void {
		global $wp_query;
		if ( is_singular( Prod_Partner::POST_TYPE ) ) {
			$store_url = filter_var( $url = Post::get_store_url( get_the_ID() ), FILTER_VALIDATE_URL );

			if ( empty( $store_url ) ) {
				$wp_query->set_404();
				status_header( 404 );
			} else {
				wp_redirect( $store_url, 302 );
				exit;
			}
		}
	}
}
