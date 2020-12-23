<?php namespace TrevorWP\Theme\Helper;

use TrevorWP\Block;
use TrevorWP\Meta;

/**
 * Post Helper
 */
class Post {
	public static function render_side_blocks( \WP_Post $post ): string {
		$out = [];

		# Highlight Block
		if ( is_singular( Block\Core_Heading::HIGHLIGHT_POST_TYPES ) && ! empty( $highlights = Meta\Post::get_highlights( $post->ID ) ) ) {
			$out[] = Block\Core_Heading::render_highlights_block( $post, $highlights );
		}

		return implode( "\n", $out );
	}
}
