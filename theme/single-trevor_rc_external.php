<?php get_header(); ?>

<main id="site-content" role="main" class="site-content">
	<div class="site-content-inner">
		<article <?php post_class( array( 'post-single' ) ); ?> id="post-<?php the_ID(); ?>">
			<?php echo \TrevorWP\Theme\Helper\Post_Header::render( $post ); ?>

			<div class="post-content-wrap">
				<?php echo \TrevorWP\Theme\Helper\Post::render_content_footer( $post ); ?>
			</div><!-- .post-content-wrap -->
		</article><!-- .post -->
	</div>
</main> <!-- #site-content -->

<?php get_footer(); ?>
