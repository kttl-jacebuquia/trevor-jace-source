<?php namespace TrevorWP\CPT;

use TrevorWP\Main;

/**
 * Support Resource Custom Post Type
 */
class Support {
	const POST_TYPE = Main::POST_TYPE_PREFIX . 'support';
	const PERMALINK_BASE = 'support';

	/* Taxonomies */
	const TAXONOMY_CATEGORY = self::POST_TYPE . '_category';
	const TAXONOMY_TAG = self::POST_TYPE . '_tag';
	const TAXONOMY_SEARCH_KEY = self::POST_TYPE . '_search_key';

	/**
	 * @see \TrevorWP\Util\Hooks::init()
	 */
	public static function init(): void {
		global $wp_rewrite;

		# Check page id for rest of the hooks
		add_action( 'current_screen', [ self::class, 'current_screen' ], 10, 1 );

		# Rewrite Rules
		add_filter( self::TAXONOMY_TAG . '_rewrite_rules', [ self::class, 'rewrite_rules_tag' ], PHP_INT_MAX, 0 );
		add_filter(
			self::TAXONOMY_CATEGORY . '_rewrite_rules',
			[ self::class, 'rewrite_rules_category' ],
			PHP_INT_MAX, 0
		);

		# Post Type
		register_post_type( self::POST_TYPE, [
			'labels'       => [
				'name'          => 'Resources',
				'singular_name' => 'Resource'
			],
			'description'  => 'Support resources.',
			'public'       => true,
			'hierarchical' => false,
			'show_in_rest' => true,
			'supports'     => [
				'title',
				'editor',
				'revisions',
				'author',
				'thumbnail'
			],
			'has_archive'  => true,
			'rewrite'      => false,
		] );

		# Taxonomies
		## Category
		register_taxonomy( self::TAXONOMY_CATEGORY, self::POST_TYPE, [
			'public'            => true,
			'hierarchical'      => true,
			'show_in_rest'      => true,
			'show_tagcloud'     => false,
			'show_admin_column' => true,
			'rewrite'           => [
				'slug'         => self::PERMALINK_BASE . '/category',
				'hierarchical' => true,
			]
		] );

		## Tag
		register_taxonomy( self::TAXONOMY_TAG, self::POST_TYPE, [
			'public'            => true,
			'hierarchical'      => false,
			'show_in_rest'      => true,
			'show_tagcloud'     => false,
			'show_admin_column' => true,
			'rewrite'           => [
				'slug'         => self::PERMALINK_BASE . '/tag',
				'hierarchical' => false,
			]
		] );

		## Search Key
		$name_sk = 'Search Key';
		register_taxonomy( self::TAXONOMY_SEARCH_KEY, self::POST_TYPE, [
			'labels'            => [
				'name'          => "{$name_sk}s",
				'singular_name' => $name_sk,
				'search_items'  => "{$name_sk}s",
				'popular_items' => "Popular {$name_sk}",
				'all_items'     => "All {$name_sk}",
				'edit_item'     => "Edit {$name_sk}",
				'view_item'     => "View {$name_sk}",
				'update_item'   => "Update {$name_sk}",
				'add_new_item'  => "Add New {$name_sk}",
				'new_item_name' => "New {$name_sk}",
			],
			'public'            => false,
			'hierarchical'      => false,
			'show_ui'           => true,
			'show_in_rest'      => true,
			'show_tagcloud'     => false,
			'show_admin_column' => false,
			'rewrite'           => false
		] );

		$post_type = self::POST_TYPE;
		$q_prefix  = "index.php?is_trevor_support=1&post_type={$post_type}";


		# Main Page
		add_rewrite_rule( self::PERMALINK_BASE . "/?$", $q_prefix, 'top' );
//		add_rewrite_rule( self::PERMALINK_BASE . "/{$wp_rewrite->pagination_base}/([0-9]{1,})/?$", $q_prefix . '&paged=$matches[1]', 'top' ); // TODO: Pagination?

		# Search
		add_rewrite_rule( self::PERMALINK_BASE . "/search/?$", "{$q_prefix}&trevor_support_search=1", 'top' );
		add_rewrite_rule( self::PERMALINK_BASE . "/search/{$wp_rewrite->pagination_base}/([0-9]{1,})/?$", "{$q_prefix}&trevor_support_search=1" . '&paged=$matches[1]', 'top' );

		# TODO: Permastruct

		# Posts
		add_rewrite_rule( self::PERMALINK_BASE . "/(\d+)-([^/]+)/?$", $q_prefix . "&p=\$matches[1]", 'top' );

		add_permastruct( $post_type, self::PERMALINK_BASE . '/%post_id%-%postname%/', [
			'with_front'  => false,
			'paged'       => false,
			'feed'        => false,
			'forcomments' => false,
			'walk_dirs'   => false,
			'endpoints'   => false
		] );

	}

	/**
	 * Fires after the current screen has been set.
	 *
	 * @param \WP_Screen $screen
	 *
	 * @link https://developer.wordpress.org/reference/hooks/current_screen/
	 */
	public static function current_screen( \WP_Screen $screen ): void {
		if ( $screen->id != self::POST_TYPE ) {
			return;
		}

		// TODO: Purpose?
	}

	/**
	 * Filters rewrite rules used for individual permastructs.
	 *
	 * @return string[]
	 *
	 * @link https://developer.wordpress.org/reference/hooks/permastructname_rewrite_rules/
	 * @see init()
	 */
	public static function rewrite_rules_tag(): array {
		return [
			self::PERMALINK_BASE . '/tag/([^/]+)/page/?([0-9]{1,})/?$' => 'index.php?trevor_support_tag=$matches[1]&paged=$matches[2]',
			self::PERMALINK_BASE . '/tag/([^/]+)/?$'                   => 'index.php?trevor_support_tag=$matches[1]'
		];
	}

	/**
	 * Filters rewrite rules used for individual permastructs.
	 *
	 * @return string[]
	 *
	 * @link https://developer.wordpress.org/reference/hooks/permastructname_rewrite_rules/
	 * @see init()
	 */
	public static function rewrite_rules_category(): array {
		return [
			self::PERMALINK_BASE . '/category/([^/]+)/page/?([0-9]{1,})/?$' => 'index.php?trevor_support_category=$matches[1]&paged=$matches[2]',
			self::PERMALINK_BASE . '/category/([^/]+)/?$'                   => 'index.php?trevor_support_category=$matches[1]'
		];
	}
}
