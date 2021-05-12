<?php namespace TrevorWP\CPT\RC;

use TrevorWP\Main;
use WP_Post;
use WP_Screen;

/**
 * Resource Center: External Resource
 */
class External extends RC_Object {
	/* Post Types */
	const POST_TYPE = self::POST_TYPE_PREFIX . 'external';

	/** @inheritDoc */
	public static function register_post_type(): void {
		# Post Type
		register_post_type(
			self::POST_TYPE,
			array(
				'labels'       => array(
					'name'          => 'External Resources',
					'singular_name' => 'External Resource',
					'add_new'       => 'Add New External Resource',
				),
				'public'       => true,
				'hierarchical' => false,
				'show_in_rest' => true,
				'supports'     => array(
					'title',
					'revisions',
					'thumbnail',
					'excerpt',
				),
				'has_archive'  => false,
				'rewrite'      => false,
			)
		);

		# Check page id for rest of the hooks
		add_action( 'current_screen', array( self::class, 'current_screen' ) );
	}

	/**
	 * Fires after the current screen has been set.
	 *
	 * @param WP_Screen $screen
	 *
	 * @link https://developer.wordpress.org/reference/hooks/current_screen/
	 * @see External::register_post_type()
	 */
	public static function current_screen( WP_Screen $screen ): void {
		if ( $screen->id != self::POST_TYPE ) {
			return;
		}

		# Add custom meta boxes
		add_action( 'edit_form_after_title', array( self::class, 'edit_form_after_title' ) );

		# Title -> Name
		add_filter( 'enter_title_here', array( self::class, 'enter_title_here' ) );

		# Save post action
		add_action( 'save_post_' . self::POST_TYPE, array( self::class, 'save_post_' ) );

		# Save post filter for WP_Post params
		add_filter( 'wp_insert_post_data', array( self::class, 'wp_insert_post_data' ) );
	}

	/**
	 * Fires after the title field.
	 *
	 * @param WP_Post $post
	 *
	 * @link https://developer.wordpress.org/reference/hooks/edit_form_after_title/
	 * @see current_screen()
	 */
	public static function edit_form_after_title( $post ): void {
		$data = array(
			'url' => self::obj_get_url( $post->ID ),
		);

		echo Main::get_twig()->render( 'admin/rc/external/edit/after-title.twig', compact( 'post', 'data' ) );
	}

	/**
	 * Filters the title field placeholder text.
	 *
	 * @return string
	 *
	 * @link https://developer.wordpress.org/reference/hooks/enter_title_here/
	 * @see current_screen()
	 */
	public static function enter_title_here(): string {
		return 'Name';
	}

	/**
	 * Fires once a post has been saved.
	 *
	 * @param int $post_ID
	 *
	 * @link https://developer.wordpress.org/reference/hooks/save_post_post-post_type/
	 * @see External::current_screen()
	 */
	public static function save_post_( int $post_ID ): void {
		$url = $_POST['_input_url'] ?? '';

		if ( ! filter_var( $url, FILTER_VALIDATE_URL ) ) {
			$url = '';
		}

		self::obj_set_url( $post_ID, $url );
	}

	/**
	 * @param int $post_id
	 *
	 * @return mixed
	 */
	public static function obj_get_url( int $post_id ) {
		return get_post_meta( $post_id, \TrevorWP\Meta\Post::KEY_RC_EXTERNAL_URL, true );
	}

	/**
	 * @param int $post_id
	 * @param string $url
	 */
	public static function obj_set_url( int $post_id, $url ): void {
		update_post_meta( $post_id, \TrevorWP\Meta\Post::KEY_RC_EXTERNAL_URL, $url );
	}

	/**
	 * Filters slashed post data just before it is inserted into the database.
	 *
	 * @param array $post_data An array of slashed post data.
	 *
	 * @return array
	 *
	 * @link https://developer.wordpress.org/reference/hooks/wp_insert_post_data/
	 * @see current_screen()
	 */
	public static function wp_insert_post_data( array $post_data ): array {
		if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
			$url = $_POST['_input_url'] ?? '';

			if ( ! filter_var( $url, FILTER_VALIDATE_URL ) ) {
				$post_data['post_status'] = 'draft'; // Mark it as draft until it has a valid URL
			}
		}

		return $post_data;
	}
}
