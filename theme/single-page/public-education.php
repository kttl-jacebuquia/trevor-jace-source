<?php /* Public Education */

use \TrevorWP\Theme\Single_Page\Public_Education as Page;

?>

<?php get_header(); ?>

	<main id="site-content" role="main" class="site-content">
		<?php # Header ?>
		<?= Page::get_component( Page::SECTION_HEADER )->render() ?>

		<?php # Info Boxes ?>
		<div class="bg-white py-20 xl:py-36">
			<div class="container mx-auto text-teal-dark">
				<?= Page::get_component( Page::SECTION_INFO_BOXES )->render( [
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
			</div>
		</div>

		<?php # Offerings ?>
		<div class="bg-gray-light py-20 xl:py-36 text-teal-dark">
			<div class="container mx-auto flex flex-col items-center">
				<h2 class="page-sub-title centered"><?= Page::get_val( Page::SETTING_OFFERINGS_TITLE ) ?></h2>
				<?php if ( $offerings_desc = Page::get_val( Page::SETTING_OFFERINGS_DESC ) ) { ?>
					<p class="page-sub-title-desc centered mb-0 mb:mb-0 xl:mb-0"><?= wp_filter_kses( $offerings_desc ) ?></p>
				<?php } ?>

				<div class="grid grid-cols-6 my-14 max-w-px319 gap-7 md:grid-cols-8 md:my-14 md:max-w-screen-md xl:grid-cols-12 xl:my-16 xl:max-w-screen-xl">
					<?= Page::get_sub_component( Page::SUB_COMPONENT_OFFERINGS_CARD_ALY )->render( [
							'btn_href'  => \TrevorWP\Theme\Single_Page\Ally_Training::get_permalink(),
							'cls'       => [
									'col-span-6',
									'md:col-start-2',
									'xl:col-span-5',
									'xl:col-start-2'
							],
							'title_cls' => $title_cls = [
									'font-bold',
									'text-px30',
									'leading-px40',
									'md:text-px32',
									'md:leading-px42',
									'xl:text-px36',
									'xl:leading-px46'
							],
							'desc_cls'  => $desc_cls = [
									'font-normal',
									'text-base',
									'leading-px22',
									'md:text-px18',
									'md:leading-px26',
									'md:tracking-px05',
									'xl:text-px20',
									'xl:leading-px30',
							],
							'btn_cls'   => $btn_cls = [
									'page-btn',
									'secondary',
							]
					] ) ?>
					<?= Page::get_sub_component( Page::SUB_COMPONENT_OFFERINGS_CARD_CARE )->render( [
							'cls'       => [ 'col-span-6', 'md:col-start-2', 'xl:col-span-5', 'xl:col-start-7' ],
							'title_cls' => $title_cls,
							'desc_cls'  => $desc_cls,
							'btn_cls'   => $btn_cls,
					] ) ?>
				</div>

				<a href="#" class="page-btn primary">Contact Our Training Team</a>

			</div>
		</div>

		<?php # Testimonials Carousel ?>
		<?= Page::get_component( Page::SECTION_TESTIMONIALS )->render() ?>

		<?php # Circulation ?>
		<?= Page::get_component( Page::SECTION_CIRCULATION )->render() ?>
	</main>

<?php get_footer();
