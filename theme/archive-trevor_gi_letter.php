<?php get_header(); ?>

<?= TrevorWP\Theme\Helper\Page_Header::text( [
	'title_top' => 'ADVOCATE FOR CHANGE',
	'title'     => 'Our State Priorities',
	'desc'      => 'Letters that The Trevor Project sent to lawmakers in support of or opposition to bills federal and state.',
	'cta_txt'   => 'Take Action Now',
	'cta_url'   => '#',
] ) ?>

<main id="site-content" role="main" class="bg-white">
	<div class="container mx-auto py-20">
		<?= \TrevorWP\Theme\Helper\Tile_Grid::posts( $wp_query->posts ) ?>
	</div>
</main> <!-- #site-content -->

<?php get_footer(); ?>
