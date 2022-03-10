<?php namespace TrevorWP\Theme\Helper;

use TrevorWP\Block;
use TrevorWP\CPT\Post as CPTPost;
use TrevorWP\CPT\RC\Article;
use TrevorWP\CPT\RC\External;
use TrevorWP\CPT\RC\Guide;
use TrevorWP\CPT\RC\Post as RCPost;
use TrevorWP\CPT\RC\RC_Object;
use TrevorWP\Meta;
use TrevorWP\Theme\ACF\Field_Group\Page_Circulation;
use TrevorWP\Theme\ACF\Field_Group\Post_Details;
use TrevorWP\Theme\ACF\Options_Page\Resource_Center;
use TrevorWP\Util\Log;
use TrevorWP\Util\Tools;
use TrevorWP\Theme\ACF\Field_Group\Custom_Heading;

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
		$out        = array();
		$highlights = array();

		# Highlight Block
		$article_highlights = Meta\Post::get_highlights( $post->ID );
		if ( is_singular( Block\Core_Heading::HIGHLIGHT_POST_TYPES ) && ! empty( $article_highlights ) ) {
			$highlights = array_merge( $highlights, $article_highlights );
		}

		# Custom Headings, to be used as Highligh Block
		$headings = Custom_Heading::get_all();
		if ( ! empty( $headings ) ) {
			$custom_headings = array();

			foreach ( $headings as $custom_heading ) {
				$custom_headings[ $custom_heading['anchor'] ] = array(
					'title'       => $custom_heading['text'],
					'description' => $custom_heading['description'],
				);
			}

			$highlights = array_merge( $highlights, $custom_headings );
		}

		$out['highlights'] .= Block\Core_Heading::render_highlights_block( $post, $highlights );

		$out['multiple_files'] = Post_Details::render_multiple_files( $post );

		return implode( "\n", array_filter( $out ) );
	}

	/**
	 * @param \WP_Post $post
	 *
	 * @return string
	 */
	public static function render_bottom_blocks( \WP_Post $post ): string {
		$out = array();

		# Featured Categories
		if ( $post->post_type == External::POST_TYPE ) {
			$out[] = Categories::render_rc_featured_hero();
		}

		$inner = implode( "\n", array_filter( $out ) );

		return "<div class='post-bottom-blocks'>{$inner}</div>";
	}

	/**
	 * @param \WP_Post $post
	 *
	 * @return string
	 */
	public static function render_after_post( \WP_Post $post ): string {
		$out = array(
			self::_render_post_recirculation( $post ),
			self::_render_page_recirculation( $post, 'after_post' ),
		);

		$out = array_filter( $out );

		// Add <main> class if there's a recirc item for CSS styling
		if ( ! empty( $out ) ) {
			add_filter( 'main_class', array( self::class, 'main_class_filter_with_recirc' ) );
		}

		return implode( "\n", array_filter( $out ) );
	}

	/**
	 * @param \WP_Post $post
	 *
	 * @return string|null
	 */
	protected static function _render_post_recirculation( \WP_Post $post ): ?string {
		if ( empty( $main_cat = Meta\Post::get_main_category( $post ) ) ) {
			return null;
		}

		$title_top = 'Learn more about';

		if ( $post->post_type === \TrevorWP\CPT\Post::POST_TYPE ) {
			$title_top = 'Read more from';
		}

		$posts = Posts::get_recirculation(
			$post,
			2,
			array(
				'force_main_category' => true,
			)
		);

		if ( empty( $posts ) || count( $posts ) != 2 ) {
			return null;
		}

		ob_start();
		?>
		<div class="post-bottom-recirculation">
			<div class="post-bottom-recirculation-inner">
				<h3 class="post-bottom-recirculation-title">
					<span class="post-bottom-recirculation-title-top"><?php echo $title_top; ?></span>
					<br>
					<span class="post-bottom-recirculation-title-name"><?php echo $main_cat->name; ?></span>
				</h3>
				<div class="post-bottom-recirculation-grid">
					<?php
					foreach ( $posts as $post ) {
						echo Card::post( $post );
					}
					?>
				</div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * @param \WP_Post $post
	 *
	 * @return string|null
	 */
	protected static function _render_bottom_tags( \WP_Post $post ): ?string {
		if ( ! in_array( $post->post_type, RC_Object::$PUBLIC_POST_TYPES ) || $post->post_type === External::POST_TYPE ) {
			return null;
		}

		$tax   = Tools::get_post_tag_tax( $post );
		$terms = wp_get_object_terms( $post->ID, $tax );

		if ( empty( $terms ) ) {
			return null;
		}

		ob_start();
		?>
		<div class="post-bottom-tags">
			<hr class="wp-block-separator is-style-wave hr-top">
			<div class="list-container inline">
				<?php foreach ( $terms as $term ) { ?>
					<a href="<?php echo RC_Object::get_search_url( $term->name ); ?>"
					   rel="tag"><?php echo esc_attr( $term->name ); ?></a>
				<?php } ?>
			</div>
			<hr class="wp-block-separator is-style-wave hr-btm">
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * @param \WP_Post $post
	 *
	 * @return string|null
	 */
	protected static function _render_bottom_categories( \WP_Post $post ): ?string {
		if ( ! in_array( $post->post_type, RC_Object::$PUBLIC_POST_TYPES ) ) {
			return null;
		}

		$featured_cat_ids = Resource_Center::get_featured_topics();

		if ( ! empty( $featured_cat_ids ) ) {
			$featured_cat_ids = array_column( $featured_cat_ids, 'term_id' );
			$terms            = get_terms(
				array(
					'taxonomy'   => RC_Object::TAXONOMY_CATEGORY,
					'orderby'    => 'include',
					'include'    => $featured_cat_ids,
					'parent'     => 0,
					'hide_empty' => false,
				)
			);
		}

		if ( empty( $terms ) ) {
			return null;
		}

		ob_start();
		?>
		<div class="post-bottom-categories">
			<h2 class="list-title">Browse trending content below or choose a topic category to explore.</h2>
			<div class="list-container">
				<?php foreach ( $terms as $term ) { ?>
					<a href="<?php echo esc_attr( get_term_link( $term ) ); ?>"
					   rel="tag"><?php echo esc_attr( $term->name ); ?></a>
				<?php } ?>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * @param \WP_Post $post
	 *
	 * @return string|null
	 */
	protected static function _render_page_recirculation( \WP_Post $post, string $where ): ?string {
		$cards = Meta\Post::get_recirculation_cards( $post->ID );
		if ( empty( $cards ) ) {
			return null;
		}

		switch ( $where ) :
			case 'after_post':
				if ( $post->post_type != \TrevorWP\CPT\Post::POST_TYPE ) {
					return null;
				}

				ob_start();
				?>
				<div class=" bg-white py-20 xl:py-24">

					<div class="container mx-auto">
						<h2 class="page-sub-title centered">
							Looking for another kind of support?
						</h2>
						<p class="page-sub-title-desc centered">
							Explore answers and information across a variety of topics, or connect to one of our trained
							counselors to receive immediate support.
						</p>

						<?php echo Page_Circulation::render_grid( $cards ); ?>
					</div>
				</div>
				<?php
				return ob_get_clean();
			case 'content_footer':
				if ( ! in_array( $post->post_type, array( RCPost::POST_TYPE, Guide::POST_TYPE, Article::POST_TYPE ) ) ) {
					return null;
				}
				ob_start();
				?>
				<div class="circulation-cards">
					<?php
					foreach ( $cards as $card ) {
						$args = Page_Circulation::get_card_args( $card );

						$method = "render_{$args['type']}";
						if ( ! method_exists( Circulation_Card::class, $method ) ) {
							continue;
						}

						?>
						<div class="circulation-card-wrap">
							<?php echo Circulation_Card::$method( $args ); ?>
						</div>
					<?php } ?>
				</div>
				<?php
				return ob_get_clean();
			default:
				Log::notice( 'Unknown $where value.', compact( 'where' ) );

				return null;
		endswitch;
	}

	/**
	 * @param \WP_Post $post
	 *
	 * @return string
	 */
	public static function render_content_footer( \WP_Post $post ): string {
		$out = array(
			# All tags
				self::_render_bottom_tags( $post ),

			# Content Bottom Recirculation
				self::_render_page_recirculation( $post, 'content_footer' ),

			# All Categories
				self::_render_bottom_categories( $post ),
		);

		$rows = array_filter( $out );

		if ( empty( $rows ) ) {
			return '';
		}

		$inner = implode( "\n", $rows );

		return "<div class='post-content-footer'>{$inner}</div>";
	}

	public static function main_class_filter_with_recirc( $main_class ) {
		return $main_class . ' with-post-recirc';
	}
}
