<?php namespace TrevorWP\Theme\ACF\Options_Page;

use TrevorWP\Theme\ACF\Field_Group\A_Field_Group;

abstract class A_Options_Page extends A_Field_Group {
	const PAGE_SLUG_PREFIX = 'trvr--';
	const PAGE_SLUG = '';

	/**
	 * @return array
	 */
	protected static function prepare_page_register_args(): array {
		return [];
	}

	/**
	 * @return array
	 */
	abstract protected static function prepare_fields(): array;

	/** @inheritDoc */
	protected static function prepare_register_args(): array {
		return [
			'key'    => static::gen_key(),
			'fields' => static::prepare_fields(),
			'title'  => str_replace( '_', ' ', ( new \ReflectionClass( static::class ) )->getShortName() ),
		];
	}

	/**
	 * @return string
	 */
	public static function gen_page_slug(): string {
		static $cache = [];

		if ( ! array_key_exists( static::class, $cache ) ) {
			$cache[ static::class ] = empty( static::PAGE_SLUG )
				? static::PAGE_SLUG_PREFIX . acf_slugify( ( new \ReflectionClass( static::class ) )->getShortName(), '-' )
				: static::PAGE_SLUG;
		}

		return $cache[ static::class ];
	}

	/**
	 * @param string|null $key
	 *
	 * @return mixed
	 */
	public static function get_page_register_args( string $key = null ) {
		static $cache = [];

		if ( ! array_key_exists( static::class, $cache ) ) {
			$args = static::prepare_page_register_args();

			# Slug
			if ( empty( $args['menu_slug'] ) ) {
				$args['menu_slug'] = static::gen_page_slug();
			}

			# Capability
			if ( ! isset( $args['capability'] ) ) {
				$args['capability'] = 'administrator';
			}

			# Page Title
			if ( ! isset( $args['page_title'] ) ) {
				$args['page_title'] = str_replace( '_', ' ', ( new \ReflectionClass( static::class ) )->getShortName() );
			}

			# Menu Title
			if ( ! isset( $args['menu_title'] ) ) {
				$args['menu_title'] = $args['page_title'];
			}

			$cache[ static::class ] = $args;
		}

		return $key
			? $cache[ static::class ][ $key ] ?? null
			: $cache[ static::class ];
	}

	/**
	 * @return mixed
	 */
	public static function register_page() {
		return acf_add_options_page( static::get_page_register_args() );
	}

	/**
	 * @param $field_name
	 *
	 * @return mixed
	 */
	public static function get_option( $field_name ) {
		$ss = static::gen_field_key( $field_name );
		return get_field($ss , 'option' );
	}
}
