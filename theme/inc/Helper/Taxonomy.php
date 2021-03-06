<?php namespace TrevorWP\Theme\Helper;

use TrevorWP\Util\Tools;

/**
 * Class Taxonomy
 * @package TrevorWP\Theme\Helper
 */
class Taxonomy {
	/**
	 * Retrieves the most distinctive tags of given post.
	 *
	 * Orders by count ASC then popularity_rank ASC.
	 *
	 * @param \WP_Post $post
	 * @param array $options
	 *
	 * @return array
	 */
	public static function get_post_tags_distinctive( \WP_Post $post, array $options = array() ): array {
		$options = array_merge(
			array(
				'limit'          => 6,
				'tax'            => Tools::get_post_tag_tax( $post ),
				'tax_q_args'     => array(),
				'filter_count_1' => true,
			),
			$options
		);

		$tax_q_args = array_merge(
			array(
				'orderby' => 'count',
				'order'   => 'ASC',
				'number'  => 0,
			),
			$options['tax_q_args']
		);

		$terms = wp_get_object_terms( $post->ID, $options['tax'], $tax_q_args );

		# Filter if count == 1
		if ( $options['filter_count_1'] ) {
			$terms = array_filter( $terms, array( self::class, '_filter_count_1' ) ); // filter if only itself
		}

		# Limit
		if ( $options['limit'] ) {
			$terms = array_slice( $terms, 0, $options['limit'] );
		}

		# Sort by popularity
		$terms = \TrevorWP\Ranks\Taxonomy::sort_terms( $terms, $options['tax'], $post->post_type ); // sort by popularity

		return $terms;
	}

	/**
	 * @param \WP_Term $term
	 *
	 * @return bool
	 */
	protected static function _filter_count_1( \WP_Term $term ): bool {
		return $term->count > 1;
	}
}
