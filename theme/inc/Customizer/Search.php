<?php namespace TrevorWP\Theme\Customizer;

use TrevorWP\CPT;
use TrevorWP\Main;
use TrevorWP\Theme\Util\Is;
use TrevorWP\Util\Tools;
use TrevorWP\CPT\RC\RC_Object;
use \TrevorWP\Ranks;
use TrevorWP\Theme\ACF\Field_Group\Page_Header;
use TrevorWP\Theme\ACF\Options_Page\Search as OP_Search;
use TrevorWP\Theme\ACF\Util\Field_Val_Getter;
use TrevorWP\Theme\Helper\Thumbnail;
use TrevorWP\Theme\Helper\Taxonomy;

class Search extends Abstract_Customizer {
	/* Query Values */
	const QV_SEARCH       = Main::QV_PREFIX . 'search';
	const QV_SEARCH_SCOPE = Main::QV_PREFIX . 'search_scope';

	/* Panel */
	const PANEL_ID = self::ID_PREFIX . 'search';

	/* Sections */
	const SECTION_GENERAL = self::PANEL_ID . '_general';
	const SECTION_HOME    = self::PANEL_ID . '_home';

	/* Sections */
	const SCOPES = array(
		'resources' => array(
			'name'      => 'Resources',
			'post_type' => array(
				CPT\RC\Article::POST_TYPE,
				CPT\RC\External::POST_TYPE,
				CPT\RC\Guide::POST_TYPE,
			),
		),
		'pages'     => array(
			'name'      => 'Pages',
			'post_type' => array(
				'page',
			),
		),
		'blogs'     => array(
			'name'      => 'Blogs',
			'post_type' => array(
				CPT\Post::POST_TYPE,
			),
		),
	);

	/* Settings */
	/* * General */
	const SETTING_GENERAL_SLUG              = self::SECTION_GENERAL . '_slug';
	const SETTING_GENERAL_PER_PAGE          = self::SECTION_GENERAL . '_per_page';
	const SETTING_GENERAL_PAGE_TITLE        = self::SECTION_GENERAL . '_page_title';
	const SETTING_GENERAL_INPUT_PLACEHOLDER = self::SECTION_GENERAL . '_input_placeholder';
	const SETTING_HOME_CAROUSEL_TITLE       = self::SECTION_HOME . '_carousel_title';
	const SETTING_HOME_CAROUSEL_DESC        = self::SECTION_HOME . '_carousel_desc';

	const DEFAULTS = array(
		self::SETTING_GENERAL_SLUG              => 'search',
		self::SETTING_GENERAL_PER_PAGE          => 20,
		self::SETTING_GENERAL_PAGE_TITLE        => 'Search The Trevor Project',
		self::SETTING_GENERAL_INPUT_PLACEHOLDER => 'What are you looking for?',
		self::SETTING_HOME_CAROUSEL_TITLE       => 'The Latest',
		self::SETTING_HOME_CAROUSEL_DESC        => 'Explore the latest from The Trevor Project.',
	);

	/** @inheritDoc */
	protected function _register_panels(): void {
		$this->_manager->add_panel( self::PANEL_ID, array( 'title' => 'Search' ) );
	}

	/** @inheritDoc */
	protected function _register_sections(): void {
		# General
		$this->_manager->add_section(
			self::SECTION_GENERAL,
			array(
				'panel' => self::PANEL_ID,
				'title' => 'General',
			)
		);

		# Home
		$this->_manager->add_section(
			self::SECTION_HOME,
			array(
				'panel' => self::PANEL_ID,
				'title' => 'Home',
			)
		);
	}

	/** @inheritDoc */
	protected function _register_controls(): void {
		# General
		$this->_manager->add_control(
			self::SETTING_GENERAL_PAGE_TITLE,
			array(
				'setting' => self::SETTING_GENERAL_PAGE_TITLE,
				'section' => self::SECTION_GENERAL,
				'label'   => 'Page Title',
				'type'    => 'text',
			)
		);

		$this->_manager->add_control(
			self::SETTING_GENERAL_PER_PAGE,
			array(
				'setting' => self::SETTING_GENERAL_PER_PAGE,
				'section' => self::SECTION_GENERAL,
				'label'   => 'Posts Per Page',
				'type'    => 'number',
			)
		);

		$this->_manager->add_control(
			self::SETTING_GENERAL_PAGE_TITLE,
			array(
				'setting' => self::SETTING_GENERAL_PAGE_TITLE,
				'section' => self::SECTION_GENERAL,
				'label'   => 'Page Title',
				'type'    => 'text',
			)
		);

		$this->_manager->add_control(
			self::SETTING_GENERAL_INPUT_PLACEHOLDER,
			array(
				'setting' => self::SETTING_GENERAL_INPUT_PLACEHOLDER,
				'section' => self::SECTION_GENERAL,
				'label'   => 'Input Placeholder',
				'type'    => 'text',
			)
		);

		# Home
		$this->_manager->add_control(
			self::SETTING_HOME_CAROUSEL_TITLE,
			array(
				'setting' => self::SETTING_HOME_CAROUSEL_TITLE,
				'section' => self::SECTION_HOME,
				'label'   => 'Carousel Title',
				'type'    => 'text',
			)
		);

		$this->_manager->add_control(
			self::SETTING_HOME_CAROUSEL_DESC,
			array(
				'setting' => self::SETTING_HOME_CAROUSEL_DESC,
				'section' => self::SECTION_HOME,
				'label'   => 'Carousel Description',
				'type'    => 'textarea',
			)
		);
	}

	/**
	 * @param string|null $search_term
	 * @param string $scope
	 *
	 * @return string
	 */
	public static function get_permalink( string $search_term = null, string $scope = 'all' ): string {
		$permalink = home_url( trailingslashit( static::get_val( static::SETTING_GENERAL_SLUG ) ) );

		if ( 'all' !== $scope ) {
			$permalink .= $scope . '/';
		}

		if ( ! empty( $search_term ) ) {
			$permalink = add_query_arg( 's', $search_term, $permalink );
		}

		return $permalink;
	}

	/**
	 * Fires after WordPress has finished loading but before any headers are sent.
	 *
	 * @link https://developer.wordpress.org/reference/hooks/init/
	 * @see Hooks::register_all()
	 */
	public static function init_all(): void {
		add_action( 'init', array( static::class, 'handle_init' ) );
		add_filter( 'query_vars', array( self::class, 'query_vars' ), 10, 1 );
		add_filter( 'body_class', array( self::class, 'body_class' ), 10, 1 );
	}

	/**
	 * @link https://developer.wordpress.org/reference/hooks/init/
	 */
	public static function handle_init(): void {
		global $wp_rewrite;
		$main_page_query = 'index.php?' . http_build_query(
			array(
				self::QV_SEARCH => 1,
			)
		);

		$base = trailingslashit( static::get_val( static::SETTING_GENERAL_SLUG ) );

		# First page
		add_rewrite_rule( $base . '?$', $main_page_query, 'top' );

		# First page, scoped
		add_rewrite_rule( ( $scoped_base = $base . '(\w+)/' ) . '?$', $main_page_query . '&' . self::QV_SEARCH_SCOPE . '=$matches[1]', 'top' );

		# Paged
		add_rewrite_rule(
			"{$base}{$wp_rewrite->pagination_base}/?([0-9]{1,})/?$",
			$main_page_query . '&paged=$matches[1]',
			'top'
		);
		# Paged, scoped
		add_rewrite_rule(
			"{$scoped_base}{$wp_rewrite->pagination_base}/?([0-9]{1,})/?$",
			$main_page_query . '&' . self::QV_SEARCH_SCOPE . '=$matches[1]&paged=$matches[2]',
			'top'
		);
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
				self::QV_SEARCH,
				self::QV_SEARCH_SCOPE,
			)
		);
	}

	/**
	 * @param \WP_Post $post
	 *
	 * @return string
	 */
	public static function render_post( \WP_Post $post ): ?string {
		if ( CPT\RC\Post::POST_TYPE === $post->post_type ) {
			return '';
		}

		$_class = array(
			'pb-10',
			'lg:pb-px50',
			'border-b',
			'border-b-1',
			'border-indigo',
			'border-opacity-20',
		);

		$title_top = null;
		$title_btm = $post->post_title;
		$desc      = $post->post_excerpt;

		switch ( $post->post_type ) {
			case CPT\RC\External::POST_TYPE:
				$title_top = 'Resource';
				$icon_cls  = 'trevor-ti-link-out';
				break;
			case CPT\RC\Guide::POST_TYPE:
				$title_top = 'Guide';
				break;
			case CPT\Donate\Fundraiser_Stories::POST_TYPE:
				$title_top = 'Fundraiser Story';
				break;
			case CPT\RC\Article::POST_TYPE:
				$categories = Ranks\Taxonomy::get_object_terms_ordered( $post, RC_Object::TAXONOMY_CATEGORY );
				$first_cat  = empty( $categories ) ? null : reset( $categories );
				$title_top  = $first_cat ? $first_cat->name : null;
				break;
			case CPT\RC\Post::POST_TYPE:
				$title_top = 'Blog';

				break;
			case CPT\Post::POST_TYPE:
				$title_top = 'Blog';
				break;
			case 'page':
				$val       = new Field_Val_Getter( Page_Header::class, $post );
				$title_top = ! empty( $val->get( Page_Header::FIELD_TITLE_TOP ) ) ? $val->get( Page_Header::FIELD_TITLE_TOP ) : static::get_post_title( $post->post_title );
				$title_btm = ! empty( $val->get( Page_Header::FIELD_TITLE ) ) ? $val->get( Page_Header::FIELD_TITLE ) : $title_btm;
				$desc      = ! empty( $val->get( Page_Header::FIELD_DESC ) ) ? $val->get( Page_Header::FIELD_DESC ) : $desc;

				$yoast_title = static::get_yoast_title( $post );
				$yoast_desc  = static::get_yoast_description( $post );

				$title_btm = ! empty( $yoast_title ) ? $yoast_title : $title_btm;
				$desc      = ! empty( $yoast_desc ) ? $yoast_desc : $desc;

				break;
		}

		# Thumbnail variants
		$thumb_variants   = array( self::_get_thumb_var( Thumbnail::TYPE_VERTICAL ) );
		$thumb_variants[] = self::_get_thumb_var( Thumbnail::TYPE_HORIZONTAL );
		$thumb_variants[] = self::_get_thumb_var( Thumbnail::TYPE_SQUARE );
		$thumb            = Thumbnail::post( $post, ...$thumb_variants );
		$has_thumbnail    = ! empty( $thumb );

		if ( ! $has_thumbnail ) {
			$_class[] = 'no-thumbnail';
		}

		# Tags
		$tags = Taxonomy::get_post_tags_distinctive( $post, array( 'filter_count_1' => false ) );

		ob_start(); ?>
		<article class="<?php echo esc_attr( implode( ' ', get_post_class( $_class, $post->ID ) ) ); ?> flex flex-row flex-wrap search-result-item text-indigo">
			<?php if ( $has_thumbnail ) { ?>
				<div class="thumbnail-wrap" data-aspectRatio="1:1">
					<a href="<?php echo get_the_permalink( $post ); ?>">
						<?php echo $thumb; ?>
					</a>
				</div>
			<?php } ?>
			<div class="text-content">
				<div class="eyebrow text-indigo uppercase mb-px23">
					<p>
						<strong class="pr-px12"><?php echo wp_strip_all_tags( $title_top ); ?></strong>
						<?php if ( in_array( $post->post_type, array( CPT\RC\Post::POST_TYPE, CPT\Post::POST_TYPE ) ) ) { ?>
							<time class="pl-px12" datetime="<?php echo $post->post_date; ?>"><?php echo date( 'F j, Y', strtotime( $post->post_date ) ); ?></time>
						<?php } ?>
					</p>
				</div>

				<div class="relative">
					<?php if ( ! empty( $icon_cls ) ) { ?>
						<div class="icon-wrap"><i class="<?php echo esc_attr( $icon_cls ); ?>"></i></div>
					<?php } ?>
					<h3 class="w-full text-px24 leading-px30 lg:text-px30 lg:leading-px40">
						<a href="<?php echo get_the_permalink( $post ); ?>"><?php echo wp_strip_all_tags( $title_btm ); ?></a>
					</h3>
				</div>

				<?php if ( ! $has_thumbnail ) { ?>
					<p><?php echo $desc; ?></p>
				<?php } ?>

				<?php if ( ! empty( $tags ) ) { ?>
					<div class="tags-box">
						<?php foreach ( $tags as $tag ) { ?>
							<a href="<?php echo esc_url( RC_Object::get_search_url( $tag->name ) ); ?>"
									class="tag-box"><?php echo $tag->name; ?></a>
						<?php } ?>
					</div>
				<?php } ?>
			</div>
		</article>
		<?php
		return ob_get_clean();
	}

	/**
	 * @return \WP_Post|null
	 */
	public static function get_glossary_item(): ?\WP_Post {
		if ( ! empty( get_query_var( self::QV_SEARCH_SCOPE ) ) ) {
			return null;
		}

		$q = new \WP_Query(
			array(
				's'              => get_search_query( false ),
				'post_type'      => CPT\RC\Glossary::POST_TYPE,
				'posts_per_page' => 1,
				'paged'          => get_query_var( 'paged' ),
			)
		);

		return $q->have_posts()
				? reset( $q->posts )
				: null;
	}

	public static function get_posts_by_tag( $search ) {
		if ( empty( get_search_query( false ) ) ) {
			return array();
		}

		$q = new \WP_Query(
			array(
				'post_type'      => 'post',
				'posts_per_page' => -1,
				'post_status'    => 'publish',
				'tax_query'      => array(
					array(
						'taxonomy' => 'post_tag',
						'field'    => 'name',
						'terms'    => array( $search ),
					),
				),
			)
		);

		return $q->have_posts()
				? $q->posts
				: array();
	}

	public static function get_rc_posts_by_tag( $search ) {
		if ( empty( get_search_query( false ) ) ) {
			return array();
		}

		$q = new \WP_Query(
			array(
				'post_type'      => array(
					CPT\RC\Article::POST_TYPE,
					CPT\RC\External::POST_TYPE,
					CPT\RC\Guide::POST_TYPE,
				),
				'posts_per_page' => -1,
				'post_status'    => 'publish',
				'tax_query'      => array(
					array(
						'taxonomy' => 'trevor_rc__tag',
						'field'    => 'name',
						'terms'    => array( $search ),
					),
				),
			)
		);

		return $q->have_posts()
				? $q->posts
				: array();
	}

	public static function get_pages( $search ) {
		$q = new \WP_Query(
			array(
				's'              => str_replace( ' ', '+', $search ),
				'post_type'      => 'page',
				'posts_per_page' => -1,
				'post_status'    => 'publish',
			)
		);

		return $q->have_posts()
				? $q->posts
				: array();
	}

	public static function get_all_posts( $search, $post_types = array() ) {
		if ( empty( $post_types ) ) {
			$post_types = array(
				CPT\RC\Article::POST_TYPE,
				CPT\RC\External::POST_TYPE,
				CPT\RC\Guide::POST_TYPE,
				CPT\Post::POST_TYPE,
			);
		}

		$q = new \WP_Query(
			array(
				's'              => str_replace( ' ', '+', $search ),
				'post_type'      => $post_types,
				'posts_per_page' => -1,
				'post_status'    => 'publish',
			)
		);

		return $q->have_posts()
				? $q->posts
				: array();
	}

	public static function filter_search() {
		$posts         = array();
		$search        = strtolower( get_search_query( false ) );
		$current_scope = self::get_current_scope();
		$post_types    = self::get_scope_post_types( $current_scope );

		switch ( $current_scope ) {
			case 'all':
				$posts = array_merge( self::get_pages( $search ), self::get_rc_posts_by_tag( $search ), self::get_posts_by_tag( $search ), self::get_all_posts( $search ) );
				break;
			case 'resources':
				$posts = array_merge( self::get_rc_posts_by_tag( $search ), self::get_all_posts( $search, $post_types ) );
				break;
			case 'blogs':
				$posts = array_merge( self::get_posts_by_tag( $search ), self::get_all_posts( $search, $post_types ) );
				break;
			default:
				$posts = self::get_all_posts( $search, $post_types );
				break;
		}

		// Remove duplicate posts
		$temp_posts = array_unique( array_column( $posts, 'ID' ) );
		$posts      = array_intersect_key( $posts, $temp_posts );

		return $posts;
	}

	public static function paginate_search_results() {
		$search_results = self::filter_search();

		$page   = ! empty( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
		$search = OP_Search::get_search();

		$total = count( $search_results );
		$limit = $search['posts_per_page'];

		$totalPages = ceil( $total / $limit );

		$page = max( $page, 1 );
		$page = min( $page, $totalPages );

		$offset = ( $page - 1 ) * $limit;

		if ( $offset < 0 ) {
			$offset = 0;
		}

		$posts = array_slice( $search_results, $offset, $limit );

		return array(
			'posts'      => $posts,
			'pagination' => array(
				'total'       => $total,
				'total_pages' => $totalPages,
				'limit'       => $limit,
				'page'        => $page,
			),
		);
	}

	/**
	 * @return string
	 */
	public static function render_scopes(): string {
		$facets  = \SolrPower_WP_Query::get_instance()->facets;
		$current = self::get_current_scope();
		$all_pts = self::get_scope_post_types( 'all' );

		$all = array(
			'all' => array(
				'name'      => 'All Results',
				'post_type' => $all_pts,
			),
		) + self::SCOPES;

		//todo:  check from facets and hide if empty

		ob_start();
		?>
		<div class="scope flex flex-wrap flex-row items-start pb-10 pt-12 md:pt-16 lg:pt-14 -mb-px14 text-indigo">
			<?php foreach ( $all as $id => $detail ) { ?>
				<div>
					<a href="<?php echo esc_url( self::get_permalink( get_search_query( false ), $id == 'all' ? 'all' : $id ) ); ?>"
					  class="scope-link <?php echo $id == $current ? 'font-bold current' : ''; ?> <?php echo $id == 'all' ? 'scope-link--first' : ''; ?>">
						<?php echo $detail['name']; ?>
					</a>
				</div>
			<?php } ?>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * @param string $scope
	 *
	 * @return array
	 */
	public static function get_scope_post_types( string $scope = 'all' ): array {
		if ( $scope == 'all' ) {
			return array_unique( call_user_func_array( 'array_merge', Tools::pluck( self::SCOPES, 'post_type' ) ) );
		}

		return self::SCOPES[ $scope ]['post_type'];
	}

	/**
	 * @return string
	 */
	public static function get_current_scope(): string {
		$val = get_query_var( self::QV_SEARCH_SCOPE );

		return array_key_exists( $val, self::SCOPES )
				? $val
				: 'all';
	}

	/**
	 * Filters the list of CSS body class names for the current post or page.
	 *
	 * @link https://developer.wordpress.org/reference/hooks/body_class/
	 */
	public static function body_class( array $classes ): array {
		if ( ( is_search() || get_query_var( self::QV_SEARCH ) ) && ! Is::rc() ) {
			$classes[] = 'is-site-search';
		}

		return $classes;
	}

	/**
	 * @param string $type
	 *
	 * @return array
	 */
	protected static function _get_thumb_var( string $type ): array {
		return Thumbnail::variant(
			Thumbnail::SCREEN_SM,
			$type,
			Thumbnail::SIZE_MD,
			array( 'class' => 'post-header-bg' )
		);
	}

	public static function get_post_title( $post_title ) {
		if ( false !== strpos( $post_title, '—' ) ) {
			$pos        = strpos( $post_title, '—' ) + 1;
			$post_title = mb_substr( $post_title, $pos );
		}

		return $post_title;
	}

	public static function get_yoast_title( \WP_Post $post ) {
		$yoast_title = get_post_meta( $post->ID, '_yoast_wpseo_title', true );
		if ( empty( $yoast_title ) ) {
			$wpseo_titles = get_option( 'wpseo_titles', array() );
			$yoast_title  = isset( $wpseo_titles[ 'title-' . $post->post_type ] ) ? $wpseo_titles[ 'title-' . $post->post_type ] : get_the_title();
		}

		$yoast_title = str_replace( ' - ' . get_bloginfo( 'name' ), '', wpseo_replace_vars( $yoast_title, $post ) );

		return $yoast_title;
	}

	public static function get_yoast_description( \WP_Post $post ): string {
		$yoast_post_description = get_post_meta( $post->ID, '_yoast_wpseo_metadesc', true );
		if ( empty( $yoast_post_description ) ) {
			$wpseo_titles           = get_option( 'wpseo_titles', array() );
			$yoast_post_description = isset( $wpseo_titles[ 'metadesc-' . $post->post_type ] ) ? $wpseo_titles[ 'metadesc-' . $post->post_type ] : '';
		}

		return wpseo_replace_vars( $yoast_post_description, $post );
	}
}
