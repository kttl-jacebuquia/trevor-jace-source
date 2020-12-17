<?php /* Resources Center LP */ ?>
<?php get_header(); ?>
<?php

use \TrevorWP\Theme\Customizer;
use \TrevorWP\Theme\Helper;

$card_num         = absint( Customizer\Resource_Center::get_val( Customizer\Resource_Center::SETTING_HOME_CARD_NUM ) );
$featured_cat_ids = wp_parse_id_list( Customizer\Resource_Center::get_val( Customizer\Resource_Center::SETTING_HOME_CATS ) );
$featured_cats    = get_terms( [
		'taxonomy'   => TrevorWP\CPT\RC\RC_Object::TAXONOMY_CATEGORY,
		'orderby'    => 'include',
		'include'    => $featured_cat_ids,
		'parent'     => 0,
		'hide_empty' => false,
] );

$featured_post_ids = wp_parse_id_list( Customizer\Resource_Center::get_val( Customizer\Resource_Center::SETTING_HOME_FEATURED ) );
$used_post_ids     = [];


// ---

$is_search = is_search();

error_log( '123' );


?>

<?php if ( ! is_paged() ) { ?>
	<div class="container mx-auto py-10 text-center flex-1">
		<div class="lg:w-4/6 mx-auto">
			<h2 class="bold text-white text-px14 leading-px18 tracking-px05">
				FIND ANSWERS
			</h2>
			<h1>
				<span class="text-white font-manrope"
					  style="font-size: 32px; line-height: 42px; letter-spacing: 0.005em;">Connection starts</span>
				<span class="block text-white font-caveat transform bold"
					  style="--tw-rotate: -1deg; font-size: 44px; line-height: 54px;">with knowledge.</span>
			</h1>

			<div class="my-10 lg:w-4/6 mx-auto">
				<?= '<form role="search" method="get" class="search-form" action="' . esc_url( home_url( \TrevorWP\CPT\RC\RC_Object::PERMALINK_BASE ) ) . '">
				<label>
					<span class="sr-only">Search for:</span>
					<input type="search" class="search-field p-4 w-full rounded-lg" placeholder="What do you want to learn about?" value="' . get_search_query( true ) . '" name="s" />
				</label>
			</form>'; ?>
			</div>

			<p class="text-white">Browse a topic or check out what’s trending.</p>

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

			<div class="mt-8 md:fixed md:bottom-10 md:right-10 z-10">
				<a href="#"
				   class="py-2 px-6 rounded-full border-2 border-orange bg-orange text-white font-bold font-px22 leading-px22 tracking-em001 shadow-2xl">Reach
					a Counselor</a>
			</div>
		</div>
	</div>
<?php } ?>

<?php # Trending ?>


<?php
$trending_posts = get_posts( [
		'post_type'      => TrevorWP\CPT\RC\RC_Object::$PUBLIC_POST_TYPES,
		'post_status'    => 'publish',
		'orderby'        => 'post__in',
		'order'          => 'ASC',
		'post__in'       => $featured_post_ids,
		'posts_per_page' => $card_num,
] );
// TODO: Get the ids
// TODO: Implement the fallback system
echo Helper\Carousel::posts( $trending_posts, [
		'title'     => 'Trending',
		'subtitle'  => 'Explore the latest articles, resources, and guides.',
		'title_cls' => 'text-center',
		'noMobile'  => true
] );

foreach ( $featured_cats as $cat ) {
	$cat_post_ids  = wp_parse_id_list( Customizer\Resource_Center::get_val( Customizer\Resource_Center::PREFIX_SETTING_HOME_CAT_POSTS . $cat->term_id ) );
	$cat_posts     = get_posts( [
			'post_type'      => TrevorWP\CPT\RC\RC_Object::$PUBLIC_POST_TYPES,
			'post_status'    => 'publish',
			'orderby'        => 'post__in',
			'order'          => 'ASC',
			'post__in'       => $cat_post_ids,
			'post__not_in'   => $used_post_ids,
			'posts_per_page' => $card_num,
			'tax_query'      => [
					'relation' => 'AND',
					[
							'taxonomy' => TrevorWP\CPT\RC\RC_Object::TAXONOMY_CATEGORY,
							'field'    => 'term_id',
							'terms'    => $cat->term_id,
							'operator' => 'IN'
					]
			]
	] );
	$used_post_ids = array_merge( $used_post_ids, \TrevorWP\Util\Tools::pluck( $cat_posts, 'ID' ) );

	echo Helper\Carousel::posts( $cat_posts, [
			'id'       => "cat-{$cat->slug}",
			'title'    => $cat->name,
			'subtitle' => $cat->description,
	] );
	?>

<?php } ?>

<?php get_footer(); ?>
