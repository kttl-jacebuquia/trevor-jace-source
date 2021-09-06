<?php namespace TrevorWP\Theme\Customizer;

use TrevorWP\CPT;
use TrevorWP\Main;
use TrevorWP\Theme\Util\Is;
use TrevorWP\Util\Tools;
use TrevorWP\CPT\RC\RC_Object;
use \TrevorWP\Ranks;
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
		'blogs'     => array(
			'name'      => 'Blogs',
			'post_type' => array(
				// CPT\Post::POST_TYPE,
				CPT\RC\Post::POST_TYPE,
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

		if ( $scope != 'all' ) {
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
		add_action( 'pre_get_posts', array( static::class, 'pre_get_posts' ) );
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
	 * @param \WP_Query $query
	 */
	public static function pre_get_posts( \WP_Query $query ): void {
		if ( $query->is_main_query() ) {
			if ( ! Is::rc() && $query->is_search() ) {
				$query->set( 'post_type', self::get_scope_post_types( self::get_current_scope() ) );
			}
		}
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
		$_class = array(
			'pb-10',
			'lg:pb-px50',
			'border-b',
			'border-b-1',
			'border-indigo',
			'border-opacity-20',
		);

		$title_top = $desc = $img = null;

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
						<strong class="pr-px12"><?php echo $title_top; ?></strong>
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
						<a href="<?php echo get_the_permalink( $post ); ?>"><?php echo $post->post_title; ?></a>
					</h3>
				</div>

				<?php if ( ! $has_thumbnail ) { ?>
					<p><?php echo $post->post_excerpt; ?></p>
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
		<div class="scope flex flex-row mb-10 mt-12 md:mt-16 lg:mt-14 text-indigo">
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
}
