<?php /* Donate: Product Partnerships */ ?>
<?php get_header(); ?>
<?php

use \TrevorWP\Theme\Helper;
use \TrevorWP\Theme\Customizer\Product_Partnerships;

?>
	<main id="site-content" role="main" class="site-content product-partnerships">
		<?php
		echo Helper\Page_Header::text(
			array(
				'title_top' => Product_Partnerships::get_val( Product_Partnerships::SETTING_HOME_HERO_TITLE_TOP ),
				'title'     => Product_Partnerships::get_val( Product_Partnerships::SETTING_HOME_HERO_TITLE ),
				'desc'      => Product_Partnerships::get_val( Product_Partnerships::SETTING_HOME_HERO_DESC ),
				'cta_txt'   => Product_Partnerships::get_val( Product_Partnerships::SETTING_HOME_HERO_CTA ),
			)
		)
		?>

		<?php
		if ( ! empty( $partner_ids = Product_Partnerships::get_val( Product_Partnerships::SETTING_HOME_STORIES ) ) ) {
			?>
			<div class="featured-stories">
				<?php
				echo Helper\Tile_Grid::posts(
					( new \WP_Query(
						array(
							'post__in'  => explode( ',', $partner_ids ),
							'post_type' => \TrevorWP\CPT\Donate\Prod_Partner::POST_TYPE,
						)
					) )->posts,
					array( 'title' => Product_Partnerships::get_val( Product_Partnerships::SETTING_HOME_STORIES_TITLE ) )
				);
				?>
			</div>
			<?php
		}
		?>

		<div class="bg-white">
			<?php
			$partners = get_posts(
				array(
					'numberposts' => - 1,
					'orderby'     => 'name',
					'order'       => 'ASC',
					'post_type'   => \TrevorWP\CPT\Donate\Prod_Partner::POST_TYPE,

				)
			);
			?>
			<div class="current-partners">
				<div class="current-partners__title"> <?php echo esc_html( Product_Partnerships::get_val( Product_Partnerships::SETTING_HOME_CURRENTS_TITLE ) ); ?></div>
				<div class="current-partners__container container mx-auto flex justify-center flex-wrap -mt-px100 lg:mt-0">
					<?php foreach ( $partners as $partner ) { ?>
						<div class="image-container w-1/2 md:w-1/4 lg:w-1/4 flex items-center content-center mt-px100 lg:mt-0" data-aspectRatio="2:1">
							<?php
								$partner_url = \TrevorWP\Meta\Post::get_store_url( $partner->ID );
								$has_url     = ! empty( $partner_url );
							?>
							<a class="mx-auto flex items-center content-center"
									rel="nofollow noreferrer noopener"
									target="_blank" href="<?php echo $has_url ? esc_attr( $partner_url ) : '#'; ?>">
									<?php
									echo wp_get_attachment_image(
										get_post_thumbnail_id( $partner ),
										'full',
										false,
										array(
											'class' => implode(
												' ',
												array(
													'mx-auto',
													'object-center',
													'object-contain',
													'max-h-px100',
													'w-auto',
													'max-w-4/5',
													'lg:max-w-3/5',
												)
											),
										)
									);
									?>
							</a>
						</div>
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
					<h3 class="banner__title"><?php echo esc_attr( $shop_title ); ?></h3>
					<p class="banner__description"><?php echo esc_attr( $shop_description ); ?></p>
					<a href="<?php echo esc_url( $shop_cta ); ?>" class="banner__cta font-bold" target="_blank">Shop Now</a>
				</div>
			</div>
		</div>

		<div class="cards">
			<div class="cards__container container mx-auto flex flex-row flex-wrap">
				<h3 class="cards__title font-bold text-px32 lg:text-px46 leading-px42 lg:leading-px56 text-center w-full">
					There are other ways to help.</h3>
				<?php echo Helper\Circulation_Card::render_fundraiser(); ?>
				<?php echo Helper\Circulation_Card::render_counselor(); ?>
			</div>
	</main>
<?php
get_footer();
