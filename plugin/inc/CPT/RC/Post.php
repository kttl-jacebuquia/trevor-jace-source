<?php namespace TrevorWP\CPT\RC;

use WP_Post;

/**
 * Resource Center: Post (Blog)
 */
class Post extends RC_Object {
	/* Post Types */
	const POST_TYPE = self::POST_TYPE_PREFIX . 'post';

	/* Permalinks */
	const PERMALINK_BASE_BLOG = self::PERMALINK_BASE . '/blog';

	/* Query Vars */
	const QV_BLOG = self::QV_BASE . '__blog';

	/**
	 * @var bool interchanged blog url usage
	 */
	private static $_x_blog_url = false;

	/** @inheritDoc */
	public static function register_post_type(): void {
		# Query Vars & Post Links
		add_filter( 'query_vars', array( self::class, 'query_vars' ), PHP_INT_MAX, 1 );
		add_filter( 'post_link', array( self::class, 'post_type_link' ), PHP_INT_MAX >> 1, 2 );
		add_filter( 'get_canonical_url', array( self::class, 'get_canonical_url' ), PHP_INT_MAX >> 1, 2 );
		add_filter( 'redirect_canonical', array( self::class, 'redirect_canonical' ), PHP_INT_MAX, 2 );
		add_action( 'pre_get_posts', array( self::class, 'pre_get_posts' ), PHP_INT_MAX, 1 );

		# Register Post Type
		register_post_type(
			self::POST_TYPE,
			array(
				'labels'       => array(
					'name'          => 'Posts',
					'singular_name' => 'Post',
					'add_new'       => 'Add New Post',
				),
				'description'  => 'Support resources.',
				'public'       => true,
				'hierarchical' => false,
				'show_in_rest' => true,
				'supports'     => array(
					'title',
					'editor',
					'revisions',
					'author',
					'thumbnail',
					'custom-fields',
					'excerpt',
				),
				'has_archive'  => false,
				'rewrite'      => false,
			)
		);

		# Set Rewrite Rules
		$q_prefix = 'index.php?' . http_build_query(
			array(
				self::QV_BASE => 1,
				self::QV_BLOG => 1,
				'post_type'   => self::POST_TYPE,
			)
		);

		## Posts
		// todo: check this, why it still have (\d+)?
		add_rewrite_rule( self::PERMALINK_BASE_BLOG . '/(\d+)-([^/]+)/?$', $q_prefix . '&p=$matches[1]', 'top' );
	}

	/**
	 * Filters the canonical redirect URL.
	 *
	 * @param string $redirect_url
	 * @param string $requested_url
	 *
	 * @return string
	 *
	 * @link https://developer.wordpress.org/reference/hooks/redirect_canonical/
	 * @see Post::register_post_type()
	 */
	public static function redirect_canonical( string $redirect_url, string $requested_url ): string {
		if ( self::$_x_blog_url ) {
			return false;
		}

		return $redirect_url;
	}

	/**
	 * Fires after the query variable object is created, but before the actual query is run.
	 *
	 * @param \WP_Query $query
	 *
	 * @link https://developer.wordpress.org/reference/hooks/pre_get_posts/
	 * @see Post::register_post_type()
	 */
	public static function pre_get_posts( \WP_Query $query ): void {
		if ( ! $query->is_main_query() ) {
			return;
		}

		if ( empty( $p = $query->get( 'p' ) ) || ! is_numeric( $p ) || ( $p = intval( $p ) ) < 1 ) {
			return;
		}

		$post_type = get_post_type( $p );

		if ( empty( $post_type ) || ! in_array(
			$post_type,
			array(
				self::POST_TYPE,
				'post',
			)
		) ) {
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
	 *
	 * @link https://developer.wordpress.org/reference/hooks/query_vars/
	 * @see Post::register_post_type()
	 */
	public static function query_vars( array $vars ): array {
		$vars[] = self::QV_BLOG;

		return $vars;
	}

	/**
	 * Filters the canonical URL for a post.
	 *
	 * @param string $canonical_url
	 * @param WP_Post $post
	 *
	 * @return string
	 *
	 * @link https://developer.wordpress.org/reference/hooks/get_canonical_url/
	 * @see Post::register_post_type()
	 */
	public static function get_canonical_url( string $canonical_url, WP_Post $post ): string {
		switch ( $post->post_type ) {
			case static::POST_TYPE:
				return trailingslashit( home_url( static::PERMALINK_BASE_BLOG . "/{$post->ID}-{$post->post_name}" ) );
			case Post::PERMALINK_BASE:
				return trailingslashit( home_url( Post::PERMALINK_BASE . "/{$post->ID}-{$post->post_name}" ) );
			default:
				return $canonical_url;
		}
	}
}
