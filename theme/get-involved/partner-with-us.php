<?php /* Get Involved: Partner with Us */ ?>
<?php get_header(); ?>
<?php

use \TrevorWP\Theme\Helper;
use \TrevorWP\Theme\Customizer\PWU;
use \TrevorWP\Theme\Helper\Circulation_Card;

?>
<main id="site-content" role="main" class="site-content">
	<?= Helper\Page_Header::split_carousel( [
			'title'   => PWU::get_val( PWU::SETTING_HOME_HERO_TITLE ),
			'desc'    => PWU::get_val( PWU::SETTING_HOME_HERO_DESC ),
			'cta_txt' => PWU::get_val( PWU::SETTING_HOME_HERO_CTA ),
			'cta_url' => '#',
			'carousel_data' => PWU::get_val( PWU::SETTING_HOME_HERO_CAROUSEL_DATA ),
	] ) ?>

	<div class="bg-teal-tint text-teal-dark py-px120">
		<div class="container mx-auto site-content-inner text-center">
			<p class="text-px16 leading-px24 uppercase mb-3.5 md:text-px18 md:leading-px26">Our Philosophy</p>
			<p class="text-px24 leading-px28 font-semibold md:mx-24 md:text-px26 md:leading-px36 xl:mx-80">Corporate partnerships empower us to serve every LGBTQ young person that needs our support.</p>
		</div>
	</div>

	<div class="pt-20 pb-20 text-white md:pt-24 lg:pt-36 lg:pb-px120">
		<div class="container mx-auto site-content-inner text-center">
			<h2 class="font-semibold text-px32 leading-px42 mb-3.5 md:mx-40 lg:text-px46 lg:leading-px56">Our Partnership Offerings</h2>
			<p class="text-px18 leading-px26 mb-px50 md:mx-9 md:mb-px50 lg:text-px26 lg:leading-px36 lg:mb-20 lg:mx-44">These are many ways we can work together to save young LGBTQ lives.</p>

			<div class="tile-fixed grid grid-cols-1 gap-y-7 md:grid-cols-2 md:gap-7 lg:grid-cols-3">
				<div class="tile bg-white text-teal-dark rounded-px10 text-left px-7 pt-7 pb-16 md:shadow-md">
					<h3 class="text-px24 leading-px28 tracking-em001 font-semibold mb-3">Corporate Partnerships</h3>
					<p class="text-px16 leading-px24 mb-4">Our partnerships are customized to align with our corporate partners’ priorities.</p>
					<a class="font-bold text-px18 leading-px24 tracking-em001 border-b-2 border-teal-dark" href="#">Learn more</a>
				</div>
				<div class="tile bg-white text-teal-dark rounded-px10 text-left px-7 pt-7 pb-16 md:shadow-md">
					<h3 class="text-px24 leading-px28 tracking-em001 font-semibold mb-3">Product Partnerships</h3>
					<p class="text-px16 leading-px24 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Auctor pellentesque id amet consectetur.</p>
					<a class="font-bold text-px18 leading-px24 tracking-em001 border-b-2 border-teal-dark" href="#">Learn more</a>
				</div>
				<div class="tile bg-white text-teal-dark rounded-px10 text-left px-7 pt-7 pb-16 md:shadow-md">
					<h3 class="text-px24 leading-px28 tracking-em001 font-semibold mb-3">Institutional Grants</h3>
					<p class="text-px16 leading-px24 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Auctor pellentesque id amet consectetur.</p>
					<a class="font-bold text-px18 leading-px24 tracking-em001 border-b-2 border-teal-dark" href="#">Learn more</a>
				</div>
			</div>

			<a class="font-bold text-px16 leading-px22 tracking-em001 text-teal-dark bg-white py-3 px-8 mt-px50 rounded-px10 self-center md:mt-px60 md:px-6 lg:mt-px50 lg:text-px20 lg:leading-px26 lg:py-5 lg:px-10" href="#">Become a Partner</a>
		</div>
	</div>

	<div class="pt-20 pb-24 text-teal-dark bg-white lg:pt-24">
		<div class="container mx-auto site-content-inner text-center">
			<h2 class="font-semibold text-px32 leading-px42 mb-3.5 mb-px60 mx-4 md:mx-20 md:mb-10 lg:text-px46 lg:leading-px56 lg:mb-20">There are other ways to help.</h2>

			<div class="grid grid-cols-1 gap-y-6 max-w-lg mx-auto lg:grid-cols-2 lg:gap-x-7 lg:max-w-none xl:max-w-px1240">
				<?= Circulation_Card::render_donation(); ?>
				<?= Circulation_Card::render_counselor(); ?>
			</div>

		</div>
	</div>
</main>
<?php get_footer(); ?>
