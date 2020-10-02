<?php namespace TrevorWP\Util;

class Hooks {
	/**
	 * Print buffer for admin_footer hook.
	 *
	 * @var string[]
	 */
	static public $admin_footer_buffer = [];

	/**
	 * Print buffer for wp_footer hook.
	 *
	 * @var array
	 */
	static public $frontend_footer_buffer = [];

	/**
	 * Print buffer for admin_notices hook.
	 *
	 * @var array
	 */
	static public $admin_notices = [];

	/**
	 * Registers all hooks
	 */
	public static function register_all() {
		# Init
		add_action( 'init', [ self::class, 'init' ] );

		# Print footer
		add_action( 'wp_footer', [ self::class, 'wp_footer' ] );
		add_action( 'admin_footer', [ self::class, 'admin_footer' ], 10, 0 );

		# Admin notices
		add_action( 'admin_notices', [ self::class, 'admin_notices' ] );

		# Register & Enqueue Scripts
		add_action( 'admin_enqueue_scripts', [ StaticFiles::class, 'register_admin' ], 10, 1 );
		add_action( 'wp_enqueue_scripts', [ StaticFiles::class, 'register_frontend' ], 10, 0 );
	}

	/**
	 * Fires after WordPress has finished loading but before any headers are sent.
	 *
	 * @link https://developer.wordpress.org/reference/hooks/init/
	 */
	public static function init(): void {
	}

	/**
	 * Prints admin screen notices.
	 *
	 * @link https://developer.wordpress.org/reference/hooks/admin_notices/
	 */
	public static function admin_notices(): void {
		foreach ( self::$admin_notices as $idx => $notice ) {
			?>
			<div class="notice <?= esc_attr( $notice['class'] ) ?> is-dismissible">
				<p><?= $notice['msg'] ?></p>
			</div>
			<?php
			unset( self::$admin_notices[ $idx ] );
		}
	}

	/**
	 * Print scripts or data before the closing body tag on the front end.
	 *
	 * @link https://developer.wordpress.org/reference/hooks/wp_footer/
	 */
	static public function wp_footer(): void {
		# Print buffer
		foreach ( self::$frontend_footer_buffer as $line ) {
			echo $line;
		}
	}

	/**
	 * Print scripts or data before the default footer scripts.
	 *
	 * @link https://developer.wordpress.org/reference/hooks/admin_footer/
	 */
	static public function admin_footer(): void {
		# Print buffer
		foreach ( self::$admin_footer_buffer as $line ) {
			echo $line;
		}

		foreach ( self::$admin_notices as $idx => $notice ) {
			?>
			<script>
				jQuery(function () {
					trevorwp.utils.notices.add("<?=esc_js( $notice['msg'] );?>", {class: "<?=esc_js( $notice['class'] );?>"})
				});
			</script>
			<?php
		}
	}
}
