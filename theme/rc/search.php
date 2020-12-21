<?php /* Resources Center: Search */ ?>
<?php get_header(); ?>
<?php
$no_results = empty( $wp_query->found_posts );
?>
<main id="site-content" role="main" class="site-content">
	<div class="container mx-auto site-content-inner">
		<div class="search-input-wrap"></div>

		<div class="search-results-container">
			<h1 class="search-results-list-title">
				<?= sprintf( $no_results ? 'There are no results for “%s”' : 'Search Results for “%s”', get_search_query() ); ?>
			</h1>
			<div class="search-results-list"></div>
			<?php if ( $no_results ) { ?>
				<div class="search-results-popular-wrap">

				</div>
			<?php } ?>
		</div>
	</div>
</main>
<?php get_footer(); ?>
