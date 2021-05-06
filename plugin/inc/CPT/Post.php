<?php namespace TrevorWP\CPT;

class Post {
	/* Post Types */
	const POST_TYPE = 'post';

	/* Permalinks */
	const PERMALINK_BASE = 'blog';

	/**
	 * @see \TrevorWP\Util\Hooks::register_all()
	 */
	public static function construct(): void {
		add_action( 'init', [ self::class, 'init' ], 10, 0 );
		add_filter( 'post_type_archive_link', [ self::class, 'post_type_archive_link' ], 10, 2 );
	}

	/**
	 * Fires after WordPress has finished loading but before any headers are sent.
	 *
	 * @link https://developer.wordpress.org/reference/hooks/init/
	 */
	public static function init(): void {
		global $wp_rewrite;
		$data = [
			'post_type' => static::POST_TYPE,
		];

		add_rewrite_rule( self::PERMALINK_BASE . '/?$', 'index.php?' . http_build_query( $data ), 'top' );
		add_rewrite_rule(
			self::PERMALINK_BASE . "/{$wp_rewrite->pagination_base}/?([0-9]{1,})/?$",
			'index.php?' . http_build_query( $data ) . '&paged=$matches[1]',
			'top'
		);
	}

	/**
	 * Filters the post type archive permalink.
	 *
	 * @param string $link The post type archive permalink.
	 * @param string $post_type Post type name.
	 *
	 * @since 3.1.0
	 *
	 */
	public static function post_type_archive_link( string $link, string $post_type ): string {
		if ( $post_type == static::POST_TYPE ) {
			return home_url( trailingslashit( static::PERMALINK_BASE ) );
		}

		return $link;
	}
}
