<?php

use \TrevorWP\Ranks;

$has_thumbnail = has_post_thumbnail();

// TODO: Will be there more than one categories?
$categories = Ranks\Taxonomy::get_object_terms_ordered( get_post(), TrevorWP\CPT\Support_Resource::TAXONOMY_CATEGORY );
$first_cat  = empty( $categories ) ? null : reset( $categories );
$tags       = Ranks\Taxonomy::get_object_terms_ordered( get_post(), TrevorWP\CPT\Support_Resource::TAXONOMY_TAG );
?>
<article class="card-post">
	<?php if ( $first_cat ) { ?>
		<a class="post-category"
		   rel="category"
		   href="<?= esc_attr( get_term_link( $first_cat, $first_cat->taxonomy ) ) ?>"><?= $first_cat->name ?></a>
	<?php }

	if ( $has_thumbnail ) { ?>
		<a href="<?= get_the_permalink() ?>">
			<div class="post-thumbnail-wrap" data-aspectRatio="1:1">

				<?php the_post_thumbnail( 'large', [ 'class' => 'post-header-bg' ] ); ?>
			</div>
		</a>
	<?php } ?>

	<h3 class="post-title">
		<a href="<?= get_the_permalink() ?>"><?php the_title(); ?></a>
	</h3>

	<a class="link-read-more" href="<?= get_the_permalink() ?>">Read More</a>

	<?php if ( ! empty( $tags ) ) { ?>
		<div class="tags-box">
			<?php foreach ( $tags as $tag ) { ?>
				<a href="<?= get_term_link( $tag, $first_cat->taxonomy ) ?>"
				   class="tag-box" rel="tag"
				><?= $tag->name ?></a>
			<?php } ?>
		</div>
	<?php } ?>
</article>
