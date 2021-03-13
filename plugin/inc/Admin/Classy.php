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

		list( $client_id, $client_secret ) = APIClient::get_credentials();

		?>
		<div class="wrap">
			<h1>Classy.org</h1>
			<form class="app-wrap" method="post" action="<?= admin_url( 'admin.php?page=' . self::MENU_SLUG ) ?>">
				<input type="hidden" name="nonce" value="<?= Tools::create_nonce( self::NONCE_KEY ) ?>">
				<table class="form-table">
					<tbody>
					<tr>
						<th scope="row"><label for="client_id">Client Id</label></th>
						<td><input name="client_id" id="client_id" type="text" class="regular-text"
								   value="<?= esc_attr( $client_id ) ?>"></td>
					</tr>
					<tr>
						<th scope="row"><label for="client_secret">Client Secret</label></th>
						<td><input name="client_secret" id="client_secret" type="text" class="regular-text"
								   value="<?= esc_attr( $client_secret ) ?>">
						</td>
					</tr>
					</tbody>
				</table>
				<p class="submit">
					<button type="submit" name="submit" id="submit" class="button button-primary">
						Save Credentials
					</button>
				</p>
			</form>
		</div>
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
				'client_id'     => FILTER_UNSAFE_RAW,
				'client_secret' => FILTER_UNSAFE_RAW
		], true );

		//todo: test before save
//		$test_instance = new APIClient( $data['client_id'], $data['client_secret'] );

		APIClient::$instance = null; // Clear previous instance
		APIClient::set_credentials( $data['client_id'], $data['client_secret'] );

		Tools::add_update_msg( "Credentials updated." );
	}
}
