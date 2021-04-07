<?php /* Research */

use \TrevorWP\Theme\Single_Page\Research as Page;
use \TrevorWP\Theme\Single_Page\Team;
use \TrevorWP\CPT\Research;
use \TrevorWP\Theme\Helper;

$staff_ids = wp_parse_id_list( Page::get_val( Page::SETTING_STAFF_LIST ) );
$researches = get_posts([
	'numberposts'  => 6,
	'post_type'    => Research::POST_TYPE,
]);

$placeholder_img = Team::get_val( Team::SETTING_GENERAL_PLACEHOLDER_IMG );

?>

<?php get_header(); ?>

	<main id="site-content" role="main" class="site-content">
		<?php # Header ?>
		<?= Page::get_component( Page::SECTION_HEADER )->render() ?>

		<div class="bg-white text-teal-dark flex flex-col">

			<?php # Latest Briefs ?>
			<div class="container mx-auto pt-px80 xl:pt-px140">
				<?= Page::get_component( Page::SECTION_LATEST )->render( [
						'title_cls' => [ 'centered', 'w-full' ],
						'desc_cls'  => [ 'centered', 'w-full' ]
				] ) ?>
			</div>

			<?php #Research Grid ?>
			<?= Helper\Tile_Grid::posts($researches, [
				'tileClass' => [ 'text-teal-dark', 'research-card' ],
				'class'     => [ 'text-white', 'container', 'mx-auto', 'research-tile-grid' ],
			]); ?>
			<a href="<?= get_post_type_archive_link( \TrevorWP\CPT\Research::POST_TYPE ) ?>" class="place-self-center font-bold text-px24 leading-px34 tracking-px05 md:text-px14 xl:text-px26 xl:leading-px36 mb-px90 md:mb-px100 xl:mb-px120">
				<span class="border-b-2 border-teal-dark wave-underline">View More</span>
			</a>

			<?php # Info Boxes ?>
			<div class="md:container bg-white mx-auto">
				<?= Page::get_component( Page::SECTION_INFO_BOXES )->render( [
						'ext_cls'      => [
								'container mx-auto',
								'bg-gray-light md:rounded-px10',
								'py-20 md:pt-px70 xl:pt-px110 xl:pb-px127',
						],
						'box_text_cls' => [ 'font-bold', 'text-px64', 'leading-px74', 'md:text-px60', 'md:leading-px70', 'xl:text-px70', 'xl:leading-px80', 'tracking-em_001' ],
						'box_desc_cls' => [
								'font-normal',
								'text-px18',
								'leading-px24',
								'xl:text-px20',
								'xl:leading-px26',
								'xl:tracking-em_001',
						]
				] ) ?>
			</div>

			<?php # Research Staff ?>
			<?= Page::get_component( Page::SECTION_STAFF )->render( [
					'posts' => ( new \WP_Query( [
							'post_type'      => \TrevorWP\CPT\Team::POST_TYPE,
							'orderby'        => 'include',
							'include'        => $staff_ids,
							'posts_per_page' => - 1,
							'post_status'    => 'publish',
					] ) )->posts,
					'title_cls'        => [ 'text-center', 'mx-auto', 'xl:mx-0', 'xl:text-left', ],
			] ) ?>

			<?php # CTA Box ?>
			<div class="container mx-auto">
				<?= Page::get_component( Page::SECTION_CTA_BOX )->render( [
						'cls'     => [ 'bg-gray-light' ],
						'btn_cls' => [ 'border-2 border-teal-dark', ]
				] ) ?>
			</div>

			<?php # Recent News ?>
			<?php /* todo: echo Page::get_component( Page::SECTION_RECENT_NEWS )->render() */ ?>
	</main>

<?php get_footer();

