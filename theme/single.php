<?php get_header(); ?>

<main id="site-content" role="main">
	<article <?php post_class( [ 'post-single' ] ); ?> id="post-<?php the_ID(); ?>">
		<?= \TrevorWP\Theme\Helper\Header::post( $post ); ?>

		<div class="post-content-wrap bg-white h-full flex-1 prose text-indigo max-w-none">
			<div class="container mx-auto flex-1 grid md:grid-cols-8 lg:grid-cols-12">
				<div class="post-content md:col-span-7 lg:col-span-8 lg:col-start-1">
					<?php the_content(); ?>
				</div>
				<div class="side-col hidden lg:block lg:col-span-3 lg:col-start-1"></div>
			</div>
		</div><!-- .post-content-wrap -->
	</article><!-- .post -->

</main> <!-- #site-content -->

<?php get_footer(); ?>
