<?php /* Donate: Donate */ ?>
<?php get_header(); ?>
<?php

use \TrevorWP\Theme\Helper;
use \TrevorWP\Theme\Customizer\Donate;

?>
	<main id="site-content" role="main" class="site-content">
		<?php /* Header */ ?>

		<?= Helper\Page_Header::img_bg( [
				'title'   => Donate::get_val( Donate::SETTING_HOME_HERO_TITLE ),
				'desc'    => Donate::get_val( Donate::SETTING_HOME_HERO_DESC ),
				'img_id'  => Donate::get_val( Donate::SETTING_HOME_HERO_IMG )
		] ) ?>

		<?php /** Featured Text */ ?>
		<?php $desc = Donate::get_val( Donate::SETTING_HOME_TEXT ); ?>
		<?php if( $desc ): ?>
		<div class="featured-text donate">
			<div class="container mx-auto text-center">
				<p><?php echo $desc; ?></p>
			</div>
		</div>
		<?php endif; ?>

		<?php /* Donation Form */ ?>
		<?php $form_heading = Donate::get_val( Donate::SETTING_DONATE_HEADING ); ?>
		<?php $form_intro = Donate::get_val( Donate::SETTING_DONATE_INTRO ); ?>
		<?php $form_img = Donate::get_val( Donate::SETTING_DONATE_IMG ); ?>

		<div class="donation-form">
			<div class="donation-form__content">
				<div class="donation-form__content-wrapper">
					<h2><?= $form_heading ?></h2>
					<p><?= $form_intro ?></p>

					<form action="https://give.thetrevorproject.org/give/63307" method="get">
						<div class="frequency">
							<input type="radio" name="donation-frequency" value="once" id="once" checked="checked">
							<input type="radio" name="donation-frequency" value="monthly" id="monthly">

							<input type="radio" name="amount" value="30" id="amount-30">
							<input type="radio" name="amount" value="60" id="amount-60">
							<input type="radio" name="amount" value="120" id="amount-120">
							<input type="radio" name="amount" value="250" id="amount-250">

							<div class="frequency--choice">
								<label for="once" class="selected text-center">Give Once</label>
								<label for="monthly" class="text-center">Give Monthly</label>
							</div>

							<div class="amount">
								<div class="amount-choice">
									<label for="amount-30" class="selected">$30</label>
									<label for="amount-60">$60</label>
									<label for="amount-120">$120</label>
									<label for="amount-250">$250</label>
								</div>
								<div class="amount-custom">
									<input type="text" name="amount-custom" placeholder="$ Custom amount">
								</div>
							</div>

							<div class="submit">
								<input type="submit" value="Donate Now"/>
							</div>
						</div>
					</form>
				</div>
			</div>
			<div class="donation-form__image">
				<?php $image_attributes = wp_get_attachment_image_src( $attachment_id = $form_img, 'full' ); ?>
				<?php if ( $image_attributes ) : ?>
					<div class="image-wrapper"><img src="<?php echo $image_attributes[0]; ?>"/></div>
				<?php endif; ?>
			</div>
		</div>

		<?php /* How your Money is Used */ ?>
		<?php $_1_title = Donate::get_val( Donate::SETTING_HOME_1_TITLE ); ?>
		<?php $_1_data = Donate::get_val( Donate::SETTING_HOME_1_DATA ); ?>
		<div class="audit">
			<div class="container mx-auto">
				<h3 class="text-center"><?= $_1_title ?></h3>
				<div class="audit--card text-center grid grid-cols-1 gap-y-6 max-w-lg mx-auto lg:grid-cols-3 lg:gap-x-7 lg:max-w-none xl:max-w-px1240">

					<div class="audit-holder mobile-only">
						<div class="audit-container swiper-container" id="audit-<?php echo esc_attr( uniqid() ); ?>">
							<div class="audit-wrapper swiper-wrapper">
								<?php foreach ( $_1_data as $audit ) : ?>
									<div class="audit--card__item swiper-slide text-center">
										<?php if ( $audit['img'] ) : ?>
											<img src="<?php echo esc_url( $audit['img']['url'] ); ?>" alt="<?php echo esc_attr( $audit['desc'] ); ?>">
										<?php endif; ?>

										<p><?php echo esc_html( $audit['desc'] ); ?></p>
									</div>
								<?php endforeach; ?>
							</div>
							<div class="swiper-pagination"></div>
						</div>
					</div>

					<?php foreach ( $_1_data as $audit ) : ?>
						<div class="audit--card__item swipe-slide text-center flex flex-col justify-between hidden lg:flex">
							<?php if ( $audit['img'] ) : ?>
								<img src="<?php echo esc_url( $audit['img']['url'] ); ?>" alt="<?php echo esc_attr( $audit['desc'] ); ?>">
							<?php endif; ?>

							<p><?php echo esc_html( $audit['desc'] ); ?></p>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>


		<?php /* More Ways to Give */ ?>
		<?php $_2_title = Donate::get_val( Donate::SETTING_HOME_2_TITLE ); ?>
		<div class="card-collection">
			<div class="container mx-auto">
				<h3 class="text-center"><?= $_2_title ?></h3>

				<?= Helper\Tile_Grid::custom( [
						[
								'title'   => 'Legacy & Stock Gifts',
								'desc'    => 'Lorem ipsum',
								'cta_txt' => 'Donate Now',
								'cta_url' => '#',
						],
						[
								'title'   => 'Workplace Giving &  Match Gifts',
								'desc'    => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Odio lorem pellentesque facilisis fermentum nisl neque id. Integer.',
								'cta_txt' => 'Donate Now',
								'cta_url' => '#',
						],
						[
								'title'   => 'Memorial / In Memory  / Tribute',
								'desc'    => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Odio lorem pellentesque facilisis fermentum nisl neque id. Integer.',
								'cta_txt' => 'Donate Now',
								'cta_url' => '#',
						],
						[
								'title'   => 'Estate Planning',
								'desc'    => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Odio lorem pellentesque facilisis fermentum nisl neque id. Integer.',
								'cta_txt' => 'Donate Now',
								'cta_url' => '#',
						],
						[
								'title'   => 'Corporate Partnerships',
								'desc'    => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Odio lorem pellentesque facilisis fermentum nisl neque id. Integer.',
								'cta_txt' => 'Donate Now',
								'cta_url' => '#',
						]
				], [] ) ?>
			</div>
		</div>
		<?php /* Testimonials */ ?>
		<div class="testimonials">
			<?php $testimonial = Donate::get_val( Donate::SETTING_HOME_QUOTE_DATA ); ?>
			<?= Helper\Carousel::testimonials( $testimonial ) ?>
		</div>


		<?php /* Charity Navigator  */ ?>
		<?php $nav_title = Donate::get_val( Donate::SETTING_HOME_NAVIGATOR_TITLE ); ?>
		<?php $nav_desc = Donate::get_val( Donate::SETTING_HOME_NAVIGATOR_DESC ); ?>
		<?php $nav_data = Donate::get_val( Donate::SETTING_HOME_NAVIGATOR_DATA ); ?>

		<div class="navigator">
			<div class="container mx-auto">
				<div class="navigator--wrapper text-center">
					<h3><?= $nav_title ?></h3>
					<p><?= $nav_desc ?></p>
					<div>
						<div class="navigator-container swiper-container" id="nav-<?= uniqid(); ?>">
							<div class="navigator-data swiper-wrapper">
								<?php foreach ( $nav_data as $navigator ): ?>
									<div class="navigator-data__item swiper-slide text-center <?php echo empty($navigator['img']) ? 'no-image' : '' ?>">
										<?php if( $navigator['img'] ): ?>
											<img src="<?= $navigator['img']["url"] ?>" alt="<?= $navigator['name'] ?>">
										<?php else: ?>	
											<div class="image-placeholder"></div>
										<?php endif; ?>
										
										<h2><?= $navigator['name'] ?></h2>
									</div>
								<?php endforeach; ?>
							</div>
							<div class="swiper-pagination"></div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<?php /* FAQ */ ?>
		<?php $faq_title = Donate::get_val( Donate::SETTING_HOME_FAQ_TITLE ); ?>
		<?php $faq_data = Donate::get_val( Donate::SETTING_HOME_FAQ_DATA ); ?>
		<?php $faq_footer = Donate::get_val( Donate::SETTING_HOME_FAQ_FOOTER ); ?>

		<div class="faqs">
			<div class="container mx-auto">
				<h3><?= $faq_title ?></h3>

				<div class="faq-list">
					<?php foreach ( $faq_data as $faq ): ?>
						<div class="faq-list__item">
							<div class="faq-list__heading">
								<h4>
									<svg class="plus" width="20" height="20" viewBox="0 0 20 20" fill="none"
										 xmlns="http://www.w3.org/2000/svg">
										<path fill-rule="evenodd" clip-rule="evenodd"
											  d="M10 0C9.44771 0 9 0.447715 9 1V9H1C0.447715 9 0 9.44771 0 10C0 10.5523 0.447715 11 1 11H9V19C9 19.5523 9.44771 20 10 20C10.5523 20 11 19.5523 11 19V11H19C19.5523 11 20 10.5523 20 10C20 9.44771 19.5523 9 19 9H11V1C11 0.447715 10.5523 0 10 0Z"
											  fill="#003A48"/>
									</svg>
									
									<svg class="minus" width="20" height="20" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path fill-rule="evenodd" clip-rule="evenodd" d="M25.1108 13.0378C25.1108 13.8662 24.4392 14.5378 23.6108 14.5378L2.6134 14.5381C1.78497 14.5382 1.11341 13.8666 1.11342 13.0382C1.11343 12.2097 1.78502 11.5382 2.61344 11.5381L23.6108 11.5378C24.4393 11.5378 25.1108 12.2094 25.1108 13.0378Z" fill="#003A48"/>
									</svg>

									<?= $faq['label'] ?>
								</h4>
							</div>
							<div class="faq-list__content">
								<?= $faq['content'] ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>

				<div class="faq-footer">
					<p><?= $faq_footer ?></p>
				</div>
			</div>
		</div>

		<?php /* Recirculation */ ?>
		<?php $circulation_title = Donate::get_val( Donate::SETTING_HOME_CIRCULATION_TITLE ); ?>
		<?php /* Two Up: Other Ways to Help   */ ?>
		<div class="other-ways">
			<div class="container mx-auto text-center">
				<h3 class="mb-px60 md:mb-px40 lg:mb-px90"><?= $circulation_title ?></h3>
				<div class="grid grid-cols-1 gap-y-6 max-w-lg mx-auto lg:grid-cols-2 lg:gap-x-7 lg:max-w-none xl:max-w-px1240">
					<?= Helper\Circulation_Card::render_fundraiser(); ?>
					<?= Helper\Circulation_Card::render_counselor(); ?>
				</div>
			</div>
		</div>
	</main>

<?php get_footer();
