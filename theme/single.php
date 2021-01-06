<?php get_header(); ?>

<main id="site-content" role="main" class="site-content">
	<div class="site-content-inner">
		<article <?php post_class( [ 'post-single' ] ); ?> id="post-<?php the_ID(); ?>">
			<?= \TrevorWP\Theme\Helper\Post_Header::render( $post ); ?>

			<div class="post-content-wrap">
				<div class="container post-content-grid">
					<div class="post-content">
						<?php the_content(); ?>
					</div>
					<div class="post-content-sidebar">
						<?= \TrevorWP\Theme\Helper\Post::render_side_blocks( $post ) ?>
					</div>
				</div>
			</div><!-- .post-content-wrap -->
			<?= \TrevorWP\Theme\Helper\Post::render_bottom_blocks( $post ) ?>
		</article><!-- .post -->
		<?= \TrevorWP\Theme\Helper\Post::render_after_post( $post ); ?>
	</div>
</main> <!-- #site-content -->

<?php get_footer(); ?>
