<?php

use TrevorWP\Parsedown\Parsedown;
use \TrevorWP\Theme\Customizer\Search as Page;

?>
<?php get_header(); ?>
	<main id="site-content" role="main">
		<?php // TODO: Add header ?>
		<div class="bg-gray-light">
			<div class="container mx-auto text-center text-indigo py-20">
				<h1 class="font-bold text-center text-px30 leading-px40 md:text-px32 md:leading-px40 lg:text-px40 lg:leading-px50 mb-7 lg:mb-10"><?= Page::get_val( Page::SETTING_GENERAL_PAGE_TITLE ) ?></h1>
				<form role="search" method="get" class="search-form relative"
						action="<?= esc_url( Page::get_permalink() ) ?>">
					<div class="relative w-full md:w-3/5 xl:max-w-px606 mx-auto">
						<?php echo \TrevorWP\Theme\Helper\Search_Input::render_rc( esc_attr( Page::get_val( Page::SETTING_GENERAL_INPUT_PLACEHOLDER ) ) ); ?>
					</div>
				</form>
			</div>
		</div>
		<div class="bg-white">
			<?php if ( is_search() ): # Show search results  ?>
				<div class="container mx-auto">
					<?php echo Page::render_scopes(); ?>
					<div class="text-indigo text-px18 leading-px26 lg:text-px20 lg:leading-px30 mb-px30 lg:mb-px40">
						<strong><?= $wp_query->found_posts ?> Results</strong> for
						<strong>“<?= get_search_query( false ) ?>”</strong>
					</div>

					<?php if ( have_posts() ): ?>
						<?php if ( ! empty( $glossary_item = Page::get_glossary_item() ) ): ?>
							<div class="w-full bg-gray-light text-indigo p-px30 mb-14 md:mb-16 rounded-px10 xl:w-7/12">
								<h3 class="font-bold lg:font-semibold text-px22 leading-px28 lg:text-px24 lg:leading-px34 tracking-px05 mb-px8 md:mb-px16 lg:mb-px10"><?= @$glossary_item->post_title_t ?></h3>
								<div class="text-px16 leading-px22 tracking-px05 mb-4"><?= @$glossary_item->post_excerpt_t ?></div>
								<div class="text-px16 leading-px24 md:text-px18 md:leading-px26 lg:text-px20 lg:leading-px28"><?= ( new Parsedown() )->text( strip_tags( @$glossary_item->post_content_t ) ) ?></div>
							</div>
						<?php endif; ?>
						<div class="grid grid-cols-1 gap-8 md:gap-10">
							<?php while ( have_posts() ) {
								the_post();
								echo Page::render_post( get_post() );
							} ?>
						</div>

						<div class="trevor-pagination-default">
							<?php get_template_part( 'template-parts/pagination' ); ?>
						</div>
					<?php else: ?>
						<div class="container mt-px60 mb-px130 md:mt-px80 text-center text-indigo">
							<h2 class="font-semibold text-px26 leading-px32 md:text-px34 md:leading-px44 lg:text-px40 lg:leading-px50">There are no results for "<?php echo get_search_query(); ?>"</h2>
							<p class="font-normal mt-4 text-px18 leading-px24 md:leading-px26 lg:text-px22 lg:leading-px32">Sorry, there are no results found for that search.</p>
						</div>
					<?php endif; ?>
				</div>
				<?php else: # Search Home ?>
					<?php // TODO: Finish carousel, this has the same functionality with RC:Home:Trending Carousel ?>
					<?= \TrevorWP\Theme\Helper\Carousel::posts( get_posts( [
							'post_type'      => \TrevorWP\Util\Tools::get_public_post_types(),
							'posts_per_page' => 3
					] ), [
							'title'     => Page::get_val( Page::SETTING_HOME_CAROUSEL_TITLE ),
							'subtitle'  => Page::get_val( Page::SETTING_HOME_CAROUSEL_DESC ),
							'class'     => 'text-indigo',
							'title_cls' => 'centered',
							'onlyMd'    => false,
					] ) ?>
				<?php endif; ?>
		</div>
	</main> <!-- #site-content -->
<?php get_footer();
