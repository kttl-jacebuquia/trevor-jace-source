<?php namespace TrevorWP\Theme\Customizer;

/**
 * Abstract Customizer
 */
abstract class Abstract_Customizer {
	/* Prefixes */
	const ID_PREFIX = 'trevor_';

	/**
	 * @var \WP_Customize_Manager
	 */
	protected $_manager;

	/**
	 * @var array
	 * @deprecated
	 */
	const ALL_SETTINGS = [];

	/**
	 * @var array
	 */
	const DEFAULTS = [];

	/**
	 * Abstract_Customizer constructor.
	 *
	 * @param \WP_Customize_Manager $manager
	 */
	public function __construct( \WP_Customize_Manager $manager ) {
		$this->_manager = $manager;

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
		foreach ( ( new \ReflectionClass( $this ) )->getConstants() as $constant => $key ) {
			if ( strpos( $constant, 'SETTING_' ) !== 0 ) {
				continue;
			}

			$this->_manager->add_setting( $key, [ 'default' => static::get_default( $key ) ] );
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
		return get_theme_mod( $name, self::get_default( $name ) );
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
}
