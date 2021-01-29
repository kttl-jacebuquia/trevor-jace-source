<?php /* Shop our Product Partners */ ?>
<?php get_header(); ?>
<?php

use \TrevorWP\Theme\Helper;
use \TrevorWP\Theme\Customizer\Shop_Product_Partners;

?>
	<main id="site-content" role="main" class="site-content">
		<?php /* Header */ ?>
		<?= Helper\Page_Header::text( [
				'title_top' => Shop_Product_Partners::get_val( Shop_Product_Partners::SETTING_HOME_HERO_TITLE_TOP ),
				'title'     => Shop_Product_Partners::get_val( Shop_Product_Partners::SETTING_HOME_HERO_TITLE ),
				'desc'      => Shop_Product_Partners::get_val( Shop_Product_Partners::SETTING_HOME_HERO_DESC ),
		] ) ?>

		<?php /* Feature Collections */ ?>
		<?php if ( get_query_var( 'paged' ) < 2 ) { // Show only on the first page
			if ( ! empty( $shop_ids = Shop_Product_Partners::get_val( Shop_Product_Partners::SETTING_HOME_STORIES ) ) ) {
				echo Helper\Tile_Grid::posts( ( new \WP_Query( [
						'post__in'  => $shop_ids,
						'post_type' => \TrevorWP\CPT\Donate\Prod_Partner::POST_TYPE,
				] ) )->posts, [ 'title' => Shop_Product_Partners::get_val( Shop_Product_Partners::SETTING_HOME_STORIES_TITLE ) ] );
			}
		} ?>

		<?php /* Some of our favorite items */ ?>
		<?php if ( get_query_var( 'paged' ) < 2 ) { // Show only on the first page
			if ( ! empty( $item_ids = Shop_Product_Partners::get_val( Shop_Product_Partners::SETTING_HOME_ITEMS ) ) ) {
				echo Helper\Tile_Grid::posts( ( new \WP_Query( [
						'post__in'  => $item_ids,
						'post_type' => \TrevorWP\CPT\Donate\Partner_Prod::POST_TYPE,
				] ) )->posts, [ 'title' => Shop_Product_Partners::get_val( Shop_Product_Partners::SETTING_HOME_ITEMS_TITLE ) ] );
			}
		} ?>

		<?php /* Current Partners */ ?>
		<?php if ( have_posts() ) : ?>
			<div class="partners-list">
				<?= Helper\Tile_Grid::posts( $wp_query->posts ) ?>

				<div class="trevor-pagination-default">
					<?php get_template_part( 'template-parts/pagination' ); ?>
				</div>
			</div>
		<?php endif; ?>


		<?php /* Recirculation */ ?>
		<?php $circulation_title = Shop_Product_Partners::get_val( Shop_Product_Partners::SETTING_HOME_CIRCULATION_TITLE ); ?>
		<?= Helper\Circulation_Card::render_fundraiser(); ?>
		<?= Helper\Circulation_Card::render_counselor(); ?>
	</main>

<?php get_footer();
