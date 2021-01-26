<?php /* Get Involved: Corporate Partnership */ ?>
<?php get_header(); ?>
<?php

use \TrevorWP\CPT\Get_Involved\Get_Involved_Object;
use \TrevorWP\CPT\Get_Involved\Partner;
use \TrevorWP\Meta;
use \TrevorWP\Theme\Helper\Circulation_Card;

$tiers = ( new \WP_Term_Query( [
	'taxonomy'   => Get_Involved_Object::TAXONOMY_PARTNER_TIER,
	'orderby'    => 'meta_value_num',
	'order'      => 'DESC',
	'hide_empty' => true,
	'meta_key'   => Meta\Taxonomy::KEY_PARTNER_TIER_VALUE,
] ) )->terms;

/** @var \WP_Term $tier */
foreach ( $tiers as $tier ) {
	$tier->logo_size = Meta\Taxonomy::get_partner_tier_logo_size( $tier->term_id );
	$tier->posts     = get_posts( [
			'numberposts'	=> -1,
			'orderby'    	=> 'name',
			'order'			 	=> 'ASC',
			'post_type'  	=> Partner::POST_TYPE,
			'tax_query'  	=> [
					[
							'taxonomy' => Get_Involved_Object::TAXONOMY_PARTNER_TIER,
							'terms'    => $tier->term_id
					]
			]
	] );
}

?>
<main id="site-content" role="main" class="site-content corporate-parnerships">
	<?= TrevorWP\Theme\Helper\Page_Header::text( [
			'title_top' => 'Partner with us',
			'title'     => 'Corporate Partners',
			'desc'      => 'Our partnerships are customized to align with our corporate partners’ priorities. How do you see our mission aligning with your brand and goals?',
			'cta_txt'   => 'Become a Partner',
			'cta_url'   => '#',
	] ) ?>

	<div class="partners bg-white flex flex-col">
		<h2 class="partners__title text-px32 leading-px40 mt-px60 mb-px50 md:mb-px40 lg:mt-px100 lg:mb-px50 text-center font-bold">Current partners</h2>
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
							<td class="logo-size-<?= $tier->logo_size ?> flex flex-col md:flex-row">
								<?php
									/** @var \WP_Post $post */
									if ( $tier->logo_size != 'text' ) :
										foreach ( $tier->posts as $post ) :
								?>
											<a href="#"
												rel="nofollow noreferrer noopener"
												target="_blank"
												title="<?= esc_attr( $post->post_title ) ?>"
											>
												<?= get_the_post_thumbnail( $post, \TrevorWP\Theme\Helper\Thumbnail::SIZE_MD, [ 'class' => 'partner-logo' ] ) ?>
											</a>
										<?php endforeach ?>

									<?php else : ?>

										<?php
											$num_posts = count( $tier->posts );
											$mobile_length = ceil( $num_posts / 2 );
											$desktop_length = ceil( $num_posts / 3 );
											$left_items_mobile = array_slice( $tier->posts, 0,  $mobile_length);
											$right_items_mobile = array_slice( $tier->posts, $mobile_length, $mobile_length );
											$left_items_desktop = array_slice( $tier->posts, 0, $desktop_length );
											$mid_items_desktop = array_slice( $tier->posts, $desktop_length, $desktop_length );
											$right_items_desktop = array_slice( $tier->posts, ceil( $num_posts * 2 / 3 ), $desktop_length );

											function render_text_links ( object $post ) : string {
												ob_start();
											?>
												<a href="#"
													rel="nofollow noreferrer noopener"
													target="_blank"
													title="<?= esc_attr( $post->post_title ) ?>"
												>
													<?= $post->post_title ?>
												</a>
											<?php return ob_get_clean();
											}
										?>

											<div class="left-mobile lg:hidden">
												<?php foreach ( $left_items_mobile as $post ) : ?>
													<?= render_text_links( $post ); ?>
												<?php endforeach ?>
											</div>
											<div class="right-mobile lg:hidden">
												<?php foreach ( $right_items_mobile as $post ) : ?>
													<?= render_text_links( $post ); ?>
													<?php endforeach ?>
											</div>

											<div class="left-desktop hidden lg:block">
												<?php foreach ( $left_items_desktop as $post ) : ?>
													<?= render_text_links( $post ); ?>
												<?php endforeach ?>
											</div>

											<div class="mid-desktop hidden lg:block">
												<?php foreach ( $mid_items_desktop as $post ) : ?>
													<?= render_text_links( $post ); ?>
												<?php endforeach ?>
											</div>

											<div class="right-desktop hidden lg:block">
												<?php foreach ( $right_items_desktop as $post ) : ?>
													<?= render_text_links( $post ); ?>
												<?php endforeach ?>
											</div>
									<?php endif ?>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div> <!-- .partners -->

	<div class="partnership-showcase">
		<div class="partnership-showcase__container container mx-auto">
			<h3 class="partnership-showcase__title font-bold text-px32 leading-px40 md:leading-px42 lg:text-px40 lg:leading-px48">Partnership Showcase</h3>
			<?php
				$cards = [
					[
						'title' => 'Sed tellus eu eget dictum id. Gravida et.',
						'description' => 'Urna dolor cras congue velit. A quis eu turpis blandit arcu in odio neque quis. Sed sed augue turpis.',
						'file'	=> '#',
					],
					[
						'title'	=> 'Fames feugiat non luctus sed imperdiet.',
						'description' => 'Enim, ipsum etiam id mi consectetur adipiscing sed sagittis, pellentesque. Leo.',
						'file'	=> '#',
					],
					[
						'title'	=> 'Ut platea blandit et eu justo et commodo.',
						'description' => 'Elit dui ultrices rhoncus quis quam pellentesque elit. Justo, nunc varius arcu pellentesque morbi. ',
						'file' => '#',
					],
				];
			?>
			<div class="partnership-showcase__cards flex flex-col md:flex-row flex-wrap">
				<?php
					foreach ( $cards as $card ) :
				?>
					<div class="partnership-showcase__card single-card">
						<h4 class="partnership-showcase__card-title"><?= $card['title'] ?></h4>
						<p class="partnership-showcase__card-description"><?= $card['description'] ?></p>
						<a href="<?= $card['file'] ?>" class="partnership-showcase__card-download" download>Download</a>
					</div>
				<?php endforeach ?>
			</div>

			<a href="#" class="partnership-showcase__partner mx-auto flex-grow-0 flex-shrink-0" target="_blank">Become a Partner</a>
		</div>
	</div> <!-- .parnership-showcase -->

	<div class="cards">
		<div class="cards__container container mx-auto flex flex-row flex-wrap">
			<h3 class="cards__title font-bold text-px32 lg:text-46 leading-px42 lg:leading-56 text-center w-full">There are other ways to help.</h3>
			<?= Circulation_Card::render_donation(); ?>
			<?= Circulation_Card::render_counselor(); ?>
		</div>
	</div>

</main>
<?php get_footer(); ?>
