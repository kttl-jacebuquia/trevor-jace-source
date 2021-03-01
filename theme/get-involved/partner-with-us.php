<?php /* Get Involved: Partner with Us */ ?>
<?php get_header(); ?>
<?php

use \TrevorWP\Theme\Helper;
use \TrevorWP\Theme\Customizer\PWU;
use \TrevorWP\Theme\Helper\Circulation_Card;

?>
<main id="site-content" role="main" class="site-content">
	<?= Helper\Page_Header::split_carousel( [
			'title'         => PWU::get_val( PWU::SETTING_HOME_HERO_TITLE ),
			'desc'          => PWU::get_val( PWU::SETTING_HOME_HERO_DESC ),
			'cta_txt'       => PWU::get_val( PWU::SETTING_HOME_HERO_CTA ),
			'cta_url'       => '#',
			'carousel_data' => PWU::get_val( PWU::SETTING_HOME_HERO_CAROUSEL_DATA ),
			'swiper'        => [
					'centeredSlides' => true,
					'slidesPerView'  => 'auto'
			]
	] ) ?>

	<div class="bg-teal-tint text-teal-dark py-px120">
		<div class="container mx-auto site-content-inner text-center">
			<p class="text-px16 leading-px24 uppercase mb-3.5 md:text-px18 md:leading-px26"><?= PWU::get_val( PWU::SETTING_HOME_OUR_PHILOSOPHY_TITLE ) ?></p>
			<p class="text-px24 leading-px28 font-semibold md:mx-24 md:text-px26 md:leading-px36 xl:mx-80"><?= PWU::get_val( PWU::SETTING_HOME_OUR_PHILOSOPHY_DESC ) ?></p>
		</div>
	</div>

	<?= Helper\Tile_Grid::custom( [
			[
					'title'   => 'Corporate Partnerships',
					'desc'    => 'Our partnerships are customized to align with our corporate partners’ priorities.',
					'cta_txt' => 'Read more',
					'cta_url' => '#'
			],
			[
					'title'   => 'Product Partnerships',
					'desc'    => 'Our partnerships are customized to align with our corporate partners’ priorities.',
					'cta_txt' => 'Learn more',
					'cta_url' => '#'
			],
			[
					'title'   => 'Institutional Grants',
					'desc'    => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Auctor pellentesque id amet consectetur.',
					'cta_txt' => 'Learn more',
					'cta_url' => '#'
			],
	], [
			'title'       => PWU::get_val( PWU::SETTING_HOME_OUR_PARTNERSHIP_OFFERINGS_TITLE ),
			'desc'        => PWU::get_val( PWU::SETTING_HOME_OUR_PARTNERSHIP_OFFERINGS_DESC ),
			'smAccordion' => false,
			'tileClass'   => [ 'text-teal-dark' ],
			'class'       => [ 'text-white', 'container', 'mx-auto', 'md:pb-2' ]
	] ) ?>
	<a class="font-bold text-px16 leading-px22 tracking-em001 text-teal-dark bg-white py-3 px-8 rounded-px10 self-center -mt-10 mb-20 md:mt-0 md:mb-px60 lg:mb-px120 md:px-6 lg:text-px18 lg:leading-px26 lg:py-5 lg:px-10"
	   href="#">Become a Partner</a>

	<div class="pt-20 pb-24 text-teal-dark bg-white lg:pt-24 lg:pb-40">
		<div class="container mx-auto site-content-inner text-center">
			<h2 class="font-semibold text-px32 leading-px42 mb-3.5 mb-px60 mx-4 md:mx-20 md:mb-10 lg:text-px46 lg:leading-px56 lg:mb-20">
				There are other ways to help.</h2>

			<div class="grid grid-cols-1 gap-y-6 max-w-lg mx-auto lg:grid-cols-2 lg:gap-x-7 lg:max-w-none xl:max-w-px1240">
				<?= Circulation_Card::render_donation(); ?>
				<?= Circulation_Card::render_counselor(); ?>
			</div>

		</div>
	</div>
</main>
<?php get_footer(); ?>
