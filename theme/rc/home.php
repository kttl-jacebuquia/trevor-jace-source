<?php /* Resources Center: Home */ ?>
<?php get_header(); ?>
<?php

use \TrevorWP\Theme\Customizer;
use \TrevorWP\Theme\Helper;

$used_post_ids = [];
$card_num      = absint( Customizer\Resource_Center::get_val( Customizer\Resource_Center::SETTING_HOME_CARD_NUM ) );

# Trending
$featured_post_ids = wp_parse_id_list( Customizer\Resource_Center::get_val( Customizer\Resource_Center::SETTING_HOME_FEATURED ) );
$trending_posts    = Helper\Posts::get_from_list( $featured_post_ids, count( $featured_post_ids ), false );
$used_post_ids     = array_merge( $used_post_ids, wp_list_pluck( $trending_posts, 'ID' ) );

# Categories
$cat_rows         = [];
$featured_cat_ids = wp_parse_id_list( Customizer\Resource_Center::get_val( Customizer\Resource_Center::SETTING_HOME_CATS ) );
$featured_cats    = get_terms( [
		'taxonomy'   => TrevorWP\CPT\RC\RC_Object::TAXONOMY_CATEGORY,
		'orderby'    => 'include',
		'include'    => $featured_cat_ids,
		'parent'     => 0,
		'hide_empty' => false,
] );

foreach ( $featured_cats as $cat ) {
	$cat_post_ids = wp_parse_id_list( Customizer\Resource_Center::get_val( Customizer\Resource_Center::PREFIX_SETTING_HOME_CAT_POSTS . $cat->term_id ) );

	$cat_posts = Helper\Posts::get_from_list( $cat_post_ids, $card_num, [ 'post__not_in' => $used_post_ids ], [
			'post_type' => TrevorWP\CPT\RC\RC_Object::$PUBLIC_POST_TYPES,
			'tax_query' => [
					'relation' => 'AND',
					[
							'taxonomy' => TrevorWP\CPT\RC\RC_Object::TAXONOMY_CATEGORY,
							'field'    => 'term_id',
							'terms'    => $cat->term_id,
							'operator' => 'IN'
					]
			]
	] );

	$cat_rows[]    = Helper\Carousel::posts( $cat_posts, [
			'id'       => "cat-{$cat->slug}",
			'title'    => '<a href="' . get_term_link( $cat ) . '">' . esc_html( $cat->name ) . '</a>',
			'subtitle' => $cat->description,
	] );
	$used_post_ids = array_merge( $used_post_ids, wp_list_pluck( $cat_posts, 'ID' ) );
}

# Guide
$featured_guide = Helper\Posts::get_one_from_list(
		wp_parse_id_list( Customizer\Resource_Center::get_val( Customizer\Resource_Center::SETTING_HOME_GUIDES ) ),
		[ 'post__not_in' => $used_post_ids ]
);

# Word
$featured_word = Helper\Posts::get_one_from_list(
		wp_parse_id_list( Customizer\Resource_Center::get_val( Customizer\Resource_Center::SETTING_HOME_GLOSSARY ) ),
		[],
		[ 'post_type' => \TrevorWP\CPT\RC\Glossary::POST_TYPE ]
);
?>

<?php if ( ! is_paged() ) { ?>
	<main id="site-content" role="main" class="site-content">
		<div class="container mx-auto text-center site-content-inner mt-8 md:mt-0 md:mb-8">
			<div class="mx-auto mb-10 md:mt-10 lg:w-4/6">
				<h2 class="font-semibold text-white text-px14 leading-px18 tracking-em001 mb-2 md:tracking-px05 lg:font-bold lg:text-px16 lg:leading-px20">
					RESOURCE CENTER
				</h2>
				<h1>
					<span class="text-white font-manrope font-bold text-px30 leading-px50 tracking-em005 md:text-px32 md:leading-px42 lg:text-px52 lg:leading-px62 lg:tracking-normal">Connection starts</span>
					<span class="block text-white font-caveat transform font-bold text-px44 leading-px54 tracking-em005 -mt-4 md:mt-0 lg:text-px70 lg:leading-px80"
						  style="--tw-rotate: -1deg;">with knowledge.</span>
				</h1>

				<div class="my-8 mx-auto md:px-8 md:my-6 lg:w-9/12 lg:px-0">
					<form role="search" method="get" class="search-form"
						  action="<?= esc_url( \TrevorWP\CPT\RC\RC_Object::get_search_url() ) ?>">
						<?= Helper\Search_Input::render_rc(); ?>
					</form>
				</div>

				<p class="text-white md:mt-8 md:mb-5">Browse a topic or check out what’s trending.</p>

				<div class="flex flex-wrap justify-center mt-4">
					<?php foreach ( $featured_cats as $cat ) { ?>
						<a href="<?= esc_url( "#cat-{$cat->slug}" ) ?>"
						   class="rounded-full py-1 px-5 bg-violet mx-2 mb-3 text-white">
							<?= esc_html( $cat->name ); ?>
						</a>
					<?php } ?>
				</div>

				<div class="mt-8 animate-bounce hidden md:block">
					<i class="trevor-ti-chevron-down text-4xl text-white"></i>
				</div>
			</div>
		</div>
	</main>
<?php } ?>

<?php # Trending
if ( ! empty( $trending_posts ) ) {
	echo Helper\Carousel::posts( $trending_posts, [
			'title'     => 'Trending',
			'subtitle'  => 'Explore the latest articles, resources, and guides.',
			'title_cls' => 'text-center',
			'onlyMd'    => true,
	] );
} ?>

<?php # First 2 Categories
foreach ( array_slice( $cat_rows, 0, 2 ) as $cat_carousel ) {
	echo $cat_carousel;
} ?>

<?php # Guide
if ( $featured_guide ) {
	$root_cls = [
			'text-white',
			'h-px600',
			'my-10',
			'md:h-px490',
			'md:justify-center',
			'lg:h-px737',
	];

	ob_start(); ?>
	<?php if ( ! empty( $main_cat = \TrevorWP\Meta\Post::get_main_category( $featured_guide ) ) ) { ?>
		<a class="text-px14 leading-px18 tracking-em002 font-semibold capitalize mb-5 lg:text-px18 lg:leading-px22 z-10"
		   href="<?= esc_url( get_term_link( $main_cat ) ) ?>"><?= esc_html( $main_cat->name ) ?></a>
	<?php } ?>
	<h2 class="text-px32 leading-px42 font-semibold mb-5 lg:text-60 lg:leading-70"><?= get_the_title( $featured_guide ); ?></h2>
	<a class="stretched-link underline font-semibold tracking-px05 text-px20 leading-px26 lg:text-px20 lg:leading-px26"
	   href="<?= get_the_permalink( $featured_guide ) ?>">Read Guide</a>
	<?php $context = ob_get_clean();
	echo Helper\Hero::img_bg( get_post_thumbnail_id( $featured_guide ), $context, [ 'root_cls' => $root_cls ] );
}
?>

<?php # Rest of the categories
foreach ( array_slice( $cat_rows, 2 ) as $cat_carousel ) {
	echo $cat_carousel;
}
?>

<?php # Glossary Item
if ( $featured_word ) {
	$root_cls    = [
			'text-indigo',
			'h-px600',
			'mt-10',
			'md:h-px490',
			'md:justify-center',
			'lg:h-px737',
	];
	$context_cls = [
			'md:mx-0',
			'md:items-start',
	];

	ob_start(); ?>
	<h2 class="text-px14 leading-px20 font-semibold capitalize tracking-px05 mb-5 lg:leading-px18">WORD OF THE DAY</h2>
	<strong class="stretched-link text-px32 leading-px42 font-semibold mb-5 md:text-px40 md:leading-px50 lg:text-px46 lg:leading-px56 lg:tracking-em_001">
		<?= get_the_title( $featured_word ) ?>
	</strong>
	<div class="font-normal text-px14 leading-px20 tracking-px05 mb-5 lg:text-px22 lg:leading-px32 lg:tracing-px05">
		<?= nl2br( esc_html( $featured_word->post_excerpt ) ) ?>
	</div>
	<p class="font-medium text-px18 leading-px24 tracking-em001 lg:text-px26 lg:leading-px36 lg:tracing-em005">
		<?= nl2br( esc_html( $featured_word->post_content ) ) ?>
	</p>
	<?php $context = ob_get_clean();
	echo Helper\Hero::img_bg(
			intval( Customizer\Resource_Center::get_val( Customizer\Resource_Center::SETTING_HOME_GLOSSARY_BG_IMG ) ),
			$context,
			[
					'root_cls'    => $root_cls,
					'context_cls' => $context_cls,
			]
	);
}
?>

<?php get_footer(); ?>
