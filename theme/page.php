<?php
	$hero = \TrevorWP\Theme\ACF\Field_Group\Page_Header::render();
	get_header();
?>

<?php
while ( have_posts() ) :
	the_post();

	?>
	<main class="bg-white" role="main" id="site-content" tabindex="0">
		<?php echo $hero; ?>
		<?php the_content(); ?>
	</main>
<?php endwhile; // end of the loop. ?>

<?php get_footer(); ?>
