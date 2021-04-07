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
				],
				'bg'            => 'blue_green',
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
							'class'	=> ['product-grid'],
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
										'class'	=> ['product-grid'],
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

			<?php
				$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
				$query = new WP_Query( [
					'post_type'   		=> \TrevorWP\CPT\Donate\Prod_Partner::POST_TYPE,
					'orderby'					=> 'ID',
					'order'						=> 'ASC',
					'posts_per_page'	=> 6,
					'paged'						=> $paged,
				] );
			?>

			<?php if ( $query->have_posts() ) : ?>
				<div class="partners-list">
					<?php echo Helper\Tile_Grid::posts( $query->posts,
							[
									'title'     => Shop_Product_Partners::get_val( Shop_Product_Partners::SETTING_HOME_LIST_TITLE ),
									'tileClass' => [ 'product-card' ],
									'class'	=> ['product-grid'],
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

			<div class="banner container">
				<div class="banner__inner">
					<h3 class="banner__title"><?= esc_attr( $banner_title ) ?></h3>
					<p class="banner__description"><?= esc_attr( $banner_desc ) ?></p>
					<a href="<?= esc_url( $banner_cta ) ?>" class="banner__cta font-bold" target="_blank">Learn More</a>
				</div>
			</div>
		</div>

		<?php /* Recirculation */ ?>
		<?= Helper\Circulation_Card::render_circulation(
			Shop_Product_Partners::get_val( Shop_Product_Partners::SETTING_HOME_CIRCULATION_TITLE ),
			null,
			[
				'fundraiser',
				'counselor'
			],
			[
				'container' => 'cards',
			]
		); ?>
	</main>

<?php get_footer();
