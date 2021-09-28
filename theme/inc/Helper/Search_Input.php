<?php namespace TrevorWP\Theme\Helper;

use TrevorWP\CPT\RC\RC_Object;

/**
 * Search Input Helper
 */
class Search_Input {
	/**
	 * @return string
	 */
	public static function render_rc( string $placeholder ): string {
		$search_keys = implode( ',', static::get_search_keys() );
		$tags        = implode( ',', static::get_search_tags() );

		ob_start();
		?>
		<label class="relative block resource-center-search">
			<div class="input-wrap bg-white rounded-px10 w-full flex justify-center items-center overflow-hidden">
				<input type="hidden" name="rc-search--keys" disabled value="<?php echo $search_keys; ?>" />
				<input type="hidden" name="rc-search--tags" disabled value="<?php echo $tags; ?>" />
				<div class="icon-wrap icon-wrap--search h-full pl-px24 pr-px12 md:pr-px14 lg:pl-px60 lg:pr-px18" aria-hidden="true">
					<i class="trevor-ti-search lg:text-xl"></i>
				</div>
				<input
					type="text"
					tabindex='0'
					name="s"
					aria-label="search box"
					role="searchbox"
					class="search-field pl-0 pr-4 max-w-full w-auto tracking-em005 rounded-lg placeholder-violet text-indigo text-px14 leading-px18 tracking-em005 md:text-base md:leading-px22 lg:text-px22 lg:leading-px32 lg:tracking-normal py-5 lg:py-6"
					id="rc-search-main"
					placeholder="<?php echo $placeholder; ?>"
					autocomplete="off"
					value="<?php echo get_search_query( true ); ?>"
				/>
				<label for="rc-search-main" hidden>Search box</label>
				<button type="button" aria-label='click to clear the your search input' class="icon-wrap icon-wrap--cancel icon-search-cancel hidden absolute h-full top-0 right-0 flex items-center bg-white px-px12 md:px-px14 lg:px-px18">
					<i class="trevor-ti-x"></i>
				</button>
			</div>
			<div id="input-suggestions" class="w-full"></div>
		</label>
		<?php
		return ob_get_clean();
	}


	protected static function get_search_keys(): array {
		$keys = get_terms(
			array(
				'taxonomy'   => 'trevor_rc__search_key',
				'hide_empty' => false,
			)
		);

		$terms = wp_list_pluck( $keys, 'name' );

		return $terms;
	}

	protected static function get_search_tags(): array {
		$tags = get_terms(
			array(
				'taxonomy'   => RC_Object::TAXONOMY_TAG,
				'hide_empty' => false,
			)
		);

		$terms = wp_list_pluck( $tags, 'name' );

		return $terms;
	}
}
