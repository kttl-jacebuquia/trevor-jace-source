<?php namespace TrevorWP\Theme\Helper;

/**
 * Search Input Helper
 */
class Search_Input {
	/**
	 * @return string
	 */
	public static function render_rc(): string {
		ob_start();
		?>
		<label class="relative block">
			<span class="sr-only">Search for:</span>
			<div class="icon-wrap absolute h-full top-0 left-0 flex items-center pl-7 md:pl-8">
				<i class="trevor-ti-search text-indigo lg:text-xl"></i>
			</div>
			<input type="search"
					class="search-field p-4 w-full rounded-lg pl-14 md:pl-16 lg:pl-24 placeholder-violet text-indigo text-px14 leading-px18 tracking-em005 md:text-base md:leading-px22 lg:text-px22 lg:leading-px32 lg:tracking-normal lg:py-6"
					id="rc-search-main"
					placeholder="What do you want to learn about?"
					autocomplete="off"
					value="<?= get_search_query( true ) ?>" name="s"/>
			<div id="input-suggestions" class="w-full"></div>
		</label>
		<?php return ob_get_clean();
	}
}
