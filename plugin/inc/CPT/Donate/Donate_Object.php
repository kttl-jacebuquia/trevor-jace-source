<?php namespace TrevorWP\CPT\Donate;

use TrevorWP\CPT\RC\RC_Object;
use TrevorWP\Main;

abstract class Donate_Object {
	/* Post Types */
	const POST_TYPE_PREFIX = Main::POST_TYPE_PREFIX;

	/* Query Vars */
	const QV_DONATE = Main::QV_PREFIX . 'donate';
	const QV_FUNDRAISE = Main::QV_PREFIX . 'fundraise';
	const QV_PROD_PARTNERSHIPS = Main::QV_PREFIX . 'prod_partnerships';
	const QV_PROD_PARTNERS_SHOP = Main::QV_PREFIX . 'prod_partners_shop';

	const _QV_ALL = [
		self::QV_DONATE,
		self::QV_FUNDRAISE,
		self::QV_PROD_PARTNERSHIPS,
		self::QV_PROD_PARTNERS_SHOP,
	];

	/* Permalinks */
	const PERMALINK_DONATE = 'donate';
	const PERMALINK_FUNDRAISE = 'fundraise';
	const PERMALINK_PROD_PARTNERSHIPS = 'product-partners';
	const PERMALINK_PROD_PARTNERS_SHOP = 'product-partners/shop';

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
	}

	public static function init(): void {
		# Post Types
		Prod_Partner::register_post_type();

		# Rewrites
		## Single Pages
		foreach (
			[
				[ self::PERMALINK_DONATE, self::QV_DONATE ],
				[ self::PERMALINK_FUNDRAISE, self::QV_FUNDRAISE ],
			] as list(
			$regex, $qv
		)
		) {
			add_rewrite_rule( $regex . '/?$', 'index.php?' . http_build_query( [
					$qv           => 1,
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
}
