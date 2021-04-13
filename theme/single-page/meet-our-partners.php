<?php /* Meet Our Partners */

use \TrevorWP\Theme\Single_Page\Meet_Our_Partners as Page;
use \TrevorWP\Theme\Customizer\Advocacy;
use \TrevorWP\Meta;

$top_partner_tier = ( new \WP_Term_Query( [
		'taxonomy'   => \TrevorWP\CPT\Get_Involved\Get_Involved_Object::TAXONOMY_PARTNER_TIER,
		'orderby'    => 'meta_value_num',
		'order'      => 'DESC',
		'hide_empty' => true,
		'meta_key'   => \TrevorWP\Meta\Taxonomy::KEY_PARTNER_TIER_VALUE,
		'number'     => 1,
] ) )->terms;
$top_partner_tier = reset( $top_partner_tier );

$top_grant_tier = ( new \WP_Term_Query( [
		'taxonomy'   => \TrevorWP\CPT\Get_Involved\Get_Involved_Object::TAXONOMY_GRANT_TIER,
		'orderby'    => 'meta_value_num',
		'order'      => 'DESC',
		'hide_empty' => true,
		'meta_key'   => \TrevorWP\Meta\Taxonomy::KEY_PARTNER_TIER_VALUE,
		'number'     => 1,
] ) )->terms;
$top_grant_tier = reset( $top_grant_tier );
?>

<?php get_header(); ?>

	<main id="site-content" role="main" class="site-content meet-our-partners">
		<?php # Header ?>
		<?= Page::get_component( Page::SECTION_HEADER )->render() ?>

		<div class="bg-white text-teal-dark pb-px100 lg2:pb-px150">
			<div class="container mx-auto">

				<div class="partners-block">
					<?php # Corporate Partners ?>
					<?php ob_start(); ?>
					<div class="partners-block__panel">
						<div class="partners-block__title">
							<div class="partners-block__tier-name"><?= $top_partner_tier->name ?></div>
							<div class="partners-block__tier-amount">
								<?= esc_html( get_term_meta( $top_partner_tier->term_id, Meta\Taxonomy::KEY_PARTNER_TIER_NAME, true ) ) ?>
							</div>
						</div>
						<div class="partners-block__list partners-block__list--images">
							<?php foreach (
									( new \WP_Query(
											[
													'tax_query' => [
															[
																	'taxonomy' => \TrevorWP\CPT\Get_Involved\Get_Involved_Object::TAXONOMY_PARTNER_TIER,
																	'terms'    => [ $top_partner_tier->term_id ]
															]
													],
													'post_type' => \TrevorWP\CPT\Get_Involved\Partner::POST_TYPE,
													'orderby'   => 'title',
													'order'     => 'ASC',
											]
									) )->posts as $partner
							): ?>
							<?php
								$featured_image = get_the_post_thumbnail_url($partner->ID, \TrevorWP\Theme\Helper\Thumbnail::SIZE_MD);
							?>
								<div class="partners-block__image">
									<div class="partners-block__img-wrap">
										<img src="<?= $featured_image ?>" alt="<?= $partner->post_title ?>">
									</div>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
					<?= Page::get_component( Page::SECTION_CORP )->render( [
							'content' => ob_get_clean(),
							'btn_cls' => [ 'primary' ],
					] ) ?>
				</div>

				<div class="partners-block">
					<?php # Institutional Funders ?>
					<?php ob_start(); ?>
					<div class="partners-block__panel">
						<div class="partners-block__title">
							<?= $top_grant_tier->name ?>
						</div>
						<div class="partners-block__list partners-block__list--text">
							<?php foreach (
									( new \WP_Query(
											[
													'tax_query' => [
															[
																	'taxonomy' => \TrevorWP\CPT\Get_Involved\Get_Involved_Object::TAXONOMY_GRANT_TIER,
																	'terms'    => [ $top_grant_tier->term_id ],
															]
													],
													'post_type' => \TrevorWP\CPT\Get_Involved\Grant::POST_TYPE,
													'orderby'   => 'title',
													'order'     => 'ASC',
											]
									) )->posts as $partner
							): ?>
								<div class="partners-block__name">
									<?= $partner->post_title ?>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
					<?= Page::get_component( Page::SECTION_INST )->render( [
							'content' => ob_get_clean(),
							'btn_cls' => [ 'primary' ],
					] ) ?>
				</div>

				<div class="partners-grid">
					<?php # Featured Organizations ?>
					<?php ob_start(); ?>
						<div class="partners-grid__list">
							<?php if (
									! empty( $partner_ids = Advocacy::get_val( Advocacy::SETTING_HOME_PARTNER_ORG_LIST ) ) &&
									! empty( $partner_ids = wp_parse_id_list( $partner_ids ) )
							) {
								foreach (
										get_posts( [
												'post__in'    => $partner_ids,
												'post_type'   => \TrevorWP\CPT\Get_Involved\Partner::POST_TYPE,
												'numberposts' => - 1,
												'orderby'     => 'post__in',
												'order'       => 'DESC',
										] ) as $partner
								) {
									if ( has_post_thumbnail( $partner ) ) { ?>
										<div class="partners-grid__item">
											<?php $has_url = ! empty( $partner_url = \TrevorWP\Meta\Post::get_partner_url( $partner->ID ) ); ?>
											<a class="partners-grid__link"
											   rel="nofollow noreferrer noopener"
											   target="_blank" href="<?= $has_url ? esc_attr( $partner_url ) : '#' ?>">
												<?= wp_get_attachment_image( get_post_thumbnail_id( $partner ), 'medium', false, [
														'class' => 'partners-grid__image'
												] ) ?>
											</a>
										</div>
										<?php
									}
								}
							} ?>
						</div>
					<?= Page::get_component( Page::SECTION_ORG )->render( [
							'content' => ob_get_clean(),
					] ) ?>
				</div>

				<?php # CTA Box ?>
				<?= Page::get_component( Page::SECTION_CTA_BOX )->render_as_cta_box( [
						'cls' => []
				] ) ?>
			</div>
		</div>
	</main>

<?php get_footer();
