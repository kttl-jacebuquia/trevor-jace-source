<?php get_header(); ?>

<?php
while ( have_posts() ) :
	the_post();
	?>
	<main class="bg-white" role="main" tabindex="0" id="site-content">
		<?php echo \TrevorWP\Theme\ACF\Field_Group\Page_Header::render(); ?>
		<?php the_content(); ?>
	</main>
<?php endwhile; // end of the loop. ?>

<?php get_footer(); ?>
