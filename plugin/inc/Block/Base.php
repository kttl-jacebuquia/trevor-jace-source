<?php namespace TrevorWP\Block;

/**
 * Abstract Editor Block
 */
abstract class Base {
	const NAME_PREFIX = 'trevor/';
	const _ALL_       = array(
		Glossary_Entry::class,
		Link_List::class,
		Bottom_List::class,
	);

	/**
	 * Registers all blocks for dynamic rendering.
	 *
	 * @link https://developer.wordpress.org/reference/hooks/init/
	 * @see \TrevorWP\Util\Hooks::register_all()
	 */
	public static function register_all() {
		foreach ( self::_ALL_ as $cls ) {
			call_user_func( array( $cls, 'register' ) );
		}
	}

	/**
	 * Registers all blocks for dynamic rendering.
	 *
	 * @return false|\WP_Block_Type
	 *
	 * @link https://developer.wordpress.org/reference/hooks/init/
	 * @see register_all()
	 */
	public static function register() {
		return register_block_type( static::BLOCK_NAME, static::get_register_args() );
	}

	/**
	 * @return array
	 */
	public static function get_register_args(): array {
		return array(
			'render_callback' => array( static::class, 'render' ),
		);
	}

	/**
	 * Renders the block.
	 *
	 * @param array $attributes
	 * @param string $content
	 *
	 * @return string
	 */
	abstract public static function render( array $attributes, string $content ): string;
}
