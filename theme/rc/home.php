<?php /* Resources Center: Home */ ?>
<?php get_header(); ?>
<?php
use \TrevorWP\Theme\ACF\Options_Page\Resource_Center;
?>

<?php if ( ! is_paged() ) { ?>

<main id="site-content" role="main" class="site-content" tabindex="0">

	<?php echo Resource_Center::render_hero(); ?>

	<?php } ?>

	<?php
	# Trending
	echo Resource_Center::render_trending();
	?>

	<?php
	# First 2 Categories
	$category_carousel = Resource_Center::render_categories();
	foreach ( array_slice( $category_carousel, 0, 2 ) as $cat_carousel ) {
		echo $cat_carousel;
	}
	?>

	<?php
		echo Resource_Center::render_guide();
	?>

	<?php
	# Rest of the categories
	foreach ( array_slice( $category_carousel, 2 ) as $cat_carousel ) {
		echo $cat_carousel;
	}
	?>

	<?php
		echo Resource_Center::render_glossary();
	?>
</main>

<?php get_footer(); ?>
