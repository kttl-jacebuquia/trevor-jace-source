<?php namespace TrevorWP\Theme\Helper;

/**
 * Search Input Helper
 */
class Search_Input {
	/**
	 * @return string
	 */
	public static function render_rc( string $placeholder ): string {
		$keys = get_terms(
			array(
				'taxonomy'   => 'trevor_rc__search_key',
				'hide_empty' => false,
			)
		);

		$terms = array_map(
			function( $item ) {
				return $item->name;
			},
			$keys
		);

		$search_keys = implode( ',', $terms );

		ob_start();
		?>
		<label class="relative block">
			<div class="input-wrap bg-white rounded-px10 w-full flex justify-center items-center overflow-hidden">
				<input type="hidden" name="rc-search--keys" disabled value="<?php echo $search_keys; ?>" />
				<div class="icon-wrap h-full pl-px24 pr-px12 md:pr-px14 lg:pl-px60 lg:pr-px18" aria-hidden="true">
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
				<button type="button" aria-label='click to clear the your search input' class="icon-wrap icon-search-cancel hidden absolute h-full top-0 right-0 flex items-center pr-px12 pr-px14 pr-px18">
					<i class="trevor-ti-x"></i>
				</button>
			</div>
			<div id="input-suggestions" class="w-full"></div>
		</label>
		<?php
		return ob_get_clean();
	}
}
