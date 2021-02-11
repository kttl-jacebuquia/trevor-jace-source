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
			<div class="input-wrap bg-white rounded-px10 w-full flex justify-start items-center overflow-hidden">
				<div class="icon-wrap inline h-full top-0 left-0 pl-8 md:pl-16 xl:pl-px108 pr-px12 md:pr-px14 lg:pr-px18">
					<i class="trevor-ti-search lg:text-xl"></i>
				</div>
				<input type="search"
						class="search-field pl-0 pr-4 w-full tracking-em005 rounded-lg placeholder-violet text-indigo text-px14 leading-px18 tracking-em005 md:text-base md:leading-px22 lg:text-px22 lg:leading-px32 lg:tracking-normal py-5 lg:py-6"
						id="rc-search-main"
						placeholder="What do you want to learn about?"
						autocomplete="off"
						size="1"
						value="<?= get_search_query( true ) ?>" name="s"/>
				<div class="icon-wrap icon-search-cancel hidden absolute h-full top-0 right-0 flex items-center pr-px12 pr-px14 pr-px18">
					<i class="trevor-ti-x"></i>
				</div>
			</div>
			<div id="input-suggestions" class="w-full"></div>
		</label>
		<?php return ob_get_clean();
	}
}
