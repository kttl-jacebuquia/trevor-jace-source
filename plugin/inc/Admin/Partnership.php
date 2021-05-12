<?php namespace TrevorWP\Admin;

use TrevorWP\Meta;
use \TrevorWP\CPT;

/**
 * Partnership CPT: Admin Controller
 */
class Partnership {
	use Mixin\Meta_Box_File;


	/* Field Names */
	const FIELD_FILE_ID = 'file_id';

	/**
	 * @see \TrevorWP\Util\Hooks::register_all()
	 */
	public static function register_hooks(): void {
		add_action(
			'add_meta_boxes_' . CPT\Get_Involved\Partnership::POST_TYPE,
			array(
				self::class,
				'add_meta_boxes',
			),
			10,
			2
		);
		add_action(
			'save_post_' . CPT\Get_Involved\Partnership::POST_TYPE,
			array(
				self::class,
				'save_post',
			),
			10,
			1
		);
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
			'partnership_file',
			'Partnership File',
			array( self::class, 'render_meta_box' ),
			$post->post_type,
			'normal',
			'high',
			array( self::FIELD_FILE_ID )
		);
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
