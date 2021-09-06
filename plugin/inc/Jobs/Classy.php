<?php namespace TrevorWP\Jobs;

use TrevorWP\Classy\Content;
use TrevorWP\CPT\Donate\Donate_Object;
use TrevorWP\Theme\ACF\Options_Page\Fundraise;
use TrevorWP\Util\Log;

/**
 * Classy jobs.
 */
class Classy {

	/**
	 * Updates top lists.
	 */
	public static function update_top_fundraise(): void {
		$fundraise   = Fundraise::get_fundraise();
		$campaign_id = $fundraise['top_list']['campaign_id'];
		$count       = $fundraise['top_list']['count'];

		Content::get_fundraisers( $campaign_id, $count, false );
		Content::get_fundraising_teams( $campaign_id, $count, false );

		# Clear cache
		if ( function_exists( 'pantheon_wp_clear_edge_paths' ) ) {
			$url = trailingslashit( home_url( Donate_Object::PERMALINK_FUNDRAISE ) );

			pantheon_wp_clear_edge_paths(
				array(
					$url,
					rtrim( $url, '/' ),
				)
			);
		} else {
			Log::alert( 'pantheon_wp_clear_edge_paths() is not exists.' );
		}
	}
}
