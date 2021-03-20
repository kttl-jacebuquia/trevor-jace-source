<?php /* Ally Training */

use \TrevorWP\Theme\Single_Page\Ally_Training as Page;

?>

<?php get_header(); ?>

<main id="site-content" role="main" class="site-content">
	<?php # Header ?>
	<?= Page::get_component( Page::SECTION_HEADER )->render() ?>

	<?php # Info Boxes ?>
	<div class="bg-gray-light py-20 lx:py-36">
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
	<div class="bg-blue_green py-20 lx:py-36">
		<div class="container mx-auto text-white">
			<?= Page::get_component( Page::SECTION_INFO_BOXES_2 )->render( [
			] ) ?>
		</div>
	</div>

	<?php # todo: Another training you might find helpful.  ?>

	<?php # Testimonials Carousel ?>
	<?= Page::get_component( Page::SECTION_TESTIMONIALS )->render() ?>

	<?php # Circulation ?>
	<?= Page::get_component( Page::SECTION_CIRCULATION )->render() ?>
</main>
