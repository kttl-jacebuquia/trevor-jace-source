<?php namespace TrevorWP\Theme\Helper;

use \TrevorWP\Ranks;
use TrevorWP\CPT;
use TrevorWP\CPT\RC\RC_Object;

class Card {
	public static function post( $post, array $options = [] ): string {
		$post          = get_post( $post );
		$options       = array_merge( [
				'class'     => [], // Additional classes
				'num_words' => 100, // for description
		], $options );
		$post_type     = get_post_type( $post );
		$has_thumbnail = has_post_thumbnail( $post );
		$_class        = &$options['class'];

		# Default class
		$_class[] = 'card-post';

		$title_top = $title_btm = $desc = $icon_cls = null;

		# Determine the type
		if ( $post_type == CPT\RC\Glossary::POST_TYPE ) {
			$title_top = 'Glossary';
			$title_btm = $post->post_excerpt;
			$desc      = $post->post_content;
		} elseif ( $post_type == CPT\RC\External::POST_TYPE ) {
			$title_top = 'Resource';
			$desc      = $post->post_excerpt;
			$_class[]  = 'bg-full'; // Full img bg
			$icon_cls  = 'trevor-ti-link-out';
		} elseif ( $post_type == CPT\RC\Guide::POST_TYPE ) {
			$title_top = 'Guide';
			$desc      = $post->post_excerpt;
			$_class[]  = 'bg-full'; // Full img bg
		} elseif ( $post_type == CPT\RC\Article::POST_TYPE ) {
			$categories = Ranks\Taxonomy::get_object_terms_ordered( $post, RC_Object::TAXONOMY_CATEGORY );
			$first_cat  = empty( $categories ) ? null : reset( $categories );
			$title_top  = $first_cat ? $first_cat->name : null;

			$desc = $post->post_excerpt;

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
					<?php /* TODO: Use Thumbnail helper to load different image types */ ?>
					<?= get_the_post_thumbnail( $post, 'medium', [ 'class' => 'post-header-bg' ] ); ?>
				</div>
			<?php } ?>

			<div class="card-content">
				<?php if ( ! empty( $icon_cls ) ) { ?>
					<div class="icon-wrap"><i class="<?= esc_attr( $icon_cls ) ?>"></i></div>
				<?php } ?>

				<?php if ( ! empty( $title_top ) ) { ?>
					<div class="title_top uppercase"><?= $title_top ?></div>
				<?php } ?>

				<h3 class="post-title">
					<a href="<?= get_the_permalink( $post ) ?>"
					   class="stretched-link"><?= get_the_title( $post ); ?></a>
				</h3>

				<?php if ( ! empty( $title_btm ) ) { ?>
					<div class="title-btm"><?= esc_html( $title_btm ) ?></div>
				<?php } ?>

				<?php if ( ! empty( $desc ) ) { ?>
					<div class="post-desc"><?= esc_html( wp_trim_words( $desc, $options['num_words'] ) ) ?></div>
				<?php } else { ?>
					<div class="flex-1"></div>
				<?php } ?>

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
