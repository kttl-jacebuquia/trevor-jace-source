<?php get_header(); ?>

<?php

use \TrevorWP\Theme\ACF\Options_Page\Post_Type\A_Post_Type as PT;
use \TrevorWP\Theme\ACF\Field_Group\Post_Grid;
use \TrevorWP\Theme\ACF\Field_Group\DOM_Attr;

$post_type       = ( empty( $qo = $wp_query->get_queried_object() ) || empty( $qo->name ) ) ? 'post' : $qo->name;
$header_data     = PT::get_option_for( $post_type, PT::FIELD_HEADER );
$pagination_type = PT::get_option_for( $post_type, PT::FIELD_ARCHIVE_PAGINATION_TYPE );
$content_top     = PT::get_option_for( $post_type, PT::FIELD_ARCHIVE_CONTENT_TOP );
$content_btm     = PT::get_option_for( $post_type, PT::FIELD_ARCHIVE_CONTENT_BTM );
$cta_data        = PT::get_option_for( $post_type, PT::FIELD_ARCHIVE_CTA );
$cta_location    = PT::get_option_for( $post_type, PT::FIELD_ARCHIVE_CTA_LOCATION );
$num_words       = PT::get_option_for( $post_type, PT::FIELD_NUM_WORDS );
$container_attrs = DOM_Attr::get_attrs_of( PT::get_option_for( $post_type, PT::FIELD_ARCHIVE_CONTAINER_ATTR ) );

# Header
if ( ! empty( $header_data ) ) {
	echo \TrevorWP\Theme\ACF\Field_Group\Page_Header::render( false, $header_data, array() );
} else {
	echo TrevorWP\Theme\Helper\Page_Header::text(
		array(
			'title' => get_queried_object()->label,
		)
	);
}

# Add default class to container
# TO DO:
# If CMS-set attributes doesn't work,
# Might implement post type check for additional classes instead
$container_attrs['class'] = implode(
	' ',
	array( 'container mx-auto max-w-screen-xl pt-10 md:py-px50 lg:py-20', $container_attrs['class'] ),
);
?>

<main id="site-content" role="main" class="bg-white">
	<div <?php echo DOM_Attr::render_attrs_of( $container_attrs ); ?>>
		<?php # Top Content ?>
		<?php if ( ! empty( $content_top ) ) { ?>
			<div class="page-ext-content page-ext-content-top"><?php echo $content_top; ?></div>
		<?php } ?>

		<?php # Sorter ?>
		<?php if ( $page_sorter = \TrevorWP\Theme\Helper\Sorter::get_page_sorter() ) { ?>
			<?php echo $page_sorter->render(); ?>
		<?php } ?>

		<?php # Grid ?>
		<?php
		echo Post_Grid::render(
			false,
			array(
				Post_Grid::FIELD_SOURCE     => Post_Grid::SOURCE_PICK,
				Post_Grid::FIELD_POST_ITEMS => $wp_query->posts,
			),
			array(
				'tileClass' => array( 'border', 'border-blue_green', 'border-opacity-50' ),
				'num_words' => $num_words,
			),
		)
		?>

		<?php # Pagination ?>
		<?php
		switch ( $pagination_type ) {
			case PT::PAGINATION_TYPE_AJAX:
				get_template_part( 'template-parts/ajax-pagination' );
				break;
			case PT::PAGINATION_TYPE_NORMAL:
			default:
				get_template_part( 'template-parts/pagination' );
				break;
		}
		?>
	</div>
</main><!-- #site-content -->

<?php get_footer(); ?>
