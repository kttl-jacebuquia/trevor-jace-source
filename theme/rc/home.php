<?php /* Resources Center: Home */ ?>
<?php get_header(); ?>
<?php

// TODO: remove this once replaced with options page
use \TrevorWP\Theme\Customizer;

use \TrevorWP\Theme\ACF\Options_Page\Resource_Center;
use \TrevorWP\Theme\Helper;

$used_post_ids = array();

$card_num      = absint( Customizer\Resource_Center::get_val( Customizer\Resource_Center::SETTING_HOME_CARD_NUM ) );

# Trending
$featured_post_ids = wp_parse_id_list( Customizer\Resource_Center::get_val( Customizer\Resource_Center::SETTING_HOME_FEATURED ) );
$trending_posts    = Helper\Posts::get_from_list( $featured_post_ids, count( $featured_post_ids ), false );
$used_post_ids     = array_merge( $used_post_ids, wp_list_pluck( $trending_posts, 'ID' ) );

# Categories
$cat_rows         = array();
$featured_cat_ids = wp_parse_id_list( Customizer\Resource_Center::get_val( Customizer\Resource_Center::SETTING_HOME_CATS ) );
$featured_cats    = get_terms(
	array(
		'taxonomy'   => TrevorWP\CPT\RC\RC_Object::TAXONOMY_CATEGORY,
		'orderby'    => 'include',
		'include'    => $featured_cat_ids,
		'parent'     => 0,
		'hide_empty' => false,
	)
);

foreach ( $featured_cats as $cat ) {
	$cat_post_ids = wp_parse_id_list( Customizer\Resource_Center::get_val( Customizer\Resource_Center::PREFIX_SETTING_HOME_CAT_POSTS . $cat->term_id ) );

	$cat_posts = Helper\Posts::get_from_list(
		$cat_post_ids,
		$card_num,
		array( 'post__not_in' => $used_post_ids ),
		array(
			'post_type' => TrevorWP\CPT\RC\RC_Object::$PUBLIC_POST_TYPES,
			'tax_query' => array(
				'relation' => 'AND',
				array(
					'taxonomy' => TrevorWP\CPT\RC\RC_Object::TAXONOMY_CATEGORY,
					'field'    => 'term_id',
					'terms'    => $cat->term_id,
					'operator' => 'IN',
				),
			),
		)
	);

	$cat_rows[]    = Helper\Carousel::posts(
		$cat_posts,
		array(
			'id'           => "cat-{$cat->slug}",
			'title'        => '<a href="' . get_term_link( $cat ) . '">' . esc_html( $cat->name ) . '</a>',
			'subtitle'     => $cat->description,
			'class'        => 'text-white',
			'card_options' => array(
				'hide_cat_eyebrow' => true,
			),
		)
	);
	$used_post_ids = array_merge( $used_post_ids, wp_list_pluck( $cat_posts, 'ID' ) );
}

# Guide
$featured_guide = Helper\Posts::get_one_from_list(
	wp_parse_id_list( Customizer\Resource_Center::get_val( Customizer\Resource_Center::SETTING_HOME_GUIDES ) ),
	array( 'post__not_in' => $used_post_ids )
);

# Word
$featured_word = Helper\Posts::get_one_from_list(
	wp_parse_id_list( Customizer\Resource_Center::get_val( Customizer\Resource_Center::SETTING_HOME_GLOSSARY ) ),
	array(),
	array( 'post_type' => \TrevorWP\CPT\RC\Glossary::POST_TYPE )
);
?>

<?php if ( ! is_paged() ) { ?>

<main id="site-content" role="main" class="site-content">

	<?php echo Resource_Center::render_hero(); ?>

	<?php } ?>

	<?php
	# Trending
	echo Resource_Center::render_trending();
	?>

	<?php
	# First 2 Categories
	$category_carousel = Resource_Center::render_categories();
	foreach ( array_slice( $category_carousel, 0, 2 ) as $cat_carousel ) {
		echo $cat_carousel;
	}
	?>

	<?php
		echo Resource_Center::render_guide();
	?>

	<?php
	# Rest of the categories
	foreach ( array_slice( $category_carousel, 2 ) as $cat_carousel ) {
		echo $cat_carousel;
	}
	?>

	<?php
		echo Resource_Center::render_glossary();
	?>
</main>

<?php get_footer(); ?>
