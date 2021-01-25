<?php namespace TrevorWP\Admin;

use TrevorWP\Meta;

/**
 * Partnership CPT: Admin Controller
 */
class Partnership {
	use Mixin\Meta_Box_File;

	/* Field Names */
	const FIELD_FILE_ID = 'field_id';

	/**
	 * @see \TrevorWP\Util\Hooks::register_all()
	 */
	public static function register_hooks(): void {
		add_action( 'add_meta_boxes', [ self::class, 'add_meta_boxes' ], 10, 2 );
		add_action( 'save_post_' . \TrevorWP\CPT\Get_Involved\Partnership::POST_TYPE, [
			self::class,
			'save_post'
		], 10, 1 );
	}

	/**
	 * Fires after all built-in meta boxes have been added.
	 *
	 * @param string $post_type
	 *
	 * @link https://developer.wordpress.org/reference/hooks/add_meta_boxes/
	 */
	public static function add_meta_boxes( string $post_type ): void {
		if ( $post_type == \TrevorWP\CPT\Get_Involved\Partnership::POST_TYPE ) {
			add_meta_box(
				'partnership_file',
				'Partnership File',
				[ self::class, 'render_meta_box' ],
				$post_type,
				'normal',
				'high',
				[ self::FIELD_FILE_ID ]
			);
		}
	}

	/**
	 * @param \WP_Post $post
	 *
	 * @see Partner::add_meta_boxes()
	 */
	public static function render_file_input( \WP_Post $post ): void {

	}

	/**
	 * Fires once a post has been saved.
	 *
	 * @param int $post_id
	 *
	 * @link https://developer.wordpress.org/reference/hooks/save_post_post-post_type/
	 */
	public static function save_post( int $post_id ): void {
		$file_id = filter_input( INPUT_POST, self::FIELD_FILE_ID, FILTER_VALIDATE_INT );

		if ( 'attachment' != get_post_type( $file_id ) ) {
			$file_id = null;
		}

		update_post_meta( $post_id, Meta\Post::KEY_FILE, $file_id );
	}
}
