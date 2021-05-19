<?php namespace TrevorWP\Jobs;

use TrevorWP\Ranks;
use TrevorWP\Util\Log;

class Jobs {
	const NAME_PREFIX      = 'trevor_job_';
	const HOOK_NAME_PREFIX = 'do__';

	const NAME_UPDATE_POST_STATS               = self::NAME_PREFIX . 'update_post_stats';
	const NAME_PROCESS_POST_STATS_PAGE         = self::NAME_PREFIX . 'process_post_stats_page';
	const NAME_UPDATE_POST_RANKS               = self::NAME_PREFIX . 'update_post_ranks';
	const NAME_UPDATE_TAXONOMY_RANKS           = self::NAME_PREFIX . 'update_taxonomy_ranks';
	const NAME_UPDATE_TREVORSPACE_ACTIVE_COUNT = self::NAME_PREFIX . 'update_trevorspace_active_count';
	const NAME_UPDATE_FUNDRAISER_TOP_LISTS     = self::NAME_PREFIX . 'update_fundraiser_top_lists';
	const NAME_UPDATE_COUNSELOR_LONG_WAIT      = self::NAME_PREFIX . 'update_counselor_long_wait';

	/**
	 * @var array[] Recurring event jobs.
	 *  [0]: Callable
	 *  [1]: Recurrence
	 */
	static $RECURRING = array(
		self::NAME_UPDATE_POST_STATS               => array( array( GA_Results::class, 'update_post_stats' ), 'daily' ),
		self::NAME_UPDATE_TREVORSPACE_ACTIVE_COUNT => array( array( Trevorspace::class, 'update_active_count' ), '30min' ),
		self::NAME_UPDATE_FUNDRAISER_TOP_LISTS     => array( array( Classy::class, 'update_top_fundraise' ), 'twicedaily' ),
		self::NAME_UPDATE_COUNSELOR_LONG_WAIT      => array( array( Long_Wait::class, 'update' ), '10min' ),
	);

	/**
	 * @var array Single event jobs.
	 */
	static $SINGLE = array(
		self::NAME_PROCESS_POST_STATS_PAGE => array( GA_Results::class, 'get_the_post_stats_report_page' ),
		self::NAME_UPDATE_POST_RANKS       => array( Ranks\Post::class, 'update_post_type_ranks' ),
		self::NAME_UPDATE_TAXONOMY_RANKS   => array( Ranks\Taxonomy::class, 'update_ranks' ),
	);

	/**
	 * Registers job hooks on init.
	 *
	 * @see \TrevorWP\Util\Hooks::register_all()
	 */
	public static function register_hooks(): void {
		foreach ( array( self::$RECURRING, self::$SINGLE ) as $list ) {
			foreach ( array_keys( $list ) as $name ) {
				add_action( $name, array( self::class, self::HOOK_NAME_PREFIX . $name ), 10, 10 );
			}
		}
	}

	/**
	 * Schedules events on activation.
	 *
	 * @see \TrevorWP\Util\Activate
	 */
	public static function schedule_events(): void {
		foreach ( self::$RECURRING as $name => list( $callable, $recurrence ) ) {
			if ( $ts = wp_next_scheduled( $name ) ) {
				wp_unschedule_event( $ts, $name );
			}

			wp_schedule_event( time(), $recurrence, $name );

			Log::debug( 'Recurring event scheduled.', array( $name, $recurrence ) );
		}
	}

	/**
	 * Unschedules events on deactivation.
	 *
	 * @see \TrevorWP\Util\Deactivate
	 */
	public static function unschedule_events(): void {
		foreach ( self::$RECURRING as $name => list( $func_or_hook, $recurrence ) ) {
			if ( $ts = wp_next_scheduled( $name ) ) {
				wp_unschedule_event( $ts, $name );

				Log::debug( 'Recurring event unscheduled.', array( $name, $recurrence ) );
			}
		}
	}

	/**
	 * @param $name
	 * @param $arguments
	 */
	public static function __callStatic( $name, $arguments ): void {
		if ( strpos( $name, self::HOOK_NAME_PREFIX ) == 0 ) {
			$job_name = substr( $name, strlen( self::HOOK_NAME_PREFIX ) );

			if ( array_key_exists( $job_name, self::$RECURRING ) ) {
				list( $function ) = self::$RECURRING[ $job_name ];
				$job_type         = 'Recurring';
			} elseif ( array_key_exists( $job_name, self::$SINGLE ) ) {
				$function = self::$SINGLE[ $job_name ];
				$job_type = 'Single';
			} else {
				throw new \BadMethodCallException();
			}

			Log::info( "{$job_type}:{$job_name} job started.", compact( 'arguments' ) );

			try {
				$result = call_user_func_array( $function, $arguments );
				Log::info( "{$job_type}:{$job_name} job finished successfully.", compact( 'result', 'arguments' ) );
			} catch ( \Exception $e ) {
				Log::error( "Exception occurred during the {$job_type}:{$job_name} job.", compact( 'e', 'arguments' ) );
			}

			return;
		}

		throw new \BadMethodCallException( 'Unknown job name: ' . var_export( $name, true ) );
	}
}
