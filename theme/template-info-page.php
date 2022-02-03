<?php
/*
Template Name: Info Page Template
*/
?>

<?php get_header(); ?>

<main role="main" class="site-content" tabindex="0" id="site-content">
	<div class="site-content-inner">
		<div class="info-page-wrap">
			<h1 class="info-page-heading"><?php the_title(); ?></h1>
			<div class="info-page-content pb-px80">
				<div class="info-page-body">
					<?php the_content(); ?>
				</div>
				<div class="info-page-sidebar">
					<?php echo TrevorWP\Theme\Helper\Page_Sidebar::render( array( 'heading' => 'QUICK LINKS' ) ); ?>
					<div class="floating-blocks-home hidden lg:block"></div>
				</div>
			</div>
		</div><!-- .info-page-wrap -->
	</div>
</main> <!-- #site-content -->

<?php get_footer(); ?>
