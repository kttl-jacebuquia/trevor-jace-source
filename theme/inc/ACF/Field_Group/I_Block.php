<?php namespace TrevorWP\Theme\ACF\Field_Group;

interface I_Block {
	/**
	 * @return void
	 * @link https://www.advancedcustomfields.com/resources/acf_register_block_type/
	 */
	public static function register_block();

	/**
	 * @return array
	 */
	public static function get_block_args(): array;

	/**
	 * @param array $block The block settings and attributes.
	 * @param string $content The block inner HTML (empty).
	 * @param bool $is_preview True during AJAX preview.
	 * @param int|string $post_id The post ID this block is saved to.
	 */
	public static function render_block( $block, $content = '', $is_preview = false, $post_id = 0 ): void;
}
