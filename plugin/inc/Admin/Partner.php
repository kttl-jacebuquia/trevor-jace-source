<?php namespace TrevorWP\Admin;

use TrevorWP\Meta;

/**
 * Partner CPT: Admin Controller
 */
class Partner {
	/* Field Names */
	const FIELD_NAME_URL = 'partner_url';

	/**
	 * @see \TrevorWP\Util\Hooks::register_all()
	 */
	public static function register_hooks(): void {
		add_action( 'add_meta_boxes', [ self::class, 'add_meta_boxes' ], 10, 1 );
		add_action( 'save_post_' . \TrevorWP\CPT\Get_Involved\Partner::POST_TYPE, [ self::class, 'save_post' ], 10, 1 );
		add_action( 'save_post_' . \TrevorWP\CPT\Get_Involved\Grant::POST_TYPE, [ self::class, 'save_post' ], 10, 1 );
	}

	/**
	 * Fires after all built-in meta boxes have been added.
	 *
	 * @param string $post_type
	 *
	 * @link https://developer.wordpress.org/reference/hooks/add_meta_boxes/
	 */
	public static function add_meta_boxes( string $post_type ): void {
		if ( in_array( $post_type, [
				\TrevorWP\CPT\Get_Involved\Partner::POST_TYPE,
				\TrevorWP\CPT\Get_Involved\Grant::POST_TYPE
		] ) ) {
			add_meta_box(
					'partner_url',
					'Partner URL',
					[ self::class, 'render_url_input' ],
					$post_type,
					'normal',
					'high'
			);
		}
	}

	/**
	 * @param \WP_Post $post
	 *
	 * @see Partner::add_meta_boxes()
	 */
	public static function render_url_input( \WP_Post $post ): void {
		$url = Meta\Post::get_partner_url( $post->ID )
		?>
		<input name="<?= esc_attr( self::FIELD_NAME_URL ) ?>"
			   value="<?= esc_attr( $url ) ?>"
			   placeholder="https://"
			   autocomplete="off"
			   type="url"
			   class="widefat"
			   required>
		<?php
	}

	/**
	 * Fires once a post has been saved.
	 *
	 * @param int $post_id
	 *
	 * @link https://developer.wordpress.org/reference/hooks/save_post_post-post_type/
	 */
	public static function save_post( int $post_id ): void {
		$url = filter_input( INPUT_POST, self::FIELD_NAME_URL, FILTER_VALIDATE_URL );
		update_post_meta( $post_id, Meta\Post::PARTNER_URL, $url );
	}
}
