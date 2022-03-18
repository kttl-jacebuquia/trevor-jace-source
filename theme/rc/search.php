<?php /* Resources Center: Search */ ?>
<?php get_header(); ?>
<?php
$posts      = $wp_query->posts;
$no_results = empty( $wp_query->found_posts );
$glossaries = \TrevorWP\CPT\RC\RC_Object::find_glossaries( trim( get_search_query( false ) ) );
$pagination = array();

if ( ! empty( $glossaries ) ) {
	$filter_results = \TrevorWP\CPT\RC\RC_Object::filter_results( $glossaries );
	$posts          = $filter_results['posts'];
	$pagination     = $filter_results['pagination'];
	$no_results     = empty( $filter_results['pagination']['total'] );
}
?>
<main id="site-content" role="main" class="site-content">
	<div class="container mx-auto site-content-inner">
		<div class="search-input-wrap md:mx-auto mb-10 md:mb-12 lg:mb-20 mt-5 md:mb-12 md:mt-10 md:w-2/3 lg:w-1/2 lg:my-10">
			<form role="search" method="get" class="search-form"
				action="<?php echo esc_url( \TrevorWP\CPT\RC\RC_Object::get_search_url() ); ?>">
				<?php echo \TrevorWP\Theme\Helper\Search_Input::render_rc( 'What do you want to learn about?' ); ?>
			</form>
		</div>

		<div class="search-results-container flex flex-col flex-1">
			<div class="trevor-grid-default-container">
				<h1 class="search-results-list-title text-white text-center text-base mb-5 md:mb-10 lg:mb-10">
					<?php if ( $no_results ) { ?>
						<span class="leading-px20 font-medium md:font-light md:text-px18 md:leading-px26 lg:font-normal lg:text-px22 lg:leading-px32 lg:tracking-px05">
						There are no results for “<?php echo get_search_query(); ?>”
					</span>
					<?php } else { ?>
						<span class="leading-px22 font-normal md:text-px18 md:leading-px24 lg:text-px22 lg:leading-px32 lg:tracking-px05">
						Search Results for “<span class="font-extrabold md:font-normal"><?php echo get_search_query(); ?></span>”
					</span>
					<?php } ?>
				</h1>
				<?php if ( ! empty( $posts ) ) { ?>
					<div class="search-results-list trevor-grid-default">
						<?php
						foreach ( $posts as $post ) {
							echo \TrevorWP\Theme\Helper\Card::post( $post );
						}
						?>

						<div class="trevor-pagination-default">
							<?php get_template_part( 'template-parts/pagination', '', $pagination ); ?>
						</div>
					</div>
				<?php } elseif ( $no_results ) { ?>
					<div class="search-results-popular-wrap text-center flex-1 text-white">
						<h2 class="font-semibold text-px26 leading-px32 -tracking-px05 mb-2 md:text-px32 md:leading-px42 md:tracking-em_001 lg:text-px46 lg:leading-px56">
							Popular Searches
						</h2>
						<p class="mx-auto text-px18 leading-px24 -tracking-em005 mb-7 md:w-7/12 lg:w-full md:tracking-em001 lg:text-px26 lg:leading-px36 lg:mb-10">
							Here’s some popular search terms other people have been exploring:
						</p>
						<div class="flex flex-wrap justify-center mb-20 lg:mb-40 lg:w-3/6 lg:mx-auto">
							<?php
							foreach (
									array(
										'LGBTQ',
										'Coming Out',
										'Mental Health',
										'Gay',
										'Lesbian',
										'Bisexual',
										'Queer',
										'Nonbinary',
										'Gender',
										'Transgender',
									) as $search
							) {
								$search_url = \TrevorWP\CPT\RC\RC_Object::get_search_url( $search );
								?>
									<?php if ( ! empty( $search_url ) ) : ?>
										<a href="<?php echo esc_url( $search_url ); ?>"
										class="bg-violet-light hover:bg-melrose text-indigo font-medium text-px14 leading-px18 tracking-em001 rounded-full py-1.5 px-3.5 mb-3 mr-2"><?php echo esc_html( $search ); ?></a>
									<?php endif; ?>
							<?php } ?>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
	<?php echo \TrevorWP\Theme\Helper\Categories::render_rc_featured_hero(); ?>
</main>
<?php get_footer(); ?>
