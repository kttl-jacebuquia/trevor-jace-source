<?php namespace TrevorWP\Util;

use TrevorWP\Main;
use TrevorWP\Jobs\Jobs;

class Activate {
	/**
	 * @param string $version Previous version.
	 *
	 * @link https://developer.wordpress.org/reference/hooks/activate_plugin/
	 */
	public static function activate( $version = \TrevorWP\VERSION ) {
		# todo: Rewrite Rules
		flush_rewrite_rules();

		# Update options
		self::_update_options();

		# Setup recurring events
		Jobs::schedule_events();

		# Set the new version
		update_option( Main::OPTION_KEY_VERSION, $version, true );

		Log::info( 'Plugin upgrade successful.', compact( 'version' ) );
	}

	/**
	 * Overwrites some wp options
	 */
	protected static function _update_options(): void {
		# Medium Size
		update_option( 'medium_size_w', 720 );// Allowed max width
		update_option( 'medium_size_h', 9999 );

		# Large Size
		update_option( 'large_size_w', 1440 ); // Allowed max width
		update_option( 'large_size_h', 9999 );

		# Comment & Ping Status
		update_option( 'default_comment_status', 'closed' );
		update_option( 'default_ping_status', 'closed' );
	}
}
