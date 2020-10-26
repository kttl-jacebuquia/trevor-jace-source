<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
	<?php get_template_part( 'template-parts/post-header', get_post_type() ); ?>

	<div class="container post-content mx-auto">
		<?php the_content(); ?>
	</div>
</article><!-- .post -->
