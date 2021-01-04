<?php namespace TrevorWP\Theme\Helper;

use TrevorWP\Block;
use TrevorWP\Meta;

/**
 * Post Helper
 */
class Post {
	/**
	 * @param \WP_Post $post
	 *
	 * @return string
	 */
	public static function render_side_blocks( \WP_Post $post ): string {
		$out = [];

		# Highlight Block
		if ( is_singular( Block\Core_Heading::HIGHLIGHT_POST_TYPES ) && ! empty( $highlights = Meta\Post::get_highlights( $post->ID ) ) ) {
			$out['highlights'] = Block\Core_Heading::render_highlights_block( $post, $highlights );
		}

		# File
		if ( is_singular( Meta\Post::FILE_ENABLED_POST_TYPES ) && ! empty( $file_id = Meta\Post::get_file_id( $post->ID ) ) ) {
			$out['file_button'] = self::_render_file_button( $file_id );
		}

		# Floating Blocks Home, for >= Large
		$out['floating_blocks_home'] = '<div class="floating-blocks-home hidden lg:block"></div>';

		// TODO: Maybe a filter for future usage?

		return implode( "\n", array_filter( $out ) );
	}

	/**
	 * @param int $attachment_id
	 *
	 * @return string
	 */
	protected static function _render_file_button( int $attachment_id ): string {
		ob_start();
		?>
		<div class="post-file-wrap">
			<a class="btn post-file-btn" href="<?= esc_attr( wp_get_attachment_url( $attachment_id ) ); ?>">
				<span class="post-file-btn-cta">Download PDF Format</span> <i class="trevor-ti-download post-file-btn-icn"></i>
			</a>
		</div>
		<?php return ob_get_clean();
	}
}
