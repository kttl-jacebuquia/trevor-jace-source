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
			'id'               => "cat-{$cat->slug}",
			'title'            => '<a href="' . get_term_link( $cat ) . '">' . esc_html( $cat->name ) . '</a>',
			'subtitle'         => $cat->description,
			'hide_cat_eyebrow' => true,
			'class'            => 'text-white',
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

<main id="site-content" role="main" class="site-content rc-landing-page">
	<div class="container mx-auto text-center site-content-inner mt-8 md:mt-0 md:mb-8">
		<div class="mx-auto mb-10 lg:w-3/4">
			<h2 class="font-semibold text-white text-px14 leading-px18 tracking-em001 mb-2 md:tracking-px05 lg:font-bold lg:leading-px20">
				RESOURCE CENTER
			</h2>
			<h1 class="heading-lg-tilted text-white has-block-tilt">
				<span>Connection starts</span>
				<tilt>with knowledge.</tilt>
			</h1>

			<div class="my-8 mx-auto md:w-3/5 md:my-6 lg:w-3/4 xl:w-4/6">
				<form role="search" method="get" class="search-form"
					  action="<?= esc_url( \TrevorWP\CPT\RC\RC_Object::get_search_url() ) ?>">
					<?= Helper\Search_Input::render_rc(); ?>
				</form>
			</div>

			<p class="text-white font-medium text-base leading-px22 tracking-em001 md:text-px18 md:leading-px24 md:mt-8 md:mb-5 lg:text-px18 lg:tracking-px05 xl:text-px20 xl:leading-px24 lg:mb-6">
				Browse a topic or check out what’s trending.</p>

			<div class="flex flex-wrap justify-center mt-4 -mx-6 md:mx-auto lg:w-3/4 xl:w-3/5">
				<?php foreach ( $featured_cats as $cat ) { ?>
					<a href="<?= get_term_link( $cat ) ?>"
					   class="rounded-full py-1 px-3 bg-violet mx-1 mb-3 tracking-px05 text-white md:px-5">
						<?= esc_html( $cat->name ); ?>
					</a>
				<?php } ?>
			</div>

			<div class="mt-8 animate-bounce hidden md:block">
				<i class="trevor-ti-chevron-down text-4xl text-white bouncing-arrow cursor-pointer"></i>
			</div>
		</div>
	</div>

	<?php } ?>

	<?php # Trending
	if ( ! empty( $trending_posts ) ) {
		echo Helper\Carousel::posts( $trending_posts, [
				'title'     => 'Trending',
				'subtitle'  => 'Explore the latest articles, resources, and guides.',
				'title_cls' => 'text-center',
				'onlyMd'    => false,
				'class'     => 'text-white md:mt-12 lg:mt-18',
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
				'mt-10',
				'mb-24',
				'mb-32',
				'text-center',
				'pt-20',
				'md:h-px490',
				'md:justify-center',
				'xl:h-px737',
				'lg:mb-20',
		];

		ob_start(); ?>
		<div class="mx-auto lg:w-3/4">
			<?php if ( ! empty( $main_cat = \TrevorWP\Meta\Post::get_main_category( $featured_guide ) ) ) { ?>
				<a class="text-px14 leading-px18 tracking-em002 font-semibold uppercase lg:text-px18 lg:leading-px22 z-10"
				   href="<?= esc_url( get_term_link( $main_cat ) ) ?>"><?= esc_html( $main_cat->name ) ?></a>
			<?php } ?>
			<h2 class="text-px32 leading-px42 font-semibold my-3 lg:my-10 lg:text-px42 lg:leading-px52 xl:text-px60 xl:leading-px70"><?= strip_tags( $featured_guide->post_excerpt, '<tilt>' ); ?></h2>
			<a class="stretched-link border-b font-semibold tracking-px05 text-px20 leading-px26 lg:text-px20 lg:leading-px26"
			   href="<?= get_the_permalink( $featured_guide ) ?>">Read Guide</a>
		</div>
		<?php $context = ob_get_clean();
		echo Helper\Hero::img_bg( Helper\Thumbnail::get_post_imgs(
				$featured_guide->ID,
				Helper\Thumbnail::variant( Helper\Thumbnail::SCREEN_SM, Helper\Thumbnail::TYPE_VERTICAL, Helper\Thumbnail::SIZE_MD ),
				Helper\Thumbnail::variant( Helper\Thumbnail::SCREEN_MD, Helper\Thumbnail::TYPE_HORIZONTAL, Helper\Thumbnail::SIZE_LG ),
		), $context, [ 'root_cls' => $root_cls ] );
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
		<h2 class="text-px14 leading-px20 font-semibold capitalize tracking-px05 mb-5 lg:leading-px18">
			WORD OF THE DAY
		</h2>
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
				[
						[
								intval( Customizer\Resource_Center::get_val( Customizer\Resource_Center::SETTING_HOME_GLOSSARY_BG_IMG ) ),
								Helper\Thumbnail::variant( Helper\Thumbnail::SCREEN_LG, Helper\Thumbnail::TYPE_HORIZONTAL ),
						]
				],
				$context,
				[
						'root_cls'    => $root_cls,
						'context_cls' => $context_cls,
				]
		);
	}
	?>
</main>

<?php get_footer(); ?>
