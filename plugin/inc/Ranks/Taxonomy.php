<?php namespace TrevorWP\Ranks;

use TrevorWP\Meta;
use TrevorWP\Exception\Internal;

class Taxonomy {
	/**
	 * Updates term ranks for the given post type.
	 *
	 * @param string $taxonomy
	 * @param string $post_type
	 * @param string[] $excluded_slugs
	 *
	 * @return int Updated term count.
	 * @throws Internal
	 */
	public static function update_ranks( string $taxonomy, string $post_type, array $excluded_slugs = [] ): int {
		global $wpdb;

		if ( ! taxonomy_exists( $taxonomy ) ) {
			throw new Internal( 'Taxonomy does not exists.', compact( 'taxonomy' ) );
		}

		$ext_where = '';
		if ( ! empty( $excluded_slugs ) ) {
			$ss        = implode( ',', array_fill( 0, count( $excluded_slugs ), '%s' ) );
			$ext_where .= call_user_func_array(
				[ $wpdb, 'prepare' ],
				array_merge( [ ' AND t.slug NOT IN (' . $ss . ') ' ], $excluded_slugs )
			);
		}

		$results = $wpdb->get_results( $wpdb->prepare( "
		SELECT t.term_id term_id
		FROM {$wpdb->terms} t
		INNER JOIN {$wpdb->term_taxonomy} tt ON tt.term_id = t.term_id AND tt.taxonomy = %s
		INNER JOIN {$wpdb->term_relationships} tr ON tr.term_taxonomy_id = t.term_id
		LEFT JOIN {$wpdb->posts} p ON p.ID = tr.object_id AND p.post_type = %s
		LEFT JOIN {$wpdb->postmeta} pm_short ON p.ID = pm_short.post_id AND pm_short.meta_key = %s
		LEFT JOIN {$wpdb->postmeta} pm_long ON p.ID = pm_long.post_id AND pm_long.meta_key = %s
		WHERE 1=1 {$ext_where}
		GROUP BY t.term_id
		ORDER BY
			(SUM(IFNULL(pm_long.meta_value,0))/4 + SUM(IFNULL(pm_short.meta_value,0))) DESC,
			tt.count DESC
		", $taxonomy, $post_type, Meta\Post::KEY_VIEW_COUNT_SHORT, Meta\Post::KEY_VIEW_COUNT_LONG ), ARRAY_N );

		$meta_key = Meta\Taxonomy::KEY_POPULARITY_RANK_PREFIX . $post_type;

		foreach ( $results as $rank => list( $term_id ) ) {
			update_term_meta( $term_id, $meta_key, $rank + 1 );
		}

		return count( $results );
	}
}
