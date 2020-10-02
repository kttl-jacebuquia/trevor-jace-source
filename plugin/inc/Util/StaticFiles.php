<?php namespace TrevorWP\Util;

use TrevorWP;

class StaticFiles {
	const NAME_PREFIX = 'trevor_';

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
		$rt_name = self::_enqueue_js_runtime();

		# Plugin Main
		wp_enqueue_script(
			self::NAME_PREFIX . 'plugin-main',
			TREVOR_PLUGIN_STATIC_URL . '/js/main.js',
			[ 'jquery', 'wp-api', $rt_name ],
			TrevorWP\VERSION,
			true
		);

		# Editor Blocks
		if ( $screen->is_block_editor ) {
			wp_enqueue_script(
				self::NAME_PREFIX . 'editor-blocks',
				TREVOR_PLUGIN_STATIC_URL . '/js/blocks.js',
				[ 'wp-api', 'wp-blocks', 'wp-element', 'wp-editor', 'jquery-ui-autocomplete', $rt_name ],
				TrevorWP\VERSION,
				true
			);
		}

//		if ( $hook_suffix == 'term.php' && in_array( $screen->id, [ 'edit-category', 'edit-post_tag' ] ) ) {
//			wp_enqueue_media(); // Required for the category image loader
//			wp_enqueue_script( 'jquery-ui-autocomplete' ); // Auto complete for posts
//		}

		# Admin Styles
		if ( ! TREVOR_ON_DEV ) {
			// Webpack loads this css file on development environment
			wp_enqueue_style(
				self::NAME_PREFIX . 'plugin-main',
				TREVOR_PLUGIN_STATIC_URL . '/css/main.css',
				TrevorWP\VERSION,
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
	 * Enqueues JS runtime.
	 *
	 * @return string Script name.
	 */
	protected static function _enqueue_js_runtime(): string {
		$name = self::NAME_PREFIX . 'runtime';

		wp_enqueue_script(
			$name,
			trailingslashit( TREVOR_ON_DEV ? getenv( 'MEDIA_DEV_PATH_PREFIX' ) : ( TREVOR_PLUGIN_STATIC_URL . '/js' ) ) . 'runtime.js',
			null,
			TrevorWP\VERSION,
			false
		);

		return $name;
	}
}
