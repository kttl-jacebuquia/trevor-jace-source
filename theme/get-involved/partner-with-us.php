<?php /* Get Involved: Partner with Us */ ?>
<?php get_header(); ?>
<?php

use TrevorWP\CPT\Donate\Donate_Object;
use \TrevorWP\Theme\Helper;
use \TrevorWP\Theme\Customizer\PWU;
use \TrevorWP\Theme\Helper\Circulation_Card;

?>
<main id="site-content" role="main" class="site-content">
	<?php
	echo Helper\Page_Header::split_carousel(
		array(
			'title'         => PWU::get_val( PWU::SETTING_HOME_HERO_TITLE ),
			'desc'          => PWU::get_val( PWU::SETTING_HOME_HERO_DESC ),
			'cta_txt'       => PWU::get_val( PWU::SETTING_HOME_HERO_CTA ),
			'cta_url'       => '#',
			'carousel_data' => PWU::get_val( PWU::SETTING_HOME_HERO_CAROUSEL_DATA ),
			'swiper'        => array(
				'centeredSlides' => true,
				'slidesPerView'  => 'auto',
			),
		)
	)
	?>

	<div class="bg-teal-tint text-teal-dark py-px120">
		<div class="container mx-auto site-content-inner text-center">
			<p class="text-px16 leading-px24 uppercase mb-3.5 md:text-px18 md:leading-px26"><?php echo PWU::get_val( PWU::SETTING_HOME_OUR_PHILOSOPHY_TITLE ); ?></p>
			<p class="text-px24 leading-px28 font-semibold md:mx-24 md:text-px26 md:leading-px36 xl:mx-80"><?php echo PWU::get_val( PWU::SETTING_HOME_OUR_PHILOSOPHY_DESC ); ?></p>
		</div>
	</div>

	<div class="partnership-offerings bg-gray-light flex flex-col">
		<?php
		echo Helper\Tile_Grid::custom(
			array(
				array(
					'title'   => 'Corporate Partnerships',
					'desc'    => 'Our partnerships are customized to align with our corporate partners’ priorities.',
					'cta_txt' => 'Read more',
					'cta_url' => '#',
				),
				array(
					'title'   => 'Product Partnerships',
					'desc'    => 'Our partnerships are customized to align with our corporate partners’ priorities.',
					'cta_txt' => 'Learn more',
					'cta_url' => '#',
				),
				array(
					'title'   => 'Institutional Grants',
					'desc'    => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Auctor pellentesque id amet consectetur.',
					'cta_txt' => 'Learn more',
					'cta_url' => '#',
				),
			),
			array(
				'title'       => PWU::get_val( PWU::SETTING_HOME_OUR_PARTNERSHIP_OFFERINGS_TITLE ),
				'desc'        => PWU::get_val( PWU::SETTING_HOME_OUR_PARTNERSHIP_OFFERINGS_DESC ),
				'smAccordion' => false,
				'tileClass'   => array( 'text-teal-dark' ),
				'class'       => array( 'text-teal-dark', 'container', 'mx-auto', 'pb-0', 'lg:pb-px50' ),
			)
		)
		?>
		<a class="font-bold text-px16 leading-px22 tracking-em001 text-white bg-teal-dark py-3 px-8 rounded-px10 self-center mt-0 mb-20 md:mb-px60 lg:mb-px120 md:px-6 lg:text-px18 lg:leading-px26 lg:py-5 lg:px-10"
		href="#">Become a Partner</a>
	</div>

	<?php /* Recirculation */ ?>
	<?php
	$donation_args                  = Helper\Circulation_Card::DEFAULTS['donation'];
	$donation_args['button']['url'] = home_url( Donate_Object::PERMALINK_DONATE );
	echo Helper\Circulation_Card::render_circulation(
		$title                      = 'There are other ways to help.',
		$subtitle                   = null,
		$cards                      = array(
			'donation'  => $donation_args,
			'counselor' => Helper\Circulation_Card::DEFAULTS['counselor'],
		),
		$class                      = null
	);
	?>
</main>
<?php get_footer(); ?>
