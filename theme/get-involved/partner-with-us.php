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



	<div class="pt-20 pb-24 text-teal-dark bg-white lg:pt-24">
		<div class="container mx-auto site-content-inner text-center">
			<h2 class="font-semibold text-px32 leading-px42 mb-3.5 mb-px60 mx-4 md:mx-20 md:mb-10 lg:text-px46 lg:leading-px56 lg:mb-20">
				There are other ways to help.</h2>

			<div class="mx-auto grid grid-cols-1 gap-y-7 gap-x-8 md:w-3/4 lg:w-full lg:grid-cols-2 xl:w-3/4">
				<?= Circulation_Card::render_donation(); ?>
				<?= Circulation_Card::render_counselor(); ?>
			</div>

		</div>
	</div>
</main>
<?php get_footer(); ?>
