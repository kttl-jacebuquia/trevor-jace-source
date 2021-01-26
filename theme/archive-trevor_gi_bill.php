<?php get_header(); ?>

<?= TrevorWP\Theme\Helper\Page_Header::text( [
		'title_top' => 'ADVOCATE FOR CHANGE',
		'title'     => 'Our Federal Priorities',
		'desc'      => 'At The Trevor Project, we have a few priorities that we want to let members of congress know that we care about.',
		'cta_txt'   => 'Take Action Now',
		'cta_url'   => '#',
] ) ?>

<main id="site-content" role="main" class="bg-white">
	<div class="container mx-auto py-20">
		<?= \TrevorWP\Theme\Helper\Tile_Grid::posts( $wp_query->posts ) ?>
	</div>
</main> <!-- #site-content -->

<?php get_footer(); ?>
