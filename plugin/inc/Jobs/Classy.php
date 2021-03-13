<?php namespace TrevorWP\Jobs;

use TrevorWP\Classy\Content;
use TrevorWP\CPT\Donate\Donate_Object;
use TrevorWP\Theme\Customizer\Fundraise;
use TrevorWP\Util\Log;

/**
 * Classy jobs.
 */
class Classy {
	/**
	 * Updates top lists.
	 */
	public static function update_top_fundraise(): void {
		$campaign_id = Fundraise::get_val( Fundraise::SETTING_TOP_LIST_CAMPAIGN_ID );
		$count       = Fundraise::get_val( Fundraise::SETTING_TOP_LIST_COUNT );
		Content::get_fundraisers( $campaign_id, $count, false );
		Content::get_fundraising_teams( $campaign_id, $count, false );

		# Clear cache
		if ( function_exists( 'pantheon_wp_clear_edge_paths' ) ) {
			$url = trailingslashit( home_url( Donate_Object::PERMALINK_FUNDRAISE ) );

			pantheon_wp_clear_edge_paths(
				[
					$url,
					rtrim( $url, '/' )
				]
			);
		} else {
			Log::alert( 'pantheon_wp_clear_edge_paths() is not exists.' );
		}
	}
}
