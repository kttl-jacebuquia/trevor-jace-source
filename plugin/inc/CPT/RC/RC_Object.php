<?php namespace TrevorWP\CPT\RC;

use Solarium\QueryType\Update\Query\Document\Document as SolariumDocument;
use TrevorWP\Block\Glossary_Entry;
use TrevorWP\Main;
use TrevorWP\Theme\Util\Is;
use TrevorWP\Util\Log;
use TrevorWP\CPT;
use TrevorWP\Theme\ACF\Options_Page\Resource_Center;
use TrevorWP\Util\Tools;

/**
 * Abstract Resource Center Object
 */
abstract class RC_Object {
	/* Flags */
	const IS_PUBLIC = true;

	/* Post Types */
	const POST_TYPE_PREFIX = Main::POST_TYPE_PREFIX . 'rc_';

	/* Taxonomies */
	const TAXONOMY_PREFIX     = self::POST_TYPE_PREFIX;
	const TAXONOMY_CATEGORY   = self::TAXONOMY_PREFIX . '_category';
	const TAXONOMY_TAG        = self::TAXONOMY_PREFIX . '_tag';
	const TAXONOMY_SEARCH_KEY = self::TAXONOMY_PREFIX . '_search_key';

	/* Query Vars */
	const QV_BASE               = Main::QV_PREFIX . 'rc';
	const QV_RESOURCES_LP       = self::QV_BASE . '__lp';
	const QV_RESOURCES_NON_BLOG = self::QV_BASE . '__non_blog';
	const QV_GET_HELP           = self::QV_BASE . '__get_help';
	const QV_TREVORSPACE        = self::QV_BASE . '__trevorspace';

	/* Permalinks */
	const PERMALINK_BASE              = 'resources';
	const PERMALINK_BASE_TAX_CATEGORY = self::PERMALINK_BASE . '/category';
	const PERMALINK_BASE_TAX_TAG      = self::PERMALINK_BASE . '/tag';
	const PERMALINK_BLOG              = self::PERMALINK_BASE . '/blog';
	const PERMALINK_GUIDE             = self::PERMALINK_BASE . '/guide';
	const PERMALINK_ARTICLE           = self::PERMALINK_BASE . '/article';
	const PERMALINK_EXTERNAL          = self::PERMALINK_BASE . '/external';

	const PERMALINK_GET_HELP    = 'get-help';
	const PERMALINK_TREVORSPACE = 'trevorspace';

	/* Collections */
	const _ALL_ = array(
		Guide::class,
		Article::class,
		External::class,
		Glossary::class,
	);

	/**
	 * @var string[]
	 */
	static $ALL_POST_TYPES = array();

	/**
	 * @var string[]
	 */
	static $PUBLIC_POST_TYPES = array();

	/**
	 * @see construct()
	 */
	abstract static function register_post_type(): void;

	/**
	 * @see \TrevorWP\Util\Hooks::register_all()
	 */
	final public static function construct(): void {
		# Init All
		/** @var RC_Object $cls */
		foreach ( self::_ALL_ as $cls ) {
			# Fill post types
			self::$ALL_POST_TYPES[] = $cls::POST_TYPE;

			if ( $cls::IS_PUBLIC ) {
				self::$PUBLIC_POST_TYPES[] = $cls::POST_TYPE;
			}
		}

		# Hooks
		add_action( 'init', array( self::class, 'init' ), 10, 0 );
		add_action( 'admin_menu', array( self::class, 'admin_menu' ), PHP_INT_MAX, 0 );
		add_filter( 'query_vars', array( self::class, 'query_vars' ), PHP_INT_MAX, 1 );
		add_filter( 'post_type_link', array( self::class, 'post_type_link' ), PHP_INT_MAX >> 1, 2 );
		add_filter( 'parent_file', array( self::class, 'parent_file' ), 10, 1 );
		add_action( 'parse_request', array( self::class, 'parse_request' ), 10, 1 );
		add_action( 'parse_query', array( self::class, 'parse_query' ), 10, 1 );
		add_filter( 'posts_request', array( self::class, 'posts_request' ), 8 /* Must be lower than the Solr's hook */, 2 );
		add_filter( 'body_class', array( self::class, 'body_class' ), 10, 1 );
		add_filter( 'solr_build_document', array( self::class, 'solr_build_document' ), 10, 2 );
		add_filter( 'the_posts', array( self::class, 'the_posts' ), 12 /* Must be higher than the Solr's hook */, 2 );
	}

	/**
	 * Fires after WordPress has finished loading but before any headers are sent.
	 *
	 * @link https://developer.wordpress.org/reference/hooks/init/
	 *
	 * @see construct()
	 */
	public static function init(): void {
		global $wp_rewrite;

		# Post Types
		foreach ( self::_ALL_ as $cls ) {
			/** @var RC_Object $cls */
			$cls::register_post_type();
		}

		## Tag
		$tag_post_types = self::$PUBLIC_POST_TYPES;
		register_taxonomy(
			self::TAXONOMY_TAG,
			$tag_post_types,
			array(
				'public'            => true,
				'hierarchical'      => false,
				'show_in_rest'      => true,
				'show_tagcloud'     => false,
				'show_admin_column' => true,
				'rewrite'           => array(
					'slug'         => self::PERMALINK_BASE_TAX_TAG,
					'hierarchical' => false,
					'with_front'   => false,
				),
				'labels'            => get_taxonomy_labels( get_taxonomy( 'tag' ) ),
			)
		);

		# Taxonomies
		## Category
		register_taxonomy(
			self::TAXONOMY_CATEGORY,
			self::$PUBLIC_POST_TYPES,
			array(
				'public'            => true,
				'hierarchical'      => false,
				'show_in_rest'      => true,
				'show_tagcloud'     => false,
				'show_admin_column' => true,
				'rewrite'           => array(
					'slug'         => self::PERMALINK_BASE_TAX_CATEGORY,
					'hierarchical' => false,
					'with_front'   => false,
				),
				'labels'            => get_taxonomy_labels( get_taxonomy( 'category' ) ),
			)
		);

		## Search Key
		register_taxonomy(
			self::TAXONOMY_SEARCH_KEY,
			self::$PUBLIC_POST_TYPES,
			array(
				'labels'            => Tools::gen_tax_labels( 'Search Key' ),
				'public'            => false,
				'hierarchical'      => false,
				'show_ui'           => true,
				'show_in_rest'      => true,
				'show_tagcloud'     => false,
				'show_admin_column' => false,
				'rewrite'           => false,
			)
		);

		# Rewrites

		## Taxonomy
		add_filter(
			self::TAXONOMY_CATEGORY . '_rewrite_rules',
			array( self::class, 'rewrite_rules_category' ),
			PHP_INT_MAX,
			0
		);
		add_filter(
			self::TAXONOMY_TAG . '_rewrite_rules',
			array( self::class, 'rewrite_rules_tag' ),
			PHP_INT_MAX,
			0
		);

		## Main Page
		$main_page_query = 'index.php?' . http_build_query(
			array_merge(
				array(
					self::QV_BASE         => 1,
					self::QV_RESOURCES_LP => 1,
				),
				array_fill_keys( RC_Object::$PUBLIC_POST_TYPES, 1 )
			)
		);

		add_rewrite_rule(
			self::PERMALINK_BASE . '/?$',
			$main_page_query,
			'top'
		);

		### Pagination for search
		add_rewrite_rule(
			self::PERMALINK_BASE . "/{$wp_rewrite->pagination_base}/?([0-9]{1,})/?$",
			$main_page_query . '&paged=$matches[1]',
			'top'
		);

		## Post Types
		$catch_all_q = array(
			self::QV_BASE               => 1,
			self::QV_RESOURCES_NON_BLOG => 1,
		);

		### Blog
		add_rewrite_rule(
			self::PERMALINK_BLOG . '/([^/]+)/?$',
			'index.php?' . http_build_query(
				array(
					self::QV_BASE => 1,
					'post_type'   => array(
						Post::POST_TYPE,
						CPT\Post::POST_TYPE,
					),
				)
			) . '&name=$matches[1]',
			'top'
		);

		### Article
		add_rewrite_rule(
			self::PERMALINK_ARTICLE . '/([^/]+)/?$',
			'index.php?' . http_build_query(
				array_merge(
					$catch_all_q,
					array(
						'post_type' => Article::POST_TYPE,
					)
				)
			) . '&name=$matches[1]',
			'top'
		);

		### Guide
		add_rewrite_rule(
			self::PERMALINK_GUIDE . '/([^/]+)/?$',
			'index.php?' . http_build_query(
				array_merge(
					$catch_all_q,
					array(
						'post_type' => Guide::POST_TYPE,
					)
				)
			) . '&name=$matches[1]',
			'top'
		);

		### External
		add_rewrite_rule(
			self::PERMALINK_EXTERNAL . '/([^/]+)/?$',
			'index.php?' . http_build_query(
				array_merge(
					$catch_all_q,
					array(
						'post_type' => External::POST_TYPE,
					)
				)
			) . '&name=$matches[1]',
			'top'
		);
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
	 * @see construct()
	 */
	public static function post_type_link( string $post_link, \WP_Post $post ): string {
		switch ( $post->post_type ) {
			case CPT\RC\Article::POST_TYPE:
				return trailingslashit(
					home_url(
						implode(
							'/',
							array(
								'',
								self::PERMALINK_ARTICLE,
								$post->post_name,
							)
						)
					)
				);
			case CPT\RC\Guide::POST_TYPE:
				return trailingslashit(
					home_url(
						implode(
							'/',
							array(
								'',
								self::PERMALINK_GUIDE,
								$post->post_name,
							)
						)
					)
				);
			case CPT\RC\External::POST_TYPE:
				return trailingslashit(
					home_url(
						implode(
							'/',
							array(
								'',
								self::PERMALINK_EXTERNAL,
								$post->post_name,
							)
						)
					)
				);
			case CPT\Post::POST_TYPE:
			case CPT\RC\Post::POST_TYPE:
				if ( is_admin() || ( defined( 'DOING_AJAX' ) && DOING_AJAX /* TODO: We need a qVar flag to force it */ ) ) {
					$is_support = $post->post_type === CPT\RC\Post::POST_TYPE;
				} else {
					$is_support = Is::rc();
				}

				$base = $is_support ? CPT\RC\Post::PERMALINK_BLOG : CPT\Post::PERMALINK_BASE;

				return trailingslashit( home_url( "{$base}/{$post->post_name}" ) );
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
	 * @see construct()
	 */
	public static function rewrite_rules_category(): array {
		global $wp_rewrite;

		return array(
			self::PERMALINK_BASE_TAX_CATEGORY . "/([^/]+)/{$wp_rewrite->pagination_base}/?([0-9]{1,})/?$" => 'index.php?' . self::TAXONOMY_CATEGORY . '=$matches[1]&paged=$matches[2]',
			self::PERMALINK_BASE_TAX_CATEGORY . '/([^/]+)/?$'                                             => 'index.php?' . self::TAXONOMY_CATEGORY . '=$matches[1]',
		);
	}

	/**
	 * Filters rewrite rules used for individual permastructs.
	 *
	 * @return string[]
	 *
	 * @link https://developer.wordpress.org/reference/hooks/permastructname_rewrite_rules/
	 * @see construct()
	 */
	public static function rewrite_rules_tag(): array {
		global $wp_rewrite;

		return array(
			static::PERMALINK_BASE_TAX_TAG . "/([^/]+)/{$wp_rewrite->pagination_base}/?([0-9]{1,})/?$" => 'index.php?' . static::TAXONOMY_TAG . '=$matches[1]&paged=$matches[2]',
			static::PERMALINK_BASE_TAX_TAG . '/([^/]+)/?$'                                             => 'index.php?' . static::TAXONOMY_TAG . '=$matches[1]',
		);
	}

	/**
	 * Fires before the administration menu loads in the admin.
	 *
	 * Modifies the admin menu tree to combine all post types into one main menu item.
	 *
	 * @see construct()
	 * @link https://developer.wordpress.org/reference/hooks/admin_menu/
	 */
	public static function admin_menu(): void {
		global $menu, $submenu;

		$indexes  = array();
		$slug_map = array();
		$pt_map   = array();
		$sm_lists = array();

		# Produce slugs
		foreach ( self::$ALL_POST_TYPES as $post_type ) {
			$slug                 = 'edit.php?' . http_build_query( compact( 'post_type' ) );
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
		$lead_sm_rest = array();
		foreach ( array_slice( $lead_sm_idx_map, 2 ) as $idx => $old_idx ) {
			$lead_sm_rest[ $old_idx + $idx_count + 5 ] = $lead_sm[ $old_idx ];
		}

		foreach ( array_slice( self::$ALL_POST_TYPES, 1 ) as $pt ) {
			# Remove menu items except the lead
			unset( $menu[ $indexes[ $pt ] ] );

			# Collect the submenu list
			$slug            = $pt_map[ $pt ];
			list( $sm_list ) = array_slice( $submenu[ $slug ], 0, 1 );
			$sm_lists[ $pt ] = $sm_list;

			# Remove submenu
			unset( $submenu[ $slug ] );
		}

		# Combine all
		$lead_sm_list = $lead_sm[ $lead_sm_idx_map[0] ];
		$lead_sm      = array( $lead_sm_idx_map[0] => $lead_sm_list );

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
	 * @see construct()
	 *
	 * @link https://developer.wordpress.org/reference/hooks/query_vars/
	 */
	public static function query_vars( array $vars ): array {
		return array_merge(
			$vars,
			array(
				self::QV_BASE,
				self::QV_RESOURCES_LP,
				self::QV_RESOURCES_NON_BLOG,
			)
		);
	}

	/**
	 * Filters the parent file of an admin menu sub-menu item.
	 *
	 * @param string $parent_file
	 *
	 * @return string
	 * @see construct()
	 *
	 * @link https://developer.wordpress.org/reference/hooks/parent_file/
	 */
	public static function parent_file( string $parent_file ): string {
		# Fix glossary main menu
		if ( $parent_file == ( 'edit.php?post_type=' . Glossary::POST_TYPE ) ||
			 $parent_file == ( 'edit.php?post_type=' . External::POST_TYPE )
		) {
			$parent_file = 'edit.php?post_type=' . reset( self::$ALL_POST_TYPES );
		}

		return $parent_file;
	}

	/**
	 * Fires once all query variables for the current request have been parsed.
	 *
	 * @param \WP $wp
	 * @param \WP_REWRITE
	 *
	 * @link https://developer.wordpress.org/reference/hooks/parse_request/
	 */
	public static function parse_request( \WP $wp ): void {
		# LP Post Types
		if ( $is_rc_lp = ! empty( $wp->query_vars[ self::QV_RESOURCES_LP ] ) ) {
			/**
			 * required for the search to work
			 * @see posts_request()
			 */
			$wp->query_vars['post_type'] = null;
			$wp->query_vars['name']      = '';
		} elseif ( ! empty( $post_type = @$wp->query_vars['post_type'] ) ) {
			$blog_pts = array( Post::POST_TYPE, CPT\Post::POST_TYPE );
			if (
				( is_array( $post_type ) && ! empty( array_intersect( $post_type, $blog_pts ) ) )
				|| in_array( $post_type, $blog_pts )
			) {
				$posts = get_posts(
					array(
						'post_type'     => $blog_pts,
						'numberposts'   => 1,
						'post_name__in' => array( $wp->query_vars['name'] ),
					)
				);

				if ( ! empty( $posts ) ) {
					$first_post = reset( $posts );
					if ( $first_post->post_type == Post::POST_TYPE ) {
						$wp->post_type                            = $first_post->post_type;
						$wp->query_vars[ $first_post->post_type ] = $wp->query_vars['name'];

						// Mark the it as RC blog post
						$wp->query_vars[ Post::QV_BLOG ] = 1;
					}
				}
			}
		}
	}

	/**
	 * Fires after the main query vars have been parsed.
	 *
	 * @param \WP_Query $query
	 *
	 * @link https://developer.wordpress.org/reference/hooks/parse_query/
	 */
	public static function parse_query( \WP_Query $query ): void {
		if ( ! $query->is_main_query() ) {
			return;
		}

		$pagination = Resource_Center::get_pagination();

		# Fix Resources LP
		$is_rc_lp = ! empty( $query->get( self::QV_RESOURCES_LP ) );
		if ( $is_rc_lp ) {
			if ( ! empty( $query->get( 's' ) ) ) {
				$query->is_search = true;
				$query->set( 'posts_per_page', $pagination['search_results'] );
			}

			$query->is_single            = false;
			$query->is_singular          = false;
			$query->is_posts_page        = true;
			$query->is_post_type_archive = true;
			$query->set( 'name', null );
		}

		# Taxonomy Pagination
		if ( $query->is_tax( array( self::TAXONOMY_CATEGORY, self::TAXONOMY_TAG ) ) ) {
			$query->set( 'posts_per_page', $pagination['tax_archive'] );
		}
	}

	/**
	 * Filters the completed SQL query before sending.
	 *
	 * @param string $request
	 * @param \WP_Query $query
	 *
	 * @return string
	 *
	 * @link https://developer.wordpress.org/reference/hooks/posts_request/
	 */
	public static function posts_request( string $request, \WP_Query $query ): string {
		/* We need to modify the post_type qv right this time and just before the solr */
		$is_rc_lp = ! empty( $query->get( self::QV_RESOURCES_LP ) );
		if ( $is_rc_lp && $query->is_search() ) {
			$query->set( 'post_type', array_merge( self::$PUBLIC_POST_TYPES, array( Glossary_Entry::POST_TYPE ) ) );
		}

		return $request;
	}

	/**
	 * @param string|null $term
	 *
	 * @return string
	 */
	public static function get_search_url( string $term = null ): string {
		$base = home_url( \TrevorWP\CPT\RC\RC_Object::PERMALINK_BASE );
		if ( empty( $term ) ) {
			return $base;
		}

		return $base . '?' . http_build_query( array( 's' => $term ) );
	}

	/**
	 * Filters the list of CSS body class names for the current post or page.
	 *
	 * @param array $classes An array of body class names.
	 *
	 * @return array
	 *
	 * @link https://developer.wordpress.org/reference/hooks/body_class/
	 */
	public static function body_class( array $classes ): array {
		$is_rc = Is::rc();

		if ( $is_rc ) {
			$classes[] = 'is-rc';
		}

		return $classes;
	}

	/**
	 * @param SolariumDocument $doc Generated Solr document.
	 * @param \WP_Post $post_info Original post object.
	 *
	 * @see \SolrPower_Sync::build_document()
	 */
	public static function solr_build_document( SolariumDocument $doc, \WP_Post $post_info ): SolariumDocument {
		if ( $post_info->post_type == Glossary::POST_TYPE ) {
			// Remove them for glossary items, we want it to match with only title.
			$doc->removeField( 'post_content' );
			$doc->removeField( 'post_excerpt' );

			// Add them as suffixed, so we'll swap back on return
			$doc->addField( 'post_content_t', $post_info->post_content );
			$doc->addField( 'post_excerpt_t', $post_info->post_excerpt );
		}

		return $doc;
	}

	/**
	 * Filters the array of retrieved posts after they’ve been fetched and internally processed.
	 *
	 * @param \WP_Post[] $posts
	 * @param \WP_Query $query
	 *
	 * @return \WP_Post[]
	 *
	 * @link https://developer.wordpress.org/reference/hooks/the_posts/
	 * @see solr_build_document()
	 * @see \SolrPower_WP_Query::the_posts()
	 * @see \SolrPower_WP_Query::setup()
	 */
	public static function the_posts( array $posts, \WP_Query $query ): array {

		if ( Is::rc() && $query->is_search() ) {
			$glossary_key = null;
			foreach ( $posts as $key => $post ) {
				if ( $post->post_type == Glossary::POST_TYPE ) {
					$post->post_content = @$post->post_content_t;
					$post->post_excerpt = @$post->post_excerpt_t;

					// Get key of first glossary item in posts.
					if ( is_null( $glossary_key ) ) {
						$glossary_key = $key;
					}
				}
			}

			if ( ! is_null( $glossary_key ) && $glossary_key > 0 ) {
				// Take first glossary item then prepend it to posts.
				$glossary = $posts[ $glossary_key ];
				unset( $posts[ $glossary_key ] );
				array_unshift( $posts, $glossary );
			}
		}

		return $posts;
	}
}
