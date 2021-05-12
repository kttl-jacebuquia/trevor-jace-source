<?php namespace TrevorWP\CPT;

use TrevorWP\Main;
use TrevorWP\Theme\Customizer\Research_Briefs;
use TrevorWP\Theme\Helper\Sorter;

class Research {
	const POST_TYPE = Main::POST_TYPE_PREFIX . 'research';

	/**
	 * @see \TrevorWP\Util\Hooks::register_all()
	 */
	public static function construct(): void {
		add_action( 'init', array( self::class, 'init' ), 10, 0 );
		add_action( 'pre_get_posts', array( self::class, 'pre_get_posts' ), 10, 1 );
	}

	/**
	 * Fires after WordPress has finished loading but before any headers are sent.
	 *
	 * @link https://developer.wordpress.org/reference/hooks/init/
	 */
	public static function init(): void {
		register_post_type(
			self::POST_TYPE,
			array(
				'public'              => true,
				'hierarchical'        => false,
				'exclude_from_search' => true,
				'publicly_queryable'  => true,
				'show_ui'             => true,
				'show_in_rest'        => true,
				'has_archive'         => true,
				'rewrite'             => array(
					'slug'       => Research_Briefs::get_val( Research_Briefs::SETTING_SLUG ),
					'with_front' => false,
					'feeds'      => false,
				),
				'supports'            => array(
					'title',
					'editor',
					'thumbnail',
					'custom-fields',
					'excerpt',
				),
				'labels'              => array(
					'name'          => 'Research',
					'singular_name' => 'Research Brief',
				),
			)
		);
	}

	/**
	 * Fires after the query variable object is created, but before the actual query is run.
	 *
	 * @param \WP_Query $query
	 *
	 * @link https://developer.wordpress.org/reference/hooks/pre_get_posts/
	 */
	public static function pre_get_posts( \WP_Query $query ): void {
		if ( is_post_type_archive( self::POST_TYPE ) ) {
			# Set per page
			set_query_var( 'posts_per_archive_page', Research_Briefs::get_val( Research_Briefs::SETTING_PER_PAGE ) );

			# Initiate sorter
			new Sorter( $query, Sorter::get_options_for_date(), 'new-old' );
		}
	}
}
