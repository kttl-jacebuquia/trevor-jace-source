<?php namespace TrevorWP\Block;

use WP_Post;
use TrevorWP\CPT;
use TrevorWP\Meta\Post;
use TrevorWP\Util\Tools;

/**
 * core/heading Block Controller
 */
class Core_Heading implements Post_Save_Handler {
	const BLOCK_NAME = 'core/heading';

	const HIGHLIGHT_POST_TYPES = [
			CPT\RC\Guide::POST_TYPE,
			CPT\RC\Article::POST_TYPE,
	];

	const HIGHLIGHT_POST_TYPE_TITLE_MAP = [
			CPT\RC\Guide::POST_TYPE   => 'This Guide includes',
			CPT\RC\Article::POST_TYPE => 'Article Highlights',
	];

	/**
	 * @param WP_Post $post
	 * @param array|null $highlights
	 *
	 * @return string|null
	 */
	public static function render_highlights_block( \WP_Post $post, array $highlights = null ): ?string {
		if ( empty( $highlights ) ) {
			$highlights = Post::get_highlights( $post->ID );
		}

		if ( empty( $highlights ) ) {
			return null;
		}
		ob_start(); ?>
		<div class="post-highlights-container">
			<h2 class="post-highlights-title"><?= self::HIGHLIGHT_POST_TYPE_TITLE_MAP[ $post->post_type ]; ?></h2>
			<ul class="post-highlights-list">
				<?php foreach ( $highlights as $dom_id => $highlight ) { ?>
					<li>
						<a class="highlight-link"
						   href="#<?= esc_attr( $dom_id ) ?>"><?= esc_html( $highlight['title'] ) ?></a>
						<span><?= esc_html( $highlight['description'] ) ?></span>
					</li>
				<?php } ?>
			</ul>
		</div>
		<?php return ob_get_clean();
	}

	/** @inheritDoc */
	public static function save_post( array $block, WP_Post $post, array &$state ): void {
		$attrs = $block['attrs'] ?? [];

		if ( empty( $description = $attrs['description'] ) ) {
			return;
		}

		list( $inside, $attributes ) = Tools::parse_simple_block_tag( 'h\d', $block['innerHTML'] );

		if ( empty( $dom_id = $attributes['id'] ) ) {
			return; // We can't accept it without an id
		}

		// Save to the state
		$state[ $dom_id ] = [
				'title'       => $inside,
				'description' => $description
		];
	}

	/** @inheritDoc */
	public static function save_post_finalize( WP_Post $post, array &$state ): void {
		// Save the state to the post meta
		update_post_meta( $post->ID, Post::KEY_HIGHLIGHTS, $state );
	}
}
