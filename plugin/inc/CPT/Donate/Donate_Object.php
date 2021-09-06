<?php namespace TrevorWP\CPT\Donate;

use TrevorWP\Main;
use TrevorWP\Meta\Post;

abstract class Donate_Object {
	/* Post Types */
	const POST_TYPE_PREFIX = Main::POST_TYPE_PREFIX;

	/* Query Vars */
	const QV_FUNDRAISE         = Main::QV_PREFIX . 'fundraise';
	const QV_PROD_PARTNERSHIPS = Main::QV_PREFIX . 'prod_partnerships';

	const _QV_ALL = array(
		self::QV_FUNDRAISE,
		self::QV_PROD_PARTNERSHIPS,
	);

	/* Permalinks */
	const PERMALINK_DONATE            = 'donate'; // todo: create a ACF options page & remove this
	const PERMALINK_FUNDRAISE         = 'fundraise';
	const PERMALINK_PROD_PARTNERS     = 'shop-products';
	const PERMALINK_PROD_PARTNERSHIPS = 'product-partnerships';
	const PERMALINK_FUND_STORY        = 'fundraise/success-stories';

	/**
	 * @var string[]
	 */
	static $ALL_POST_TYPES = array(
		Prod_Partner::POST_TYPE,
		Partner_Prod::POST_TYPE,
		Fundraiser_Stories::POST_TYPE,
	);

	/**
	 * @see construct()
	 */
	abstract static function register_post_type(): void;

	/**
	 * @see \TrevorWP\Util\Hooks::register_all()
	 */
	final public static function construct(): void {
		add_action( 'init', array( self::class, 'init' ), 10, 0 );
		add_action( 'template_redirect', array( self::class, 'template_redirect' ), 10, 0 );
	}

	public static function init(): void {
		# Post Types
		Prod_Partner::register_post_type();
		Partner_Prod::register_post_type();
		Fundraiser_Stories::register_post_type();
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
