<?php /* Ally Training */

use \TrevorWP\Theme\Single_Page\Ally_Training as Page;

?>

<?php get_header(); ?>

	<main id="site-content" role="main" class="site-content">
		<?php # Header ?>
		<?= Page::get_component( Page::SECTION_HEADER )->render() ?>

		<?php # Info Boxes ?>
		<div class="bg-white py-16 md:py-20 xl:py-24">
			<div class="container mx-auto text-teal-dark">
				<?= Page::get_component( Page::SECTION_INFO_BOXES )->render( [
						'box_text_cls' => [
								'font-semibold',
								'text-px24',
								'leading-px30',
								'tracking-em005',
								'md:leading-px28',
								'md:tracking-em001',
								'xl:text-px28',
								'xl:leading-px34',
								'xl:tracking-em005',
						],
						'box_desc_cls' => [
								'mt-2.5',
								'font-normal',
								'text-px18',
								'leading-px26',
								'tracking-px05',
								'md:leading-px24',
								'xl:text-px24',
								'xl:leading-px32',
						]
				] )
				?>
			</div>
		</div>

		<?php # Info Boxes 2 ?>
		<div class="bg-blue_green py-20 xl:py-32">
			<div class="container mx-auto text-white">
				<?= Page::get_component( Page::SECTION_INFO_BOXES_2 )->render( [
						'box_img_cls'   => [
								'h-40',
						],
						'container_cls' => [
								'xl:w-10/12 xl:mx-auto'
						],
						'box_desc_cls'  => [
								'font-medium',
								'text-px18',
								'leading-px24',
								'tracking-px05',
								'md:leading-px22',
								'md:tracking-em005',
								'xl:text-px20',
								'xl:leading-px26',
								'xl:tracking-px05',
						]
				] ) ?>
			</div>
		</div>

		<?php # Other Training  ?>
		<div class="other-training bg-gray-light py-20 xl:py-32 text-teal-dark">
			<div class="container mx-auto">
				<div class="grid grid-cols-6 gap-7 md:grid-cols-8 xl:grid-cols-12">
					<div class="col-span-6 text-center md:col-span-6 md:col-start-2 xl:col-span-6 xl:text-left xl:flex xl:flex-col xl:justify-center">
						<?php
						$contact_comp = Page::get_sub_component( Page::SUB_COMPONENT_OTHER_TRAINING_CONTACT );
						$button       = $contact_comp->get_val( \TrevorWP\Theme\Customizer\Component\Info_Card::SETTING_BUTTONS );
						if ( empty( $button ) ) {
							$button = false;
						} else {
							$button = reset( $button );
						}
						?>
						<h2 class="page-sub-title mx-auto text-center xl:text-left xl:ml-0">
							<?= $contact_comp->get_val( \TrevorWP\Theme\Customizer\Component\Info_Card::SETTING_TITLE ) ?>
						</h2>
						<?php if ( ! empty( $desc = $contact_comp->get_val( \TrevorWP\Theme\Customizer\Component\Info_Card::SETTING_DESC ) ) ): ?>
							<p class="page-sub-title-desc mx-auto text-center xl:text-left xl:ml-0 xl:max-w-px605"><?= $desc ?></p>
						<?php endif; ?>
						<div class="mt-6">
							<a href="#" class="page-btn primary"><?= esc_html( @$button['label'] ) ?></a>
						</div>
					</div>
					<div class="col-span-6 md:col-span-6 md:col-start-2 xl:col-span-5 xl:col-start-8">
						<?= Page::get_sub_component( Page::SUB_COMPONENT_OTHER_TRAINING_CARE )->render( [
								'cls'       => [ 'bg-white' ],
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
					</div>
				</div>
			</div>
		</div>

		<?php # Testimonials Carousel ?>
		<?= Page::get_component( Page::SECTION_TESTIMONIALS )->render() ?>

		<?php # Circulation ?>
		<?= Page::get_component( Page::SECTION_CIRCULATION )->render() ?>
	</main>

<?php get_footer();
