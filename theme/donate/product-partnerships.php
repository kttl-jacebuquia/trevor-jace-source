<?php /* Donate: Product Partnerships */ ?>
<?php get_header(); ?>
<?php

use \TrevorWP\Theme\Helper;
use \TrevorWP\Theme\Customizer\Product_Partnerships;

?>
	<main id="site-content" role="main" class="site-content product-partnerships">
		<?= Helper\Page_Header::text( [
				'title_top' => Product_Partnerships::get_val( Product_Partnerships::SETTING_HOME_HERO_TITLE_TOP ),
				'title'     => Product_Partnerships::get_val( Product_Partnerships::SETTING_HOME_HERO_TITLE ),
				'desc'      => Product_Partnerships::get_val( Product_Partnerships::SETTING_HOME_HERO_DESC ),
				'cta_txt'   => Product_Partnerships::get_val( Product_Partnerships::SETTING_HOME_HERO_CTA ),
		] ) ?>

		<?php
		if ( ! empty ( $partner_ids = Product_Partnerships::get_val( Product_Partnerships::SETTING_HOME_STORIES ) ) ) {
			?>
			<div class="featured-stories">
				<?php
				echo Helper\Tile_Grid::posts( ( new \WP_Query( [
						'post__in'  => explode( ",", $partner_ids ),
						'post_type' => \TrevorWP\CPT\Donate\Prod_Partner::POST_TYPE,
				] ) )->posts, [ 'title' => Product_Partnerships::get_val( Product_Partnerships::SETTING_HOME_STORIES_TITLE ) ] );
				?>
			</div>
			<?php
		} ?>

		<div class="bg-white">
			<?php
			$partners = get_posts( [
					'numberposts' => - 1,
					'orderby'     => 'name',
					'order'       => 'ASC',
					'post_type'   => \TrevorWP\CPT\Donate\Prod_Partner::POST_TYPE,

			] );
			?>
			<div class="current-partners">
				<div class="current-partners__title"> <?= Product_Partnerships::get_val( Product_Partnerships::SETTING_HOME_CURRENTS_TITLE ) ?></div>
				<div class="current-partners__container container mx-auto">
					<?php foreach ( $partners as $partner ) { ?>
						<?php echo get_the_post_thumbnail( $partner, 'full', [ 'class' => 'partner-thumbnail' ] ); ?>
					<?php } ?>
				</div>
			</div>

			<?php
			$shop_title       = Product_Partnerships::get_val( Product_Partnerships::SETTING_HOME_SHOP_TITLE );
			$shop_description = Product_Partnerships::get_val( Product_Partnerships::SETTING_HOME_SHOP_DESC );
			$shop_cta         = Product_Partnerships::get_val( Product_Partnerships::SETTING_HOME_SHOP_CTA );
			?>

			<div class="banner">
				<div class="banner__inner">
					<h3 class="banner__title"><?= esc_attr( $shop_title ) ?></h3>
					<p class="banner__description"><?= esc_attr( $shop_description ) ?></p>
					<a href="<?= esc_url( $shop_cta ) ?>" class="banner__cta" target="_blank">Shop Now</a>
				</div>
			</div>
		</div>

		<div class="cards">
			<div class="cards__container container mx-auto flex flex-row flex-wrap">
				<h3 class="cards__title font-bold text-px32 lg:text-px46 leading-px42 lg:leading-px56 text-center w-full">
					There are other ways to help.</h3>
				<?= Helper\Circulation_Card::render_fundraiser(); ?>
				<?= Helper\Circulation_Card::render_counselor(); ?>
			</div>
	</main>
<?php get_footer();
