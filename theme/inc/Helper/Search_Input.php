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
			<div class="icon-wrap absolute w-full h-full top-0 left-0 flex items-center pl-8 lg:pl-12">
				<i class="trevor-ti-search text-indigo lg:text-xl"></i>
			</div>
			<input type="search" class="search-field p-4 w-full rounded-lg pl-14 lg:pl-20 placeholder-violet text-indigo text-base leading-px22 tracking-em005 lg:text-px24 lg:leading-px30 lg:tracking-normal"
				   id="rc-search-main"
				   placeholder="What do you want to learn about?"
				   value="<?= get_search_query( true ) ?>" name="s"/>
		</label>
		<?php return ob_get_clean();
	}
}
