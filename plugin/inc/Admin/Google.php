<?php namespace TrevorWP\Admin;

use TrevorWP\Exception\Unauthorized;
use TrevorWP\Main;
use TrevorWP\Util\Tools;
use TrevorWP\Util\Google_API;
use TrevorWP\Options;

/**
 * Admin Google Controller
 */
class Google {
	const MENU_SLUG = Main::ADMIN_MENU_SLUG_PREFIX . 'google-api';
	const NONCE_KEY = self::MENU_SLUG;

	/**
	 * Fires after WordPress has finished loading but before any headers are sent.
	 *
	 * @link https://developer.wordpress.org/reference/hooks/admin_menu/
	 * @see \TrevorWP\Util\Hooks::register_all()
	 */
	public static function register(): void {
		add_menu_page(
				'Google API',
				'Google API',
				'update_core',
				self::MENU_SLUG,
				[ self::class, 'render' ],
				'none',
				102
		);
	}

	/**
	 * @throws Unauthorized
	 */
	public static function render(): void {
		if ( ! empty( $_GET['auth_success'] ) ) {
			Tools::add_update_msg( "Authentication successful." );
		}

		$has_token = Google_API::has_token();
		$auth_url  = Google_API::get_return_url();

		if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
			self::_handle_save();
		}

		$options = [
				'auth'      => [
						'has_token' => $has_token,
						'auth_url'  => $auth_url
				],
				'analytics' => [
						'view_id'           => get_option( Options\Google::KEY_GA_VIEW_ID, '' ),
						'page_view_timeout' => get_option( Options\Google::KEY_GA_PAGE_VIEW_TO, Options\Google::DEFAULTS[ Options\Google::KEY_GA_PAGE_VIEW_TO ] ),
				],
				'page'      => [
						'form_action' => admin_url( 'admin.php?page=' . self::MENU_SLUG ),
						'nonce'       => Tools::create_nonce( self::NONCE_KEY )
				]
		];

		?>
		<div class="wrap">
			<h1>Google API</h1>
			<div class="app-wrap">
				<?php
				if ( $has_token ) {


					?>
					<div id="app-root"></div>
					<script>
						jQuery(function () {
							TrevorWP.adminApps.google(document.getElementById('app-root'), <?=json_encode( $options )?>);
						});
					</script>
				<?php
				}else{
				?>
					<p>
						To be able to use this feature please <a href="<?= esc_attr( $auth_url ) ?>">authorize</a>.
					</p>
					<?php
				}
				?>
			</div>
		</div>
		<?php
	}

	/**
	 * @throws Unauthorized
	 */
	protected static function _handle_save(): void {
		$section = $_GET['section'] ?? null;
		if ( ! $section ) {
			return;
		}

		# Verify Nonce
		Tools::verify_nonce( self::NONCE_KEY, $_POST['nonce'] );

		# Verify Cap
		if ( ! current_user_can( 'update_core' ) ) {
			throw new Unauthorized();
		}

		switch ( $section ) {
			case 'analytics';
				self::_save_analytics_form();
				break;
		}
	}

	/**
	 * Saves Analytics Form
	 */
	protected static function _save_analytics_form(): void {
		$data = filter_input_array( INPUT_POST, [
				'view_id'           => FILTER_SANITIZE_STRING,
				'page_view_timeout' => FILTER_SANITIZE_NUMBER_INT
		], true );

		update_option( Options\Google::KEY_GA_VIEW_ID, $data['view_id'], true );
		update_option( Options\Google::KEY_GA_PAGE_VIEW_TO, $data['page_view_timeout'], true );

		Tools::add_update_msg( 'Analytics Settings saved.' );
	}
}
