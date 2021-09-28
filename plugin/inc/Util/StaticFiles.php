<?php namespace TrevorWP\Util;

use TrevorWP;

class StaticFiles {
	const NAME_PREFIX     = 'trevor_';
	const NAME_JS_RUNTIME = self::NAME_PREFIX . 'runtime';

	/**
	 * Enqueue scripts for all admin pages.
	 *
	 * @param string $hook_suffix The current admin page.
	 *
	 * @link https://developer.wordpress.org/reference/hooks/admin_enqueue_scripts/
	 */
	public static function register_admin( string $hook_suffix ): void {
		$screen = get_current_screen();

		# Runtime
		self::_enqueue_js_runtime();

		# Plugin Main
		wp_enqueue_script(
			self::NAME_PREFIX . 'plugin-main',
			TREVOR_PLUGIN_STATIC_URL . '/js/main.js',
			array( 'jquery', 'wp-api', self::NAME_JS_RUNTIME ),
			$GLOBALS['trevor_wp_static_ver'],
			true
		);

		# Editor Blocks
		if ( $screen->is_block_editor ) {
			wp_enqueue_script(
				self::NAME_PREFIX . 'editor-blocks',
				TREVOR_PLUGIN_STATIC_URL . '/js/blocks.js',
				array( 'wp-api', 'wp-blocks', 'wp-element', 'wp-editor', 'jquery-ui-autocomplete', self::NAME_JS_RUNTIME ),
				$GLOBALS['trevor_wp_static_ver'],
				true
			);
		}

		//      if ( $hook_suffix == 'term.php' && in_array( $screen->id, [ 'edit-category', 'edit-post_tag' ] ) ) {
		//          wp_enqueue_media(); // Required for the category image loader
		//          wp_enqueue_script( 'jquery-ui-autocomplete' ); // Auto complete for posts
		//      }

		# Admin Styles
		if ( TREVOR_ON_DEV ) {
			wp_enqueue_script(
				self::NAME_PREFIX . 'plugin-admin-css',
				TREVOR_PLUGIN_STATIC_URL . '/css/main.js',
				array( self::NAME_JS_RUNTIME ),
				$GLOBALS['trevor_wp_static_ver'],
				false
			);
		} else {
			wp_enqueue_style(
				self::NAME_PREFIX . 'plugin-main',
				TREVOR_PLUGIN_STATIC_URL . '/css/main.css',
				null,
				$GLOBALS['trevor_wp_static_ver'],
				'all'
			);
		}
	}

	/**
	 * Enqueue scripts for frontend.
	 *
	 * @link https://developer.wordpress.org/reference/hooks/wp_enqueue_scripts/
	 */
	public static function register_frontend(): void {
		# Runtime
		self::_enqueue_js_runtime(); // Plugin loads the frontend runtime, so the theme doesn't need to
	}

	/**
	 * Enqueues Webpack runtime.
	 */
	protected static function _enqueue_js_runtime(): void {
		wp_enqueue_script(
			self::NAME_JS_RUNTIME,
			trailingslashit( TREVOR_ON_DEV ? getenv( 'MEDIA_DEV_PATH_PREFIX' ) : ( TREVOR_PLUGIN_STATIC_URL . '/js' ) ) . 'runtime.js',
			null,
			$GLOBALS['trevor_wp_static_ver'],
			false
		);
	}
}
