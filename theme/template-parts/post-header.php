<?php

use \TrevorWP\Theme\Helper;
use \TrevorWP\Ranks;

$has_thumbnail = has_post_thumbnail();

// TODO: Will be there more than one categories?
$categories = Ranks\Taxonomy::get_object_terms_ordered( get_post(), TrevorWP\CPT\Support_Resource::TAXONOMY_CATEGORY );
$first_cat  = empty( $categories ) ? null : reset( $categories );
$tags       = Ranks\Taxonomy::get_object_terms_ordered( get_post(), TrevorWP\CPT\Support_Resource::TAXONOMY_TAG );
?>

<div class="post-header">
	<div class="container mx-auto">
		<div class="inner">
			<?php if ( $first_cat ) { ?>
				<a class="post-category"
				   rel="category"
				   href="<?= get_term_link( $first_cat, $first_cat->taxonomy ) ?>"><?= $first_cat->name ?></a>
			<?php } ?>
			<h1 class="post-title">
				<a href="<?= get_the_permalink() ?>"><?php the_title(); ?></a>
			</h1>

			<div class="sharing">
				<i class="share-icon trevor-ti-facebook-f-brands"></i>
				<i class="share-icon trevor-ti-twitter-brands"></i>
				<i class="share-icon trevor-ti-share-alt-solid"></i>
			</div>

			<div class="post-length"><?php /* TODO: Get this dynamically  */ ?>
				<span class="length-title">Article Length: </span>
				<span class="length-value">Medium</span>
			</div>

			<?php if ( ! empty( $tags ) ) { ?>
				<div class="tags-box">
					<?php foreach ( $tags as $tag ) { ?>
						<a href="<?= get_term_link( $tag, $tag->taxonomy ) ?>"
						   class="tag-box" rel="tag"
						><?= $tag->name ?></a>
					<?php } ?>
				</div>
			<?php } ?>
		</div>
	</div>

	<?php if ( $has_thumbnail ) { ?>
		<div class="post-thumbnail-wrap">
			<?php the_post_thumbnail( 'large', [ 'class' => 'post-header-bg' ] ); ?>
		</div>
	<?php } ?>
</div>
