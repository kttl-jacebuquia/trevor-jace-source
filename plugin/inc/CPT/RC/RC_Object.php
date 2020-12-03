<?php namespace TrevorWP\CPT\RC;

use TrevorWP\Main;
use TrevorWP\Theme\Util\Is;
use TrevorWP\Util\Log;
use TrevorWP\CPT;

/**
 * Abstract Resource Center Object
 */
abstract class RC_Object {
	/* Flags */
	const IS_PUBLIC = true;

	/* Post Types */
	const POST_TYPE_PREFIX = Main::POST_TYPE_PREFIX . 'rc_';

	/* Taxonomies */
	const TAXONOMY_PREFIX = self::POST_TYPE_PREFIX;
	const TAXONOMY_CATEGORY = self::TAXONOMY_PREFIX . '_category';
	const TAXONOMY_TAG = self::TAXONOMY_PREFIX . '_tag';
	const TAXONOMY_SEARCH_KEY = self::TAXONOMY_PREFIX . '_search_key';

	/* Query Vars */
	const QV_BASE = Main::QV_PREFIX . 'rc';
	const QV_SEARCH = self::QV_BASE . '__search';
	const QV_RESOURCES_LP = self::QV_BASE . '__lp';

	/* Permalinks */
	const PERMALINK_BASE = 'resources';
	const PERMALINK_BASE_TAX_CATEGORY = self::PERMALINK_BASE . '/category';
	const PERMALINK_BASE_TAX_TAG = self::PERMALINK_BASE . '/tag';
	const PERMALINK_BASE_SEARCH = self::PERMALINK_BASE . '/search';

	/* Collections */
	const _ALL_ = [
		Post::class,
		Guide::class,
		Article::class,
		Glossary::class,
	];

	/**
	 * @var string[]
	 */
	static $ALL_POST_TYPES = [];

	/**
	 * @var string[]
	 */
	static $PUBLIC_POST_TYPES = [];

	/**
	 * @see init_all()
	 */
	abstract static function init(): void;

	/**
	 * @see \TrevorWP\Util\Hooks::init()
	 */
	final public static function init_all(): void {
		global $wp_rewrite;

		# Init All
		foreach ( self::_ALL_ as $cls ) {
			# Init
			/** @var RC_Object $cls */
			$cls::init();

			# Fill post types
			self::$ALL_POST_TYPES[] = $cls::POST_TYPE;

			if ( $cls::IS_PUBLIC ) {
				self::$PUBLIC_POST_TYPES[] = $cls::POST_TYPE;
			}
		}

		# Taxonomies
		## Category
		register_taxonomy( self::TAXONOMY_CATEGORY, self::$PUBLIC_POST_TYPES, [
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
		register_taxonomy( self::TAXONOMY_TAG, self::$PUBLIC_POST_TYPES, [
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
		register_taxonomy( self::TAXONOMY_SEARCH_KEY, self::$PUBLIC_POST_TYPES, [
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

		# Other Hooks
		add_action( 'admin_menu', [ self::class, 'admin_menu' ], PHP_INT_MAX, 0 );
		add_filter( 'query_vars', [ self::class, 'query_vars' ], PHP_INT_MAX, 1 );
		add_filter( 'post_type_link', [ self::class, 'post_type_link' ], PHP_INT_MAX >> 1, 2 );
		add_filter( 'parent_file', [ self::class, 'parent_file' ], 10, 1 );

		# Rewrites
		$q_prefix = "index.php?" . http_build_query( [ self::QV_BASE => 1 ] );

		## Taxonomy
		add_filter( self::TAXONOMY_TAG . '_rewrite_rules', [ self::class, 'rewrite_rules_tag' ], PHP_INT_MAX, 0 );
		add_filter(
			self::TAXONOMY_CATEGORY . '_rewrite_rules',
			[ self::class, 'rewrite_rules_category' ],
			PHP_INT_MAX, 0
		);

		## Main Page
		add_rewrite_rule(
			self::PERMALINK_BASE . "/?$",
			implode( '&', [ $q_prefix, http_build_query( [ self::QV_RESOURCES_LP => 1 ] ) ] ),
			'top'
		);

		## Search
		$q_prefix_search = implode( '&', [ $q_prefix, http_build_query( [ self::QV_SEARCH => 1 ] ) ] );
		add_rewrite_rule( self::PERMALINK_BASE_SEARCH . "/?$", $q_prefix_search, 'top' );
		add_rewrite_rule( self::PERMALINK_BASE_SEARCH . "/{$wp_rewrite->pagination_base}/([0-9]{1,})/?$", $q_prefix_search . '&paged=$matches[1]', 'top' );

		## Posts
		add_rewrite_rule( self::PERMALINK_BASE . "/(\d+)-([^/]+)/?$", $q_prefix . "&p=\$matches[1]", 'top' );
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
	 * @see init_all()
	 */
	public static function post_type_link( string $post_link, \WP_Post $post ) {
		switch ( $post->post_type ) {
			case CPT\RC\Article::POST_TYPE:
			case CPT\RC\Guide::POST_TYPE:
				return home_url( static::PERMALINK_BASE . "/{$post->ID}-{$post->post_name}" );
			case CPT\Post::POST_TYPE:
			case CPT\RC\Post::POST_TYPE:
				if ( is_admin() || ( defined( 'DOING_AJAX' ) && DOING_AJAX /* TODO: We need a qVar flag to force it */ ) ) {
					$is_support = $post->post_type === CPT\RC\Post::POST_TYPE;
				} else {
					$is_support = Is::support();
				}

				$base = $is_support ? CPT\RC\Post::PERMALINK_BASE : CPT\Post::PERMALINK_BASE;

				return trailingslashit( home_url( "{$base}/{$post->ID}-{$post->post_name}" ) );
			default:
				return $post_link;
		}
	}

	/**
	 * Filters rewrite rules used for individual permastructs.
	 *
	 * @return string[]
	 *
	 * @link https://developer.wordpress.org/reference/hooks/permastructname_rewrite_rules/
	 * @see init_all()
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
	 * @see init_all()
	 */
	public static function rewrite_rules_category(): array {
		global $wp_rewrite;

		return [
			self::PERMALINK_BASE_TAX_CATEGORY . "/([^/]+)/{$wp_rewrite->pagination_base}/?([0-9]{1,})/?$" => 'index.php?' . self::TAXONOMY_CATEGORY . '=$matches[1]&paged=$matches[2]',
			self::PERMALINK_BASE_TAX_CATEGORY . '/([^/]+)/?$'                                             => 'index.php?' . self::TAXONOMY_CATEGORY . '=$matches[1]'
		];
	}

	/**
	 * Fires before the administration menu loads in the admin.
	 *
	 * Modifies the admin menu tree to combine all post types into one main menu item.
	 *
	 * @see init_all()
	 * @link https://developer.wordpress.org/reference/hooks/admin_menu/
	 */
	public static function admin_menu(): void {
		global $menu, $submenu;

		$indexes  = [];
		$slug_map = [];
		$pt_map   = [];
		$sm_lists = [];

		# Produce slugs
		foreach ( self::$ALL_POST_TYPES as $post_type ) {
			$slug                 = "edit.php?" . http_build_query( compact( 'post_type' ) );
			$slug_map[ $slug ]    = $post_type;
			$pt_map[ $post_type ] = $slug;
		}

		# Find indexes
		foreach ( $menu as $idx => list( , , $slug ) ) {
			if ( array_key_exists( $slug, $slug_map ) ) {
				$post_type             = $slug_map[ $slug ];
				$indexes[ $post_type ] = $idx;
			}
		}

		# Count check
		if ( ( $idx_count = count( $indexes ) ) !== count( $slug_map ) ) {
			Log::alert( 'Some menu items could not find.', compact( 'indexes', 'slug_map' ) );

			return;
		}

		foreach ( $slug_map as $slug => $post_type ) {
			# Check the index
			if ( empty( $indexes[ $post_type ] ) ) {
				Log::alert( 'Could not find the menu index.', compact( 'slug', 'post_type' ) );

				return;
			}

			# Check the submenu
			if ( empty( $submenu[ $slug ] ) ) {
				Log::alert( 'Could not find the submenu.', compact( 'slug', 'post_type' ) );

				return;
			}
		}

		# Determine the lead post type
		$lead_pt         = reset( self::$ALL_POST_TYPES );
		$lead_idx        = $indexes[ $lead_pt ];
		$lead_slug       = $pt_map[ $lead_pt ];
		$lead_sm         = &$submenu[ $lead_slug ];
		$lead_sm_idx_map = array_keys( $lead_sm );

		# Main menu title
		$menu[ $lead_idx ][0] = 'Resource Center';

		# Make room on the lead submenu
		$lead_sm_rest = [];
		foreach ( array_slice( $lead_sm_idx_map, 2 ) as $idx => $old_idx ) {
			$lead_sm_rest[ $old_idx + $idx_count + 5 ] = $lead_sm[ $old_idx ];
		}

		foreach ( array_slice( self::$ALL_POST_TYPES, 1 ) as $pt ) {
			# Remove menu items except the lead
			unset( $menu[ $indexes[ $pt ] ] );

			# Collect the submenu list
			$slug = $pt_map[ $pt ];
			list( $sm_list ) = array_slice( $submenu[ $slug ], 0, 1 );
			$sm_lists[ $pt ] = $sm_list;

			# Remove submenu
			unset( $submenu[ $slug ] );
		}

		# Combine all
		$lead_sm_list = $lead_sm[ $lead_sm_idx_map[0] ];
		$lead_sm      = [ $lead_sm_idx_map[0] => $lead_sm_list ];

		for ( $i = 1; $i < $idx_count; $i ++ ) {
			$pt                                    = self::$ALL_POST_TYPES[ $i ];
			$lead_sm[ $lead_sm_idx_map[ $i ] + 1 ] = $sm_lists[ $pt ];
		}

		$lead_sm = $lead_sm + $lead_sm_rest;
	}

	/**
	 * Filters rewrite rules used for individual permastructs.
	 *
	 * @param array $vars
	 *
	 * @return array
	 * @see init_all()
	 *
	 * @link https://developer.wordpress.org/reference/hooks/query_vars/
	 */
	public static function query_vars( array $vars ): array {
		$vars[] = self::QV_SEARCH;
		$vars[] = self::QV_RESOURCES_LP;

		return $vars;
	}

	/**
	 * Filters the parent file of an admin menu sub-menu item.
	 *
	 * @param string $parent_file
	 *
	 * @return string
	 * @see init_all()
	 *
	 * @link https://developer.wordpress.org/reference/hooks/parent_file/
	 */
	public static function parent_file( string $parent_file ): string {
		# Fix glossary main menu
		if ( $parent_file == ( "edit.php?post_type=" . Glossary::POST_TYPE ) ) {
			$parent_file = "edit.php?post_type=" . reset( self::$ALL_POST_TYPES );
		}

		return $parent_file;
	}
}
