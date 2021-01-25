<?php /* Get Involved: Advocate For Change */ ?>
<?php get_header(); ?>
<?php

use \TrevorWP\Theme\Customizer\Advocacy;
use \TrevorWP\Theme\Helper;

$featured_bill_ids = wp_parse_id_list( Advocacy::get_val( Advocacy::SETTING_HOME_FEATURED_BILLS ) );
$featured_bills    = Helper\Posts::get_from_list( $featured_bill_ids, 6 );

$featured_letter_ids = wp_parse_id_list( Advocacy::get_val( Advocacy::SETTING_HOME_FEATURED_LETTERS ) );
$featured_letters    = Helper\Posts::get_from_list( $featured_letter_ids, 6 );

?>
<main id="site-content" role="main" class="site-content">
	<?= Helper\Page_Header::split_img( [
			'title'   => Advocacy::get_val( Advocacy::SETTING_HOME_HERO_TITLE ),
			'desc'    => Advocacy::get_val( Advocacy::SETTING_HOME_HERO_DESC ),
			'img_id'  => Advocacy::get_val( Advocacy::SETTING_HOME_HERO_IMG ),
			'cta_txt' => Advocacy::get_val( Advocacy::SETTING_HOME_HERO_CTA ),
			'cta_url' => '#',
	] ) ?>

	<?= Helper\Carousel::big_img( Advocacy::get_val( Advocacy::SETTING_HOME_CAROUSEL_DATA ), [
			'title' => Advocacy::get_val( Advocacy::SETTING_HOME_CAROUSEL_TITLE )
	] ) ?>

	<?= Helper\Tile_Grid::custom( [
			[
					'title'   => 'Ending Conversion Therapy',
					'desc'    => 'The largest campaign in the nation working to protect LGBTQ youth from conversion therapy.',
					'cta_txt' => 'Read more',
					'cta_url' => '#'
			],
			[
					'title'   => 'Collecting LGBTQ Life Data',
					'desc'    => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Odio lorem pellentesque facilisis fermentum nisl neque id. Integer.',
					'cta_txt' => 'Read more',
					'cta_url' => '#'
			],
			[
					'title'   => 'Preventing Suicide In Schools',
					'desc'    => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Odio lorem pellentesque facilisis fermentum nisl neque id. Integer.',
					'cta_txt' => 'Read more',
					'cta_url' => '#'
			],
			[
					'title'   => 'Research Studies',
					'desc'    => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Odio lorem pellentesque facilisis fermentum nisl neque id. Integer.',
					'cta_txt' => 'Read more',
					'cta_url' => '#'
			],
			[
					'title'   => 'Resources & Guides',
					'desc'    => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Odio lorem pellentesque facilisis fermentum nisl neque id. Integer.',
					'cta_txt' => 'Read more',
					'cta_url' => '#'
			],
	], [
			'title'       => Advocacy::get_val( Advocacy::SETTING_HOME_OUR_WORK_TITLE ),
			'desc'        => Advocacy::get_val( Advocacy::SETTING_HOME_OUR_WORK_DESC ),
			'smAccordion' => true,
	] ) ?>

	<!--
	<div class="gi_main_lp-main-content">

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
-->
<!--	<div class="pt-20 pb-px60 text-white lg:pt-28 lg:pb-48">-->
<!--		<div class="container mx-auto site-content-inner text-center">-->
<!--			<h2 class="font-semibold text-px32 leading-px42 mb-3.5 md:mx-40 lg:text-px46 lg:leading-px56 tracking-em_001">--><?//= Advocacy::get_val( Advocacy::SETTING_HOME_OUR_WORK_TITLE ) ?><!--</h2>-->
<!--			<p class="text-px18 leading-px26 mb-px50 md:mx-20 md:mb-20 lg:text-px26 lg:leading-px36 lg:mb-20 lg:mx-56">--><?//= Advocacy::get_val( Advocacy::SETTING_HOME_OUR_WORK_DESC ) ?><!--</p>-->
<!--		</div>-->
<!---->
<!--		<div class="accordion text-teal-dark text-left bg-white md:grid md:grid-cols-2 md:gap-7 md:mx-px50 md:bg-transparent lg:grid-cols-3 lg:mx-px106"-->
<!--			 id="accordionExample">-->
<!--			<div class="accordion-item md:rounded-px10 md:bg-white md:shadow-md">-->
<!--				<h2 class="accordion-header" id="headingOne">-->
<!--					<button class="accordion-button text-px20 leading-px26 py-8 px-7 w-full text-left border-b border-blue_green border-opacity-20 flex justify-between font-semibold md:pt-9 md:mb-3 md:border-0 md:pb-0 md:text-px24 md:leading-px28"-->
<!--							type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true"-->
<!--							aria-controls="collapseOne">-->
<!--						Ending Conversion Therapy-->
<!--					</button>-->
<!--				</h2>-->
<!--				<div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"-->
<!--					 data-bs-parent="#accordionExample">-->
<!--					<div class="accordion-body px-7 pt-5 pb-9 bg-gray-light md:bg-transparent md:pb-36">-->
<!--						<p class="text-px18 leading-px28 mb-6 md:text-px16 md:leading-px24">The largest campaign in the-->
<!--							nation working to protect LGBTQ youth from conversion therapy.</p>-->
<!--						<a class="font-bold text-px18 leading-px28 border-b-2 border-teal-dark" href="#">Read more</a>-->
<!--					</div>-->
<!--				</div>-->
<!--			</div>-->
<!--			<div class="accordion-item md:rounded-px10 md:bg-white md:shadow-md">-->
<!--				<h2 class="accordion-header" id="headingTwo">-->
<!--					<button class="accordion-button text-px20 leading-px26 py-8 px-7 w-full text-left border-b border-blue_green border-opacity-20 flex justify-between font-semibold collapsed md:pt-9 md:mb-3 md:border-0 md:pb-0 md:text-px24 md:leading-px28"-->
<!--							type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false"-->
<!--							aria-controls="collapseTwo">-->
<!--						Collecting LGBTQ Life Data-->
<!--					</button>-->
<!--				</h2>-->
<!--				<div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"-->
<!--					 data-bs-parent="#accordionExample">-->
<!--					<div class="accordion-body px-7 pt-5 pb-9 bg-gray-light md:bg-transparent md:pb-36">-->
<!--						<p class="text-px18 leading-px28 mb-6 md:text-px16 md:leading-px24">Lorem ipsum dolor sit amet,-->
<!--							consectetur adipiscing elit. Odio lorem pellentesque facilisis fermentum nisl neque id.-->
<!--							Integer.</p>-->
<!--						<a class="font-bold text-px18 leading-px28 border-b-2 border-teal-dark" href="#">Read more</a>-->
<!--					</div>-->
<!--				</div>-->
<!--			</div>-->
<!--			<div class="accordion-item md:rounded-px10 md:bg-white md:shadow-md">-->
<!--				<h2 class="accordion-header" id="headingThree">-->
<!--					<button class="accordion-button text-px20 leading-px26 py-8 px-7 w-full text-left border-b border-blue_green border-opacity-20 flex justify-between font-semibold collapsed md:pt-9 md:mb-3 md:border-0 md:pb-0 md:text-px24 md:leading-px28"-->
<!--							type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree"-->
<!--							aria-expanded="false" aria-controls="collapseThree">-->
<!--						Preventing Suicide In Schools-->
<!--					</button>-->
<!--				</h2>-->
<!--				<div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree"-->
<!--					 data-bs-parent="#accordionExample">-->
<!--					<div class="accordion-body px-7 pt-5 pb-9 bg-gray-light md:bg-transparent md:pb-36">-->
<!--						<p class="text-px18 leading-px28 mb-6 md:text-px16 md:leading-px24">Lorem ipsum dolor sit amet,-->
<!--							consectetur adipiscing elit. Odio lorem pellentesque facilisis fermentum nisl neque id.-->
<!--							Integer.</p>-->
<!--						<a class="font-bold text-px18 leading-px28 border-b-2 border-teal-dark" href="#">Read more</a>-->
<!--					</div>-->
<!--				</div>-->
<!--			</div>-->
<!--			<div class="accordion-item md:rounded-px10 md:bg-white md:shadow-md">-->
<!--				<h2 class="accordion-header" id="headingFour">-->
<!--					<button class="accordion-button text-px20 leading-px26 py-8 px-7 w-full text-left border-b border-blue_green border-opacity-20 flex justify-between font-semibold collapsed md:pt-9 md:mb-3 md:border-0 md:pb-0 md:text-px24 md:leading-px28"-->
<!--							type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false"-->
<!--							aria-controls="collapseFour">-->
<!--						Research Studies-->
<!--					</button>-->
<!--				</h2>-->
<!--				<div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour"-->
<!--					 data-bs-parent="#accordionExample">-->
<!--					<div class="accordion-body px-7 pt-5 pb-9 bg-gray-light md:bg-transparent md:pb-36">-->
<!--						<p class="text-px18 leading-px28 mb-6 md:text-px16 md:leading-px24">Lorem ipsum dolor sit amet,-->
<!--							consectetur adipiscing elit. Odio lorem pellentesque facilisis fermentum nisl neque id.-->
<!--							Integer.</p>-->
<!--						<a class="font-bold text-px18 leading-px28 border-b-2 border-teal-dark" href="#">Read more</a>-->
<!--					</div>-->
<!--				</div>-->
<!--			</div>-->
<!--			<div class="accordion-item md:rounded-px10 md:bg-white md:shadow-md">-->
<!--				<h2 class="accordion-header" id="headingFive">-->
<!--					<button class="accordion-button text-px20 leading-px26 py-8 px-7 w-full text-left border-b border-blue_green border-opacity-20 flex justify-between font-semibold collapsed md:pt-9 md:mb-3 md:border-0 md:pb-0 md:text-px24 md:leading-px28"-->
<!--							type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false"-->
<!--							aria-controls="collapseFive">-->
<!--						Resources & Guides-->
<!--					</button>-->
<!--				</h2>-->
<!--				<div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive"-->
<!--					 data-bs-parent="#accordionExample">-->
<!--					<div class="accordion-body px-7 pt-5 pb-9 bg-gray-light md:bg-transparent md:pb-36">-->
<!--						<p class="text-px18 leading-px28 mb-6 md:text-px16 md:leading-px24">Lorem ipsum dolor sit amet,-->
<!--							consectetur adipiscing elit. Odio lorem pellentesque facilisis fermentum nisl neque id.-->
<!--							Integer.</p>-->
<!--						<a class="font-bold text-px18 leading-px28 border-b-2 border-teal-dark" href="#">Read more</a>-->
<!--					</div>-->
<!--				</div>-->
<!--			</div>-->
<!--		</div>-->
<!--	</div>-->

	<div class="h-px737 bg-canary lg:h-px600 lg:flex lg:items-center">
		<figure class="pt-20 pb-28 container text-left text-teal-dark md:flex-1 md:px-24 md:pt-24 md:pb-20 lg:ml-56 lg:p-0 lg:w-5/12 lg:flex-initial">
			<div class="flex flex-row justify-start md:mb-2 lg:mb-5">
				<i class="trevor-ti-quote-open -mt-2 mr-0.5 md:text-px26 lg:text-px32 lg:mr-2"></i>
				<i class="trevor-ti-quote-close md:text-px26 lg:text-px32"></i>
			</div>
			<blockquote
					class="font-bold text-3xl my-4 md:text-px30 md:leading-px40 md:mr-24 lg:text-px40 lg:leading-px48 lg:font-semibold">
				Thank you for everything you do for our community and humans around the world.
			</blockquote>
			<figcaption class="text-px18 leading-px26 lg:text-px22 lg:leading-px32">@itsannawalker</figcaption>
		</figure>
	</div>

	<div class="pt-20 pb-20 text-white md:pt-24 lg:pt-36 lg:pb-28">
		<div class="container mx-auto site-content-inner text-center">
			<h2 class="font-semibold text-px32 leading-px42 mb-3.5 md:mx-40 lg:text-px46 lg:leading-px56"><?= Advocacy::get_val( Advocacy::SETTING_HOME_BILL_TITLE ) ?></h2>
			<p class="text-px18 leading-px26 mb-px50 md:mx-9 md:mb-px50 lg:text-px26 lg:leading-px36 lg:mb-20 lg:mx-44"><?= Advocacy::get_val( Advocacy::SETTING_HOME_BILL_DESC ) ?></p>

			<div class="grid grid-cols-1 gap-y-7 mb-10 md:grid-cols-2 md:gap-7 lg:grid-cols-3">
				<div class="bg-white text-teal-dark rounded-px10 text-left px-7 pt-7 pb-16 md:shadow-md">
					<p class="text-px16 leading-px24 tracking-em_001 font-semibold mb-3.5">H.R. 3273 / S. 1570</p>
					<h3 class="text-px24 leading-px28 tracking-em001 font-semibold mb-3">LGBT Data Inclusion Act</h3>
					<p class="text-px16 leading-px24 mb-4">We know that LGBT people face disparities in nearly every
						realm of life, such as mental health and substance use disparities, barriers in access to health
						insurance cov...</p>
					<a class="font-bold text-px18 leading-px24 tracking-em001 border-b-2 border-teal-dark" href="#">Read
						more</a>
				</div>
				<div class="bg-white text-teal-dark rounded-px10 text-left px-7 pt-7 pb-16 md:shadow-md">
					<p class="text-px16 leading-px24 tracking-em_001 font-semibold mb-3.5">H.R. 2913 / S. 1370</p>
					<h3 class="text-px24 leading-px28 tracking-em001 font-semibold mb-3">Mental Health In Schools
						Act</h3>
					<p class="text-px16 leading-px24 mb-4">The Mental Health In Schools Act will provide $200,000,000 in
						competitive grants of up to $1 million each. It expands th...</p>
					<a class="font-bold text-px18 leading-px24 tracking-em001 border-b-2 border-teal-dark" href="#">Read
						more</a>
				</div>
				<div class="bg-white text-teal-dark rounded-px10 text-left px-7 pt-7 pb-16 md:shadow-md">
					<p class="text-px16 leading-px24 tracking-em_001 font-semibold mb-3.5">H.R. 2119 / S. 928</p>
					<h3 class="text-px24 leading-px28 tracking-em001 font-semibold mb-3">Therapeutic Fraud Prevention
						Act of 2017</h3>
					<p class="text-px16 leading-px24 mb-4">In relation to the same important topic addressed with the
						Stop Harming Our Kids Resolution of 2015 , the Ther...</p>
					<a class="font-bold text-px18 leading-px24 tracking-em001 border-b-2 border-teal-dark" href="#">Read
						more</a>
				</div>
			</div>

			<a class="font-bold text-px24 leading-px34 tracking-em001 border-b-2 border-white text-white mb-px50 self-center md:mb-20"
			   href="#">View All</a>

			<h2 class="font-semibold text-px32 leading-px42 mb-3.5 md:mx-40 lg:text-px46 lg:leading-px56"><?= Advocacy::get_val( Advocacy::SETTING_HOME_LETTER_TITLE ) ?></h2>
			<p class="text-px18 leading-px26 mb-px50 md:mx-9 md:mb-20 lg:text-px26 lg:leading-px36 lg:mb-20 lg:mx-44"><?= Advocacy::get_val( Advocacy::SETTING_HOME_LETTER_DESC ) ?></p>

			<div class="grid grid-cols-1 gap-y-7 mb-10 md:grid-cols-2 md:gap-7 lg:grid-cols-3">
				<div class="bg-white text-teal-dark rounded-px10 text-left px-7 pt-7 pb-24 md:shadow-md">
					<h3 class="text-px24 leading-px28 tracking-em001 font-semibold mb-3">Et fringilla nibh aliquam
						gravida malesuada nec.</h3>
					<p class="text-px16 leading-px24 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit.
						Purus tristique sed tincidunt rhoncus amet posuere leo quisque eget. </p>
					<a class="font-bold text-px18 leading-px24 tracking-em001 border-b-2 border-teal-dark" href="#">Read
						the Letter <i class="trevor-ti-download text-px16 ml-2"></i></a>
				</div>
				<div class="bg-white text-teal-dark rounded-px10 text-left px-7 pt-7 pb-24 md:shadow-md">
					<h3 class="text-px24 leading-px28 tracking-em001 font-semibold mb-3">Et fringilla nibh aliquam
						gravida malesuada nec.</h3>
					<p class="text-px16 leading-px24 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit.
						Purus tristique sed tincidunt rhoncus amet posuere leo quisque eget. </p>
					<a class="font-bold text-px18 leading-px24 tracking-em001 border-b-2 border-teal-dark" href="#">Read
						the Letter <i class="trevor-ti-download text-px16 ml-2"></i></a>
				</div>
				<div class="bg-white text-teal-dark rounded-px10 text-left px-7 pt-7 pb-24 md:shadow-md">
					<h3 class="text-px24 leading-px28 tracking-em001 font-semibold mb-3">Et fringilla nibh aliquam
						gravida malesuada nec.</h3>
					<p class="text-px16 leading-px24 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit.
						Purus tristique sed tincidunt rhoncus amet posuere leo quisque eget. </p>
					<a class="font-bold text-px18 leading-px24 tracking-em001 border-b-2 border-teal-dark" href="#">Read
						the Letter <i class="trevor-ti-download text-px16 ml-2"></i></a>
				</div>
			</div>

			<a class="font-bold text-px24 leading-px34 tracking-em001 border-b-2 border-white text-white mb-10 self-center"
			   href="#">View All</a>
			<a href="#"
			   class="inline-block font-bold text-indigo bg-white py-3 px-8 rounded-px10 md:px-8 lg:text-px20 lg:leading-px26 lg:py-5 lg:px-10 self-center"><?= Advocacy::get_val( Advocacy::SETTING_HOME_TAN_CTA ) ?></a>
		</div>
	</div>

	<div class="pt-20 pb-20 text-teal-dark bg-white lg:pt-28 lg:pb-48">
		<div class="container mx-auto site-content-inner text-center">
			<h2 class="font-semibold text-px32 leading-px42 mb-3.5 md:mx-0 lg:text-px46 lg:leading-px56"><?= Advocacy::get_val( Advocacy::SETTING_HOME_PARTNER_ORG_TITLE ) ?></h2>
			<p class="text-px18 leading-px26 mb-px50 md:mx-20 md:mb-4 lg:text-px26 lg:leading-px36 lg:mb-20 lg:mx-44"><?= Advocacy::get_val( Advocacy::SETTING_HOME_PARTNER_ORG_DESC ) ?></p>

			<div class="flex flex-row flex-wrap space-y-4 mb-px72 md:justify-center md:space-y-16 lg:mx-52">
				<div class="flex justify-center items-center flex-initial w-1/2 md:w-1/3 lg:w-1/4 md:mt-16">
					<picture>
						<source srcset="http://placehold.it/152x29" media="(min-width: 1440px)">
						<source srcset="http://placehold.it/130x25" media="(min-width: 768px)">
						<img class="mx-auto" src="http://placehold.it/128x24" alt=""/>
					</picture>
				</div>
				<div class="flex justify-center items-center flex-initial w-1/2 md:w-1/3 lg:w-1/4">
					<picture>
						<source srcset="http://placehold.it/97x87" media="(min-width: 768px)">
						<img class="mx-auto" src="http://placehold.it/81x80" alt=""/>
					</picture>
				</div>
				<div class="flex justify-center items-center flex-initial w-1/2 md:w-1/3 lg:w-1/4">
					<picture>
						<img class="mx-auto" src="http://placehold.it/119x59" alt=""/>
					</picture>
				</div>
				<div class="flex justify-center items-center flex-initial w-1/2 md:w-1/3 lg:w-1/4">
					<picture>
						<source srcset="http://placehold.it/130x39" media="(min-width: 768px)">
						<img class="mx-auto" src="http://placehold.it/136x40" alt=""/>
					</picture>
				</div>
				<div class="flex justify-center items-center flex-initial w-1/2 md:w-1/3 lg:w-1/4">
					<picture>
						<source srcset="http://placehold.it/106x110" media="(min-width: 1440px)">
						<source srcset="http://placehold.it/90x93" media="(min-width: 768px)">
						<img class="mx-auto" src="http://placehold.it/103x108" alt=""/>
					</picture>
				</div>
				<div class="flex justify-center items-center flex-initial w-1/2 md:w-1/3 lg:w-1/4">
					<picture>
						<source srcset="http://placehold.it/155x53" media="(min-width: 1440px)">
						<img class="mx-auto" src="http://placehold.it/134x46" alt=""/>
					</picture>
				</div>
				<div class="flex justify-center items-center flex-initial w-1/2 md:w-1/3 lg:w-1/4">
					<picture>
						<source srcset="http://placehold.it/114x45" media="(min-width: 768px)">
						<img class="mx-auto" src="http://placehold.it/104x42" alt=""/>
					</picture>
				</div>
				<div class="flex justify-center items-center flex-initial w-1/2 md:w-1/3 lg:w-1/4">
					<picture>
						<source srcset="http://placehold.it/138x53" media="(min-width: 768px)">
						<img class="mx-auto" src="http://placehold.it/138x53" alt=""/>
					</picture>
				</div>
			</div>

			<a class="font-bold text-px24 leading-px34 tracking-em001 border-b-2 border-teal-dark text-teal-dark mb-px50 self-center"
			   href="#">Load More</a>
		</div>
	</div>
</main>
<?php get_footer(); ?>
