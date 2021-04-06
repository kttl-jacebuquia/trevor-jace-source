<?php namespace TrevorWP\Theme\ACF\Field;

/**
 * Abstract Field
 */
abstract class A_Field {
	/**
	 * @param string $key
	 * @param string $name
	 * @param array $ext_args
	 *
	 * @return array
	 */
	public static function gen_args( string $key, string $name, array $ext_args = [] ): array {
		return array_merge( compact( 'key', 'name' ), $ext_args );
	}
}
