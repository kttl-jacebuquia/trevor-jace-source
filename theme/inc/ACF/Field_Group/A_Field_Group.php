<?php namespace TrevorWP\Theme\ACF\Field_Group;

use TrevorWP\Theme\ACF\Options_Page\A_Options_Page;
use TrevorWP\Util\Tools;

/**
 * Field Group
 */
abstract class A_Field_Group {
	const KEY_PREFIX = 'trvr-';
	const KEY = '';
	const FIELD_PREFIX_TAB = 'tab_';

	/**
	 * @param string $suffix
	 *
	 * @return string
	 */
	public static function gen_key( string $suffix = '' ): string {
		static $cache = [];

		if ( ! array_key_exists( static::class, $cache ) ) {
			$cache[ static::class ] = empty( static::KEY )
				? static::KEY_PREFIX . acf_slugify( ( new \ReflectionClass( static::class ) )->getShortName(), '-' )
				: static::KEY;
		}

		return $cache[ static::class ] . $suffix;
	}

	/**
	 * @return string
	 */
	public static function get_key(): string {
		return static::get_register_args( 'key' );
	}

	/**
	 * @return array
	 */
	abstract protected static function prepare_register_args(): array;

	/**
	 * @param string|null $key
	 *
	 * @return mixed
	 */
	public static function get_register_args( string $key = null ) {
		static $cache = [];

		if ( ! array_key_exists( static::class, $cache ) ) {
			$args = static::prepare_register_args();

			# Check key
			if ( empty( $args['key'] ) ) {
				$args['key'] = static::gen_key();
			}

			# Get Location
			empty( $args['location'] ) && $args['location'] = [];
			empty( $args['location'][0] ) && $args['location'][0] = [];
			empty( $args['location'][1] ) && $args['location'][1] = [];

			$and_rules = &$args['location'][0];
			$or_rules  = &$args['location'][1];

			# Add Block to Location
			if ( static::is_block() ) {
				$or_rules[] = [
					'param'    => 'block',
					'operator' => '==',
					'value'    => 'acf/' . $args['key'],
				];
			} elseif ( static::is_options_page() ) {
				$and_rules[] = [
					'param'    => 'options_page',
					'operator' => '==',
					'value'    => static::gen_page_slug(),
				];
			}

			$cache[ static::class ] = $args;
		}

		return $key
			? $cache[ static::class ][ $key ] ?? null
			: $cache[ static::class ];
	}

	/**
	 * @param string $field_name
	 * @param string|null $key
	 *
	 * @return string
	 */
	public static function gen_field_key( string $field_name, string $key = null ): string {
		return ( $key ?: static::gen_key() ) . ':f:' . $field_name;
	}

	/**
	 * @param string $field_name
	 *
	 * @return string|null
	 */
	public static function get_field_key( string $field_name ): ?string {
		static $cache = [];

		if ( ! array_key_exists( $field_name, $cache ) ) {
			$fields               = static::get_register_args( 'fields' );
			$cache[ $field_name ] = ( $fields[ $field_name ] ?? [] )['key'] ?? null;
		}

		return $cache[ $field_name ];
	}

	/**
	 * @return bool
	 */
	public static function register(): bool {
		return acf_add_local_field_group( static::get_register_args() );
	}

	/**
	 * @param array $args
	 *
	 * @return array
	 */
	public static function clone( array $args = [] ): array {
		return array_merge( $args, [
			'type'  => 'clone',
			'clone' => [
				static::get_key(),
			],
		] );
	}

	/**
	 * @param string $field
	 * @param null|int|\WP_Post $post
	 *
	 * @return mixed
	 */
	public static function get_val( string $field, $post = null ) {
		return get_field( static::get_field_key( $field ), $post );
	}

	/**
	 * @return array
	 */
	public static function get_block_args(): array {
		return [
			'category' => 'common',
			'icon'     => 'book-alt',
		];
	}

	/**
	 * @return mixed
	 * @see acf_register_block_type()
	 */
	public static function register_block() {
		$args = array_merge( [
			'render_callback' => [ static::class, 'render_block' ],
		], static::get_block_args() );

		return acf_register_block_type( $args );
	}

	/**
	 * @return bool
	 */
	public static function is_block(): bool {
		return is_subclass_of( static::class, I_Block::class, true );
	}

	/**
	 * @return bool
	 */
	public static function is_options_page(): bool {
		return is_subclass_of( static::class, A_Options_Page::class, true );
	}

	/**
	 * @param array $class
	 * @param array $attributes
	 *
	 * @return string
	 */
	public static function render_attrs( array $class, array $attributes = [] ): string {
		if ( ! empty( $attributes['class'] ) ) {
			$class[] = $attributes['class'];
			unset( $attributes['class'] );
		}

		return Tools::flat_attr( [ 'class' => implode( ' ', $class ) ] + $attributes );
	}

	/**
	 * @param string $label
	 * @param array $args
	 * @arg $args['name'] Can be defined. Otherwise produces one from the label.
	 *
	 * @return array
	 */
	protected static function _gen_tab_field( string $label, array $args = [] ): array {
		$name = empty( $args['name'] )
			? acf_slugify( $label, '-' )
			: $args['name'];


		$field_name = static::FIELD_PREFIX_TAB . $name;

		return [
			$field_name => array_merge( [
				'key'   => static::gen_field_key( $field_name ),
				'type'  => 'tab',
				'label' => $label,
			], $args ),
		];
	}

	/**
	 * @param string $name
	 * @param array $args
	 *
	 * @return array
	 */
	protected static function _gen_tab_end_field( string $name = 'end', array $args = [] ): array {
		return static::_gen_tab_field( '', array_merge( [ 'endpoint' => 1, 'label' => '', 'name' => $name ], $args ) );
	}
}
