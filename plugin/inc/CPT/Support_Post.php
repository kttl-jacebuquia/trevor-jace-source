<?php namespace TrevorWP\CPT;

use TrevorWP\Theme\Util\Is;
use TrevorWP\Util\Log;

/**
 * Support Center: Blog Post
 */
class Support_Post {
	/* Post Types */
	const POST_TYPE = Support_Resource::POST_TYPE_SC_PREFIX . 'post';

	/* Permalinks */
	const PERMALINK_BASE = Support_Resource::PERMALINK_BASE . '/blog';

	/* Query Vars */
	const QV_BLOG = Support_Resource::QV_SC . '__blog';

	/**
	 * @var bool interchanged blog url usage
	 */
	private static $_x_blog_url = false;

	/**
	 * @see \TrevorWP\Util\Hooks::init()
	 */
	public static function init(): void {
		# Query Vars & Post Links
		add_filter( 'query_vars', [ self::class, 'query_vars' ], PHP_INT_MAX, 1 );
		add_filter( 'post_type_link', [ self::class, 'post_type_link' ], PHP_INT_MAX >> 1, 2 );
		add_filter( 'post_link', [ self::class, 'post_type_link' ], PHP_INT_MAX >> 1, 2 );

		if ( is_admin() ) {
			add_action( 'admin_menu', [ self::class, 'admin_menu' ], PHP_INT_MAX, 0 );
		} else {
			add_filter( 'redirect_canonical', [ self::class, 'redirect_canonical' ], PHP_INT_MAX, 2 );
			add_action( 'pre_get_posts', [ self::class, 'pre_get_posts' ], PHP_INT_MAX, 1 );
		}

		# Register Post Type
		register_post_type( self::POST_TYPE, [
			'labels'       => [
				'name'          => 'Posts',
				'singular_name' => 'Post',
				'add_new'       => 'Add New Post'
			],
			'description'  => 'Support resources.',
			'public'       => true,
			'hierarchical' => false,
			'show_in_rest' => true,
			'supports'     => [
				'title',
				'editor',
				'revisions',
				'author',
				'thumbnail'
			],
			'has_archive'  => true,
			'rewrite'      => false,
			'taxonomies'   => [
				Support_Resource::TAXONOMY_CATEGORY,
				Support_Resource::TAXONOMY_TAG,
				Support_Resource::TAXONOMY_SEARCH_KEY,
			],
		] );

		# Set Rewrite Rules
		$q_prefix = "index.php?" . http_build_query( [
				Support_Resource::QV_SC => 1,
				self::QV_BLOG           => 1,
				'post_type'             => self::POST_TYPE
			] );

		## Posts
		add_rewrite_rule( self::PERMALINK_BASE . "/(\d+)-([^/]+)/?$", $q_prefix . "&p=\$matches[1]", 'top' ); //
	}

	/**
	 * Fires before the administration menu loads in the admin.
	 *
	 * @see Support_Post::init()
	 * @link https://developer.wordpress.org/reference/hooks/admin_menu/
	 */
	public static function admin_menu(): void {
		global $menu, $submenu;
		$res_slug  = "edit.php?" . http_build_query( [ 'post_type' => Support_Resource::POST_TYPE ] );
		$post_slug = "edit.php?" . http_build_query( [ 'post_type' => self::POST_TYPE ] );

		$res_menu_idx  = null;
		$post_menu_idx = null;

		# Find indexes
		foreach ( $menu as $idx => list( , , $slug ) ) {
			switch ( $slug ) {
				case $res_slug:
					$res_menu_idx = $idx;
					continue 2;
				case $post_slug:
					$post_menu_idx = $idx;
					continue 2;
				default:
					if ( $res_menu_idx && $post_menu_idx ) {
						break 2;
					}
			}
		}

		$menu[ $res_menu_idx ][0] = 'Support Center';

		if ( ! $post_menu_idx || ! $res_menu_idx ) {
			Log::alert( 'Could not find support resources admin menus.' );

			return;
		}

		if ( ! isset( $submenu[ $post_slug ] ) || ! isset( $submenu[ $res_slug ] ) ) {
			Log::alert( 'Could not find support resources admin submenu.' );

			return;
		}

		/*
		 * Unset post's admin menu & move (Posts & Add New Post) menu items to under Resource Center Menu
		 */
		unset( $menu[ $post_menu_idx ] );
		list( $post_sm_list ) = array_slice( $submenu[ $post_slug ], 0, 1 );
		unset( $submenu[ $post_slug ] );


		$res_sm         = &$submenu[ $res_slug ];
		$res_sm_idx_map = array_keys( $res_sm );

		$res_sm_rest = [];
		foreach ( array_slice( $res_sm_idx_map, 2 ) as $idx => $old_idx ) {
			$res_sm_rest[ $old_idx + 5 ] = $res_sm[ $old_idx ]; // move the rest by 5
		}

		$res_sm_list = $res_sm[ $res_sm_idx_map[0] ];

		$res_sm = [
			          $res_sm_idx_map[0]     => $res_sm_list,
			          $res_sm_idx_map[1] + 2 => $post_sm_list,
		          ] + $res_sm_rest;
	}

	/**
	 * Filters the canonical redirect URL.
	 *
	 * @param string $redirect_url
	 * @param string $requested_url
	 *
	 * @return string
	 */
	public static function redirect_canonical( string $redirect_url, string $requested_url ): string {
		if ( self::$_x_blog_url ) {
			return false;
		}

		return $redirect_url;
	}

	public static function pre_get_posts( \WP_Query $query ): void {
		if ( ! $query->is_main_query() ) {
			return;
		}

		if ( empty( $p = $query->get( 'p' ) ) || ! is_numeric( $p ) || ( $p = intval( $p ) ) < 1 ) {
			return;
		}

		$post_type = get_post_type( $p );

		if ( empty( $post_type ) || ! in_array( $post_type, [
				self::POST_TYPE,
				'post'
			] ) ) {
			return;
		}

		$is_sc_blog = ! empty( $query->get( self::QV_BLOG ) );

		if ( $post_type == 'post' && $is_sc_blog ) {
			$query->set( 'post_type', 'post' );
			self::$_x_blog_url = true;
		} elseif ( $post_type == self::POST_TYPE && ! $is_sc_blog ) {
			$query->set( 'post_type', self::POST_TYPE );
			self::$_x_blog_url = true;
		}
	}

	/**
	 * Filters rewrite rules used for individual permastructs.
	 *
	 * @param array $vars
	 *
	 * @return array
	 * @see Support_Post::init()
	 *
	 * @link https://developer.wordpress.org/reference/hooks/query_vars/
	 */
	public static function query_vars( array $vars ): array {
		$vars[] = self::QV_BLOG;

		return $vars;
	}

	/**
	 * Filters the permalink for a post of a custom post type.
	 *
	 * @param string $post_link The post's permalink.
	 * @param \WP_Post $post The post in question.
	 *
	 * @return string
	 *
	 * @link https://developer.wordpress.org/reference/hooks/post_type_link/
	 * @see Support_Post::init()
	 */
	public static function post_type_link( string $post_link, \WP_Post $post ): string {
		switch ( $post->post_type ) {
			case static::POST_TYPE:
			case Post::POST_TYPE:
				$is_support = Is::support();
				$base       = $is_support ? static::PERMALINK_BASE : Post::PERMALINK_BASE;

				return home_url( "{$base}/{$post->ID}-{$post->post_name}" );
			default:
				return $post_link;
		}
	}
}
