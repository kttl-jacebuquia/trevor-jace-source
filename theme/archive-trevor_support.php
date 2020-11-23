<?php get_header(); ?>

<?php if ( ! is_paged() ) { ?>
	<div class="bg-gray">
		<div class="container mx-auto py-10">
			<div class="lg:w-1/2">
				<h1 class="font-bold text-5xl">LGBTQ Support Center</h1>
				<p class="text-xl">Find answers and resources related to sexual orientation, gender identity, and
					more.</p>

				<div class="my-10">
					<?= '<form role="search" method="get" class="search-form" action="' . esc_url( get_post_type_archive_link( \TrevorWP\CPT\Support_Resource::POST_TYPE ) . 'search' ) . '">
				<label>
					<span class="sr-only">Search for:</span>
					<input type="search" class="search-field p-4 w-full" placeholder="What are you looking for?" value="' . get_search_query( true ) . '" name="s" />
				</label>
			</form>'; ?>
				</div>

				<div>
					<div class="font-bold mb-2">Choose a topic category or browse trending topics below.</div>
					<div class="grid md:grid-cols-2 gap-y-2 gap-x-1 text-blue-dark font-bold mt-4">
						<a href="#"><i class="trevor-ti-arrow-right-solid"></i> Orientation & Gender Identity</a>
						<a href="#"><i class="trevor-ti-arrow-right-solid"></i> My Health & Safety</a>
						<a href="#"><i class="trevor-ti-arrow-right-solid"></i> Understanding Suicide</a>
						<a href="#"><i class="trevor-ti-arrow-right-solid"></i> Around the World</a>
						<a href="#"><i class="trevor-ti-arrow-right-solid"></i> Family & Relationships</a>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php } ?>

<?php if ( have_posts() ) { ?>
	<div class="container mx-auto mt-10 mb-20">
		<h2 class="text-2xl font-bold">Trending at Trevor</h2>
		<p class="text-lg">Explore our latest articles, resources, and guides.</p>
		<div class="mt-4 grid md:grid-cols-2 lg:grid-cols-3 gap-6">
			<?php while ( have_posts() ) {
				the_post();

				get_template_part( 'template-parts/card-post', get_post_type() );
			} ?>
		</div>
	</div>
	<?php
} else {
	// TODO: No posts
}

get_template_part( 'template-parts/pagination' );

?>


<?php get_footer(); ?>
