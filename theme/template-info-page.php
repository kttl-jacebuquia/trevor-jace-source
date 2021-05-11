<?php
/*
Template Name: Info Page Template
*/
?>

<?php get_header(); ?>

<main id="site-content" role="main" class="site-content">
	<div class="site-content-inner">
		<div class="post-content-wrap">
			<div class="container post-content-grid">
				<div class="post-content">
					<?php the_content(); ?>
				</div>
				<div class="post-content-sidebar">
					<?= \TrevorWP\Theme\ACF\Field_Group\Page_Sidebar::render() ?>
					<div class="floating-blocks-home hidden lg:block"></div>
				</div>
			</div>
		</div><!-- .post-content-wrap -->
	</div>
</main> <!-- #site-content -->

<?php get_footer(); ?>
