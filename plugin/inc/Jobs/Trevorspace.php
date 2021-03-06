<?php namespace TrevorWP\Jobs;

use TrevorWP\CPT\RC\RC_Object;
use TrevorWP\Main;
use TrevorWP\Theme\ACF\Options_Page\Trevorspace as Options_Trevorspace;
use TrevorWP\Util\Log;

/**
 * Trevorspace related jobs
 */
class Trevorspace {

	/**
	 * Gets the latest active count.
	 *
	 * @return int
	 */
	public static function update_active_count(): int {
		$trevorspace = Options_Trevorspace::get_trevorspace();
		$counter_url = $trevorspace['active_count_url'];

		$resp = wp_remote_get( $counter_url );

		if ( $resp['response']['code'] == 200 ) {
			$count = intval( $resp['body'] );

			# Update value
			update_option( Main::OPTION_KEY_TREVORSPACE_ACTIVE_COUNT, $count, false );

			# Clear cache
			if ( function_exists( 'pantheon_wp_clear_edge_paths' ) ) {
				$url = trailingslashit( home_url( RC_Object::PERMALINK_TREVORSPACE ) );

				pantheon_wp_clear_edge_paths(
					array(
						$url,
						rtrim( $url, '/' ),
					)
				);
			} else {
				Log::alert( 'pantheon_wp_clear_edge_paths() is not exists.' );
			}

			return $count;
		} else {
			Log::warning( 'Trevorspace active count server returned bad response.', compact( 'resp' ) );

			return - 1;
		}
	}
}
