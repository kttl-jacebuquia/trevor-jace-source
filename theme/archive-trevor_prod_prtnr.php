<?php /* Shop our Product Partners */ ?>
<?php get_header(); ?>
<?php

use \TrevorWP\Theme\Helper;
use \TrevorWP\Theme\Customizer\Shop_Product_Partners;

?>
	<main id="site-content" role="main" class="site-content product-partner">
		<?php /* Header */ ?>
		<?= Helper\Page_Header::split_carousel( [
				'title_top'     => Shop_Product_Partners::get_val( Shop_Product_Partners::SETTING_HOME_HERO_TITLE_TOP ),
				'title'         => Shop_Product_Partners::get_val( Shop_Product_Partners::SETTING_HOME_HERO_TITLE ),
				'desc'          => Shop_Product_Partners::get_val( Shop_Product_Partners::SETTING_HOME_HERO_DESC ),
				'carousel_data' => Shop_Product_Partners::get_val( Shop_Product_Partners::SETTING_HOME_HERO_CAROUSEL ),
				'swiper'        => [
						'centeredSlides' => true,
						'slidesPerView'  => 'auto'
				]
		] ); ?>

		<?php
		/**
		 * Feature Collections
		 *
		 * Product Items should go here...
		 * (e.g.
		 *    Bryton Sneaker In Rainbow Canvas of Dolce Vita,
		 *    Face Masks of Abercrombie & Fitch,
		 *    PRISM Exfoliating Glow Serum & Facial of HERBIVORE
		 * )
		 *
		 */
		?>
		<?php if ( get_query_var( 'paged' ) < 2 ) { // Show only on the first page
			if ( ! empty( $item_ids = Shop_Product_Partners::get_val( Shop_Product_Partners::SETTING_HOME_STORIES ) ) ) {
				?>
				<div class="featured-collections">
					<?php
					echo Helper\Tile_Grid::posts( ( new \WP_Query( [
							'orderby'   => 'title',
							'order'     => 'ASC',
							'post__in'  => explode( ",", $item_ids ),
							'post_type' => \TrevorWP\CPT\Donate\Partner_Prod::POST_TYPE,
					] ) )->posts, [
							'title'     => Shop_Product_Partners::get_val( Shop_Product_Partners::SETTING_HOME_STORIES_TITLE ),
							'tileClass' => [ 'product-card' ],
					] );
					?>
				</div>

			<?php }
		} ?>

		<div class="bg-white">
			<?php
			/**
			 * Some of our favorite items
			 *
			 * Product Items should go here
			 * (e.g.
			 *    Bryton Sneaker In Rainbow Canvas of Dolce Vita,
			 *    Face Masks of Abercrombie & Fitch,
			 *    PRISM Exfoliating Glow Serum & Facial of HERBIVORE
			 * )
			 *
			 *
			 */
			?>
			<?php if ( get_query_var( 'paged' ) < 2 ) { // Show only on the first page
				if ( ! empty( $item_ids = Shop_Product_Partners::get_val( Shop_Product_Partners::SETTING_HOME_ITEMS ) ) ) {
					?>
					<div class="favorite-items">
						<?php
						echo Helper\Tile_Grid::posts( ( new \WP_Query( [
								'orderby'   => 'title',
								'order'     => 'ASC',
								'post__in'  => explode( ",", $item_ids ),
								'post_type' => \TrevorWP\CPT\Donate\Partner_Prod::POST_TYPE,
						] ) )->posts,
								[
										'title'     => Shop_Product_Partners::get_val( Shop_Product_Partners::SETTING_HOME_ITEMS_TITLE ),
										'tileClass' => [ 'clickable-card', 'product-card' ],
								] );
						?>
					</div>
				<?php }
			} ?>

			<?php /* Current Partners */
			/**
			 * Current Partners
			 *
			 * Product Partners should go here
			 * (e.g.
			 *    Dolce Vita,
			 *    Abercrombie & Fitch
			 *    Chubbies, etc...
			 * )
			 */
			?>

			<?php if ( have_posts() ) : ?>
				<div class="partners-list">
					<?php echo Helper\Tile_Grid::posts( $wp_query->posts,
							[
									'title'     => Shop_Product_Partners::get_val( Shop_Product_Partners::SETTING_HOME_LIST_TITLE ),
									'tileClass' => [ 'product-card' ],
							] );
					?>

					<?php get_template_part( 'template-parts/ajax-pagination', null, [
							'data-containerSelector' => '.partners-list .tile-grid-container'
					] ); ?>
				</div>
			<?php endif; ?>

			<?php
			$banner_title = Shop_Product_Partners::get_val( Shop_Product_Partners::SETTING_HOME_BANNER_TITLE );
			$banner_desc  = Shop_Product_Partners::get_val( Shop_Product_Partners::SETTING_HOME_BANNER_DESC );
			$banner_cta   = Shop_Product_Partners::get_val( Shop_Product_Partners::SETTING_HOME_BANNER_CTA );
			?>

			<div class="banner">
				<div class="banner__inner">
					<h3 class="banner__title"><?= esc_attr( $banner_title ) ?></h3>
					<p class="banner__description"><?= esc_attr( $banner_desc ) ?></p>
					<a href="<?= esc_url( $banner_cta ) ?>" class="banner__cta font-bold" target="_blank">Learn More</a>
				</div>
			</div>
		</div>

		<div class="cards">
			<div class="cards__container container mx-auto flex flex-row flex-wrap">
				<h3 class="cards__title font-bold text-px32 lg:text-px46 leading-px42 lg:leading-px56 text-center w-full">
					<?= Shop_Product_Partners::get_val( Shop_Product_Partners::SETTING_HOME_CIRCULATION_TITLE ) ?>
				</h3>
				<?= Helper\Circulation_Card::render_fundraiser(); ?>
				<?= Helper\Circulation_Card::render_counselor(); ?>
			</div>
		</div>
	</main>

<?php get_footer();
