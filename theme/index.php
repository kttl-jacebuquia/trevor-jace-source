<?php get_header(); ?>

<main id="site-content" role="main">
	<?php
	if ( have_posts() ) {
		while ( have_posts() ) {
			the_post();

			get_template_part( 'template-parts/loop-excerpt', get_post_type() );
		}
	} else {
		?>
			<div>No posts found.</div>
		<?php
	}

	get_template_part( 'template-parts/pagination' );
	?>
</main> <!-- #site-content -->

<?php get_footer(); ?>
