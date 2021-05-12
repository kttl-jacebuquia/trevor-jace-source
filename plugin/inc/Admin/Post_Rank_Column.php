<?php namespace TrevorWP\Admin;

use TrevorWP\Meta\Post;
use TrevorWP\Util\Hooks;

class Post_Rank_Column {
	/* Col Names */
	const COL_NAME_PREFIX = 'trevor_';
	const COL_NAME_RANK   = self::COL_NAME_PREFIX . 'rank';

	/* Query Vars */
	const QUERY_VAR_PREFIX = 'qv_';
	const QUERY_VAR_RANK   = self::QUERY_VAR_PREFIX . self::COL_NAME_RANK;

	/* Screen Ids */
	const SCREEN_ID_EDIT_POST = 'edit-post';

	/**
	 * @see Hooks::register_all()
	 */
	public static function register_hooks() {
		$post_type = 'post';
		$screen_id = self::SCREEN_ID_EDIT_POST;

		add_action( "manage_{$post_type}_posts_custom_column", array( self::class, 'manage_posts_custom_column' ), 10, 2 );
		add_filter( "manage_{$screen_id}_sortable_columns", array( self::class, 'manage_screen_sortable_columns' ), 10, 1 );
		add_filter( 'manage_posts_columns', array( self::class, 'manage_posts_columns' ), 10, 2 );
		add_action( 'pre_get_posts', array( self::class, 'pre_get_posts' ), 10, 1 );
	}

	/**
	 * @param string $column_name
	 * @param int $post_id
	 *
	 * @link https://developer.wordpress.org/reference/hooks/manage_post-post_type_posts_custom_column/
	 * @see register_hooks()
	 */
	public static function manage_posts_custom_column( string $column_name, int $post_id ): void {
		if ( $column_name == self::COL_NAME_RANK ) {
			$rank  = (int) get_post_meta( $post_id, Post::KEY_POPULARITY_RANK, true );
			$short = (int) get_post_meta( $post_id, Post::KEY_VIEW_COUNT_SHORT, true );
			$long  = (int) get_post_meta( $post_id, Post::KEY_VIEW_COUNT_LONG, true );
			?>
			<span title="Short|Long#Rank">
				<em><?php echo $short; ?></em>|<em><?php echo $long; ?></em>#<strong><?php echo $rank; ?></strong>
			</span>
			<?php
		}
	}

	/**
	 * @param array $sortable_columns
	 *
	 * @return array
	 * @link https://developer.wordpress.org/reference/hooks/manage_this-screen-id_sortable_columns/
	 * @see register_hooks()
	 */
	public static function manage_screen_sortable_columns( array $sortable_columns ): array {
		$sortable_columns[ self::COL_NAME_RANK ] = self::QUERY_VAR_RANK;

		return $sortable_columns;
	}

	/**
	 * @param array $post_columns
	 * @param string $post_type
	 *
	 * @return array
	 * @link https://developer.wordpress.org/reference/hooks/manage_posts_columns/
	 * @see register_hooks()
	 */
	public static function manage_posts_columns( array $post_columns, string $post_type ): array {
		if ( $post_type == 'post' ) {
			$post_columns[ self::COL_NAME_RANK ] = 'Rank';
		}

		return $post_columns;
	}

	/**
	 * @param \WP_Query $query
	 *
	 * @link https://developer.wordpress.org/reference/hooks/pre_get_posts/
	 * @see register_hooks()
	 */
	public static function pre_get_posts( \WP_Query $query ): void {
		global $current_screen;

		if ( ! isset( $current_screen ) || $current_screen->id != self::SCREEN_ID_EDIT_POST ) {
			return;
		}

		if ( $query->get( 'orderby' ) === self::QUERY_VAR_RANK ) {
			$query->set( 'orderby', 'meta_value' );
			$query->set( 'meta_key', Post::KEY_POPULARITY_RANK );
			$query->set( 'meta_type', 'numeric' );

			# Reverse ordering
			$query->set( 'order', $query->get( 'order' ) == 'asc' ? 'desc' : 'asc' );
		}
	}
}
