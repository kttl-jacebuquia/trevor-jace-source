<?php get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>
	<?= \TrevorWP\Theme\ACF\Field_Group\Page_Header::render() ?>
	<main id="site-content" class="bg-white" role="main">
		<?php the_content(); ?>
	</main>
<?php endwhile; // end of the loop. ?>

<?php get_footer(); ?>
