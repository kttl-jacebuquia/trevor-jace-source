<?php namespace TrevorWP\CPT\RC;

use TrevorWP\Main;
use WP_Post;
use WP_Screen;

/**
 * Resource Center: Glossary Entry
 */
class Glossary extends RC_Object {
	/* Flags */
	const IS_PUBLIC = false;

	/* Post Types */
	const POST_TYPE = self::POST_TYPE_PREFIX . 'glossary';

	/** @inheritDoc */
	static function init(): void {
		# Post Type
		register_post_type( self::POST_TYPE, [
			'labels'       => [
				'name'          => 'Glossary',
				'singular_name' => 'Glossary Entry',
				'add_new'       => 'Add New Entry'
			],
			'public'       => false,
			'show_ui'      => true,
			'hierarchical' => false,
			'show_in_rest' => true,
			'supports'     => [ 'title' ],
			'has_archive'  => false,
			'rewrite'      => false,
		] );

		# Check page id for rest of the hooks
		add_action( 'current_screen', [ self::class, 'current_screen' ] );
	}

	/**
	 * Fires after the current screen has been set.
	 *
	 * @param WP_Screen $screen
	 *
	 * @link https://developer.wordpress.org/reference/hooks/current_screen/
	 * @see Glossary::init()
	 */
	public static function current_screen( WP_Screen $screen ): void {
		if ( $screen->id != self::POST_TYPE ) {
			return;
		}

		# Add custom meta boxes
		add_action( 'edit_form_after_title', [ self::class, 'edit_form_after_title' ] );

		# Title -> Name
		add_filter( 'enter_title_here', [ self::class, 'enter_title_here' ] );

		# Save post filter for WP_Post params
		add_filter( 'wp_insert_post_data', [ self::class, 'wp_insert_post_data' ] );
	}

	/**
	 * Fires after the title field.
	 *
	 * @param WP_Post $post
	 *
	 * @link https://developer.wordpress.org/reference/hooks/edit_form_after_title/
	 * @see current_screen()
	 */
	public static function edit_form_after_title( WP_Post $post ): void {
		echo Main::get_twig()->render( 'admin/rc/glossary/edit/after-title.twig', [
			'post' => $post,
		] );
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
		$post_data['post_excerpt'] = $_POST['post_excerpt'] ?? '';
		$post_data['post_content'] = $_POST['post_content'] ?? '';

		return $post_data;
	}
}
