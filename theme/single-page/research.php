<?php /* Research */

use \TrevorWP\Theme\Single_Page\Research as Page;

$staff_ids = wp_parse_id_list( Page::get_val( Page::SETTING_STAFF_LIST ) );
?>

<?php get_header(); ?>

	<main id="site-content" role="main" class="site-content">
		<?php # Header ?>
		<?= Page::get_component( Page::SECTION_HEADER )->render() ?>

		<div class="bg-white text-teal-dark">

			<?php # Latest Briefs ?>
			<div class="container mx-auto">
				<?= Page::get_component( Page::SECTION_LATEST )->render( [
						'title_cls' => [ 'centered' ],
						'desc_cls'  => [ 'centered' ]
				] ) ?>
				<?php /* todo: View All -> get_post_type_archive_link( \TrevorWP\CPT\Research::POST_TYPE ) */ ?>
			</div>

			<?php # Info Boxes ?>
			<?= Page::get_component( Page::SECTION_INFO_BOXES )->render( [
					'ext_cls'      => [
							'container mx-auto',
							'bg-gray-light rounded-px10'
					],
					'box_text_cls' => [ 'font-bold', 'text-px80', 'leading-px90', 'tracking-em_001' ],
					'box_desc_cls' => [
							'font-normal',
							'text-px20',
							'leading-px26',
							'xl:text-px22',
							'xl:leading-px28',
							'xl:tracking-em_001',
					]
			] ) ?>

			<?php # Research Staff ?>
			<?= Page::get_component( Page::SECTION_STAFF )->render( [
					'posts' => ( new \WP_Query( [
							'post_type'      => \TrevorWP\CPT\Team::POST_TYPE,
							'orderby'        => 'include',
							'include'        => $staff_ids,
							'posts_per_page' => - 1,
							'post_status'    => 'publish'
					] ) )->posts
			] ) ?>

			<?php # CTA Box ?>
			<div class="container mx-auto">
				<?= Page::get_component( Page::SECTION_CTA_BOX )->render( [
						'cls'     => [ 'bg-gray-light' ],
						'btn_cls' => [ 'border-2 border-teal-dark' ]
				] ) ?>
			</div>

			<?php # Recent News ?>
			<?php /* todo: echo Page::get_component( Page::SECTION_RECENT_NEWS )->render() */ ?>
	</main>

<?php get_footer();

