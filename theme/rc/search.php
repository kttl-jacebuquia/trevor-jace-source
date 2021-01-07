<?php /* Resources Center: Search */ ?>
<?php get_header(); ?>
<?php
$no_results = empty( $wp_query->found_posts );
?>
<main id="site-content" role="main" class="site-content">
	<div class="container mx-auto site-content-inner">
		<div class="search-input-wrap mb-10 mt-5 md:mb-12 md:mt-10 md:w-2/3 lg:w-1/2 lg:mb-20">
			<form role="search" method="get" class="search-form"
				  action="<?= esc_url( \TrevorWP\CPT\RC\RC_Object::get_search_url() ) ?>">
				<?= \TrevorWP\Theme\Helper\Search_Input::render_rc() ?>
			</form>
		</div>

		<div class="search-results-container flex flex-col flex-1">
			<h1 class="search-results-list-title text-white text-base leading-px20 font-medium mb-5 md:font-light md:text-px18 md:leading-px26 md:mb-10 lg:font-normal lg:text-px22 lg:leading-px32 lg:tracking-px05">
				<?= sprintf( $no_results ? 'There are no results for “%s”' : 'Search Results for “%s”', '<span class="font-extrabold md:font-normal">' . get_search_query() . '</span>' ); ?>
			</h1>
			<?php if ( have_posts() ) { ?>
				<div class="search-results-list trevor-grid-default">
					<?php while ( have_posts() ) {
						the_post();
						echo \TrevorWP\Theme\Helper\Card::post( get_post() );
						?>
					<?php } ?>

					<div class="trevor-pagination-default">
						<?php get_template_part( 'template-parts/pagination' ); ?>
					</div>
				</div>
			<?php } else if ( $no_results ) { ?>
				<div class="search-results-popular-wrap flex-1 text-white">
					<h2 class="font-semibold text-px26 leading-px32 -tracking-px05 mb-2">Popular Searches</h2>
					<p class="text-px18 leading-px24 -tracking-em005 mb-7">Here’s some popular search terms other people
						have
						been exploring:</p>
					<div class="flex flex-wrap mb-20">
						<?php foreach (
								[
										'Arbitrary Data',
										'Not Implemented!',
										'Coming Out',
										'Mental Health',
										'Gay',
										'Lesbian',
										'Bisexual',
										'Queer',
										'Nonbinary',
										'Gender',
										'Transgender',
								] as $search
						) { ?>
							<a href="#"
							   class="bg-violet-light text-indigo font-medium text-px14 leading-px18 tracking-em001 rounded-full py-1.5 px-3.5 mb-3 mr-2"><?= $search ?></a>
						<?php } ?>
					</div>
				</div>
			<?php } ?>
		</div>
	</div>
	<?= \TrevorWP\Theme\Helper\Categories::render_rc_featured_hero(); ?>
</main>
<?php get_footer(); ?>
