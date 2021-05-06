<?php get_header(); ?>

<?php

use \TrevorWP\Theme\ACF\Options_Page\Post_Type\A_Post_Type as PT;
use \TrevorWP\Theme\ACF\Field_Group\Post_Grid;

$post_type       = ( empty( $qo = $wp_query->get_queried_object() ) || empty( $qo->name ) ) ? 'post' : $qo->name;
$header_data     = PT::get_option_for( $post_type, PT::FIELD_HEADER );
$pagination_type = PT::get_option_for( $post_type, PT::FIELD_ARCHIVE_PAGINATION_TYPE );
$content_top     = PT::get_option_for( $post_type, PT::FIELD_ARCHIVE_CONTENT_TOP );
$content_btm     = PT::get_option_for( $post_type, PT::FIELD_ARCHIVE_CONTENT_BTM );
$cta_data        = PT::get_option_for( $post_type, PT::FIELD_ARCHIVE_CTA );
$cta_location    = PT::get_option_for( $post_type, PT::FIELD_ARCHIVE_CTA_LOCATION );

# Header
if ( ! empty( $header_data ) ) {
	echo \TrevorWP\Theme\ACF\Field_Group\Page_Header::render( false, $header_data, [] );
} else {
	echo TrevorWP\Theme\Helper\Page_Header::text( [
			'title' => get_queried_object()->label,
	] );
}
?>

<main id="site-content" role="main" class="bg-white">
	<?php # Top Content ?>
	<?php if ( ! empty( $content_top ) ) { ?>
		<div class="page-ext-content page-ext-content-top"><?= $content_top ?></div>
	<?php } ?>

	<?php # Sorter ?>
	<?php if ( $page_sorter = \TrevorWP\Theme\Helper\Sorter::get_page_sorter() ) { ?>
		<?= $page_sorter->render(); ?>
	<?php } ?>

	<?php # Grid ?>
	<?= Post_Grid::render( false, [
			Post_Grid::FIELD_SOURCE     => Post_Grid::SOURCE_PICK,
			Post_Grid::FIELD_POST_ITEMS => $wp_query->posts,
	], [ 'tileClass' => [ 'border', 'border-blue_green', 'border-opacity-50' ] ] ) ?>

	<?php # Pagination ?>
	<?php switch ( $pagination_type ) {
		case PT::PAGINATION_TYPE_AJAX:
			get_template_part( 'template-parts/ajax-pagination' );
			break;
		case PT::PAGINATION_TYPE_NORMAL:
		default:
			get_template_part( 'template-parts/pagination' );
			break;
	} ?>

	<?php # CTA Before Bottom ?>
	<?php if ( $cta_location === PT::CTA_LOCATION_OPTION_BEFORE && ! empty( $cta_data ) ) : ?>
		<?= \TrevorWP\Theme\ACF\Field_Group\Button_Group::render( false, $cta_data, [] ) ?>
	<?php endif; ?>

	<?php # Bottom Content ?>
	<?php if ( ! empty( $content_btm ) ) { ?>
		<div class="page-ext-content page-ext-content-btm">
			<?= $content_btm ?>

			<?php # CTA Inside Bottom ?>
			<?php if ( $cta_location === PT::CTA_LOCATION_OPTION_INSIDE && ! empty( $cta_data ) ) : ?>
				<?= \TrevorWP\Theme\ACF\Field_Group\Button_Group::render( false, $cta_data, [] ) ?>
			<?php endif; ?>
		</div>
	<?php } ?>

	<?php # CTA After Bottom ?>
	<?php if ( $cta_location === PT::CTA_LOCATION_OPTION_AFTER && ! empty( $cta_data ) ) : ?>
		<?= \TrevorWP\Theme\ACF\Field_Group\Button_Group::render( false, $cta_data, [] ) ?>
	<?php endif; ?>
</main><!-- #site-content -->

<?php get_footer(); ?>
