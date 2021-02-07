<?php namespace TrevorWP\CPT\Org;

use TrevorWP\Main;

abstract class Org_Object {
	const QV_BASE = Main::QV_PREFIX . 'org';
	const QV_ORG_LP = self::QV_BASE . '__lp';

	/* Permalinks */
	const PERMALINK_ORG_LP = 'trevor';

	/**
	 * @see \TrevorWP\Util\Hooks::register_all()
	 */
	final public static function construct(): void {
		# Hooks
		add_action( 'init', [ self::class, 'init' ], 10, 0 );
		add_filter( 'query_vars', [ self::class, 'query_vars' ], PHP_INT_MAX, 1 );
	}

	/**
	 * Fires after WordPress has finished loading but before any headers are sent.
	 *
	 * @link https://developer.wordpress.org/reference/hooks/init/
	 */
	public static function init(): void {
		add_rewrite_rule(
			self::PERMALINK_ORG_LP . '/?$',
			"index.php?" . http_build_query( [
				self::QV_BASE   => 1,
				self::QV_ORG_LP => 1
			] ),
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
			self::QV_ORG_LP,
		] );
	}
}
