<?php namespace TrevorWP\Theme\Helper;

use \TrevorWP\Theme\Customizer;
use \TrevorWP\CPT\RC\RC_Object;
use TrevorWP\Theme\ACF\Options_Page\Resource_Center;

/**
 * Categories Helper
 */
class Categories {
	/**
	 * Renders the featured RC categories hero.
	 *
	 * @return string
	 */
	public static function render_rc_featured_hero(): string {
		$featured_cat_ids = Resource_Center::get_featured_topics();

		if ( ! empty( $featured_cat_ids ) ) {
			$featured_cat_ids = array_column( $featured_cat_ids, 'term_id' );
			$featured_cats    = get_terms(
				array(
					'taxonomy'   => RC_Object::TAXONOMY_CATEGORY,
					'orderby'    => 'include',
					'include'    => $featured_cat_ids,
					'parent'     => 0,
					'hide_empty' => false,
				)
			);
		}

		if ( empty( $featured_cats ) ) {
			return null;
		}

		ob_start();
		?>
		<div class="bg-white text-violet">
			<div class="container mx-auto tw-grid lg:tw-grid-desktop py-px60 md:py-20 flex flex-col items-center">
				<p class="col-span-6 md:col-start-2 lg:col-start-4 text-center font-medium text-base leading-px22 tracking-px05 mb-px25 md:mb-px28 md:px-px60 md:text-px18 md:leading-px22 md:tracking-em001 lg:px-px45 lg:text-px20 lg:leading-px24 md:tracking-px05 xl:px-px75">
					Browse trending content below or choose a topic category to explore.</p>
				<div class="mobile:-mx-px28 col-span-6 md:col-start-2 lg:col-span-8 lg:col-start-3 flex flex-wrap justify-center">
					<?php foreach ( $featured_cats as $cat ) { ?>
						<a href="<?php echo get_term_link( $cat ); ?>"
						class="rounded-full hover:bg-persian_blue-lighter py-1.5 px-5 bg-violet mx-2 mb-3 text-white text-px14 leading-px18 tracking-em001 lg:text-px18 lg:leading-px22 lg:tracking-px05"><?php echo $cat->name; ?></a>
					<?php } ?>
				</div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}
}
