<?php namespace TrevorWP\Classy;

use TrevorWP\Main;

class Content {
	const CACHE_EXPIRATION = 60 * 60 * 24 /* <- 24 hours */ * 2; # 2 day in seconds

	/**
	 * @param string $campaign_id
	 * @param int $count
	 * @param bool $use_cache
	 *
	 * @return array|null
	 */
	public static function get_fundraisers( string $campaign_id, int $count = 10, bool $use_cache = true ): ?array {
		$endpoint  = '/fundraising-pages';
		$cache_key = implode( '|', array( Main::CACHE_GROUP_PREFIX, $campaign_id, $endpoint, $count ) );

		if ( ! $use_cache || empty( $result = get_transient( $cache_key ) ) ) {
			$result = self::get_campaign_data( $campaign_id, $endpoint, array( 'per_page' => $count ) );

			set_transient( $cache_key, $result, self::CACHE_EXPIRATION );
		}

		return $result;
	}

	/**
	 * @param string $campaign_id
	 * @param int $count
	 * @param bool $use_cache
	 *
	 * @return array|null
	 */
	public static function get_fundraising_teams( string $campaign_id, int $count = 10, bool $use_cache = true ): ?array {
		$endpoint  = '/fundraising-teams';
		$cache_key = implode( '|', array( Main::CACHE_GROUP_PREFIX, $campaign_id, $endpoint, $count ) );

		if ( ! $use_cache || empty( $result = get_transient( $cache_key ) ) ) {
			$result = self::get_campaign_data( $campaign_id, $endpoint, array( 'per_page' => $count ) );

			set_transient( $cache_key, $result, self::CACHE_EXPIRATION );
		}

		return $result;
	}

	public static function get_campaign_data( string $campaign_id, string $endpoint, array $params = array() ): ?array {
		$client = APIClient::get_instance();
		if ( empty( $client ) ) {
			return null;
		}

		$teams  = $client->request(
			'/campaigns/' . $campaign_id . $endpoint,
			'GET',
			array_merge(
				array(
					'aggregates' => 'true',
					'sort'       => 'total_raised:desc',
					'filter'     => 'status=active',
					'count'      => 10,
				),
				$params
			)
		);
		$result = json_decode( $teams, true );
		$result = $result['data'];

		return $result;
	}

	public static function get_events_data() {
		$events = array();

		// pagination flag
		$has_more_events = true;
		$current_page    = 1;

		while ( $has_more_events ) {
			$events_data = static::fetch_events_paginated( $current_page );
			$events      = array_merge( $events, $events_data->data );

			// Break if no more page left
			if ( empty( $events_data->next_page_url ) ) {
				$has_more_events = false;
				break;
			}

			// Continue fetching next page
			$current_page++;
		}

		return $events;
	}

	protected static function fetch_events_paginated( $page = 1 ) {
		$client = APIClient::get_instance();
		$org_id = APIClient::TREVOR_ORGANIZATION_ID;

		// To be used later for filtering out past events
		$current_date_gmt = gmdate( 'Y-m-d\TH:i:s+0000' );

		// TODO: Include started_at filter when there are available future events
		$filters = array(
			'status=active',
			urlencode( "started_at>={$current_date_gmt}" ),
		);

		$params = array(
			'per_page' => 100,
			'sort'     => 'started_at:asc',
			'page'     => $page,
			'filter'   => implode( ',', $filters ),
		);

		// Get events data from API
		$response = $client->request(
			"organizations/{$org_id}/campaigns",
			'GET',
			$params,
		);

		return json_decode( $response );
	}
}
