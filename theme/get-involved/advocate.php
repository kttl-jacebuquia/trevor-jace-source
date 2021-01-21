<?php /* Get Involved: Advocate For Change */ ?>
<?php get_header(); ?>
<?php

use \TrevorWP\Theme\Customizer\Advocacy;
use \TrevorWP\Theme\Helper\Thumbnail;

?>
<main id="site-content" role="main" class="site-content">
	<div class="gi_main_lp-hero">
		<div class="gi_main_lp-hero-inner">
			<div class="gi_main_lp-hero-content-wrap">
				<h1 class="heading-lg-tilted gi_main_lp-hero-title">
					<?= Advocacy::get_val( Advocacy::SETTING_HOME_HERO_TITLE ) ?>
				</h1>
				<p class="gi_main_lp-hero-desc"><?= Advocacy::get_val( Advocacy::SETTING_HOME_HERO_DESC ) ?></p>
				<a href="#"
				   class="gi_main_lp-hero-cta"><?= Advocacy::get_val( Advocacy::SETTING_HOME_HERO_CTA ) ?></a>
			</div>
			<div class="gi_main_lp-hero-img-wrap">
				<?= Thumbnail::print_img_variants( [
						[
								intval( Advocacy::get_val( Advocacy::SETTING_HOME_HERO_IMG ) ),
								Thumbnail::variant( Thumbnail::SCREEN_MD, null, Thumbnail::SIZE_MD, [
										'class' => [
												'object-center',
												'object-cover',
												'w-full',
												'h-full',
										],
								] ),
						],
				] ) ?>
			</div>
		</div>
	</div>

	<div class="gi_main_lp-main-content">
		<div class="gi_main_lp-carousel">
			<h2 class="gi_main_lp-carousel-title"><?= Advocacy::get_val( Advocacy::SETTING_HOME_CAROUSEL_TITLE ) ?></h2>
			<div class="gi_main_lp-carousel-wrap"></div>
		</div>

		<div class="gi_main_lp-our-work">
			<h2 class="gi_main_lp-our-work-title"><?= Advocacy::get_val( Advocacy::SETTING_HOME_OUR_WORK_TITLE ) ?></h2>
			<p class="gi_main_lp-our-work-desc"><?= Advocacy::get_val( Advocacy::SETTING_HOME_OUR_WORK_DESC ) ?></p>
			<div class="div gi_main_lp-our-work-grid"></div>
		</div>

		<div class="gi_main_lp-quote"></div>

		<div class="gi_main_lp-posts">
			<h2 class="gi_main_lp-posts-title"><?= Advocacy::get_val( Advocacy::SETTING_HOME_BILL_TITLE ) ?></h2>
			<p class="gi_main_lp-posts-desc"><?= Advocacy::get_val( Advocacy::SETTING_HOME_BILL_DESC ) ?></p>
			<div class="gi_main_lp-posts-grid"></div>
			<a href="#" class="gi_main_lp-posts-link">View All</a>
		</div>

		<div class="gi_main_lp-posts">
			<h2 class="gi_main_lp-posts-title"><?= Advocacy::get_val( Advocacy::SETTING_HOME_LETTER_TITLE ) ?></h2>
			<p class="gi_main_lp-posts-desc"><?= Advocacy::get_val( Advocacy::SETTING_HOME_LETTER_DESC ) ?></p>
			<div class="gi_main_lp-posts-grid"></div>
			<a href="#" class="gi_main_lp-posts-link">View All</a>
		</div>

		<div class="gi_main_lp-tan-wrap">
			<a href="#" class="gi_main_lp-tan-btn"><?= Advocacy::get_val( Advocacy::SETTING_HOME_TAN_CTA ) ?></a>
		</div>
	</div>

	<div class="gi_main_lp-partners">
		<h2 class="gi_main_lp-partners-title"><?= Advocacy::get_val( Advocacy::SETTING_HOME_PARTNER_ORG_TITLE ) ?></h2>
		<h2 class="gi_main_lp-partners-desc"><?= Advocacy::get_val( Advocacy::SETTING_HOME_PARTNER_ORG_DESC ) ?></h2>
		<h2 class="gi_main_lp-partners-list"></h2>
	</div>
</main>
<?php get_footer(); ?>
