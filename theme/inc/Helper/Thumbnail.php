<?php namespace TrevorWP\Theme\Helper;


use TrevorWP\Util\Log;

class Thumbnail {
	/* Screens */
	const SCREEN_SM = '';
	const SCREEN_MD = 'md';
	const SCREEN_LG = 'lg';
	const SCREEN_XL = 'xl';

	/* Types */
	const TYPE_VERTICAL   = 'vertical';
	const TYPE_HORIZONTAL = 'horizontal';
	const TYPE_SQUARE     = 'square';

	/* Sizes */
	const SIZE_MD = 'medium';
	const SIZE_LG = 'large';

	/* Screen Order */
	const _SCREEN_ORDER = array(
		self::SCREEN_SM => 0,
		self::SCREEN_MD => 1,
		self::SCREEN_LG => 2,
		self::SCREEN_XL => 3,
	);

	/* Defaults */
	const _DEFAULT_SCREEN  = self::SCREEN_SM;
	const _DEFAULT_TYPE    = self::TYPE_VERTICAL;
	const _DEFAULT_SIZE    = self::SIZE_LG;
	const _DEFAULT_VARIANT = array( self::_DEFAULT_SCREEN, self::_DEFAULT_TYPE, self::_DEFAULT_SIZE, array() );

	/**
	 * @param int|\WP_Post $post
	 * @param array ...$variants
	 *
	 * @return string|null
	 */
	public static function post( $post = null, ...$variants ): ?string {
		$post = get_post( $post );

		if ( ! $post ) {
			return null;
		}

		$post_image = self::post_image( $post->ID, ...$variants );
		$post_type  = get_post_type( $post );

		return implode( "\n", wp_list_pluck( $post_image, 0 ) );
	}

	/**
	 * @param int $post_id
	 * @param mixed ...$variants
	 *
	 * @return array|null
	 */
	public static function post_image( int $post_id, ...$variants ): ?array {
		$found_images = self::get_post_imgs( $post_id, ...$variants );

		return self::render_img_variants( $found_images, array(), $post_id );
	}

	/**
	 * @param $a
	 * @param $b
	 *
	 * @return int
	 */
	protected static function _order_variant( $a, $b ): int {
		list( $screen_a ) = $a;
		list( $screen_b ) = $b;

		$idx_a = self::_SCREEN_ORDER[ $screen_a ];
		$idx_b = self::_SCREEN_ORDER[ $screen_b ];

		return $idx_a <=> $idx_b;
	}

	/**
	 * Generates a image variant.
	 *
	 * @param string $screen
	 * @param string $type
	 * @param string $size
	 * @param array $attr
	 *
	 * @return array [$screen, $type, $size, $attr]
	 */
	public static function variant( $screen = self::_DEFAULT_SCREEN, $type = self::_DEFAULT_TYPE, $size = self::_DEFAULT_SIZE, array $attr = array() ): array {
		return array( $screen, $type, $size, $attr );
	}

	/**
	 * @param array $images_data
	 *
	 * @return array
	 */
	public static function render_img_variants( array $images_data, array $custom_attr = array(), $post ): array {
		$images_data = array_filter(
			$images_data,
			function ( $img_data ): bool {
				// Filter missing, or non-img entries
				list( $img_id ) = $img_data;

				if ( ! wp_attachment_is_image( $img_id ) ) {
					return false;
				}

				return true;
			}
		);

		$images_data = array_values( $images_data ); // reset numeric index, we'll use it below
		$img_count   = count( $images_data );
		$out         = array();


		foreach ( $images_data as $idx => $img_data ) {
			list( $img_id, $variant )       = $img_data;
			list( $screen, , $size, $attr ) = $variant;

			$prev_count = count( $out );
			$prev       = $prev_count > 0 ? $out[ $prev_count - 1 ] : null;
			$next       = $idx + 1 < $img_count ? $images_data[ $idx + 1 ] : null;

			# Alt
			$attr['alt'] = $custom_attr['alt'] ?? get_post_meta( $img_id, '_wp_attachment_image_alt', true );

			# Class
			if ( empty( $attr['class'] ) ) {
				$attr['class'] = array();
			} elseif ( ! is_array( $attr['class'] ) ) {
				$attr['class'] = explode( ' ', $attr['class'] );
			}
			$class = &$attr['class'];

			if ( $next ) {
				list( , list( $next_screen ) ) = $next;
				$class[]                       = $next_screen . ( empty( $next_screen ) ? '' : ':' ) . 'hidden';
			}

			if ( $prev ) {
				$class[] = 'hidden'; // tailwindcss md:hidden lg:hidden md:block lg:block
				$class[] = $screen . ( empty( $screen ) ? '' : ':' ) . 'block';
			}

			$class = implode( ' ', $class );
			$html  = wp_get_attachment_image( $img_id, $size, false, $attr );
			if ( ! empty( $html ) ) {
				$out[] = array( $html, $variant );
			}
		}

		return $out;
	}

	/**
	 * @param int $post_id
	 * @param mixed ...$variants
	 *
	 * @return array
	 */
	public static function get_post_imgs( int $post_id, ...$variants ): array {
		$found_images = array();

		# Check variants
		if ( empty( $variants ) ) {
			$variants = array( self::_DEFAULT_VARIANT );
		}

		# Order variants
		usort( $variants, array( self::class, '_order_variant' ) );

		# Group by screen
		$screen_groups = array_fill_keys( array_keys( self::_SCREEN_ORDER ), array() );
		foreach ( $variants as $variant ) {
			list( $screen )             = $variant;
			$screen_groups[ $screen ][] = $variant;
		}

		# Make sure the default screen is defined
		if ( empty( $screen_groups[ self::SCREEN_SM ] ) ) {
			$screen_groups[ self::SCREEN_SM ] = array( self::_DEFAULT_VARIANT );
			Log::warning( 'Small screen variant should be defined.', compact( 'variants', 'img_id' ) );
		}

		# Find the appropriate images
		foreach ( $screen_groups as $screen => $variants ) {
			foreach ( $variants as $idx => $variant ) {
				list( , $type ) = $variant;

				$img_id = call_user_func( array( \TrevorWP\Meta\Post::class, "get_{$type}_img_id" ), $post_id );

				if ( empty( $img_id ) ) {
					continue;
				}

				$is_image = wp_attachment_is_image( $img_id );

				if ( $is_image ) {
					$found_images[] = array( $img_id, $variant );
					continue 2;
				}
			}
		}

		return $found_images;
	}

	public static function print_img_variants( array $images_data ): string {
		$rendered = self::render_img_variants( $images_data );

		return implode( "\n", wp_list_pluck( $rendered, 0 ) );
	}
}
