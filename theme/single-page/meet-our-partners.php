<?php /* Meet Our Partners */

use \TrevorWP\Theme\Single_Page\Meet_Our_Partners as Page;

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

	<main id="site-content" role="main" class="site-content">
		<?php # Header ?>
		<?= Page::get_component( Page::SECTION_HEADER )->render() ?>

		<div class="bg-white text-teal-dark">
			<div class="container mx-auto">
				<?php # Corporate Partners ?>
				<?php ob_start(); ?>
				<div class="flex flex-col rounded-px10 w-full">
					<div class="bg-gray-light w-full">
						<?= $top_partner_tier->name ?>
					</div>
					<div class="flex flex-wrap">
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
							<div class="flex w-full md:w-1/3">
								<?= $partner->post_title /* todo: logo here */ ?>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
				<?= Page::get_component( Page::SECTION_CORP )->render( [
						'content' => ob_get_clean(),
						'btn_cls' => [ 'primary' ],
				] ) ?>

				<?php # Institutional Funders ?>
				<?php ob_start(); ?>
				<div class="flex flex-col rounded-px10 w-full">
					<div class="bg-gray-light w-full">
						<?= $top_grant_tier->name ?>
					</div>
					<div class="flex flex-wrap">
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
							<div class="flex w-full md:w-1/3">
								<?= $partner->post_title ?>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
				<?= Page::get_component( Page::SECTION_INST )->render( [
						'content' => ob_get_clean(),
						'btn_cls' => [ 'primary' ],
				] ) ?>

				<?php # Featured Organizations ?>
				<?php ob_start(); ?>
				<div>
					//todo:
				</div>
				<?= Page::get_component( Page::SECTION_ORG )->render( [
						'content' => ob_get_clean(),
				] ) ?>

				<?php # CTA Box ?>
				<?= Page::get_component( Page::SECTION_CTA_BOX )->render_as_cta_box( [
						'cls' => []
				] ) ?>
			</div>
		</div>
	</main>

<?php get_footer();
