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

		<div class="custom-select">
			<ul>
				<li class="label">
					<button>Sort By: Newest to Oldest</button>
					<ul class="dropdown">
						<li class="active">Sort By: Newest to Oldest</li>
						<li>Sort By: Oldest to Newest</li>
					</ul>
				</li>
			</ul>
		</div>

		<?= \TrevorWP\Theme\Helper\Tile_Grid::posts( $wp_query->posts, [
				'tileClass' => [ 'border', 'border-blue_green', 'border-opacity-50' ]
		] ) ?>

		<?php get_template_part( 'template-parts/ajax-pagination' ); ?>
	</div>
</main> <!-- #site-content -->

<?php get_footer(); ?>
