<?php namespace TrevorWP\Ranks;

use TrevorWP\Meta;

class Post {
	/**
	 * Updates post rankings.
	 *
	 * @param string $post_type
	 *
	 * @return int Updated post count.
	 */
	public static function update_ranks( string $post_type = 'post' ): int {
		global $wpdb;

		$results = $wpdb->get_results(
			$sql = $wpdb->prepare(
				"
		SELECT p.ID id, (IFNULL(pm_long.meta_value, 0)/4 + IFNULL(pm_short.meta_value, 0)) avgVisits
		FROM {$wpdb->posts} p
		LEFT JOIN {$wpdb->postmeta} pm_short ON p.ID = pm_short.post_id AND pm_short.meta_key = %s
		LEFT JOIN {$wpdb->postmeta} pm_long ON p.ID = pm_long.post_id AND pm_long.meta_key = %s
		WHERE p.post_type = %s
		ORDER BY
			avgVisits DESC,
			p.post_date DESC
		",
				Meta\Post::KEY_VIEW_COUNT_SHORT,
				Meta\Post::KEY_VIEW_COUNT_LONG,
				$post_type
			),
			ARRAY_N
		);

		foreach ( $results as $rank => list( $post_id, $avg_visits ) ) {
			update_post_meta( $post_id, Meta\Post::KEY_POPULARITY_RANK, $rank + 1 );
			update_post_meta( $post_id, Meta\Post::KEY_AVG_VISITS, absint( $avg_visits ) );
		}

		do_action( 'trevor_post_ranks_updated', $post_type );

		return count( $results );
	}
}
