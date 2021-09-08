<?php namespace TrevorWP\Theme\ACF\Field_Group;

use TrevorWP\CPT;
use TrevorWP\CPT\RC\RC_Object;
use TrevorWP\Ranks;
use TrevorWP\Theme\Helper\Thumbnail;
use TrevorWP\Theme\Helper\Taxonomy;
use TrevorWP\Parsedown\Parsedown;

class Article_River extends A_Field_Group implements I_Block, I_Renderable {
	const FIELD_DISPLAY_LIMIT = 'display_limit';
	const FIELD_CATEGORY      = 'category';

	/**
	 * @inheritDoc
	 */
	protected static function prepare_register_args(): array {
		$display_limit = static::gen_field_key( static::FIELD_DISPLAY_LIMIT );
		$category      = static::gen_field_key( static::FIELD_CATEGORY );

		return array(
			'title'  => 'Article River',
			'fields' => array(
				static::FIELD_CATEGORY      => array(
					'key'           => $category,
					'name'          => static::FIELD_CATEGORY,
					'label'         => 'Category',
					'type'          => 'taxonomy',
					'taxonomy'      => 'category',
					'field_type'    => 'select',
					'add_term'      => 0,
					'return_format' => 'id',
					'multiple'      => 0,
					'allow_null'    => 1,
				),
				static::FIELD_DISPLAY_LIMIT => array(
					'key'           => $display_limit,
					'name'          => static::FIELD_DISPLAY_LIMIT,
					'label'         => 'Display Limit',
					'type'          => 'number',
					'required'      => 1,
					'default_value' => 10,
					'min'           => 1,
					'step'          => 1,
				),
			),
		);
	}

	/**
	 * @inheritDoc
	 */
	public static function get_block_args(): array {
		return array_merge(
			parent::get_block_args(),
			array(
				'name'       => static::get_key(),
				'title'      => 'Article River',
				'post_types' => array( 'page' ),
			)
		);
	}

	/**
	 * @inheritDoc
	 */
	public static function render( $post = false, array $data = null, array $options = array() ): ?string {
		$limit       = static::get_val( static::FIELD_DISPLAY_LIMIT );
		$category    = static::get_val( static::FIELD_CATEGORY );
		$filter_type = $options['filter_type'] ?? 'sort'; // "sort"|"tabs"

		$options = array(
			'limit'                 => $limit ?? 10,
			'category'              => $category ?? null,
			'search'                => '',
			'page'                  => 1,
			'show_taxonomy_eyebrow' => true,
			'show_tags'             => true,
			'sort'                  => 'desc', // desc|asc
			'pagination_type'       => 'ajax', // reload|ajax
		);

		list( $posts, $total_posts ) = array_values( static::get_paged_posts( $options ) );

		// Save total posts count for pagination
		$options['total_posts'] = $total_posts;

		// Show category eyebrow and tags if category is given
		if ( ! empty( $options['category'] ) ) {
			$options['show_taxonomy_eyebrow'] = false;
			$options['show_tags']             = false;
		}

		// Determine parameters to use according to pagination_type
		if ( 'reload' === $options['pagination_type'] ) {
			$options['page'] = $_GET['page'];
			$options['sort'] = $_GET['sort'];
		}

		$class = array( 'article-river', 'article-river--' . $options['pagination_type'] );
		$attrs = array(
			'data-pagination-type' => $options['pagination_type'],
			'data-filter-type'     => $options['filter_type'],
			'data-category'        => $options['category'],
			'data-limit'           => $options['limit'],
		);

		ob_start();
		?>
		<div <?php echo static::render_attrs( $class, $attrs ); ?>>
			<div class="article-river__container">
				<div class="article-river__filter-wrap">
					<?php
					switch ( $filter_type ) {
						case 'sort':
							echo static::render_sort();
							break;
					}
					?>
				</div>
				<div class="article-river__list" role="list">
					<?php echo static::render_articles( $posts, $options ); ?>
				</div>
				<?php if ( $options['total_posts'] > $options['limit'] ) : ?>
					<?php echo static::render_pagination( $options ); ?>
				<?php endif; ?>
			</div>
		</div>

		<?php
		return ob_get_clean();
	}

	/**
	 * @inheritDoc
	 */
	public static function render_articles( array $posts, array $options = array() ): string {
		// Render glossary if first page and unfiltered category
		$glossary = null;
		if ( 1 === $options['page'] && empty( $options['category'] ) ) {
			// Get glossary item.
			$glossaries = get_posts(
				array(
					's'           => $options['search'],
					'post_type'   => CPT\RC\Glossary::POST_TYPE,
					'numberposts' => 1,
				)
			);
			if ( ! empty( $glossaries ) ) {
				$glossary = $glossaries[0];
			}
		}

		// Render posts.
		ob_start();

		if ( ! empty( $glossary ) ) {
			echo static::render_glossary( $glossary );
		}
		foreach ( $posts as $post ) {
			echo static::render_post( $post, $options );
		}

		return ob_get_clean();
	}

	public static function render_glossary( $glossary_item ): string {
		ob_start();
		?>
		<div role="listitem"
			class="article-river__glossary w-full bg-gray-light text-indigo p-px30 rounded-px10 xl:w-7/12">
			<h3
				class="font-bold lg:font-semibold text-px22 leading-px28 lg:text-px24 lg:leading-px34 tracking-px05 mb-px8 md:mb-px16 lg:mb-px10">
				<?php echo $glossary_item->post_title; ?></h3>
			<div class="text-px16 leading-px22 tracking-px05 mb-4"><?php echo $glossary_item->post_excerpt; ?></div>
			<div class="text-px16 leading-px24 md:text-px18 md:leading-px26 lg:text-px20 lg:leading-px28 lg:tracking-px_05">
				<?php echo ( new Parsedown() )->text( strip_tags( $glossary_item->post_content ) ); ?></div>
		</div>
		<?php
		return ob_get_clean();
	}

	public static function render_post( $post, $options = array() ): string {
		$options = array_merge(
			array(
				'show_taxonomy_eyebrow' => true,
				'show_tags'             => true,
			),
			$options
		);

		$_class = array(
			'pb-10',
			'lg:pb-px50',
			'border-b',
			'border-b-1',
			'border-indigo',
			'border-opacity-20',
		);

		$title_top = null;

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
		$tags = ( $options['show_tags'] ) ? Taxonomy::get_post_tags_distinctive( $post, array( 'filter_count_1' => false ) ) : null;

		# Class
		$class = array_merge(
			array( 'article-river__item flex flex-row flex-wrap search-result-item' ),
			get_post_class( $_class, $post->ID ),
		);

		# Attrs
		$attrs = array(
			'role' => 'listitem',
		);

		ob_start();
		?>
		<article <?php echo static::render_attrs( $class, $attrs ); ?>>
				<?php if ( $has_thumbnail ) { ?>
			<div class="article-river__thumbnail-wrap thumbnail-wrap" data-aspectRatio="1:1">
				<a class="article-river__thumbnail-link block" href="<?php echo get_the_permalink( $post ); ?>">
					<?php echo $thumb; ?>
				</a>
			</div>
			<?php } ?>
			<div class="text-content">
				<div class="eyebrow article-river__item-eyebrow">
					<?php if ( $options['show_taxonomy_eyebrow'] ) : ?>
					<strong class="pr-px24"><?php echo $title_top; ?></strong>
					<?php endif; ?>
					<?php if ( in_array( $post->post_type, array( CPT\RC\Post::POST_TYPE, CPT\Post::POST_TYPE ) ) ) { ?>
					<time
						datetime="<?php echo $post->post_date; ?>"><?php echo date( 'F j, Y', strtotime( $post->post_date ) ); ?></time>
					<?php } ?>
				</div>

				<div class="article-river__item-heading">
					<?php if ( ! empty( $icon_cls ) ) { ?>
					<div class="icon-wrap"><i class="<?php echo esc_attr( $icon_cls ); ?>"></i></div>
					<?php } ?>
					<h3 class="article-river__item-title">
						<a href="<?php echo get_the_permalink( $post ); ?>"><?php echo $post->post_title; ?></a>
					</h3>
				</div>

				<div class="article-river__item-excerpt"><?php echo $post->post_excerpt; ?></div>

				<?php if ( ! empty( $tags ) ) { ?>
				<div class="article-river__tags">
					<div class="tags-box">
						<?php foreach ( $tags as $tag ) { ?>
						<a href="<?php echo esc_url( RC_Object::get_search_url( $tag->name ) ); ?>"
							class="tag-box"><?php echo $tag->name; ?></a>
						<?php } ?>
					</div>
				</div>
				<?php } ?>
			</div>
		</article>
		<?php
		return ob_get_clean();
	}

	public static function render_sort(): string {
		ob_start();
		?>
		<div class="article-river__sort">
			<div class="custom-select">
				<ul>
					<li class="label">
						<button type="button" class="article-river__sort-label">Sort By: Newest to Oldest</button>
						<ul class="dropdown">
							<li class="active article-river__sort-option" data-sort="desc">
								<button>
									Sort By: Newest to Oldest
								</button>
							</li>
							<li class="article-river__sort-option" data-sort="asc">
								<button>
									Sort By: Oldest to Newest
								</button>
							</li>
						</ul>
					</li>
				</ul>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	public static function render_pagination( array $options = array() ): string {
		$options = array_merge(
			array(
				'limit' => 10,
				'page'  => 1,
			),
			$options
		);

		$total_pages = ceil( $options['total_posts'] / $options['limit'] );
		$pages       = range( 1, $total_pages );

		ob_start();
		?>
		<div class="article-river__pagination">
			<div class="article-river__pagination-nav swiper-button-prev trevor-ti-chevron-thick-right"></div>
			<div class="swiper-container">
				<div class="article-river__pagination-pages swiper-wrapper">
					<?php foreach ( $pages as $page ) : ?>
						<?php
						$tag   = 'ajax' === $options['pagination_type'] ? 'button' : 'a';
						$class = array( 'article-river__pagination-page', 'swiper-slide' );
						if ( (int) $page === $options['page'] ) {
							$class[] = 'active';
						}
						$attrs = array();

						if ( 'ajax' === $options['pagination_type'] ) {
							$attrs['data-page'] = $page;
						} else {
							$attrs['href'] = static::build_url_params( compact( 'page' ) );
						}
						?>
						<<?php echo $tag; ?>
							<?php echo static::render_attrs( $class, $attrs ); ?>
						>
							<span><?php echo $page; ?></span>
						</<?php echo $tag; ?>>
					<?php endforeach; ?>
				</div>
			</div>
			<div class="article-river__pagination-nav swiper-button-next trevor-ti-chevron-thick-right"></div>
		</div>
		<?php
		return ob_get_clean();
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

	/**
	 * @inheritDoc
	 */
	public static function render_block( $block, $content = '', $is_preview = false, $post_id = 0 ): void {
		echo static::render( $post_id, null, compact( 'is_preview' ) );
	}

	/**
	 * @inheritDoc
	 */
	public static function build_url_params( array $params = array() ): string {
		$base_url          = strtok( $_SERVER['REQUEST_URI'], '?' );
		$base_url_segments = array_filter( explode( '/', $base_url ) );

		$params = array_merge(
			$_GET,
			$params,
		);

		$params_segments = array();

		foreach ( $params as $key => $value ) {
			$params_segments[] = implode( '=', array( $key, $value ) );
		}

		return '/' . implode( '/', $base_url_segments ) . '?' . implode( '&', $params_segments );
	}

	public static function get_paged_posts( array $options = array() ): array {
		$query_args = array(
			'posts_per_page'   => (int) $options['limit'] ?? 10,
			'paged'            => (int) $options['page'] ?? 1,
			'suppress_filters' => true,
		);

		// Add category arg if no category filter
		if ( ! empty( $options['category'] ) ) {
			$query_args['cat'] = (int) $options['category'];
		}

		// Add search query
		if ( ! empty( $options['search'] ) ) {
			$query_args['s'] = $query_args['search'];
		}

		// Add sort
		$query_args['orderby'] = 'date';
		$query_args['order']   = strtoupper( $options['sort'] ?? 'desc' );

		// Query posts
		$query       = new \WP_Query( $query_args );
		$posts       = $query->posts;
		$total_posts = $query->found_posts;

		return compact( 'posts', 'total_posts' );
	}

	public static function ajax_entries( $request ) {
		$params = $request->get_params();

		list( $posts ) = array_values( static::get_paged_posts( $params ) );
		$posts_html    = static::render_articles( $posts );

		$resp = new \WP_REST_Response(
			array(
				'success'      => true,
				'entries_html' => $posts_html,
			),
			200
		);

		return $resp;
	}
}
