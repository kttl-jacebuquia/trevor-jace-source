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
				<h2 class="font-bold"><?= Page::get_val( Page::SETTING_ADDRESSES_TITLE ) ?></h2>
				<div class="flex">
					<?php foreach ( Page::get_val( Page::SETTING_ADDRESSES_DATA ) as $address ): ?>
						<div>
							<h3><?= esc_html( @$address['name'] ) ?></h3>
							<div><?= esc_html( @$address['line1'] ) ?></div>
							<div><?= esc_html( @$address['line2'] ) ?></div>
						</div>
					<?php endforeach; ?>
				</div>

				<?php # Phones ?>
				<h2 class="font-bold"><?= Page::get_val( Page::SETTING_PHONES_TITLE ) ?></h2>
				<div class="flex">
					<?php foreach ( Page::get_val( Page::SETTING_PHONES_DATA ) as $phone ): ?>
						<div>
							<h3><?= esc_html( @$phone['name'] ) ?></h3>
							<div><?= esc_html( @$phone['number'] ) ?></div>
						</div>
					<?php endforeach; ?>
				</div>

				<?php # Info Box ?>
				<div class="bg-light-gray">
					<?= Page::get_sub_component( Page::SUB_COMPONENT_INFO_CARD )->render( [
							'title_cls' => [],
							'desc_cls'  => [],
							'btn_cls'   => [ 'page-btn', 'primary' ],
					] ) ?>
				</div>

				<?php # Footnote ?>
				<div>
					<?= Page::get_val( Page::SETTING_FOOTNOTE_NOTE ) ?>
				</div>

			</div>
		</div>
	</main>

<?php get_footer();
