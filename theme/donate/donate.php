<?php /* Donate: Donate */ ?>
<?php get_header(); ?>
<?php

use \TrevorWP\Theme\Helper;
use \TrevorWP\Theme\Customizer\Donate;

?>
	<main id="site-content" role="main" class="site-content">
		<?php /* Header */ ?>
		<?= Helper\Page_Header::split_img( [
				'title'  => Donate::get_val( Donate::SETTING_HOME_HERO_TITLE ),
				'desc'   => Donate::get_val( Donate::SETTING_HOME_HERO_DESC ),
				'img_id' => Donate::get_val( Donate::SETTING_HOME_HERO_IMG ),
		] ) ?>

		<?php /* Form */ ?>

		<?php /* 1 */ ?>
		<?php $_1_title = Donate::get_val( Donate::SETTING_HOME_1_TITLE ); ?>
		<?php $_1_data = Donate::get_val( Donate::SETTING_HOME_1_DATA ); ?>

		<?php /* 2 */ ?>
		<?php $_2_title = Donate::get_val( Donate::SETTING_HOME_2_TITLE ); ?>
		<?= Helper\Tile_Grid::custom( [
				[
						'title'   => 'Legacy & Stock Gifts',
						'desc'    => 'Lorem ipsum',
						'cta_txt' => 'Donate Now',
						'cta_url' => '#',
				]
		], [] ) ?>

		<?php /* Testimonials */ ?>
		<?= Helper\Carousel::testimonials( Donate::get_val( Donate::SETTING_HOME_QUOTE_DATA ) ) ?>

		<?php /* Charity Navigator */ ?>
		<?php $nav_data = Donate::get_val( Donate::SETTING_HOME_NAVIGATOR_DATA ); ?>

		<?php /* FAQ */ ?>
		<?php $faq_title = Donate::get_val( Donate::SETTING_HOME_FAQ_TITLE ); ?>
		<?php $faq_data = Donate::get_val( Donate::SETTING_HOME_FAQ_DATA ); ?>
		<?php $faq_footer = Donate::get_val( Donate::SETTING_HOME_FAQ_FOOTER ); ?>

		<?php /* Recirculation */ ?>
		<?php $circulation_title = Donate::get_val( Donate::SETTING_HOME_CIRCULATION_TITLE ); ?>
		<?= Helper\Circulation_Card::render_fundraiser(); ?>
		<?= Helper\Circulation_Card::render_counselor(); ?>
	</main>

<?php get_footer();
