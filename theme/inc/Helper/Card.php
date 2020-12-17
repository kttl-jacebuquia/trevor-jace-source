<?php namespace TrevorWP\Theme\Helper;

use \TrevorWP\Ranks;
use TrevorWP\CPT;
use TrevorWP\CPT\RC\RC_Object;

class Card {
	public static function post( $post, array $options = [] ): string {
		$post          = get_post( $post );
		$options       = array_merge( [
				'class' => '', // Additional classes
		], $options );
		$post_type     = get_post_type( $post );
		$has_thumbnail = has_post_thumbnail( $post );
		$_class        = &$options['class'];

		# Default class
		$_class .= " card-post";

		# Determine the type
		$title_top = $desc = null;

		if ( $post_type == CPT\RC\Guide::POST_TYPE ) {
			$_class    .= ' bg-full'; // Full img bg
			$title_top = 'Guide';

			$desc = get_the_excerpt( $post );
		} elseif ( $post_type == CPT\RC\Article::POST_TYPE ) {
			$categories = Ranks\Taxonomy::get_object_terms_ordered( $post, RC_Object::TAXONOMY_CATEGORY );
			$first_cat  = empty( $categories ) ? null : reset( $categories );
			$title_top  = $first_cat ? $first_cat->name : null;

			$desc = get_the_excerpt( $post );

		} elseif ( in_array( $post_type, [ CPT\RC\Post::POST_TYPE, CPT\Post::POST_TYPE ] ) ) {
			$title_top = 'Blog';
		}

		# Tags
		$tags = Ranks\Taxonomy::get_object_terms_ordered( $post, RC_Object::TAXONOMY_TAG );

		ob_start();
		?>
		<article class="<?= esc_attr( implode( ' ', get_post_class( $_class, $post->ID ) ) ) ?>">
			<?php if ( $has_thumbnail ) { ?>
				<div class="post-thumbnail-wrap">
					<?= get_the_post_thumbnail( $post, 'medium', [ 'class' => 'post-header-bg' ] ); ?>
				</div>
			<?php } ?>

			<div class="card-content">
				<?php if ( ! empty( $title_top ) ) { ?>
					<div class="title_top uppercase"><?= $title_top ?></div>
				<?php } ?>

				<h3 class="post-title">
					<a href="<?= get_the_permalink( $post ) ?>"
					   class="stretched-link"><?= get_the_title( $post ); ?></a>
				</h3>

				<?php if ( ! empty( $desc ) ) { ?>
					<div class="post-desc"><?= $desc ?></div>
				<?php } ?>

				<div class="flex-1"></div>

				<?php if ( ! empty( $tags ) ) { ?>
					<div class="tags-box">
						<?php foreach ( $tags as $tag ) { ?>
							<a href="<?= get_term_link( $tag ) ?>" class="tag-box"
							><?= $tag->name ?></a>
						<?php } ?>
					</div>
				<?php } ?>
			</div>
		</article>
		<?php
		return ob_get_clean();
	}
} ?>
