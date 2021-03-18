<?php /* Public Education */

use \TrevorWP\Theme\Single_Page\Public_Education as Page;

?>

<?php get_header(); ?>

<main id="site-content" role="main" class="site-content">
	<?php # Header ?>
	<?= Page::get_component( Page::SECTION_HEADER )->render() ?>

	<?php # todo: Info Boxes ?>

	<?php # todo: Offerings ?>

	<?php # Testimonials Carousel ?>
	<?= Page::get_component( Page::SECTION_TESTIMONIALS )->render() ?>

	<?php # Circulation ?>
	<?= Page::get_component( Page::SECTION_CIRCULATION )->render( [ 'cards' => [ 'donation', 'fundraiser' ] ] ) ?>
</main>

<?php get_footer();
