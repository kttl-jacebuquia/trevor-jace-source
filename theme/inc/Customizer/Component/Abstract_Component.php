<?php namespace TrevorWP\Theme\Customizer\Component;

use TrevorWP\Theme\Customizer\Abstract_Customizer;

/**
 * Abstract Component
 */
abstract class Abstract_Component {
	/**
	 * @var string
	 */
	protected $_panel_id;

	/**
	 * @var string
	 */
	protected $_section_id;

	/**
	 * @var array
	 */
	protected $_options;

	/**
	 * @var array
	 */
	protected $_defaults;

	/**
	 * @var Abstract_Customizer
	 */
	protected $_customizer;

	/**
	 * Abstract_Component constructor.
	 *
	 * @param string $panel_id
	 * @param string $section_id
	 * @param array $settings
	 */
	public function __construct( string $panel_id, string $section_id, array $settings = [] ) {
		$this->_panel_id   = $panel_id;
		$this->_section_id = $section_id;

		$this->_options  = $settings['options'] ?? [];
		$this->_defaults = $settings['defaults'] ?? [];
	}

	/**
	 * @param Abstract_Customizer $customizer
	 *
	 * @return $this
	 */
	public function set_customizer( Abstract_Customizer $customizer ): Abstract_Component {
		$this->_customizer = $customizer;

		return $this;
	}

	/**
	 * @return Abstract_Customizer
	 */
	public function get_customizer(): Abstract_Customizer {
		return $this->_customizer;
	}

	/**
	 * @param array $ext_options
	 *
	 * @return string|null
	 */
	abstract function render( array $ext_options = [] ): ?string;

	/**
	 * Registers the section.
	 *
	 * @param array $args
	 */
	abstract public function register_section( array $args = [] ): void;

	/**
	 * Registers the settings.
	 */
	public function register_settings() {
		$manager = $this->get_manager();

		foreach ( ( new \ReflectionClass( $this ) )->getConstants() as $constant => $key ) {
			if ( strpos( $constant, 'SETTING_' ) !== 0 ) {
				continue;
			}

			$manager->add_setting(
				$this->get_setting_id( $key ),
				[ 'default' => $this->get_default( $key ) ],
			);
		}
	}

	/**
	 * Registers the controls.
	 */
	abstract public function register_controls(): void;

	/**
	 * @return string
	 */
	public function get_section_id(): string {
		return $this->_section_id;
	}

	/**
	 * @return string
	 */
	public function get_panel_id(): string {
		return $this->_panel_id;
	}

	/**
	 * @return \WP_Customize_Manager
	 */
	public function get_manager(): \WP_Customize_Manager {
		return $this->get_customizer()->get_manager();
	}

	/**
	 * @param string $name
	 *
	 * @return string
	 */
	public function get_setting_id( string $name ): string {
		$name_prefix = $this->get_option( 'name_prefix' );

		return $this->get_section_id() . ( empty( $name_prefix ) ? '' : "_{$name_prefix}" ) . "_{$name}";
	}

	/**
	 * @param string $key
	 *
	 * @return mixed
	 */
	public function get_default( string $key ) {
		return array_key_exists( $key, $this->_defaults )
			? $this->_defaults[ $key ]
			: null;
	}

	/**
	 * @param string $name
	 *
	 * @return mixed
	 */
	public function get_val( string $name ) {
		return get_theme_mod( $this->get_setting_id( $name ), $this->get_default( $name ) );
	}

	/**
	 * @param string $name
	 *
	 * @return mixed
	 */
	public function get_option( string $name ) {
		return array_key_exists( $name, $this->_options )
			? $this->_options[ $name ]
			: null;
	}
}
