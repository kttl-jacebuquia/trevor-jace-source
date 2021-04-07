<?php /* Careers */

use \TrevorWP\Theme\Single_Page\Careers as Page;

?>

<?php get_header(); ?>

	<main id="site-content" role="main" class="site-content">
		<?php # Header ?>
		<?= Page::get_component( Page::SECTION_HEADER )->render([ 'bg'  => 'teal-dark' ]) ?>

		<div class="bg-white text-teal-dark">
			<div class="mx-auto md:container">
				<div class="bg-gray-light py-20 md:rounded-px10 xl:py-24">
					<?= Page::get_component( Page::SECTION_INFO_BOXES )->render( [
							'ext_cls'      => [
									'container',
									'mx-auto',
							],
							'box_img_cls'  => [
									'h-24',
									'w-24',
									'my-6',
									'mb-8',
							],
							'box_text_cls' => [
									'mb-2',
									'font-bold',
									'text-px24',
									'leading-px28',
									'tracking-em001',
									'md:-tracking-px05',
									'md:mb-3',
									'xl:text-px26',
									'xl:leading-px36',
									'xl:tracking-em005',
							],
							'box_desc_cls' => [
									'font-normal',
									'text-px18',
									'leading-px24',
									'md:tracking-px05',
									'xl:text-px20',
									'xl:leading-px26',
									'xl:tracking-normal',
							],
					] ) ?>

					<div class="cta-container text-center mt-12 xl:mt-16">
						<a href="<?= Page::get_val( Page::SETTING_INFO_BOXES_CTA_URL ) ?>"
						   target="_blank" class="page-btn primary" rel="noopener nofollow noreferrer">
							<?= Page::get_val( Page::SETTING_INFO_BOXES_CTA_TEXT ) ?>
						</a>
					</div>
				</div>
			</div>
		</div>

		<?php # Circulation ?>
		<?= Page::get_component( Page::SECTION_CIRCULATION )->render() ?>
	</main>

<?php get_footer();
