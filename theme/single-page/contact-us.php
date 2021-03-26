<?php /* Contact Us */

use \TrevorWP\Theme\Single_Page\Contact_Us as Page;

?>

<?php get_header(); ?>

	<main id="site-content" role="main" class="site-content">
		<?php # Header ?>
		<?= Page::get_component( Page::SECTION_HEADER )->render() ?>

		<div class="bg-white text-teal-dark">
			<div class="container mx-auto">

				<?php # Addresses ?>
				<h2 class="font-bold mt-px60 md:mt-px80 lg:mt-px90 text-px28 leading-px34 md:text-px32 md:leading-px42 lg:text-px36 lg:leading-px40"><?= Page::get_val( Page::SETTING_ADDRESSES_TITLE ) ?></h2>
				<div class="flex address-container mt-px20 lg:mt-px30">
					<?php foreach ( Page::get_val( Page::SETTING_ADDRESSES_DATA ) as $address ): ?>
						<div>
							<h3 class="font-bold text-px20 leading-px30 md:leading-px26 lg:text-px24 lg:leading-px30"><?= esc_html( @$address['name'] ) ?></h3>
							<div class="mt-px6 lg:mt-px12 font-normal text-px18 leading-px24 lg:text-px22 lg:leading-px28"><?= esc_html( @$address['line1'] ) ?></div>
							<div class="font-normal text-px18 leading-px24 lg:text-px22 lg:leading-px28"><?= esc_html( @$address['line2'] ) ?></div>
						</div>
					<?php endforeach; ?>
				</div>

				<?php # Phones ?>
				<h2 class="font-bold mt-px50 md:mt-px45 lg:mt-px90 text-px28 leading-px34 md:text-px32 md:leading-px42 lg:text-px36 lg:leading-px40"><?= Page::get_val( Page::SETTING_PHONES_TITLE ) ?></h2>
				<div class="flex flex-col md:flex-row flex-wrap phones-container mt-px20 md:-mt-px20 lg:mt-px30 mb-14 md:mb-20">
					<?php foreach ( Page::get_val( Page::SETTING_PHONES_DATA ) as $phone ): ?>
						<div class="mb-px28 md:mb-0 md:mr-px40 lg:mr-px50 md:mt-px40 lg:mt-0">
							<h3 class="font-bold text-px20 leading-px30 md:leading-px26 lg:text-px24 lg:leading-px30"><?= esc_html( @$phone['name'] ) ?></h3>
							<div class="font-normala text-px18 leading-px24 lg:text-px22 lg:leading-px28 tracking-px05"><?= esc_html( @$phone['number'] ) ?></div>
						</div>
					<?php endforeach; ?>
				</div>

				<?php # Info Box ?>
				<?= Page::get_sub_component( Page::SUB_COMPONENT_INFO_CARD )->render( [
						'title_cls' => [],
						'desc_cls'  => [],
						'btn_cls'   => [ 'page-btn', 'primary' ],
				] ) ?>

				<?php # Footnote ?>
				<div class="footnote flex flex-col items-center lg:flex-row lg:justify-center mt-px50 md:mt-px40 lg:mt-px60 pb-px150 md:pb-px100">
					<p class="text-center font-normal text-px18 leading-px26 tracking-px05 mb-px30 lg:mb-0 lg:mr-px16"><?= Page::get_val( Page::SETTING_FOOTNOTE_NOTE ) ?></p>
					<a href="#" class="font-bold inline text-px18 leading-px24 tracking-px05"><span class="wave-underline border-b-2 border-teal-dark">Privacy Policy</span></a>
				</div>

			</div>
		</div>
	</main>

<?php get_footer();
