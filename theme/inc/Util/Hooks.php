<?php namespace TrevorWP\Theme\Util;

use TrevorWP\CPT;
use TrevorWP\CPT\RC\RC_Object;
use TrevorWP\Main;
use TrevorWP\Theme\ACF\ACF;
use TrevorWP\Theme\ACF\Field_Group\Page_Header;
use TrevorWP\Theme\ACF\Field_Group\Chat_Link_Option;
use TrevorWP\Theme\ACF\Options_Page;
use TrevorWP\Theme\ACF\Options_Page\Post_Type\A_Post_Type;
use TrevorWP\Theme\ACF\Options_Page\Site_Banners;
use TrevorWP\Theme\ACF\Util\Field_Val_Getter;
use TrevorWP\Theme\Ajax\ADP;
use TrevorWP\Theme\Ajax\MailChimp;
use TrevorWP\Theme\Ajax\PhoneTwoAction;
use TrevorWP\Theme\Ajax\SVG;
use TrevorWP\Theme\Customizer;
use TrevorWP\Theme\Helper\Sorter;
use TrevorWP\Theme\Helper\Trevor_Chat;
use TrevorWP\Util\StaticFiles;

/**
 * Theme Hooks
 */
class Hooks {
	const NAME_PREFIX = 'trevor_';

	/**
	 * Registers all hooks
	 */
	public static function register_all() {
		add_action( 'init', array( self::class, 'init' ), 10, 0 );
		add_action( 'admin_init', array( self::class, 'admin_init' ), 10, 0 );

		# Media
		add_action( 'wp_enqueue_scripts', array( self::class, 'wp_enqueue_scripts' ), 10, 0 );
		add_action( 'admin_enqueue_scripts', array( self::class, 'admin_enqueue_scripts' ), 10, 0 );

		# Theme Support
		add_action( 'after_setup_theme', array( self::class, 'after_setup_theme' ), 10, 0 );

		# Open Graph Headers
		add_action( 'wp_head', array( self::class, 'wp_head' ), 10, 0 );
		add_filter( 'language_attributes', array( self::class, 'language_attributes' ), 10, 1 );

		# Custom Nav Menu Item Fields
		add_action( 'wp_nav_menu_item_custom_fields', array( self::class, 'wp_nav_menu_item_custom_fields' ), 10, 1 );
		add_action( 'wp_update_nav_menu_item', array( self::class, 'wp_update_nav_menu_item' ), 10, 2 );
		add_filter( 'nav_menu_link_attributes', array( self::class, 'nav_menu_link_attributes' ), 10, 2 );
		add_filter( 'nav_menu_item_title', array( self::class, 'nav_menu_item_title' ), 10, 4 );

		# Admin Bar
		add_action( 'admin_bar_init', array( self::class, 'admin_bar_init' ), 10, 0 );

		# Excerpt Length
		add_filter( 'excerpt_length', array( self::class, 'excerpt_length' ), 10, 1 );

		# Template Load Fixes
		add_filter( 'template_include', array( self::class, 'template_include' ), PHP_INT_MAX >> 2, 1 );

		# Footer
		add_action( 'wp_footer', array( self::class, 'wp_footer' ), 10, 0 );

		# Pre Get Posts
		add_action( 'pre_get_posts', array( self::class, 'pre_get_posts' ), 10, 1 );

		# REST API
		add_action( 'rest_api_init', array( self::class, 'rest_api_init' ), 10, 0 );

		# Body Class
		add_filter( 'body_class', array( self::class, 'body_class' ), 10, 1 );

		# Allow SVG
		add_filter( 'upload_mimes', array( self::class, 'upload_mimes' ) );

		# WPSEO Title
		add_filter( 'wpseo_title', array( self::class, 'custom_seo_title' ) );

		# GTM4WP
		if ( defined( 'GTM4WP_WPFILTER_COMPILE_DATALAYER' ) ) {
			add_filter( GTM4WP_WPFILTER_COMPILE_DATALAYER, array( self::class, 'datalayer_data_update' ) );
		}

		# Trevor Chat Button
		Trevor_Chat::init();

		# Search
		Customizer\Search::init_all();

		# ACF
		ACF::construct();

		# Phone2Action API
		PhoneTwoAction::construct();

		# ADP API
		ADP::construct();

		# MailChimp API
		MailChimp::construct();

		# SVG API
		SVG::construct();
	}

	/**
	 * Fires after WordPress has finished loading but before any headers are sent.
	 *
	 * @link https://developer.wordpress.org/reference/hooks/init/
	 */
	public static function init(): void {
		# Register Nav Menu(s)
		register_nav_menus(
			array(
				'header-menu' => __( 'Header Menu' ),
			)
		);

		acf_add_options_page(
			array(
				'page_title' => 'General Settings',
				'menu_title' => 'General Settings',
				'menu_slug'  => 'general-settings',
				'capability' => 'administrator',
				'redirect'   => true,
			)
		);
	}

	/**
	 * Fires as an admin screen or script is being initialized.
	 *
	 * @link https://developer.wordpress.org/reference/hooks/admin_init/
	 */
	public static function admin_init(): void {
		$current_screen = get_current_screen();
		if ( TREVOR_ON_DEV ) {
			add_action(
				'enqueue_block_editor_assets',
				function () {
					# Block Editor Styles
					wp_enqueue_script(
						self::NAME_PREFIX . 'theme-editor-main',
						TREVOR_THEME_STATIC_URL . '/css/editor.js',
						array( 'jquery' ),
						$GLOBALS['trevor_theme_static_ver'],
						true
					);
				},
				10,
				0
			);
		} else {
			add_editor_style( TREVOR_THEME_STATIC_URL . '/css/editor.css' );
		}

		if ( current_user_can( 'editor' ) && ! current_user_can( 'unfiltered_upload' ) ) {
			$contributor = get_role( 'editor' );
			$contributor->add_cap( 'unfiltered_upload' );
		}
	}

	/**
	 * Fires when scripts and styles are enqueued.
	 *
	 * @link https://developer.wordpress.org/reference/hooks/wp_enqueue_scripts/
	 */
	public static function wp_enqueue_scripts(): void {
		wp_enqueue_script( 'jquery-ui-autocomplete' );

		// FIXME: This should change only on deployments
		$GLOBALS['trevor_theme_static_ver'] = WP_DEBUG ? uniqid( \TrevorWP\Theme\VERSION . '-' ) : \TrevorWP\Theme\VERSION;

		# Theme's frontend JS package
		wp_enqueue_script(
			self::NAME_PREFIX . 'theme-frontend-main',
			TREVOR_THEME_STATIC_URL . '/js/frontend.js',
			array( 'jquery' ),
			$GLOBALS['trevor_theme_static_ver'],
			true
		);

		# Site Banners JS
		wp_enqueue_script(
			self::NAME_PREFIX . 'theme-site-banners',
			TREVOR_THEME_STATIC_URL . '/js/site-banners.js',
			array( 'jquery' ),
			$GLOBALS['trevor_theme_static_ver'],
			false
		);

		# Frontend style
		if ( TREVOR_ON_DEV ) {
			wp_enqueue_script(
				self::NAME_PREFIX . 'theme-frontend-css',
				TREVOR_THEME_STATIC_URL . '/css/frontend.js',
				array( StaticFiles::NAME_JS_RUNTIME ),
				$GLOBALS['trevor_theme_static_ver'],
				false
			);
		} else {
			wp_enqueue_style(
				self::NAME_PREFIX . 'theme-frontend',
				TREVOR_THEME_STATIC_URL . '/css/frontend.css',
				array(),
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
			array( 'jquery' ),
			$GLOBALS['trevor_theme_static_ver'],
			true
		);

		wp_localize_script(
			self::NAME_PREFIX . 'theme-admin-main',
			'script_vars',
			array( 'AJAXurl' => admin_url( 'admin-ajax.php' ) )
		);

		# Admin Style
		if ( TREVOR_ON_DEV ) {
			wp_enqueue_script(
				self::NAME_PREFIX . 'theme-admin-css',
				TREVOR_THEME_STATIC_URL . '/css/admin.js',
				array( StaticFiles::NAME_JS_RUNTIME ),
				$GLOBALS['trevor_theme_static_ver'],
				false
			);
		} else {
			wp_enqueue_style(
				self::NAME_PREFIX . 'theme-admin',
				TREVOR_THEME_STATIC_URL . '/css/admin.css',
				array(),
				$GLOBALS['trevor_theme_static_ver'],
				'all'
			);
		}

		# Apply Frontend style only on Block Editor
		if ( Is::block_editor() ) {
			if ( TREVOR_ON_DEV ) {
				wp_enqueue_script(
					self::NAME_PREFIX . 'theme-frontend-css',
					TREVOR_THEME_STATIC_URL . '/css/frontend.js',
					array( StaticFiles::NAME_JS_RUNTIME ),
					$GLOBALS['trevor_theme_static_ver'],
					false
				);
			} else {
				wp_enqueue_style(
					self::NAME_PREFIX . 'theme-frontend',
					TREVOR_THEME_STATIC_URL . '/css/frontend.css',
					array(),
					$GLOBALS['trevor_theme_static_ver'],
					'all'
				);
			}
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
		add_theme_support( 'editor-styles' );
		add_theme_support( 'admin-bar', array( 'callback' => '__return_false' ) );

		register_nav_menus(
			array(
				'header-organization' => '[Header] Organization',
				'header-support'      => '[Header] Support',
				'footer'              => 'Footer',
			)
		);
	}

	/**
	 * Prints scripts or data in the head tag on the front end.
	 *
	 * @link https://developer.wordpress.org/reference/hooks/wp_head/
	 */
	public static function wp_head(): void {
		if ( is_front_page() ) {
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
			<label for="edit-menu-item-subtitle-<?php echo esc_attr( $item_id ); ?>">
				Subtitle<br>
				<input type="text"
					id="edit-menu-item-subtitle-<?php echo esc_attr( $item_id ); ?>"
					class="widefat edit-menu-item-subtitle"
					name="menu-item-subtitle[<?php echo esc_attr( $item_id ); ?>]"
					value="<?php echo esc_attr( $val ); ?>">
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
		$title = "<div class='menu-link-text'><span class='title-wrap'>{$title}</span>";
		if ( 0 === $depth ) {
			$title .= '<span class="submenu-icon trevor-ti-caret-down"></span>';
		} elseif ( 1 === $depth ) {
			$subtitle = get_post_meta( $item->ID, Meta::KEY_MENU_ITEM_SUBTITLE, true );

			if ( ! empty( $subtitle ) ) {
				$title .= '<div class="subtitle">' . esc_html( $subtitle ) . '</div>';
			}
		}

		$title .= '</div>'; // Closing .menu-link-text

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
		add_action(
			'wp_head',
			function () {
				?>
			<script>document.documentElement.classList.add('admin-bar');</script>
				<?php
			},
			PHP_INT_MAX,
			0
		);
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
			} elseif ( ! empty( $wp_query->get( Customizer\Search::QV_SEARCH ) ) ) {
				$template = locate_template( 'search.php', false );
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
		if ( empty( get_query_var( CPT\RC\RC_Object::QV_GET_HELP ) ) ) {
			?>
			<aside class="floating-crisis-btn-wrap">
				<a class="btn floating-crisis-btn" href="<?php echo esc_attr( home_url( \TrevorWP\CPT\RC\RC_Object::PERMALINK_GET_HELP ) ); ?>">
					Reach a Counselor</a>
			</aside>
			<?php
		}

		// TODO:
		// Remove these modal renders once integrated through the Advanced_Link field

		// Donation Modal
		if ( Options_Page\Donation_Modal::will_render_in( get_the_ID() ) ) {
			echo ( new \TrevorWP\Theme\Helper\DonationModal(
				Options_Page\Donation_Modal::render( array( 'dedication' => true ) ),
				array(
					'target'     => '.js-donation-modal',
					'dedication' => true,
				)
			) )->render();
		}

		// Quick Exit Modal
		echo ( new \TrevorWP\Theme\Helper\Modal(
			Options_Page\Quick_Exit::render(),
			array(
				'target' => '.js-quick-exit-modal',
				'id'     => 'js-quick-exit-modal',
				'class'  => array( 'quick-exit-modal', 'js-quick-exit-modal' ),
			)
		) )->render();
	}

	/**
	 * Fires after the query variable object is created, but before the actual query is run.
	 *
	 * @param \WP_Query $query
	 *
	 * @link https://developer.wordpress.org/reference/hooks/pre_get_posts/
	 */
	public static function pre_get_posts( \WP_Query $query ): void {
		$updates = array();

		if ( $query->is_main_query() ) {
			if ( ! $query->is_admin ) {
				if ( $query->is_post_type_archive || $query->is_home ) {
					$qo = $query->get_queried_object();
					$pt = ( empty( $qo ) || empty( $qo->name ) ) ? 'post' : $qo->name;

					# Pagination
					$per_page = (int) A_Post_Type::get_option_for( $pt, A_Post_Type::FIELD_ARCHIVE_PP );
					if ( $per_page && empty( $query->get( RC_Object::QV_RESOURCES_LP ) ) ) {
						$updates[ 'post' === $pt ? 'posts_per_page' : 'posts_per_archive_page' ] = $per_page;
					}

					# Init Sorter
					if ( A_Post_Type::get_option_for( $pt, A_Post_Type::FIELD_SORTER_ACTIVE ) ) {
						new Sorter( $query, Sorter::get_options_for_date(), 'new-old' );
					}
				}
			}
		}

		# Apply updates
		if ( ! empty( $updates ) ) {
			foreach ( $updates as $key => $val ) {
				$query->set( $key, $val );
			}
		}
	}

	/**
	 * Fires when preparing to serve a REST API request.
	 */
	public static function rest_api_init(): void {
		register_rest_route(
			'trevor/v1',
			'/site-banners',
			array(
				'methods'  => 'GET',
				'callback' => array( self::class, 'ajax_site_banners' ),
			)
		);

		// Cards REST API
		register_rest_route(
			'trevor/v1',
			'/post-cards',
			array(
				'methods'  => 'GET',
				'callback' => array( 'TrevorWP\Theme\ACF\Field_Group\Post_Grid', 'ajax_post_cards' ),
			)
		);

		// Article River Entries API
		register_rest_route(
			'trevor/v1',
			'/article-river-entries',
			array(
				'methods'  => 'GET',
				'callback' => array( 'TrevorWP\Theme\ACF\Field_Group\Article_River', 'ajax_entries' ),
			)
		);
	}

	/**
	 * Check if custom site banner is active.
	 */
	public static function is_custom_site_banner_entry_active( array $entry, string $current_date ): bool {
		$active     = $entry[ Site_Banners::FIELD_CUSTOM_ENTRY_ACTIVE ];
		$start_date = $entry[ Site_Banners::FIELD_CUSTOM_ENTRY_START_DATE ];
		$end_date   = $entry[ Site_Banners::FIELD_CUSTOM_ENTRY_END_DATE ];

		if ( $active && empty( $start_date ) && empty( $end_date ) ) {
			return true;
		}

		if ( $active && ! empty( $start_date ) && ! empty( $end_date ) ) {
			if ( $current_date >= $start_date && $current_date <= $end_date ) {
				return true;
			}
		}

		if ( $active && ! empty( $start_date ) && empty( $end_date ) ) {
			if ( $current_date >= $start_date ) {
				return true;
			}
		}

		if ( $active && empty( $start_date ) && ! empty( $end_date ) ) {
			if ( $current_date <= $end_date ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Site banners ajax handler.
	 */
	public static function ajax_site_banners() {
		$banners = array();

		$current_date = current_datetime();
		$current_date = wp_date( 'M j, Y H:i:s', $current_date->date, $current_date->timezone );

		# Custom banners
		$custom_entries = Site_Banners::get_option( Site_Banners::FIELD_CUSTOM_ENTRIES );
		if ( ! empty( $custom_entries ) ) {
			foreach ( $custom_entries as $entry ) {
				if ( static::is_custom_site_banner_entry_active( $entry, $current_date ) ) {
					$banners[] = array(
						'title' => $entry[ Site_Banners::FIELD_CUSTOM_ENTRY_TITLE ],
						'desc'  => $entry[ Site_Banners::FIELD_CUSTOM_ENTRY_MESSAGE ],
						'type'  => 'custom',
					);
				}
			}
		}

		# Long waiting banner
		$is_long_wait = get_option( Main::OPTION_KEY_COUNSELOR_LONG_WAIT, true );
		$force_show   = Site_Banners::get_option( Site_Banners::FIELD_LONG_WAIT_FORCE_SHOW );

		if ( $force_show ) {
			$is_long_wait = true;
		}

		if ( $is_long_wait && empty( $banners ) ) {
			$banners[] = array(
				'title' => Site_Banners::get_option( Site_Banners::FIELD_LONG_WAIT_TITLE ),
				'desc'  => Site_Banners::get_option( Site_Banners::FIELD_LONG_WAIT_DESCRIPTION ),
				'type'  => 'long_wait',
			);
		}

		# Add their signatures
		foreach ( $banners as &$banner ) {
			$banner['id'] = substr( \TrevorWP\Util\Tools::get_obj_signature( $banner ), 3, 6 );
		}

		$resp = new \WP_REST_Response(
			array(
				'success' => true,
				'banners' => $banners,
			),
			200
		);

		// Cache timeouts
		$browser_to = 5 * 60; # 5 min
		$proxy_to   = 10; # 10 sec
		$resp->header( 'Cache-Control', sprintf( 'public, max-age=%d, s-maxage=%d', $browser_to, $proxy_to ) );

		return $resp;
	}

	/**
	 * Filters the list of CSS body class names for the current post or page.
	 *
	 * @param array $classes
	 *
	 * @return array
	 *
	 * @link https://developer.wordpress.org/reference/hooks/body_class/
	 */
	public static function body_class( array $classes ): array {
		// BG Color
		if ( Is::rc() ) {
			$classes['general_bg'] = 'bg-indigo';
		} else {
			$classes['general_bg'] = 'bg-teal-dark';
		}

		if ( is_404() ) {
			$classes['general_bg'] = 'bg-white';
		}

		$hero_type = Page_Header::get_hero_type();

		// If RC, Trevorspace or Crisis pages, set text-color to indigo.
		if (
			Is::rc() ||
			is_404() ||
			$hero_type == 'support_crisis_services' ||
			$hero_type == 'support_trevorspace'
		) {
			$classes['general_txt_clr'] = 'text-indigo';
		} else {
			$classes['general_txt_clr'] = 'text-teal-dark';
		}

		return $classes;
	}

	/**
	 * Add SVG type to Uploads
	 *
	 * @param array $file_types
	 *
	 * @return array $file_types
	 *
	 * @link https://developer.wordpress.org/reference/hooks/upload_mimes/
	 */
	public static function upload_mimes( $file_types ) {
		$new_filetypes        = array();
		$new_filetypes['svg'] = 'image/svg+xml';
		$file_types           = array_merge( $file_types, $new_filetypes );
		return $file_types;
	}

	/**
	 * Change the WPSEO Title value to Page Hero Title
	 *
	 * @param string $title
	 *
	 * @return string $title
	 *
	 * @link http://hookr.io/filters/wpseo_title/
	 */
	public static function custom_seo_title( $title ) {
		if ( is_page() ) {
			$hero_title = Page_Header::get_val( Page_Header::FIELD_TITLE );

			if ( ! empty( $hero_title ) ) {
				$title = str_replace( get_the_title(), Page_Header::get_val( Page_Header::FIELD_TITLE ), $title );
			}
		}

		if ( is_archive() ) {
			$title = str_replace( 'Archives', '', $title );
			$title = str_replace( 'Archive', '', $title );
		}

		return $title;
	}

	/**
	 * Converts some of the dataLayer array values into comma-separated string.
	 *
	 * @param array $dataLayer
	 *
	 * @return array
	 *
	 */
	public static function datalayer_data_update( $dataLayer ) {
		$dataLayerKeys = array( 'pageCategory', 'pageAttributes', 'pagePostTerms' );

		foreach ( $dataLayerKeys as $key ) {
			if ( array_key_exists( $key, $dataLayer ) ) {
				$values = $dataLayer[ $key ];

				if ( $key == 'pagePostTerms' ) {
					foreach ( $values as $subKey => $value ) {
						$dataLayer[ $key ][ $subKey ] = implode( ',', $value );
					}
				} else {
					$dataLayer[ $key ] = implode( ',', $values );
				}
			}
		}

		return $dataLayer;
	}

	/**
	 * Adds the Trevor chat class to menu item link
	 * if the chat-link option is checked.
	 *
	 * @param array $atts - anchor tag attributes
	 * @param object $item - Menu item data.
	 *
	 * @return array
	 *
	 */
	public static function nav_menu_link_attributes( $atts, $item ) {
		$val          = new Field_Val_Getter( Chat_Link_Option::class, $item );
		$is_chat_link = $val->get( Chat_Link_Option::FIELD_CHAT_OPTION );

		if ( $is_chat_link ) {
			$atts['class'] = 'tcb-link';
		}

		return $atts;
	}
}
