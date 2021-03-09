<?php get_header(); ?>

<?php
$current = ( (int) ( $wp_query->get( 'paged' ) ?: 1 ) );
$total   = $wp_query->max_num_pages ?? 1;
$links   = paginate_links( [
		'total'     => $total,
		'current'   => $current,
		'show_all'  => false,
		'end_size'  => 0,
		'mid_size'  => 0,
		'prev_next' => true,
		'type'      => 'array',
		'next_text' => 'Load More'
] );
?>

<?= TrevorWP\Theme\Helper\Page_Header::text( [
		'title_top' => 'ADVOCATE FOR CHANGE',
		'title'     => 'Our Federal Priorities',
		'desc'      => 'At The Trevor Project, we have a few priorities that we want to let members of congress know that we care about.',
		'cta_txt'   => 'Take Action Now',
		'cta_url'   => '#',
] ) ?>

<main id="site-content" role="main" class="bg-white">
	<div class="container mx-auto pt-10 md:pt-px50 lg:pt-20">

		<?php if ( $page_sorter = \TrevorWP\Theme\Helper\Sorter::get_page_sorter() ) { ?>
			<?= $page_sorter->render(); ?>
		<?php } ?>

		<?= \TrevorWP\Theme\Helper\Tile_Grid::posts( $wp_query->posts, [
				'tileClass' => [ 'border', 'border-blue_green', 'border-opacity-50' ]
		] ) ?>

		<?php get_template_part( 'template-parts/ajax-pagination' ); ?>
		<div class="anchor-container text-center w-full mb-px120 md:mb-px80 xl:mb-px100">
			<a class="take-action-cta inline-block bg-teal-dark text-white text-px16 leading-px22 xl:text-px18 xl:leading-px26 font-bold text-center"
			   href="#!">Take Action Now</a>
		</div>
	</div>
</main> <!-- #site-content -->

<?php get_footer(); ?>
