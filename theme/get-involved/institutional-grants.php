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
	$tier->posts = get_posts( [
			'numberposts' => - 1,
			'orderby'     => 'name',
			'order'       => 'ASC',
			'post_type'   => Grant::POST_TYPE,
			'tax_query'   => [
					[
							'taxonomy' => Get_Involved_Object::TAXONOMY_GRANT_TIER,
							'terms'    => $tier->term_id
					]
			]
	] );
}

?>
<main id="site-content" role="main" class="site-content institutional-grants">
	<?= TrevorWP\Theme\Helper\Page_Header::text( [
			'title_top' => 'Partner with us',
			'title'     => 'Institutional Grants',
			'desc'      => 'The Trevor Project’s life-saving programs are supported by grants from institutional funders such as government and private foundations.',
			'cta_txt'   => 'Become a Partner',
			'cta_url'   => '#',
	] ) ?>

	<div class="funders bg-white flex flex-col">
		<h2 class="funders__title text-px32 leading-px40 md:leading-px42 lg:text-px40 lg:leading-px48 mt-px60 mb-px50 md:mb-px40 lg:mt-px100 lg:mb-px50 text-center font-bold">
			Current Funders</h2>
		<div class="table-container">
			<table>
				<tbody>
				<?php foreach ( $tiers as $tier ) :
					?>
					<tr class="flex flex-col md:flex-row text-center">
						<th>
							<div class="tier-name text-px20 leading-px26 md:text-left font-semibold lg:mb-0 lg:text-px26 lg:leading-px36"><?= $tier->name ?></div>
							<div class="tier-value font-normal text-px20 md:text-left leading-px26 lg:text-px22 lg:leading-px32"><?= esc_html( get_term_meta( $tier->term_id, Meta\Taxonomy::KEY_PARTNER_TIER_NAME, true ) ) ?></div>

						</th>
						<td class="logo-size flex flex-col md:flex-row">
							<?php
							/** @var \WP_Post $post */
							foreach ( $tier->posts as $post ) :
								?>
								<a href="$" rel="nofollow noreferrer noopener" target="_blank"
								   title="<?= esc_attr( $post->title ) ?>">
									<?= $post->post_title ?>
								</a>
							<?php endforeach ?>
						</td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>

		<a href="#" target="_blank" class="funders__partner inline-block mx-auto flex-grow-0 flex-shrink-0">Become a
			Partner</a>
	</div>

	<div class="cards">
		<div class="cards__container container mx-auto flex flex-row flex-wrap">
			<h3 class="cards__title font-bold text-px32 lg:text-46 leading-px42 lg:leading-56 text-center w-full">There
				are other ways to help.</h3>
			<?= Circulation_Card::render_donation(); ?>
			<?= Circulation_Card::render_fundraiser(); ?>
		</div>
	</div>

</main>
<?php get_footer(); ?>
