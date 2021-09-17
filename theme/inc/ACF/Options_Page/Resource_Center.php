<?php namespace TrevorWP\Theme\ACF\Options_Page;

use TrevorWP\CPT\RC\Guide;
use TrevorWP\CPT\RC\RC_Object;
use TrevorWP\Meta\Post;
use TrevorWP\Theme\Helper;

class Resource_Center extends A_Options_Page {
	const FIELD_PAGE_SLUG           = 'rc_page_slug';
	const FIELD_EYEBROW             = 'eyebrow';
	const FIELD_HEADLINE            = 'headline';
	const FIELD_SEARCH_PLACEHOLDER  = 'search_placeholder';
	const FIELD_DESCRIPTION         = 'description';
	const FIELD_FEATURED_TOPICS     = 'featured_topics';
	const FIELD_TRENDING            = 'trending';
	const FIELD_GUIDES              = 'guide';
	const FIELD_GLOSSARY_IMAGE      = 'glossary_image';
	const FIELD_CARD_NUM            = 'card_num';
	const PAGINATION_TAX_ARCHIVE    = 'pagination_tax_archive';
	const PAGINATION_SEARCH_RESULTS = 'pagination_search_results';

	public static $used_post_ids = array();
	public static $category_num  = 4;

	/** @inheritDoc */
	protected static function prepare_fields(): array {
		$page_slug                 = static::gen_field_key( static::FIELD_PAGE_SLUG );
		$eyebrow                   = static::gen_field_key( static::FIELD_EYEBROW );
		$headline                  = static::gen_field_key( static::FIELD_HEADLINE );
		$search_placeholder        = static::gen_field_key( static::FIELD_SEARCH_PLACEHOLDER );
		$description               = static::gen_field_key( static::FIELD_DESCRIPTION );
		$featured_topics           = static::gen_field_key( static::FIELD_FEATURED_TOPICS );
		$trending                  = static::gen_field_key( static::FIELD_TRENDING );
		$guides                    = static::gen_field_key( static::FIELD_GUIDES );
		$glossary_image            = static::gen_field_key( static::FIELD_GLOSSARY_IMAGE );
		$card_num                  = static::gen_field_key( static::FIELD_CARD_NUM );
		$pagination_tax_archive    = static::gen_field_key( static::PAGINATION_TAX_ARCHIVE );
		$pagination_search_results = static::gen_field_key( static::PAGINATION_SEARCH_RESULTS );

		return array_merge(
			array(
				static::FIELD_PAGE_SLUG => array(
					'key'     => $page_slug,
					'name'    => static::FIELD_PAGE_SLUG,
					'label'   => 'Page Slug',
					'type'    => 'message',
					'message' => '/' . RC_Object::PERMALINK_BASE,
				),
			),
			static::_gen_tab_field( 'General' ),
			array(
				static::FIELD_CARD_NUM => array(
					'key'           => $card_num,
					'name'          => static::FIELD_CARD_NUM,
					'label'         => 'Number of Cards',
					'type'          => 'number',
					'required'      => 1,
					'default_value' => 4,
				),
			),
			static::_gen_tab_field( 'Hero' ),
			array(
				static::FIELD_EYEBROW            => array(
					'key'      => $eyebrow,
					'name'     => static::FIELD_EYEBROW,
					'label'    => 'Eyebrow',
					'type'     => 'text',
					'required' => 1,
				),
				static::FIELD_HEADLINE           => array(
					'key'      => $headline,
					'name'     => static::FIELD_HEADLINE,
					'label'    => 'Headline',
					'type'     => 'text',
					'required' => 1,
				),
				static::FIELD_SEARCH_PLACEHOLDER => array(
					'key'           => $search_placeholder,
					'name'          => static::FIELD_SEARCH_PLACEHOLDER,
					'label'         => 'Search Placeholder',
					'type'          => 'text',
					'required'      => 1,
					'default_value' => 'What do you want to learn about?',
				),
				static::FIELD_DESCRIPTION        => array(
					'key'      => $description,
					'name'     => static::FIELD_DESCRIPTION,
					'label'    => 'Description',
					'type'     => 'textarea',
					'required' => 1,
				),
				static::FIELD_FEATURED_TOPICS    => array(
					'key'           => $featured_topics,
					'name'          => static::FIELD_FEATURED_TOPICS,
					'label'         => 'Featured Topics',
					'type'          => 'taxonomy',
					'required'      => 1,
					'taxonomy'      => RC_Object::TAXONOMY_CATEGORY,
					'field_type'    => 'multi_select',
					'allow_null'    => 0,
					'add_term'      => 0,
					'save_terms'    => 0,
					'load_terms'    => 0,
					'return_format' => 'object',
					'multiple'      => 0,
				),
			),
			static::_gen_tab_field( 'Trending' ),
			array(
				static::FIELD_TRENDING => array(
					'key'           => $trending,
					'name'          => static::FIELD_TRENDING,
					'label'         => 'Trending',
					'type'          => 'relationship',
					'required'      => 1,
					'post_type'     => array_merge( array( 'post' ), RC_Object::$PUBLIC_POST_TYPES ),
					'taxonomy'      => '',
					'filters'       => array(
						0 => 'search',
						1 => 'post_type',
					),
					'elements'      => '',
					'min'           => '',
					'max'           => '',
					'return_format' => 'id',
				),
			),
			static::_gen_tab_field( 'Guides' ),
			array(
				static::FIELD_GUIDES => array(
					'key'           => $guides,
					'name'          => static::FIELD_GUIDES,
					'label'         => 'Guide',
					'type'          => 'post_object',
					'required'      => 1,
					'post_type'     => array(
						0 => Guide::POST_TYPE,
					),
					'taxonomy'      => '',
					'allow_null'    => 0,
					'multiple'      => 1,
					'return_format' => 'object',
					'ui'            => 1,
				),
			),
			static::_gen_tab_field( 'Glossary' ),
			array(
				static::FIELD_GLOSSARY_IMAGE => array(
					'key'           => $glossary_image,
					'name'          => static::FIELD_GLOSSARY_IMAGE,
					'label'         => 'Glossary Image',
					'type'          => 'image',
					'return_format' => 'url',
					'preview_size'  => 'thumbnail',
					'library'       => 'all',
				),
			),
			static::_gen_tab_field( 'Pagination' ),
			array(
				static::PAGINATION_TAX_ARCHIVE => array(
					'key'           => $pagination_tax_archive,
					'name'          => static::PAGINATION_TAX_ARCHIVE,
					'label'         => 'Taxonomy Pages',
					'type'          => 'number',
					'default_value' => 6,
				),
			),
			array(
				static::PAGINATION_SEARCH_RESULTS => array(
					'key'           => $pagination_search_results,
					'name'          => static::PAGINATION_SEARCH_RESULTS,
					'label'         => 'Search Results',
					'type'          => 'number',
					'default_value' => 6,
				),
			),
		);
	}

	/** @inheritDoc */
	protected static function prepare_page_register_args(): array {
		return array_merge(
			parent::prepare_page_register_args(),
			array(
				'parent_slug' => 'edit.php?post_type=' . Guide::POST_TYPE,
				'menu_title'  => 'Options',
				'page_title'  => 'Resource Center Options',
			)
		);
	}

	public static function render_hero() {
		$eyebrow            = static::get_option( static::FIELD_EYEBROW );
		$headline           = static::get_option( static::FIELD_HEADLINE );
		$search_placeholder = static::get_option( static::FIELD_SEARCH_PLACEHOLDER );
		$description        = static::get_option( static::FIELD_DESCRIPTION );
		$featured_topics    = static::get_option( static::FIELD_FEATURED_TOPICS );

		ob_start();
		?>
		<div class="container mx-auto text-center site-content-inner mt-8 md:mt-0 resource-center-hero">
			<div class="resource-center-hero__content">
				<?php if ( ! empty( $eyebrow ) ) : ?>
					<h2 class="resource-center-hero__eyebrow font-semibold text-white text-px14 leading-px18 tracking-em001 mb-2 md:tracking-px05 lg:font-bold lg:leading-px20">
						<?php echo esc_html( $eyebrow ); ?>
					</h2>
				<?php endif; ?>

				<?php if ( ! empty( $headline ) ) : ?>
					<h1 class="heading-lg-tilted text-white has-block-tilt">
						<?php echo $headline; ?>
					</h1>
				<?php endif; ?>

				<div class="search-container my-8 mx-auto md:w-3/5 md:my-6 lg:w-3/4 xl:w-4/6">
					<form role="search" method="get" class="search-form"
						action="<?php echo esc_url( \TrevorWP\CPT\RC\RC_Object::get_search_url() ); ?>">
						<?php echo Helper\Search_Input::render_rc( $search_placeholder ); ?>
					</form>
				</div>

				<?php if ( ! empty( $description ) ) : ?>
					<p class="resource-center-hero__description text-white font-medium text-base leading-px22 tracking-em001 md:text-px18 md:leading-px24 md:mt-8 md:mb-5 lg:text-px18 lg:tracking-px05 xl:text-px20 xl:leading-px24 lg:mb-6">
						<?php echo esc_html( $description ); ?>
					</p>
				<?php endif; ?>

				<?php if ( ! empty( $featured_topics ) ) : ?>
					<div class="resource-center-hero__topics flex flex-wrap justify-center mt-4 -mx-6 md:mx-auto">
						<?php foreach ( $featured_topics as $topic ) : ?>
							<a href="<?php echo get_term_link( $topic ); ?>" class="resource-center-hero__topic rounded-full py-1 px-3 bg-violet mx-1 mb-3 tracking-px05 text-white md:px-5 hover:bg-persian_blue-lighter">
								<?php echo esc_html( $topic->name ); ?>
							</a>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

				<div class="mt-8 animate-bounce hidden md:block">
					<i class="trevor-ti-chevron-down text-4xl text-white bouncing-arrow cursor-pointer"></i>
				</div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	public static function render_trending() {
		$trending_ids = static::get_option( static::FIELD_TRENDING );

		if ( empty( $trending_ids ) ) {
			return;
		}

		$trending_posts = Helper\Posts::get_from_list( $trending_ids, count( $trending_ids ), false );

		self::$used_post_ids = array_merge( self::$used_post_ids, wp_list_pluck( $trending_posts, 'ID' ) );

		return Helper\Carousel::posts(
			$trending_posts,
			array(
				'title'      => 'Trending',
				'subtitle'   => 'Explore the latest articles, resources, and guides.',
				'title_cls'  => 'centered lg:no-centered',
				'breakpoint' => 'tabletAndUp',
				'class'      => 'text-white md:mt-12 lg:mt-18 resource-center-trending',
			)
		);
	}

	public static function render_categories() {
		$featured_topics = static::get_option( static::FIELD_FEATURED_TOPICS );

		if ( empty( $featured_topics ) ) {
			return;
		}

		$cat_rows         = array();
		$featured_cat_ids = wp_list_pluck( $featured_topics, 'term_taxonomy_id' );
		$featured_cats    = get_terms(
			array(
				'taxonomy'   => RC_Object::TAXONOMY_CATEGORY,
				'orderby'    => 'include',
				'include'    => $featured_cat_ids,
				'parent'     => 0,
				'hide_empty' => false,
			)
		);

		$card_num = static::get_option( static::FIELD_CARD_NUM );

		foreach ( $featured_cats as $cat ) {
			$cat_posts = Helper\Posts::get_from_list(
				$featured_cat_ids,
				$card_num,
				array( 'post__not_in' => self::$used_post_ids ),
				array(
					'post_type' => RC_Object::$PUBLIC_POST_TYPES,
					'tax_query' => array(
						'relation' => 'AND',
						array(
							'taxonomy' => RC_Object::TAXONOMY_CATEGORY,
							'field'    => 'term_id',
							'terms'    => $cat->term_id,
							'operator' => 'IN',
						),
					),
				)
			);

			// Ensure there is at lease one post in carousel
			if ( count( $cat_posts ) === 0 ) {
				continue;
			}

			$cat_rows[] = Helper\Carousel::posts(
				$cat_posts,
				array(
					'id'           => "cat-{$cat->slug}",
					'title'        => '<a href="' . get_term_link( $cat ) . '">' . esc_html( $cat->name ) . '</a>',
					'subtitle'     => $cat->description,
					'class'        => 'text-white resource-center-category',
					'card_options' => array(),
				)
			);

			self::$used_post_ids = array_merge( self::$used_post_ids, wp_list_pluck( $cat_posts, 'ID' ) );
		}

		return $cat_rows;
	}

	public static function render_guide() {
		$guides = static::get_option( static::FIELD_GUIDES );

		if ( empty( $guides ) ) {
			return;
		}

		$root_cls = array(
			'text-white',
			'h-px600',
			'mt-10',
			'mb-24',
			'mb-32',
			'text-center',
			'pt-20',
			'md:h-px490',
			'md:justify-center',
			'xl:h-px737',
			'lg:mb-20',
			'resource-center-guide',
		);

		$guide = Helper\Posts::get_one_from_list(
			wp_list_pluck( $guides, 'ID' ),
			array( 'post__not_in' => self::$used_post_ids )
		);

		$main_cat = Post::get_main_category( $guide );

		ob_start();
		?>
		<div class="mx-auto lg:w-3/4">
			<?php if ( ! empty( $main_cat ) ) : ?>
				<a class="resource-center-guide__eyebrow text-px14 leading-px18 tracking-em002 font-semibold uppercase lg:text-px18 lg:leading-px22 z-10" href="<?php echo esc_url( get_the_permalink( $guide ) ); ?>"><?php echo esc_html( $guide->post_title ); ?></a>
			<?php endif; ?>
			<h2 class="resource-center-guide__heading text-px32 leading-px42 font-semibold my-3 lg:my-10 lg:text-px42 lg:leading-px52 xl:text-px60 xl:leading-px70"><?php echo strip_tags( $guide->post_excerpt, '<tilt>' ); ?></h2>
			<a class="stretched-link border-b font-semibold tracking-px05 text-px20 leading-px26 lg:text-px20 lg:leading-px26" href="<?php echo get_the_permalink( $guide ); ?>">Read Guide</a>
		</div>
		<?php
		$context = ob_get_clean();

		return Helper\Hero::img_bg(
			Helper\Thumbnail::get_post_imgs(
				$guide->ID,
				Helper\Thumbnail::variant( Helper\Thumbnail::SCREEN_SM, Helper\Thumbnail::TYPE_VERTICAL, Helper\Thumbnail::SIZE_MD ),
				Helper\Thumbnail::variant( Helper\Thumbnail::SCREEN_MD, Helper\Thumbnail::TYPE_HORIZONTAL, Helper\Thumbnail::SIZE_LG ),
			),
			$context,
			array( 'root_cls' => $root_cls )
		);
	}

	public static function render_glossary() {
		$glossary_image = static::get_option( static::FIELD_GLOSSARY_IMAGE );

		$featured_word = Helper\Posts::get_one_from_list(
			array(),
			array( 'post_type' => \TrevorWP\CPT\RC\Glossary::POST_TYPE )
		);

		if ( empty( $featured_word ) ) {
			return;
		}

		$options = array(
			'word'           => get_the_title( $featured_word ),
			'pronounciation' => $featured_word->post_excerpt,
			'description'    => $featured_word->post_content,
			'image'          => $glossary_image,
		);

		return Helper\Word_Of_The_Day::render( $options );
	}

	public static function get_featured_topics() {
		$featured_topics = static::get_option( static::FIELD_FEATURED_TOPICS );

		return $featured_topics;
	}

	public static function get_pagination() {
		$pagination_tax_archive    = static::get_option( static::PAGINATION_TAX_ARCHIVE );
		$pagination_search_results = static::get_option( static::PAGINATION_SEARCH_RESULTS );

		$data = array(
			'tax_archive'    => $pagination_tax_archive,
			'search_results' => $pagination_search_results,
		);

		return $data;
	}
}
