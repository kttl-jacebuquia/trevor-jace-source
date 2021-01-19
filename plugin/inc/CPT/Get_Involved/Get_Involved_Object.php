<?php namespace TrevorWP\CPT\Get_Involved;


use TrevorWP\Main;

abstract class Get_Involved_Object {
	/* Post Types */
	const POST_TYPE_PREFIX = Main::POST_TYPE_PREFIX . 'gi_';

	/* Query Vars */
	const QV_BASE = Main::QV_PREFIX . 'gi';
	const QV_MAIN_LP = self::QV_BASE . '_main_lp';
	const QV_ECT = self::QV_BASE . '_ect'; // Ending Conversion Therapy
	const QV_VOLUNTEER = self::QV_BASE . '_volunteer'; // Ending Conversion Therapy

	/* Permalinks */
	const PERMALINK_BASE = 'get-involved';
	const PERMALINK_ECT = self::PERMALINK_BASE . '/' . 'ending-conversion-therapy';
	const PERMALINK_VOLUNTEER = self::PERMALINK_BASE . '/' . 'volunteer';

	/* Collections */
	const _ALL_ = [];

	/**
	 * @see construct()
	 */
	abstract static function register_post_type(): void;

	/**
	 * @see \TrevorWP\Util\Hooks::register_all()
	 */
	final public static function construct(): void {
		add_action( 'init', [ self::class, 'init' ], 10, 0 );
		add_action( 'parse_query', [ self::class, 'parse_query' ], 10, 1 );
		add_filter( 'query_vars', [ self::class, 'query_vars' ], PHP_INT_MAX, 1 );
	}

	/**
	 * Fires after WordPress has finished loading but before any headers are sent.
	 *
	 * @link https://developer.wordpress.org/reference/hooks/init/
	 *
	 * @see construct()
	 */
	public static function init(): void {
		# Rewrites
		## Ending Conversion Therapy
		add_rewrite_rule( self::PERMALINK_ECT . '/?$', 'index.php?' . http_build_query( [
				self::QV_BASE => 1,
				self::QV_ECT  => 1,
			] ), 'top' );

		## Volunteer
		add_rewrite_rule( self::PERMALINK_VOLUNTEER . '/?$', 'index.php?' . http_build_query( [
				self::QV_BASE      => 1,
				self::QV_VOLUNTEER => 1,
			] ), 'top' );

		## Main Page
		add_rewrite_rule(
			self::PERMALINK_BASE . '/?$',
			"index.php?" . http_build_query( array_merge( [
				self::QV_BASE    => 1,
				self::QV_MAIN_LP => 1
			], array_fill_keys( self::_ALL_, 1 ) ) ),
			'top'
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
		return array_merge( $vars, [
			self::QV_BASE,
			self::QV_MAIN_LP,
			self::QV_ECT,
			self::QV_VOLUNTEER,
		] );
	}

	/**
	 * Fires after the main query vars have been parsed.
	 *
	 * @param \WP_Query $query
	 *
	 * @link https://developer.wordpress.org/reference/hooks/parse_query/
	 */
	public static function parse_query( \WP_Query $query ): void {
		if ( ! $query->is_main_query() ) {
			return;
		}

		# Fix Resources LP
		$is_main_lp = ! empty( $query->get( self::QV_MAIN_LP ) );
		if ( $is_main_lp ) {
			$query->is_single            = false;
			$query->is_singular          = false;
			$query->is_posts_page        = true;
			$query->is_post_type_archive = true;
			$query->set( 'name', null );
		}
	}
}
