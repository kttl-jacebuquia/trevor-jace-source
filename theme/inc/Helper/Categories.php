<?php namespace TrevorWP\Theme\Helper;

use \TrevorWP\Theme\Customizer;
use \TrevorWP\CPT\RC\RC_Object;

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
		$featured_cat_ids = wp_parse_id_list( Customizer\Resource_Center::get_val( Customizer\Resource_Center::SETTING_HOME_CATS ) );
		$featured_cats    = get_terms( [
				'taxonomy'   => RC_Object::TAXONOMY_CATEGORY,
				'orderby'    => 'include',
				'include'    => $featured_cat_ids,
				'parent'     => 0,
				'hide_empty' => false,
		] );

		ob_start();
		?>
		<div class="bg-white text-violet">
			<div class="container mx-auto my-20 flex flex-col items-center">
				<p class="font-medium text-base leading-px22 tracking-px05 mb-5 md:text-px18 md:leading-px22 md:tracking-em001 lg:text-px20 lg:leading-px24 md:tracking-px05">
					Browse trending content below or choose a topic category to explore.</p>
				<div class="flex flex-wrap justify-center">
					<?php foreach ( $featured_cats as $cat ) { ?>
						<a href="<?= get_term_link( $cat ) ?>"
						   class="rounded-full py-1.5 px-5 bg-violet mx-2 mb-3 text-white text-px14 leading-px18 tracking-em001 lg:text-px18 lg:leading-px22 lg:tracking-px05"><?= $cat->name ?></a>
					<?php } ?>
				</div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}
}
