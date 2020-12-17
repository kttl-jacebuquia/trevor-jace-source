<?php namespace TrevorWP\Theme\Helper;


class Thumbnail {
	/**
	 * @param \WP_Post|int|null $post
	 * @param string $size
	 * @param array $attr
	 *
	 * @return array [$html, $img_id]
	 */
	public static function post( $post = null, string $size = 'large', array $attr = [] ): array {
		$post = get_post( $post );

		$img_id = get_post_thumbnail_id( $post );

		if ( ! empty( $img_id ) && is_numeric( $img_id ) ) {

			if ( ! empty( $html = wp_get_attachment_image( $img_id, $size, false, $attr ) ) ) {
				return [ $html, $img_id ];
			}

		}

		# Fallback
		return [ get_the_post_thumbnail( $post, $size, $attr ), get_post_thumbnail_id( $post ) ];
	}
}
