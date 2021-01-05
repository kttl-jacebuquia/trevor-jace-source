<?php namespace TrevorWP\Ranks;

use TrevorWP\Main;
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

		$meta_key = self::get_meta_key( $post_type );

		foreach ( $results as $rank => list( $term_id ) ) {
			update_term_meta( $term_id, $meta_key, $rank + 1 );
		}

		# Clear the cache
		wp_cache_delete( $post_type, Main::CACHE_GROUP_TAX_PREFIX . $taxonomy );

		return count( $results );
	}

	/**
	 * Returns the ranks of terms in a taxonomy for a post type.
	 *
	 * @param string $taxonomy
	 * @param string $post_type
	 *
	 * @return array [$term->id => $rank, ...]
	 */
	public static function get_ranks( string $taxonomy, string $post_type ): array {
		global $wpdb;

		$ranks = wp_cache_get( $post_type, Main::CACHE_GROUP_TAX_PREFIX . $taxonomy, false, $found );

		if ( ! $found || ! is_array( $ranks ) ) {
			$q = $wpdb->prepare( "
			SELECT tm.term_id term_id, tm.meta_value rank
			FROM {$wpdb->termmeta} tm
			INNER JOIN {$wpdb->term_taxonomy} tt ON tt.term_id = tm.term_id
			WHERE
				tm.meta_key = %s AND
				tt.taxonomy = %s
			", self::get_meta_key( $post_type ), $taxonomy );

			$ranks = $wpdb->get_results( $q, OBJECT_K );
			$ranks = array_map( function ( \stdClass $row ) {
				return intval( $row->rank );
			}, $ranks );

			# Save to cache
			wp_cache_set(
				$post_type,
				$ranks,
				Main::CACHE_GROUP_TAX_PREFIX . $taxonomy,
				60
			);
		}

		return $ranks;
	}

	/**
	 * @param \WP_Post $post
	 * @param string $taxonomy
	 *
	 * @return array
	 */
	public static function get_object_terms_ordered( \WP_Post $post, string $taxonomy ): array {
		$terms = get_the_terms( $post, $taxonomy );

		if ( ! $terms || is_wp_error( $terms ) ) {
			return [];
		}

		return self::sort_terms( $terms, $taxonomy, $post->post_type );
	}

	/**
	 * @param array $terms
	 * @param string $taxonomy
	 * @param string $post_type
	 *
	 * @return array
	 */
	public static function sort_terms( array $terms, string $taxonomy, string $post_type ): array {
		$ranks = self::get_ranks( $taxonomy, $post_type );

		foreach ( $terms as $term ) {
			$term->rank = $ranks[ $term->term_id ] ?? PHP_INT_MAX;
		}

		# Sort by rank
		usort( $terms, function ( $a, $b ) {
			return $a->rank <=> $b->rank;
		} );

		return $terms;
	}

	/**
	 * @param string $post_type
	 *
	 * @return string
	 */
	public static function get_meta_key( string $post_type ): string {
		return Meta\Taxonomy::KEY_POPULARITY_RANK_PREFIX . $post_type;
	}
}
