<?php /* Shop our Product Partners */ ?>
<?php get_header(); ?>
<?php

use \TrevorWP\Theme\Helper;
use \TrevorWP\Theme\Customizer\Research_Briefs as Page;

?>
	<main id="site-content" role="main" class="site-content product-partner">
		<?php /* Header */ ?>
		<?= Helper\Page_Header::text( [
				'title' => Page::get_val( Page::SETTING_HEADER_TITLE ),
				'desc'  => Page::get_val( Page::SETTING_HEADER_DESC ),
		] ); ?>

		<main id="site-content" role="main" class="bg-white">
			<div class="container mx-auto pt-10 md:pt-px50 lg:pt-20">
				<?php if ( $page_sorter = \TrevorWP\Theme\Helper\Sorter::get_page_sorter() ) { ?>
					<?= $page_sorter->render(); ?>
				<?php } ?>

				<?= \TrevorWP\Theme\Helper\Tile_Grid::posts( $wp_query->posts, [
						'tileClass' => [ 'border', 'border-blue_green', 'border-opacity-50' ]
				] ) ?>

				<?php get_template_part( 'template-parts/ajax-pagination' ); ?>
			</div>
		</main> <!-- #site-content -->

	</main>

<?php get_footer();
