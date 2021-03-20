<?php namespace TrevorWP\Theme\Single_Page;

use TrevorWP\Main;
use TrevorWP\Theme\Customizer\Abstract_Customizer;

/**
 * Abstract Single Page
 */
abstract class Abstract_Single_Page extends Abstract_Customizer {
	/* Sections */
	const SECTION_GENERAL = self::PANEL_ID . '_general';
	const SECTION_HEADER = self::PANEL_ID . '_header';

	const PREFIX_GENERAL = self::SECTION_GENERAL . '_';
	const SETTING_GENERAL_SLUG = self::PREFIX_GENERAL . 'slug';

	/**
	 * All Single Pages
	 */
	const ALL = [
		Public_Education::class,
		Ally_Training::class,
	];

	/**
	 * Initializes all hooks.
	 *
	 * @see \TrevorWP\Theme\Util\Hooks::register_all()
	 */
	final static public function init_all(): void {
		add_action( 'init', [ static::class, 'handle_init' ], 10, 0 );
		add_filter( 'query_vars', [ self::class, 'handle_query_vars' ], PHP_INT_MAX, 1 );
		add_filter( 'body_class', [ self::class, 'handle_body_class' ], 10, 1 );
		add_filter( 'template_include', [ self::class, 'handle_template_include' ], PHP_INT_MAX >> 1, 1 );
	}

	/**
	 * Fires after WordPress has finished loading but before any headers are sent.
	 *
	 * @see init_all()
	 * @link https://developer.wordpress.org/reference/hooks/init/
	 */
	public static function handle_init(): void {
		# Rewrites
		/** @var Abstract_Single_Page $cls */
		foreach ( self::ALL as $cls ) {
			add_rewrite_rule( $cls::get_slug() . '/?$', 'index.php?' . http_build_query( [
					$cls::get_qv() => 1,
				] ), 'top' );
		}
	}

	/**
	 * Filters rewrite rules used for individual permastructs.
	 *
	 * @param array $vars
	 *
	 * @return array
	 *
	 * @see init_all()
	 * @link https://developer.wordpress.org/reference/hooks/query_vars/
	 */
	public static function handle_query_vars( array $vars ): array {
		/** @var Abstract_Single_Page $cls */
		foreach ( self::ALL as $cls ) {
			$vars[] = $cls::get_qv();
		}

		return $vars;
	}

	/**
	 * Filters the list of CSS body class names for the current post or page.
	 *
	 * @param array $classes An array of body class names.
	 *
	 * @return array
	 *
	 * @see init_all()
	 * @link https://developer.wordpress.org/reference/hooks/body_class/
	 */
	public static function handle_body_class( array $classes ): array {
		/** @var Abstract_Single_Page $cls */
		foreach ( self::ALL as $cls ) {
			$qv = $cls::get_qv();
			if ( get_query_var( $qv ) ) {
				$classes[] = 'is-single-page';
				$classes[] = 'sp-' . substr( $qv, strlen( Main::QV_PREFIX ) );
			}
		}

		return $classes;
	}

	/**
	 * Filters the path of the current template before including it.
	 *
	 * @param string $template
	 *
	 * @return string
	 *
	 * @see init_all()
	 * @link https://developer.wordpress.org/reference/hooks/template_include/
	 */
	public static function handle_template_include( string $template ): string {
		/** @var Abstract_Single_Page $cls */
		foreach ( self::ALL as $cls ) {
			$qv = $cls::get_qv();

			if ( ! empty( get_query_var( $qv ) ) ) {
				$template = locate_template( $cls::get_template_file(), false );
				break;
			}
		}

		return $template;
	}

	/**
	 * @return string
	 */
	static function get_qv(): string {
		return Main::QV_PREFIX . static::get_default_slug();
	}

	/** @inheritdoc */
	protected function _register_sections(): void {
		# General Section
		$this->get_manager()->add_section( static::SECTION_GENERAL, [
			'panel' => static::PANEL_ID,
			'title' => 'General',
		] );

		# Header
		$this->get_component( static::SECTION_HEADER )->register_section();

		parent::_register_sections();
	}

	/** @inheritdoc */
	protected function _register_controls(): void {
		# Register component controls
		foreach ( array_keys( static::$_section_components ) as $component ) {
			$this->get_component( $component )->register_controls();
		}

		# General
		## Slug
		$this->get_manager()->add_control(
			static::SETTING_GENERAL_SLUG,
			[
				'setting' => self::SETTING_GENERAL_SLUG,
				'section' => self::SECTION_GENERAL,
				'label'   => 'Slug',
				'type'    => 'text',
			]
		);

		parent::_register_controls();
	}

	/** @inheritdoc */
	protected function _register_settings(): void {
		parent::_register_settings();

		# Set default slug
		$this->get_manager()->get_setting( static::SETTING_GENERAL_SLUG )->default = static::get_default_slug();
	}

	/**
	 * @return string
	 */
	static public function get_slug(): string {
		$slug = static::get_val( static::SETTING_GENERAL_SLUG );
		if ( empty( $slug ) ) {
			$slug = static::get_default_slug();
		}

		return $slug;
	}

	/**
	 * @return string
	 */
	static public function get_default_slug(): string {
		static $cache = [];

		if ( ! array_key_exists( $class = get_called_class(), $cache ) ) {
			$parts           = explode( '\\', $class );
			$class_name      = end( $parts );
			$cache[ $class ] = str_replace( '_', '-', sanitize_title_with_dashes( $class_name ) );
		}

		return $cache[ $class ];
	}

	/**
	 * @return string
	 */
	static public function get_permalink(): string {
		return home_url( '/' . static::get_slug() );
	}

	/**
	 * @return string
	 */
	static public function get_template_file(): string {
		return 'single-page/' . static::get_default_slug() . '.php';
	}
}
