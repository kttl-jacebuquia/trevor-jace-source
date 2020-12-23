<?php namespace TrevorWP\Block;

use WP_Post;

/**
 * Blocks that capable of handling `save_post` hook.
 */
interface Post_Save_Handler {
	/**
	 * @param array $block
	 * @param WP_Post $post
	 * @param array $state
	 *
	 * @link https://developer.wordpress.org/reference/hooks/save_post/
	 * @see \TrevorWP\Util\Hooks::save_post_blocks()
	 */
	public static function save_post( array $block, WP_Post $post, array &$state ): void;

	/**
	 * Fires after all blocks processed.
	 *
	 * @param WP_Post $post
	 * @param array $state
	 *
	 * @see \TrevorWP\Util\Hooks::save_post_blocks()
	 * @see save_post()
	 */
	public static function save_post_finalize( WP_Post $post, array &$state ): void;
}
