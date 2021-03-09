<?php namespace TrevorWP\Theme\Util;

use TrevorWP\CPT;
use TrevorWP\Theme\Customizer;
use TrevorWP\Theme\Helper\Sorter;
use TrevorWP\Util\StaticFiles;

/**
 * Theme Hooks
 */
class Hooks {
	const NAME_PREFIX = 'trevor_';

	const SINGLE_PAGE_FILES = [
			[ CPT\RC\RC_Object::QV_GET_HELP, 'rc/get-help.php' ],
			[ CPT\RC\RC_Object::QV_TREVORSPACE, 'rc/trevor-space.php' ],
			[ CPT\Get_Involved\Get_Involved_Object::QV_ECT, 'get-involved/ending-conversion-therapy.php' ],
			[ CPT\Get_Involved\Get_Involved_Object::QV_VOLUNTEER, 'get-involved/volunteer.php' ],
			[ CPT\Get_Involved\Get_Involved_Object::QV_PARTNER_W_US, 'get-involved/partner-with-us.php' ],
			[ CPT\Get_Involved\Get_Involved_Object::QV_CORP_PARTNERSHIPS, 'get-involved/corporate-partnerships.php' ],
			[ CPT\Get_Involved\Get_Involved_Object::QV_INSTITUTIONAL_GRANTS, 'get-involved/institutional-grants.php' ],
			[ CPT\Donate\Donate_Object::QV_DONATE, 'donate/donate.php' ],
			[ CPT\Donate\Donate_Object::QV_FUNDRAISE, 'donate/fundraise.php' ],
			[ CPT\Donate\Donate_Object::QV_PROD_PARTNERSHIPS, 'donate/product-partnerships.php' ],
			[ CPT\Org\Org_Object::PERMALINK_ORG_LP, 'home.php' ],
	];

	/**
	 * Registers all hooks
	 */
	public static function register_all() {
		add_action( 'init', [ self::class, 'init' ], 10, 0 );

		# Media
		add_action( 'wp_enqueue_scripts', [ self::class, 'wp_enqueue_scripts' ], 10, 0 );
		add_action( 'admin_enqueue_scripts', [ self::class, 'admin_enqueue_scripts' ], 10, 0 );

		# Theme Support
		add_action( 'after_setup_theme', [ self::class, 'after_setup_theme' ], 10, 0 );

		# Theme Customizers
		add_action( 'customize_register', [ self::class, 'customize_register' ], 10, 1 );

		# Open Graph Headers
		add_action( 'wp_head', [ self::class, 'wp_head' ], 10, 0 );
		add_filter( 'language_attributes', [ self::class, 'language_attributes' ], 10, 1 );

		# Custom Nav Menu Item Fields
		add_action( 'wp_nav_menu_item_custom_fields', [ self::class, 'wp_nav_menu_item_custom_fields' ], 10, 1 );
		add_action( 'wp_update_nav_menu_item', [ self::class, 'wp_update_nav_menu_item' ], 10, 2 );
		add_filter( 'nav_menu_item_title', [ self::class, 'nav_menu_item_title' ], 10, 4 );

		# Admin Bar
		add_action( 'admin_bar_init', [ self::class, 'admin_bar_init' ], 10, 0 );

		# Excerpt Length
		add_filter( 'excerpt_length', [ self::class, 'excerpt_length' ], 10, 1 );

		# Template Load Fixes
		add_filter( 'template_include', [ self::class, 'template_include' ], PHP_INT_MAX >> 1, 1 );

		# Footer
		add_action( 'wp_footer', [ self::class, 'wp_footer' ], 10, 0 );

		# Redirects
		add_action( 'template_redirect', [ self::class, 'template_redirect' ], 10, 0 );

		# Pre Get Posts
		add_action( 'pre_get_posts', [ self::class, 'pre_get_posts' ], 10, 1 );
	}

	/**
	 * Fires after WordPress has finished loading but before any headers are sent.
	 *
	 * @link https://developer.wordpress.org/reference/hooks/init/
	 */
	public static function init(): void {
		# Register Nav Menu(s)
		register_nav_menus( [
				'header-menu' => __( 'Header Menu' ),
		] );
	}

	/**
	 * Fires when scripts and styles are enqueued.
	 *
	 * @link https://developer.wordpress.org/reference/hooks/wp_enqueue_scripts/
	 */
	public static function wp_enqueue_scripts(): void {
		// FIXME: This should change only on deployments
		$GLOBALS['trevor_theme_static_ver'] = WP_DEBUG ? uniqid( \TrevorWP\Theme\VERSION . '-' ) : \TrevorWP\Theme\VERSION;

		# Theme's frontend JS package
		wp_enqueue_script(
				self::NAME_PREFIX . 'theme-frontend-main',
				TREVOR_THEME_STATIC_URL . '/js/frontend.js',
				[ 'jquery' ],
				$GLOBALS['trevor_theme_static_ver'],
				true
		);

		// Auto-complete test
		// wp_enqueue_style( 'jquery-ui-theme', 'https://code.jquery.com/ui/1.11.4/themes/cupertino/jquery-ui.css' );
		wp_enqueue_script( 'jquery-ui-autocomplete' );

		// wp_enqueue_script( 'algoliasearch', 'https://cdn.jsdelivr.net/algoliasearch/3/algoliasearch.min.js' );
		// wp_enqueue_script( 'autocomplete.js', 'https://cdn.jsdelivr.net/autocomplete.js/0/autocomplete.jquery.min.js' );

		# Frontend style
		if ( TREVOR_ON_DEV ) {
			wp_enqueue_script(
					self::NAME_PREFIX . 'theme-frontend-css',
					TREVOR_THEME_STATIC_URL . '/css/frontend.js',
					[ StaticFiles::NAME_JS_RUNTIME ],
					$GLOBALS['trevor_theme_static_ver'],
					false
			);
		} else {
			wp_enqueue_style(
					self::NAME_PREFIX . 'theme-frontend',
					TREVOR_THEME_STATIC_URL . '/css/frontend.css',
					[],
					$GLOBALS['trevor_theme_static_ver'],
					'all'
			);
		}
	}

	/**
	 * Fires when scripts and styles are enqueued.
	 *
	 * @link https://developer.wordpress.org/reference/hooks/wp_enqueue_scripts/
	 */
	public static function admin_enqueue_scripts(): void {
		# Admin JS
		wp_enqueue_script(
				self::NAME_PREFIX . 'theme-admin-main',
				TREVOR_THEME_STATIC_URL . '/js/admin.js',
				[ 'jquery' ],
				$GLOBALS['trevor_theme_static_ver'],
				true
		);

		# Admin Style
		if ( TREVOR_ON_DEV ) {
			wp_enqueue_script(
					self::NAME_PREFIX . 'theme-admin-css',
					TREVOR_THEME_STATIC_URL . '/css/admin.js',
					[ StaticFiles::NAME_JS_RUNTIME ],
					$GLOBALS['trevor_theme_static_ver'],
					false
			);
		} else {
			wp_enqueue_style(
					self::NAME_PREFIX . 'theme-admin',
					TREVOR_THEME_STATIC_URL . '/css/admin.css',
					[],
					$GLOBALS['trevor_theme_static_ver'],
					'all'
			);
		}
	}

	/**
	 * Fires after the theme is loaded.
	 *
	 * @link https://developer.wordpress.org/reference/hooks/after_setup_theme/
	 */
	public static function after_setup_theme(): void {
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'html5' );
		add_theme_support( 'admin-bar', [ 'callback' => '__return_false' ] );

		register_nav_menus( [
				'header-organization' => '[Header] Organization',
				'header-support'      => '[Header] Support',
		] );
	}

	/**
	 * Fires once WordPress has loaded, allowing scripts and styles to be initialized.
	 *
	 * @param \WP_Customize_Manager $manager
	 *
	 * @link https://developer.wordpress.org/reference/hooks/customize_register/
	 */
	public static function customize_register( \WP_Customize_Manager $manager ): void {
		# Panels
		new Customizer\External_Scripts( $manager );
		new Customizer\Resource_Center( $manager );
		new Customizer\Trevorspace( $manager );
		new Customizer\Posts( $manager );
		new Customizer\Advocacy( $manager );
		new Customizer\Volunteer( $manager );
		new Customizer\PWU( $manager );
		new Customizer\ECT( $manager );
		new Customizer\Donate( $manager );
		new Customizer\Product_Partnerships( $manager );
		new Customizer\Shop_Product_Partners( $manager );
		new Customizer\Fundraise( $manager );
		new Customizer\Social_Media_Accounts( $manager );
	}

	/**
	 * Prints scripts or data in the head tag on the front end.
	 *
	 * @link https://developer.wordpress.org/reference/hooks/wp_head/
	 */
	public static function wp_head(): void {
		if ( $is_fp = is_front_page() ) {
			echo '<meta name="description" content="' . esc_attr( get_option( 'blogdescription' ) ) . '"/>' . PHP_EOL;
			echo '<meta property="og:title" content="' . esc_attr( get_option( 'blogname' ) ) . '"/>' . PHP_EOL;
			echo '<meta property="og:url" content="' . home_url() . '"/>' . PHP_EOL;
			echo '<meta property="og:description" content="' . esc_attr( get_option( 'blogdescription' ) ) . '"/>' . PHP_EOL;
			echo '<meta property="og:site_name" content="' . esc_attr( get_option( 'blogname' ) ) . '"/>' . PHP_EOL;

			return;
		}

		if ( ! is_singular( \TrevorWP\Util\Tools::get_public_post_types() ) ) {
			return;
		}

		echo '<meta property="og:title" content="' . esc_attr( get_the_title() ) . '"/>' . PHP_EOL;
		echo '<meta property="og:type" content="article"/>' . PHP_EOL;
		echo '<meta property="og:url" content="' . get_permalink() . '"/>' . PHP_EOL;
		echo '<meta property="og:site_name" content="' . esc_attr( get_option( 'blogname' ) ) . '"/>' . PHP_EOL;

		if ( has_post_thumbnail() ) {
			$thumbnail_src = wp_get_attachment_image_src( get_post_thumbnail_id(), 'medium' );
			echo '<meta property="og:image" content="' . esc_attr( $thumbnail_src[0] ) . '"/>' . PHP_EOL;
		}
	}

	/**
	 * Filters the language attributes for display in the html tag.
	 *
	 * @param string $doctype
	 *
	 * @return string
	 *
	 * @link https://developer.wordpress.org/reference/hooks/language_attributes/
	 */
	public static function language_attributes( string $doctype = 'html' ): string {
		if ( is_singular( 'post' ) ) {
			$doctype .= ' xmlns:og="http://opengraphprotocol.org/schema/" xmlns:fb="http://www.facebook.com/2008/fbml"';
		}

		return $doctype;
	}

	/**
	 * Fires just before the move buttons of a nav menu item in the menu editor.
	 *
	 * @param int $item_id Menu item ID.
	 *
	 * @link https://developer.wordpress.org/reference/hooks/wp_nav_menu_item_custom_fields/
	 */
	public static function wp_nav_menu_item_custom_fields( int $item_id ) {
		$val = get_post_meta( $item_id, Meta::KEY_MENU_ITEM_SUBTITLE, true );
		?>
		<p class="description description-wide trevor-custom-menu-item-subtitle">
			<label for="edit-menu-item-subtitle-<?= esc_attr( $item_id ) ?>">
				Subtitle<br>
				<input type="text"
					   id="edit-menu-item-subtitle-<?= esc_attr( $item_id ) ?>"
					   class="widefat edit-menu-item-subtitle"
					   name="menu-item-subtitle[<?= esc_attr( $item_id ) ?>]"
					   value="<?= esc_attr( $val ) ?>">
			</label>
		</p>
		<?php
	}

	/**
	 * Fires after a navigation menu item has been updated.
	 *
	 * @param int $menu_id
	 * @param int $menu_item_db_id
	 *
	 * @link https://developer.wordpress.org/reference/hooks/wp_update_nav_menu_item/
	 */
	public static function wp_update_nav_menu_item( int $menu_id, int $menu_item_db_id ): void {
		$val = $_POST['menu-item-subtitle'][ $menu_item_db_id ];

		if ( empty( $val ) ) {
			delete_post_meta( $menu_item_db_id, Meta::KEY_MENU_ITEM_SUBTITLE );
		} else {
			update_post_meta( $menu_item_db_id, Meta::KEY_MENU_ITEM_SUBTITLE, sanitize_text_field( $val ) );
		}
	}

	/**
	 * Filters a menu item’s title.
	 *
	 * @param string $title The menu item's title.
	 * @param \WP_Post $item The current menu item.
	 * @param \stdClass $args An object of wp_nav_menu() arguments.
	 * @param int $depth Depth of menu item. Used for padding.
	 *
	 * @return string
	 *
	 * @link https://developer.wordpress.org/reference/hooks/nav_menu_item_title/
	 */
	public static function nav_menu_item_title( string $title, \WP_Post $item, \stdClass $args, int $depth ): string {
		$title = "<span class='title-wrap'>{$title}</span>";
		if ( $depth == 0 ) {
			$title .= '<span class="submenu-icon trevor-ti-caret-down"></span>';
		} elseif ( $depth == 1 ) {
			$subtitle = get_post_meta( $item->ID, Meta::KEY_MENU_ITEM_SUBTITLE, true );

			if ( ! empty( $subtitle ) ) {
				$title .= '<div class="subtitle">' . esc_html( $subtitle ) . '</div>';
			}
		}

		return $title;
	}

	/**
	 * Fires after WP_Admin_Bar is initialized.
	 *
	 * @link https://developer.wordpress.org/reference/hooks/admin_bar_init/
	 */
	public static function admin_bar_init(): void {
		/**
		 * @see _admin_bar_bump_cb()
		 */
		add_action( 'wp_head', function () {
			?>
			<script>document.documentElement.classList.add('admin-bar');</script>
			<?php
		}, PHP_INT_MAX, 0 );
	}

	/**
	 * @param $length
	 *
	 * @return int
	 *
	 * @link https://developer.wordpress.org/reference/hooks/excerpt_length/
	 */
	public static function excerpt_length( int $length ): int {
		return 20;
	}

	/**
	 * Filters the path of the current template before including it.
	 *
	 * @param string $template
	 *
	 * @return string
	 *
	 * @link https://developer.wordpress.org/reference/hooks/template_include/
	 */
	public static function template_include( string $template ): string {
		global $wp_query;

		$is_single = false;

		# Single Pages
		foreach ( self::SINGLE_PAGE_FILES as list( $qv, $path ) ) {
			if ( ! empty( $wp_query->get( $qv ) ) ) {
				$template  = locate_template( $path, false );
				$is_single = true;
				break;
			}
		}

		# More specific pages
		if ( ! $is_single ) {
			# Resources Center
			if ( ! empty( $wp_query->get( CPT\RC\RC_Object::QV_BASE ) ) ) {
				# RC: Home
				if ( ! empty( $wp_query->get( CPT\RC\RC_Object::QV_RESOURCES_LP ) ) ) {
					$template = locate_template( 'rc/home.php', false );

					# Search
					if ( $wp_query->is_search() ) {
						$template = locate_template( 'rc/search.php', false );
					}
				}
			} # Get Involved
			elseif ( ! empty( $wp_query->get( CPT\Get_Involved\Get_Involved_Object::QV_BASE ) ) ) {
				if ( ! empty( $wp_query->get( CPT\Get_Involved\Get_Involved_Object::QV_ADVOCACY ) ) ) {
					$template = locate_template( 'get-involved/advocate.php', false );
				}
			}
		}

		return $template;
	}

	/**
	 * Prints scripts or data before the closing body tag on the front end.
	 *
	 * @link https://developer.wordpress.org/reference/hooks/wp_footer/
	 */
	public static function wp_footer(): void {
		if ( empty( get_query_var( CPT\RC\RC_Object::QV_GET_HELP ) ) ) { ?>
			<div class="floating-crisis-btn-wrap">
				<a class="btn floating-crisis-btn"
				   href="<?= esc_attr( home_url( \TrevorWP\CPT\RC\RC_Object::PERMALINK_GET_HELP ) ) ?>">
					Reach a Counselor</a>
			</div>
			<?php
		}
	}

	/**
	 * Fires before determining which template to load.
	 *
	 * @link https://developer.wordpress.org/reference/hooks/template_redirect/
	 */
	public static function template_redirect(): void {
		if ( '/' === $_SERVER['REQUEST_URI'] ) {
			wp_redirect( home_url( CPT\RC\RC_Object::PERMALINK_BASE ), 301 );
			exit;
		}
	}

	/**
	 * Fires after the query variable object is created, but before the actual query is run.
	 *
	 * @param \WP_Query $query
	 *
	 * @link https://developer.wordpress.org/reference/hooks/pre_get_posts/
	 */
	public static function pre_get_posts( \WP_Query $query ): void {
		$updates = [];

		# Post type archive
		if ( is_post_type_archive() ) {
			switch ( $query->get( 'post_type' ) ) {
				case CPT\Get_Involved\Bill::POST_TYPE:
					$updates['posts_per_page'] = (int) Customizer\Advocacy::get_val( Customizer\Advocacy::SETTING_PAGINATION_BILLS );
					new Sorter( $query, Sorter::get_options_for_date(), 'new-old' );
					break;
				case CPT\Get_Involved\Letter::POST_TYPE:
					$updates['posts_per_page'] = (int) Customizer\Advocacy::get_val( Customizer\Advocacy::SETTING_PAGINATION_LETTERS );
					new Sorter( $query, Sorter::get_options_for_date(), 'new-old' );
					break;
				case CPT\Donate\Prod_Partner::POST_TYPE:
					$updates['posts_per_page'] = (int) Customizer\Shop_Product_Partners::get_val( Customizer\Shop_Product_Partners::SETTING_HOME_LIST_PER_PAGE );
					break;
			}
		}

		# Apply updates
		if ( ! empty( $updates ) ) {
			foreach ( $updates as $key => $val ) {
				$query->set( $key, $val );
			}
		}
	}
}
