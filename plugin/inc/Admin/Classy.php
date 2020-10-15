<?php namespace TrevorWP\Admin;

use TrevorWP\Main;

/**
 * Classy Admin Page Controller
 */
class Classy {
	const MENU_SLUG = Main::ADMIN_MENU_SLUG_PREFIX . 'classy-api';

	/**
	 * Fires after WordPress has finished loading but before any headers are sent.
	 *
	 * @link https://developer.wordpress.org/reference/hooks/admin_menu/
	 * @see \TrevorWP\Util\Hooks::register_all()
	 */
	public static function register() {
		add_menu_page(
				'Classy API',
				'Classy API',
				'update_core',
				self::MENU_SLUG,
				[ self::class, 'render' ],
				'none',
				101.3
		);
	}

	public static function render() {
		?>
		<div class="wrap">
			<h1>Classy.org</h1>
			<div class="app-wrap">
				<div id="app-root"></div>
			</div>
		</div>
		<script>
			jQuery(function () {
				TrevorWP.adminApps.classy(document.getElementById('app-root'));
			});
		</script>
		<?php
	}
}
