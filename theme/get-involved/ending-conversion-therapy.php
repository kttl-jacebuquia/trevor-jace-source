<?php /* Get Involved: Ending Conversion Therapy */ ?>
<?php get_header(); ?>
<?php

use \TrevorWP\Theme\Customizer\ECT;
use \TrevorWP\Theme\Helper\Page_Header;

?>
<main id="site-content" role="main" class="site-content">
	<?= Page_Header::split_img( [
			'img_id'    => ECT::get_val( ECT::SETTING_HOME_HERO_IMG ),
			'title_top' => ECT::get_val( ECT::SETTING_HOME_HERO_TITLE_TOP ),
			'title'     => ECT::get_val( ECT::SETTING_HOME_HERO_TITLE ),
			'desc'      => ECT::get_val( ECT::SETTING_HOME_HERO_DESC ),
			'cta_txt'   => ECT::get_val( ECT::SETTING_HOME_HERO_CTA ),
			'cta_url'   => '#',
	] ) ?>

	<div class="py-14 text-teal-dark bg-white md:py-20 lg:py-28">
		<div class="container mx-auto site-content-inner text-center">
			<h2 class="font-bold text-px32 leading-px42 mb-3.5 mx-6 md:mx-0 lg:text-px46 lg:leading-px56">
				<?= ECT::get_val( ECT::SETTING_HOME_1_TITLE ) ?>
			</h2>
			<p class="mx-auto text-px18 leading-px26 lg:text-px22 lg:leading-px32 lg:w-5/6">
				<?= ECT::get_val( ECT::SETTING_HOME_1_DESC ) ?>
			</p>
		</div>
	</div>

	<div class="flex flex-col lg:flex-row">
		<div class="pt-px72 pb-px60 text-white bg-blue_green md:pt-10 lg:py-0 lg:flex-1 lg:flex lg:items-center lg:w-2/6 lg:flex-auto">
			<div class="container mx-auto site-content-inner text-center lg:px-20">
				<h2 class="font-bold text-px32 leading-px42 mb-3.5 mx-6 md:mx-40 md:text-px24 md:leading-px28 md:mx-54 lg:text-px32 lg:leading-px42 lg:mx-0">
					Where we’re making an impact</h2>
				<p class="text-px18 leading-px26 mb-7 md:mx-54 lg:text-px20 lg:leading-px26 lg:mx-0 lg:mb-px50">Track
					our progress through this interactive map.</p>

				<form>
					<label for="search" class="sr-only">Search</label>
					<div class="flex full-w relative md:w-80 md:mx-auto lg:mx-0 lg:w-full">
						<input id="search" class="bg-white rounded-px10 text-blue_green py-5 px-7 flex-1"/>
						<button type="submit" class="absolute inset-0"><i
									class="trevor-ti-search text-px20 mx-7 lg:text-px28 text-blue_green"></i></button>
					</div>
				</form>
			</div>
		</div>
		<div class="h-px600 bg-gray relative flex lg:w-4/6 lg:flex-auto">
			<div class="pt-2 px-7 pb-3 bg-teal-tint flex self-start w-full flex-nowrap">
				<a href="#"
				   class="text-px14 leading-px18 rounded-full text-white bg-teal-dark font-bold py-2 px-3.5 mr-0.5">Passed</a>
				<a href="#" class="text-px14 leading-px18 rounded-full text-teal-dark py-2 px-3.5 mr-0.5">Pending</a>
				<a href="#" class="text-px14 leading-px18 rounded-full text-teal-dark py-2 px-3.5 mr-0.5">Regulations &
					Executive Orders</a>
			</div>
			<a href="#"
			   class="text-white text-px16 leading-px22 rounded-full py-3 px-7 bg-teal-dark absolute bottom-10 md:right-px50">Download
				Map <i class="trevor-ti-download text-px16 ml-2 text-white"></i></a>
		</div>
	</div>

	<div class="py-14 text-blue_green bg-white md:py-28 lg:py-36">
		<div class="container mx-auto">
			<div class="mx-auto site-content-inner text-center">
				<h2 class="font-bold text-px32 leading-px42 mb-3.5 mx-6 md:mx-40 lg:text-px46 lg:leading-px56 lg:mb-7">
					<?= ECT::get_val( ECT::SETTING_HOME_2_TITLE ) ?>
				</h2>
				<p class="mx-auto text-px18 leading-px26 mb-px60 md:mx-9 lg:text-px26 lg:leading-px36 lg:mb-20 lg:w-5/6">
					<?= ECT::get_val( ECT::SETTING_HOME_2_DESC ) ?>
				</p>
			</div>
			<picture class="rounded-px10">
				<?php if ( ! empty( $img_id = ECT::get_val( ECT::SETTING_HOME_2_IMG ) ) ) { ?>
					<?= wp_get_attachment_image( $img_id, 'large', false, [
							'class' => implode( ' ', [
									'w-full',
									'h-auto',
									'object-cover',
									'object-center',
									'rounded-px10',
							] )
					] ) ?>
				<?php } ?>
			</picture>
		</div>
	</div>

	<div class="text-teal-dark bg-teal-tint bg-white">
		<?= \TrevorWP\Theme\Helper\Carousel::posts( ( new WP_Query() )->query( [
				's'         => $search_term = ECT::get_val( ECT::SETTING_HOME_CAROUSEL_TERMS ),
				'post_type' => array_merge( \TrevorWP\CPT\RC\RC_Object::$PUBLIC_POST_TYPES, [ \TrevorWP\CPT\RC\Glossary::POST_TYPE ] ),
		] ), [
				'title'    => ECT::get_val( ECT::SETTING_HOME_CAROUSEL_TITLE ),
				'subtitle' => ECT::get_val( ECT::SETTING_HOME_CAROUSEL_DESC ),
		] ) ?>

		<div class="text-center mx-auto -mt-10 pb-20 lg:pb-28 lg:-mt-28">
			<a href="<?= \TrevorWP\CPT\RC\RC_Object::get_search_url( $search_term ) ?>"
			   class="font-bold text-px24 leading-px34 border-b-2 border-teal-dark lg:text-px26 lg:leading-px36">
				View All Results
			</a>
		</div>
	</div>

	<div class="pt-14 pb-12 text-white bg-teal-dark md:pb-20 lg:pt-28 lg:pb-28">
		<div class="container mx-auto site-content-inner text-center">
			<h2 class="font-semibold text-px32 leading-px42 mb-3.5 lg:text-px46 lg:leading-px56">Join The
				Campaign</h2>
			<p class="text-px18 leading-px26 mb-px50 md:text-px22 md:leading-px32 lg:text-px22 lg:leading-px32 lg:mb-px72">
				Enter your information to take action.</p>

			<form class="mx-auto lg:w-3/4">
				<div class="md:flex md:grid md:grid-cols-2 md:gap-7 md:mb-10 lg:gap-x-7 lg:gap-y-5 lg:mb-px60">
					<div class="flex full-w relative mb-7 md:mb-0">
						<label for="fullname" class="sr-only">Full Name*</label>
						<input id="fullname"
							   class="bg-white rounded-px10 text-blue_green py-5 px-7 flex-1 placeholder-teal-dark lg:text-px20 lg:leading-px24 lg:py-6"
							   placeholder="Full Name*"/>
					</div>
					<div class="flex full-w relative mb-7 md:mb-0">
						<label for="email" class="sr-only">Email*</label>
						<input id="email"
							   class="bg-white rounded-px10 text-blue_green py-5 px-7 flex-1 placeholder-teal-dark lg:text-px20 lg:leading-px24 lg:py-6"
							   placeholder="Email*"/>
					</div>
					<div class="flex full-w relative mb-7 md:mb-0">
						<label for="mobilephone" class="sr-only">Mobile Phone</label>
						<input id="mobilephone"
							   class="bg-white rounded-px10 text-blue_green py-5 px-7 flex-1 placeholder-teal-dark lg:text-px20 lg:leading-px24 lg:py-6"
							   placeholder="Mobile Phone"/>
					</div>
					<div class="flex full-w relative mb-12 md:mb-0">
						<label for="zipcode" class="sr-only">Zip Code*</label>
						<input id="zipcode"
							   class="bg-white rounded-px10 text-blue_green py-5 px-7 flex-1 placeholder-teal-dark lg:text-px20 lg:leading-px24 lg:py-6"
							   placeholder="Zip Code*"/>
					</div>
				</div>
				<div class="flex flex-row full-w relative mb-8">
					<input id="checkbox-1" type="checkbox" checked
						   class="mr-5 w-7 h-7 border-0 rounded"/>
					<label for="checkbox-1"
						   class="text-px16 leading-px24 text-white text-left cursor-pointer mt-0.5 lg:text-px18 lg:leading-px26">Send
						me emails about this campaign.</label>
				</div>
				<div class="flex flex-row full-w relative mb-px50">
					<input id="checkbox-2" type="checkbox" checked
						   class="mr-5 w-7 h-7 border-0 rounded"/>
					<label for="checkbox-2"
						   class="text-px16 leading-px24 text-white text-left cursor-pointer mt-0.5 lg:text-px18 lg:leading-px26">Send
						me text messages about this campaign.</label>
				</div>

				<button type="submit"
						class="block w-full font-bold text-teal-dark bg-white py-5 px-8 rounded-px10 md:py-4 md:px-20 lg:text-px20 lg:leading-px26 lg:py-5 md:w-auto">
					Submit
				</button>
			</form>
		</div>
	</div>
</main>
<?php get_footer(); ?>
