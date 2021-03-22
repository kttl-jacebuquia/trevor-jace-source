<?php namespace TrevorWP\Theme\Customizer;

use TrevorWP\Exception\Internal;
use TrevorWP\Theme\Customizer\Component\Abstract_Component;

/**
 * Abstract Customizer
 */
abstract class Abstract_Customizer {
	const PANEL_ID = '';

	/* Prefixes */
	const ID_PREFIX = 'trevor_';

	/**
	 * @var \WP_Customize_Manager
	 */
	protected $_manager;

	/**
	 * @var array
	 */
	const DEFAULTS = [];

	/**
	 * @var array
	 */
	protected static $_section_components = [];

	/**
	 * @var Component\Abstract_Component[]
	 */
	protected static $_section_component_objs = [];

	/**
	 * @var string[]
	 */
	protected static $_sub_components = [];

	/**
	 * @var Component\Abstract_Component[]
	 */
	protected static $_sub_component_objs = [];

	/**
	 * Abstract_Customizer constructor.
	 *
	 * @param \WP_Customize_Manager $manager
	 */
	public function __construct( \WP_Customize_Manager $manager ) {
		$this->_manager = $manager;

		foreach ( array_keys( static::$_section_components ) as $section_id ) {
			static::get_component( $section_id )->set_customizer( $this );// Add itself to all
		}

		$this->_register_all();
	}

	/**
	 * Registers all.
	 */
	protected function _register_all(): void {
		$this->_register_panels();
		$this->_register_sections();
		$this->_register_settings();
		$this->_register_controls();
	}

	/**
	 * Registers panels.
	 */
	protected function _register_panels(): void {
	}

	/**
	 * Registers sections.
	 */
	protected function _register_sections(): void {
	}

	/**
	 * Registers settings.
	 */
	protected function _register_settings(): void {
		# Own settings
		foreach ( ( new \ReflectionClass( $this ) )->getConstants() as $constant => $key ) {
			if ( strpos( $constant, 'SETTING_' ) !== 0 ) {
				continue;
			}

			$this->_manager->add_setting( $key, [ 'default' => static::get_default( $key ) ] );
		}

		# Section component settings
		foreach ( static::$_section_component_objs as $section_component ) {
			/** @var Abstract_Component $section_component */
			$section_component->register_settings();
		}

		# Sub Component settings
		foreach ( array_keys( static::$_sub_components ) as $sub_component_name ) {
			static::get_sub_component( $sub_component_name )->set_customizer($this)->register_settings();
		}
	}

	/**
	 * Registers controls.
	 */
	protected function _register_controls(): void {
	}

	/**
	 * @param string $name
	 *
	 * @return mixed
	 */
	static public function get_val( string $name ) {
		return get_theme_mod( $name, static::get_default( $name ) );
	}

	/**
	 * Get a default value.
	 *
	 * @param string $name
	 *
	 * @return mixed|null
	 */
	static public function get_default( string $name ) {
		return static::DEFAULTS[ $name ] ?? null;
	}

	/**
	 * @return string
	 */
	static public function get_panel_id(): string {
		return static::PANEL_ID;
	}

	/**
	 * @return \WP_Customize_Manager
	 */
	public function get_manager(): \WP_Customize_Manager {
		return $this->_manager;
	}

	/**
	 * @param string $section
	 *
	 * @return Abstract_Component|null
	 * @throws Internal
	 */
	public static function get_component( string $section ): ?Abstract_Component {
		if ( ! array_key_exists( $section, static::$_section_component_objs ) ) {
			if ( ! array_key_exists( $section, static::$_section_components ) ) {
				throw new Internal( 'Section component settings does not exist.' );
			}

			list( $class, $settings ) = static::$_section_components[ $section ];
			if ( ! is_subclass_of( $class, Abstract_Component::class ) ) {
				throw new Internal( 'Provided class is not a child of the Abstract_Component.', compact( 'class', 'section' ) );
			}

			static::$_section_component_objs[ $section ] = new $class( static::get_panel_id(), $section, $settings );
		}

		return static::$_section_component_objs[ $section ];
	}

	/**
	 * @param string $name
	 *
	 * @return Abstract_Component
	 * @throws Internal
	 */
	public static function get_sub_component( string $name ) {
		if ( ! array_key_exists( $name, static::$_sub_component_objs ) ) {
			if ( ! array_key_exists( $name, static::$_sub_components ) ) {
				throw new Internal( 'Sub component settings does not exist.' );
			}

			list( $class, $section, $settings ) = static::$_sub_components[ $name ];

			if ( ! is_subclass_of( $class, Abstract_Component::class ) ) {
				throw new Internal( 'Provided class is not a child of the Abstract_Component.', compact( 'class', 'name' ) );
			}

			static::$_sub_component_objs[ $name ] = new $class( static::get_panel_id(), $section, $settings );
		}

		return static::$_sub_component_objs[ $name ];
	}

	/**
	 * @param string $section
	 *
	 * @return Abstract_Component|null
	 */
	public static function get_section_component_obj( string $section ): ?Abstract_Component {
		return @static::$_section_component_objs[ $section ];
	}
}
