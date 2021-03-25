<?php

use TrevorWP\Parsedown\Parsedown;
use \TrevorWP\Theme\Customizer\Search as Page;

?>
<?php get_header(); ?>
	<main id="site-content" role="main">
		<?php // TODO: Add header ?>
		<div class="bg-gray-light">
			<div class="container mx-auto text-center text-indigo py-20">
				<h1 class="text-px48 mb-10"><?= Page::get_val( Page::SETTING_GENERAL_PAGE_TITLE ) ?></h1>
				<form role="search" method="get" class="search-form"
					  action="<?= esc_url( Page::get_permalink() ) ?>">
					<input name="s" type="search" value="<?= get_search_query( true ) ?>"
						   placeholder="<?= esc_attr( Page::get_val( Page::SETTING_GENERAL_INPUT_PLACEHOLDER ) ) ?>"
						   class="w-1/2">
				</form>
			</div>
		</div>
		<div class="bg-white">
			<div class="container mx-auto">
				<?php if ( is_search() ): # Show search results  ?>
					<div class="mb-10"><?= Page::render_scopes() ?></div>
					<div>
						<strong><?= $wp_query->found_posts ?> Results</strong> for
						<strong>“<?= get_search_query( false ) ?>”</strong>
					</div>

					<?php if ( have_posts() ): ?>
						<?php if ( ! empty( $glossary_item = Page::get_glossary_item() ) ): ?>
							<div class="w-full bg-gray-light rounded-px10 xl:w-7/12">
								<h3><?= @$glossary_item->post_title_t ?></h3>
								<div><?= @$glossary_item->post_excerpt_t ?></div>
								<p><?= ( new Parsedown() )->text( strip_tags( @$glossary_item->post_content_t ) ) ?></p>
							</div>
						<?php endif; ?>
						<div class="grid grid-cols-1 gap-7">
							<?php while ( have_posts() ) {
								the_post();
								echo Page::render_post( get_post() );
							} ?>
						</div>

						<div class="trevor-pagination-default bg-gray">
							<?php get_template_part( 'template-parts/pagination' ); ?>
						</div>
					<?php else:
						// TODO: No results found page
						echo 'No results';
					endif; ?>
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
		</div>
	</main> <!-- #site-content -->
<?php get_footer();
