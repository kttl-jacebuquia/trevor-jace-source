<?php /* Get Involved: Institutional Grants */ ?>
<?php get_header(); ?>
<?php

use \TrevorWP\CPT\Get_Involved\Get_Involved_Object;
use \TrevorWP\CPT\Get_Involved\Grant;
use \TrevorWP\Meta;
use \TrevorWP\Theme\Helper\Circulation_Card;

$tiers = ( new \WP_Term_Query( [
	'taxonomy'   => Get_Involved_Object::TAXONOMY_GRANT_TIER,
	'orderby'    => 'meta_value_num',
	'order'      => 'DESC',
	'hide_empty' => true,
	'meta_key'   => Meta\Taxonomy::KEY_PARTNER_TIER_VALUE,
] ) )->terms;


/** @var \WP_Term $tier */
foreach ( $tiers as $tier ) {
	$tier->posts     = get_posts( [
			'orderby'    => 'name',
			'post_type'  => Grant::POST_TYPE,
			'tax_query'  => [
					[
							'taxonomy' => Get_Involved_Object::TAXONOMY_GRANT_TIER,
							'terms'    => $tier->term_id
					]
			]
	] );
}

?>
<main id="site-content" role="main" class="site-content institutional-grants">
	<div class="hero h-px600 md:h-px490 lg:h-px546 flex items-center text-white lg:justify-start">
		<div class="container mx-auto text-center site-content-inner items-center w-full">
			<p class="uppercase text-px16 md:text-px14 leading-px24 md:leading-px18 mb-2.5">Partner with us</p>
			<h1 class="text-px32 leading-px40 md:leading-px42 mb-2.5 md:mb-5 lg:mb-7 flex flex-col font-bold md:inline-block md:mb-px20 lg:text-px46 lg:leading-px56">Institutional Grants</h1>
			<p class="hero__description text-px18 mb-9 md:mb-px30 lg:text-px26 lg:leading-px36 lg:max-w-2xl">The Trevor Project’s life-saving programs are supported by grants from institutional funders such as government and private foundations.</p>
			<a href="#" class="hero__btn-1 inline-block font-bold bg-white py-3 px-8 rounded-px10 md:px-8 lg:text-px20 lg:leading-px26 lg:py-5 lg:px-10">Become a Partner</a>
			<a href="#" class="hero__btn-2 inline-block font-bold text-white py-5 px-6 text-xl md:hidden lg:block">Research A Counselor</a>
		</div>
	</div>

	<div class="funders bg-white flex flex-col">
		<h2 class="funders__title text-px32 leading-px40 mt-px60 mb-px50 md:mb-px40 lg:mt-px100 lg:mb-px50 text-center font-bold">Current Funders</h2>
		<div class="table-container">
			<table>
				<tbody>
					<?php foreach ( $tiers as $tier ) : 
					?>
						<tr class="flex flex-col md:flex-row text-center">
							<th>
								<div class="tier-name text-px20 leading-px26 font-semibold lg:mb-0"><?= $tier->name ?></div>
								<div class="tier-value font-normal text-px20 leading-px26"><?= esc_html( get_term_meta( $tier->term_id, Meta\Taxonomy::KEY_PARTNER_TIER_NAME, true ) ) ?></div>
								
							</th>
							<td class="logo-size flex flex-col md:flex-row">
								<?php
									/** @var \WP_Post $post */
									foreach ( $tier->posts as $post ) :
								?>
									<a href="$" rel="nofollow noreferrer noopener" target="_blank" title="<?= esc_attr( $post->title ) ?>">
										<?= $post->post_title ?>
									</a>
								<?php endforeach ?>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	
		<a href="#" target="_blank" class="funders__partner inline-block mx-auto flex-grow-0 flex-shrink-0">Become a Partner</a>
	</div>

	<div class="cards">
		<div class="cards__container container mx-auto flex flex-row flex-wrap">
			<h3 class="cards__title font-bold text-px32 lg:text-46 leading-px42 lg:leading-56 text-center w-full">There are other ways to help.</h3>
			<?= Circulation_Card::render_donation(); ?>
			<?= Circulation_Card::render_fundraiser(); ?>
		</div>
	</div>

</main>
<?php get_footer(); ?>
