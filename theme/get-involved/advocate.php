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
			'title' => Advocacy::get_val( Advocacy::SETTING_HOME_CAROUSEL_TITLE ),
			'class' => 'text-white',
	] ) ?>

	<?= Helper\Tile_Grid::custom( [
			[
					'title'   => 'Ending Conversion Therapy',
					'desc'    => 'The largest campaign in the nation working to protect LGBTQ youth from conversion therapy.',
					'cta_txt' => 'Read more',
					'cta_url' => home_url( \TrevorWP\CPT\Get_Involved\Get_Involved_Object::PERMALINK_ECT )
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
					'cta_url' => home_url( \TrevorWP\CPT\RC\RC_Object::PERMALINK_BASE ),
			],
	], [
			'title'       => Advocacy::get_val( Advocacy::SETTING_HOME_OUR_WORK_TITLE ),
			'desc'        => Advocacy::get_val( Advocacy::SETTING_HOME_OUR_WORK_DESC ),
			'smAccordion' => true,
			'tileClass'   => [ 'text-teal-dark' ],
			'class'       => [ 'text-white', 'md:container', 'mx-auto' ]
	] ) ?>

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

	<?= Helper\Tile_Grid::posts( $featured_bills, [
			'title'     => Advocacy::get_val( Advocacy::SETTING_HOME_BILL_TITLE ),
			'desc'      => Advocacy::get_val( Advocacy::SETTING_HOME_BILL_DESC ),
			'tileClass' => [ 'text-teal-dark' ],
			'class'     => [ 'text-white', 'container', 'mx-auto' ]
	] ) ?>
	<a class="font-bold text-px24 leading-px34 tracking-em001 border-b-2 border-white text-white mb-10 self-center -mt-10 md:-mt-14 lg:-mt-36"
	   href="<?= get_post_type_archive_link( \TrevorWP\CPT\Get_Involved\Bill::POST_TYPE ) ?>">
		View All
	</a>

	<?= Helper\Tile_Grid::posts( $featured_letters, [
			'title'     => Advocacy::get_val( Advocacy::SETTING_HOME_LETTER_TITLE ),
			'desc'      => Advocacy::get_val( Advocacy::SETTING_HOME_LETTER_DESC ),
			'tileClass' => [ 'text-teal-dark' ],
			'class'     => [ 'text-white', 'container', 'mx-auto' ]
	] ) ?>

	<a class="font-bold text-px24 leading-px34 tracking-em001 border-b-2 border-white text-white mb-10 self-center -mt-10 md:-mt-14 lg:-mt-36"
	   href="<?= get_post_type_archive_link( \TrevorWP\CPT\Get_Involved\Letter::POST_TYPE ) ?>">View All</a>

	<div class="py-10 text-center">
		<a href="#"
		   class="inline-block font-bold text-indigo bg-white py-3 px-8 rounded-px10 md:px-8 lg:text-px20 lg:leading-px26 lg:py-5 lg:px-10 self-center">
			<?= Advocacy::get_val( Advocacy::SETTING_HOME_TAN_CTA ) ?>
		</a>
	</div>

	<!--		<div class="pt-20 pb-20 text-teal-dark bg-white lg:pt-28 lg:pb-48">-->
	<!--			<div class="container mx-auto site-content-inner text-center">-->
	<!--				<h2 class="font-semibold text-px32 leading-px42 mb-3.5 md:mx-0 lg:text-px46 lg:leading-px56">-->
	<? //= Advocacy::get_val( Advocacy::SETTING_HOME_PARTNER_ORG_TITLE ) ?><!--</h2>-->
	<!--				<p class="text-px18 leading-px26 mb-px50 md:mx-20 md:mb-4 lg:text-px26 lg:leading-px36 lg:mb-20 lg:mx-44">-->
	<? //= Advocacy::get_val( Advocacy::SETTING_HOME_PARTNER_ORG_DESC ) ?><!--</p>-->
	<!---->
	<!--				<div class="flex flex-row flex-wrap space-y-4 mb-px72 md:justify-center md:space-y-16 lg:mx-52">-->
	<!--					<div class="flex justify-center items-center flex-initial w-1/2 md:w-1/3 lg:w-1/4 md:mt-16">-->
	<!--						<picture>-->
	<!--							<source srcset="http://placehold.it/152x29" media="(min-width: 1440px)">-->
	<!--							<source srcset="http://placehold.it/130x25" media="(min-width: 768px)">-->
	<!--							<img class="mx-auto" src="http://placehold.it/128x24" alt=""/>-->
	<!--						</picture>-->
	<!--					</div>-->
	<!--					<div class="flex justify-center items-center flex-initial w-1/2 md:w-1/3 lg:w-1/4">-->
	<!--						<picture>-->
	<!--							<source srcset="http://placehold.it/97x87" media="(min-width: 768px)">-->
	<!--							<img class="mx-auto" src="http://placehold.it/81x80" alt=""/>-->
	<!--						</picture>-->
	<!--					</div>-->
	<!--					<div class="flex justify-center items-center flex-initial w-1/2 md:w-1/3 lg:w-1/4">-->
	<!--						<picture>-->
	<!--							<img class="mx-auto" src="http://placehold.it/119x59" alt=""/>-->
	<!--						</picture>-->
	<!--					</div>-->
	<!--					<div class="flex justify-center items-center flex-initial w-1/2 md:w-1/3 lg:w-1/4">-->
	<!--						<picture>-->
	<!--							<source srcset="http://placehold.it/130x39" media="(min-width: 768px)">-->
	<!--							<img class="mx-auto" src="http://placehold.it/136x40" alt=""/>-->
	<!--						</picture>-->
	<!--					</div>-->
	<!--					<div class="flex justify-center items-center flex-initial w-1/2 md:w-1/3 lg:w-1/4">-->
	<!--						<picture>-->
	<!--							<source srcset="http://placehold.it/106x110" media="(min-width: 1440px)">-->
	<!--							<source srcset="http://placehold.it/90x93" media="(min-width: 768px)">-->
	<!--							<img class="mx-auto" src="http://placehold.it/103x108" alt=""/>-->
	<!--						</picture>-->
	<!--					</div>-->
	<!--					<div class="flex justify-center items-center flex-initial w-1/2 md:w-1/3 lg:w-1/4">-->
	<!--						<picture>-->
	<!--							<source srcset="http://placehold.it/155x53" media="(min-width: 1440px)">-->
	<!--							<img class="mx-auto" src="http://placehold.it/134x46" alt=""/>-->
	<!--						</picture>-->
	<!--					</div>-->
	<!--					<div class="flex justify-center items-center flex-initial w-1/2 md:w-1/3 lg:w-1/4">-->
	<!--						<picture>-->
	<!--							<source srcset="http://placehold.it/114x45" media="(min-width: 768px)">-->
	<!--							<img class="mx-auto" src="http://placehold.it/104x42" alt=""/>-->
	<!--						</picture>-->
	<!--					</div>-->
	<!--					<div class="flex justify-center items-center flex-initial w-1/2 md:w-1/3 lg:w-1/4">-->
	<!--						<picture>-->
	<!--							<source srcset="http://placehold.it/138x53" media="(min-width: 768px)">-->
	<!--							<img class="mx-auto" src="http://placehold.it/138x53" alt=""/>-->
	<!--						</picture>-->
	<!--					</div>-->
	<!--				</div>-->
	<!---->
	<!--				<a class="font-bold text-px24 leading-px34 tracking-em001 border-b-2 border-teal-dark text-teal-dark mb-px50 self-center"-->
	<!--				   href="#">Load More</a>-->
	<!--			</div>-->
</main>
<?php get_footer(); ?>
