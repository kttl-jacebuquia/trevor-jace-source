<?php namespace TrevorWP\Admin;

use TrevorWP\Classy\APIClient;
use TrevorWP\Exception\Unauthorized;
use TrevorWP\Main;
use TrevorWP\Util\Tools;

/**
 * Classy Admin Page Controller
 */
class Classy {
	const MENU_SLUG = Main::ADMIN_MENU_SLUG_PREFIX . 'classy-api';
	const NONCE_KEY = self::MENU_SLUG;

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
		if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
			self::_handle_save();
		}

		?>
		<div class="wrap">
			<h1>Classy.org</h1>
			<form class="app-wrap" method="post" action="<?= admin_url( 'admin.php?page=' . self::MENU_SLUG ) ?>">
				<div id="app-root"></div>
			</form>
		</div>
		<script>
			jQuery(function () {
				TrevorWP.adminApps.classy(document.getElementById('app-root'));
			});
		</script>
		<?php
	}

	/**
	 * @throws Unauthorized
	 */
	protected static function _handle_save(): void {
		# Verify Nonce
		Tools::verify_nonce( self::NONCE_KEY, $_POST['nonce'] );

		# Verify Cap
		if ( ! current_user_can( 'update_core' ) ) {
			throw new Unauthorized();
		}

		$data = filter_input_array( INPUT_POST, [
				'clientId'     => FILTER_SANITIZE_STRING,
				'clientSecret' => FILTER_SANITIZE_STRING
		], true );

		$test_instance = APIClient::getInstance( $data['clientId'], $data['clientSecret'] );

		// TODO: Complete here...
	}
}
