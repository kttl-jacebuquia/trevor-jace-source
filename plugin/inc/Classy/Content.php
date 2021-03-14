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
		$cache_key = implode( "|", [ Main::CACHE_GROUP_PREFIX, $campaign_id, $endpoint, $count ] );

		if ( ! $use_cache || empty( $result = get_transient( $cache_key ) ) ) {
			$result = self::get_campaign_data( $campaign_id, $endpoint, [ 'per_page' => $count ] );

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
		$cache_key = implode( "|", [ Main::CACHE_GROUP_PREFIX, $campaign_id, $endpoint, $count ] );

		if ( ! $use_cache || empty( $result = get_transient( $cache_key ) ) ) {
			$result = self::get_campaign_data( $campaign_id, $endpoint, [ 'per_page' => $count ] );

			set_transient( $cache_key, $result, self::CACHE_EXPIRATION );
		}

		return $result;
	}

	public static function get_campaign_data( string $campaign_id, string $endpoint, array $params = [] ): ?array {
		$client = APIClient::get_instance();
		if(empty($client)){
			return null;
		}

		$teams  = $client->request( '/campaigns/' . $campaign_id . $endpoint, 'GET', array_merge( [
			'aggregates' => 'true',
			'sort'       => 'total_raised:desc',
			'filter'     => 'status=active',
			'count'      => 10,
		], $params ) );
		$result = json_decode( $teams, true );
		$result = $result['data'];

		return $result;
	}
}
