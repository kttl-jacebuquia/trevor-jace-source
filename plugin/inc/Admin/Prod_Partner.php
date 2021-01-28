<?php namespace TrevorWP\Admin;

use TrevorWP\Meta;

/**
 * Product Partner CPT: Admin Controller
 */
class Prod_Partner {
	const FIELD_FILE_ID = '__file_id';
	const FIELD_URL = '__url';

	use Mixin\Meta_Box_File {
		render_meta_box as render_file_meta_box;
	}

	/**
	 * @see \TrevorWP\Util\Hooks::register_all()
	 */
	public static function register_hooks(): void {
		add_action( 'add_meta_boxes_' . \TrevorWP\CPT\Donate\Prod_Partner::POST_TYPE, [
			self::class,
			'add_meta_boxes'
		], 10, 2 );
		add_action( 'save_post_' . \TrevorWP\CPT\Donate\Prod_Partner::POST_TYPE, [
			self::class,
			'save_post'
		], 10, 1 );
	}

	/**
	 * Fires after all built-in meta boxes have been added, contextually for the given post type.
	 *
	 * @param \WP_Post $post
	 *
	 * @link https://developer.wordpress.org/reference/hooks/add_meta_boxes_post_type/
	 */
	public static function add_meta_boxes( \WP_Post $post ): void {
		add_meta_box(
			'store_img',
			'Store Image',
			[ self::class, 'render_file_meta_box' ],
			$post->post_type,
			'normal',
			'high',
			[ self::FIELD_FILE_ID ]
		);
		add_meta_box(
			'store_url',
			'Store URL',
			[ self::class, 'render_url_input' ],
			$post->post_type,
			'normal',
			'high'
		);
	}

	/**
	 * @param \WP_Post $post
	 *
	 * @see Partner::add_meta_boxes()
	 */
	public static function render_url_input( \WP_Post $post ): void {
		$url = Meta\Post::get_store_url( $post->ID )
		?>
		<input name="<?= esc_attr( self::FIELD_URL ) ?>"
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
		# File
		$file_id = filter_input( INPUT_POST, self::FIELD_FILE_ID, FILTER_VALIDATE_INT );

		if ( 'attachment' != get_post_type( $file_id ) ) {
			$file_id = null;
		}

		update_post_meta( $post_id, Meta\Post::STORE_IMG, $file_id );

		# URL
		$url = filter_input( INPUT_POST, self::FIELD_URL, FILTER_VALIDATE_URL );
		update_post_meta( $post_id, Meta\Post::STORE_URL, $url );
	}
}
