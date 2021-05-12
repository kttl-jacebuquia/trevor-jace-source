<?php namespace TrevorWP\Theme\ACF\Field_Group;

interface I_Renderable {
	/**
	 * @param false $post
	 * @param array|null $data
	 * @param array $options
	 *
	 * @return string|null
	 */
	public static function render( $post = false, array $data = null, array $options = array() ): ?string;
}
