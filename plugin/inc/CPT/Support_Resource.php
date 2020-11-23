<?php namespace TrevorWP\CPT;

use TrevorWP\Main;

/**
 * Support Center: Resource
 */
class Support_Resource {
	/* Post Types */
	const POST_TYPE_SC_PREFIX = Main::POST_TYPE_PREFIX . 'sc_';
	const POST_TYPE = self::POST_TYPE_SC_PREFIX . 'resource';

	/* Taxonomies */
	const TAXONOMY_CATEGORY = self::POST_TYPE . '_category';
	const TAXONOMY_TAG = self::POST_TYPE . '_tag';
	const TAXONOMY_SEARCH_KEY = self::POST_TYPE . '_search_key';

	/* Permalinks */
	const PERMALINK_BASE = 'support';
	const PERMALINK_BASE_TAX_CATEGORY = self::PERMALINK_BASE . '/category';
	const PERMALINK_BASE_TAX_TAG = self::PERMALINK_BASE . '/tag';
	const PERMALINK_BASE_SEARCH = self::PERMALINK_BASE . '/search';

	/* Query Vars */
	const QV_SC = Main::QV_PREFIX . 'sc';
	const QV_SEARCH = self::QV_SC . '__search';

	/**
	 * @see \TrevorWP\Util\Hooks::init()
	 */
	public static function init(): void {
		global $wp_rewrite;

		add_filter( 'post_type_link', [ self::class, 'post_type_link' ], PHP_INT_MAX >> 1, 2 );

		if ( ! is_admin() ) {
			add_filter( 'query_vars', [ self::class, 'query_vars' ], PHP_INT_MAX, 1 );
		}

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
				'singular_name' => 'Resource',
				'add_new'       => 'Add New Resource'
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
				'slug'         => self::PERMALINK_BASE_TAX_CATEGORY,
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
				'slug'         => self::PERMALINK_BASE_TAX_TAG,
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

		# Rewrite
		$q_prefix = "index.php?" . http_build_query( [ self::QV_SC => 1, 'post_type' => self::POST_TYPE ] );

		## Main Page
		add_rewrite_rule( self::PERMALINK_BASE . "/?$", $q_prefix, 'top' );
//		add_rewrite_rule( self::PERMALINK_BASE . "/{$wp_rewrite->pagination_base}/([0-9]{1,})/?$", $q_prefix . '&paged=$matches[1]', 'top' ); // TODO: Pagination?

		## Search
		$q_prefix_search = implode( '&', [
			$q_prefix,
			http_build_query( [ self::QV_SEARCH => 1 ] )
		] );
		add_rewrite_rule( self::PERMALINK_BASE_SEARCH . "/?$", $q_prefix_search, 'top' );
		add_rewrite_rule( self::PERMALINK_BASE_SEARCH . "/{$wp_rewrite->pagination_base}/([0-9]{1,})/?$", $q_prefix_search . '&paged=$matches[1]', 'top' );

		## Posts
		add_rewrite_rule( self::PERMALINK_BASE . "/(\d+)-([^/]+)/?$", $q_prefix . "&p=\$matches[1]", 'top' );
	}

	/**
	 * Filters rewrite rules used for individual permastructs.
	 *
	 * @return string[]
	 *
	 * @link https://developer.wordpress.org/reference/hooks/permastructname_rewrite_rules/
	 * @see Support_Resource::init()
	 */
	public static function rewrite_rules_tag(): array {
		global $wp_rewrite;

		return [
			self::PERMALINK_BASE_TAX_TAG . "/([^/]+)/{$wp_rewrite->pagination_base}/?([0-9]{1,})/?$" => 'index.php?' . self::TAXONOMY_TAG . '=$matches[1]&paged=$matches[2]',
			self::PERMALINK_BASE_TAX_TAG . '/([^/]+)/?$'                                             => 'index.php?' . self::TAXONOMY_TAG . '=$matches[1]'
		];
	}

	/**
	 * Filters rewrite rules used for individual permastructs.
	 *
	 * @return string[]
	 *
	 * @link https://developer.wordpress.org/reference/hooks/permastructname_rewrite_rules/
	 * @see Support_Resource::init()
	 */
	public static function rewrite_rules_category(): array {
		global $wp_rewrite;

		return [
			self::PERMALINK_BASE_TAX_CATEGORY . "/([^/]+)/{$wp_rewrite->pagination_base}/?([0-9]{1,})/?$" => 'index.php?' . self::TAXONOMY_CATEGORY . '=$matches[1]&paged=$matches[2]',
			self::PERMALINK_BASE_TAX_CATEGORY . '/([^/]+)/?$'                                             => 'index.php?' . self::TAXONOMY_CATEGORY . '=$matches[1]'
		];
	}

	/**
	 * Filters rewrite rules used for individual permastructs.
	 *
	 * @param array $vars
	 *
	 * @return array
	 * @see Support_Resource::init()
	 *
	 * @link https://developer.wordpress.org/reference/hooks/query_vars/
	 */
	public static function query_vars( array $vars ): array {
		$vars[] = self::QV_SC;

		return $vars;
	}

	/**
	 * Filters the permalink for a post of a custom post type.
	 *
	 * @param string $post_link The post's permalink.
	 * @param \WP_Post $post The post in question.
	 *
	 * @return string
	 *
	 * @link https://developer.wordpress.org/reference/hooks/post_type_link/
	 * @see Support_Post::init()
	 */
	public static function post_type_link( string $post_link, \WP_Post $post ) {
		if ( $post->post_type == static::POST_TYPE ) {
			return home_url( static::PERMALINK_BASE . "/{$post->ID}-{$post->post_name}" );
		}

		return $post_link;
	}
}
