<?php namespace TrevorWP\Theme\Util;

use TrevorWP\Theme\Customizer;

/**
 * Theme Hooks
 */
class Hooks {
	const NAME_PREFIX = 'trevor_';

	/**
	 * Registers all hooks
	 */
	public static function register_all() {
		add_action( 'init', [ self::class, 'init' ], 10, 0 );

		# Media
		add_action( 'wp_enqueue_scripts', [ self::class, 'wp_enqueue_scripts' ], 10, 0 );
		add_action( 'admin_enqueue_scripts', [ self::class, 'admin_enqueue_scripts' ], 10, 0 );

		# Theme Support
		add_action( 'after_setup_theme', [ self::class, 'after_setup_theme' ], 10, 0 );

		# Theme Customizers
		add_action( 'customize_register', [ self::class, 'customize_register' ], 10, 1 );

		# Open Graph Headers
		add_action( 'wp_head', [ self::class, 'wp_head' ], 10, 0 );
		add_filter( 'language_attributes', [ self::class, 'language_attributes' ], 10, 1 );

		# Custom Nav Menu Item Fields
		add_action( 'wp_nav_menu_item_custom_fields', [ self::class, 'wp_nav_menu_item_custom_fields' ], 10, 5 );
		add_action( 'wp_nav_menu_item_custom_fields_customize_template', [
			self::class,
			'wp_nav_menu_item_custom_fields_customize_template'
		], 10, 0 );
	}

	/**
	 * Fires after WordPress has finished loading but before any headers are sent.
	 *
	 * @link https://developer.wordpress.org/reference/hooks/init/
	 */
	public static function init(): void {
		# Register Nav Menu(s)
		register_nav_menus( [
			'header-menu' => __( 'Header Menu' ),
		] );
	}

	/**
	 * Fires when scripts and styles are enqueued.
	 *
	 * @link https://developer.wordpress.org/reference/hooks/wp_enqueue_scripts/
	 */
	public static function wp_enqueue_scripts(): void {
		# Theme's frontend JS package
		wp_enqueue_script(
			self::NAME_PREFIX . 'theme-frontend-main',
			TREVOR_THEME_STATIC_URL . '/js/frontend.js',
			[ 'jquery' ],
			\TrevorWP\Theme\VERSION,
			true
		);

		# Main style file
		if ( ! TREVOR_ON_DEV ) {
			wp_enqueue_style(
				self::NAME_PREFIX . 'theme-frontend',
				TREVOR_THEME_STATIC_URL . '/css/frontend.css',
				[],
				\TrevorWP\Theme\VERSION,
				'all'
			);
		}
	}

	/**
	 * Fires when scripts and styles are enqueued.
	 *
	 * @link https://developer.wordpress.org/reference/hooks/wp_enqueue_scripts/
	 */
	public static function admin_enqueue_scripts(): void {
		# Admin Style
		if ( ! constant( 'TREVOR_ON_DEV' ) ) {
			wp_enqueue_style(
				self::NAME_PREFIX . 'theme-admin-main',
				TREVOR_THEME_STATIC_URL . '/css/admin.css',
				[],
				\TrevorWP\Theme\VERSION,
			);
		}
	}

	/**
	 * Fires after the theme is loaded.
	 *
	 * @link https://developer.wordpress.org/reference/hooks/after_setup_theme/
	 */
	public static function after_setup_theme(): void {
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'title-tag' );
	}

	/**
	 * Fires once WordPress has loaded, allowing scripts and styles to be initialized.
	 *
	 * @param \WP_Customize_Manager $manager
	 *
	 * @link https://developer.wordpress.org/reference/hooks/customize_register/
	 */
	public static function customize_register( \WP_Customize_Manager $manager ): void {
		# Panels
		new Customizer\External_Scripts( $manager );
	}

	/**
	 * Prints scripts or data in the head tag on the front end.
	 *
	 * @link https://developer.wordpress.org/reference/hooks/wp_head/
	 */
	public static function wp_head(): void {
		if ( is_front_page() ) {
			echo '<meta name="description" content="' . esc_attr( get_option( 'blogdescription' ) ) . '"/>' . PHP_EOL;
			echo '<meta property="og:title" content="' . esc_attr( get_option( 'blogname' ) ) . '"/>' . PHP_EOL;
			echo '<meta property="og:url" content="' . home_url() . '"/>' . PHP_EOL;
			echo '<meta property="og:description" content="' . esc_attr( get_option( 'blogdescription' ) ) . '"/>' . PHP_EOL;
			echo '<meta property="og:site_name" content="' . esc_attr( get_option( 'blogname' ) ) . '"/>' . PHP_EOL;

			return;
		}

		if ( ! is_singular( 'post' ) ) {
			return;
		}

		echo '<meta property="og:title" content="' . esc_attr( get_the_title() ) . '"/>' . PHP_EOL;
		echo '<meta property="og:type" content="article"/>' . PHP_EOL;
		echo '<meta property="og:url" content="' . get_permalink() . '"/>' . PHP_EOL;
		echo '<meta property="og:site_name" content="' . esc_attr( get_option( 'blogname' ) ) . '"/>' . PHP_EOL;

		if ( has_post_thumbnail() ) {
			$thumbnail_src = wp_get_attachment_image_src( get_post_thumbnail_id(), 'medium' );
			echo '<meta property="og:image" content="' . esc_attr( $thumbnail_src[0] ) . '"/>' . PHP_EOL;
		}
	}

	/**
	 * Filters the language attributes for display in the html tag.
	 *
	 * @param string $doctype
	 *
	 * @return string
	 *
	 * @link https://developer.wordpress.org/reference/hooks/language_attributes/
	 */
	public static function language_attributes( string $doctype = 'html' ): string {
		if ( is_singular( 'post' ) ) {
			$doctype .= ' xmlns:og="http://opengraphprotocol.org/schema/" xmlns:fb="http://www.facebook.com/2008/fbml"';
		}

		return $doctype;
	}

	/**
	 * Fires just before the move buttons of a nav menu item in the menu editor.
	 *
	 * @param int $item_id Menu item ID.
	 * @param \WP_Post $item Menu item data object.
	 * @param int $depth Depth of menu item. Used for padding.
	 * @param \stdClass $args An object of menu item arguments.
	 * @param int $id Nav menu ID.
	 *
	 * @link https://developer.wordpress.org/reference/hooks/wp_nav_menu_item_custom_fields/
	 */
	public static function wp_nav_menu_item_custom_fields( int $item_id, \WP_Post $item, int $depth, \stdClass $args, int $id ) {
	}

	/**
	 * Fires at the end of the form field template for nav menu items in the customizer.
	 *
	 * @link https://developer.wordpress.org/reference/hooks/wp_nav_menu_item_custom_fields_customize_template/
	 */
	public static function wp_nav_menu_item_custom_fields_customize_template() {
	}
}
