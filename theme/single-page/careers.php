<?php /* Careers */

use \TrevorWP\Theme\Single_Page\Careers as Page;

?>

<?php get_header(); ?>

	<main id="site-content" role="main" class="site-content">
		<?php # Header ?>
		<?= Page::get_component( Page::SECTION_HEADER )->render() ?>

		<div class="bg-white text-teal-dark">

			<div class="container content bg-gray-light md:bg-transparent mx-auto">
				<?= Page::get_component( Page::SECTION_INFO_BOXES )->render( [] ) ?>

				<div class="cta-container text-center">
					<a href="<?= Page::get_val( Page::SETTING_INFO_BOXES_CTA_URL ) ?>" target="_blank"
							class="page-btn primary" rel="noopener nofollow noreferrer">
						<?= Page::get_val( Page::SETTING_INFO_BOXES_CTA_TEXT ) ?>
					</a>
				</div>
			</div>
		</div>

		<?php # Circulation ?>
		<?= Page::get_component( Page::SECTION_CIRCULATION )->render() ?>
	</main>

<?php get_footer();
