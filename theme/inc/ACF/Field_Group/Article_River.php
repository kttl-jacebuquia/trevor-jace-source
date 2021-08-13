<?php namespace TrevorWP\Theme\ACF\Field_Group;

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
				static::FIELD_CATEGORY     => array(
					'key'           => $category,
					'name'          => static::FIELD_CATEGORY,
					'label'         => 'Category',
					'type'          => 'taxonomy',
					'taxonomy'      => 'category',
					'field_type'    => 'select',
					'add_term'      => 0,
					'save_terms'    => 0,
					'load_terms'    => 1,
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
		$limit    = static::get_val( static::FIELD_DISPLAY_LIMIT );
		$category = static::get_val( static::FIELD_CATEGORY );

		// $press = get_term_by( 'slug', 'press', 'category', 'ARRAY_A' );

		// $args = array(
		// 	'numberposts' => $display_limit,
		// 	'category'    => ! empty( $press['term_id'] ) ? $press['term_id'] : 0,
		// 	'orderby'     => 'date',
		// 	'order'       => 'DESC',
		// 	'post_type'   => 'post',
		// );

		// $posts = get_posts( $args );

		return static::render_articles( compact( $limit, $category ) );
	}

	/**
	 * @inheritDoc
	 */
	public static function render_articles( array $options ): string {
		$options = array_merge(
			array(
				'limit'       => 10,
				'category'    => null,
				'search'      => 'daniel',
				'filter_type' => 'sort', // "sort"|"tabs"
			),
			$options,
		);

		$args = array(
			'numberposts' => $options['limit'],
		);

		// Add category filter
		if ( ! empty( $options['category'] ) ) {
			$args['category'] = $options['category'];
		}

		// Add search query
		if ( ! empty( $options['search'] ) ) {
			$args['s'] = $options['search'];

			// Add filter to prevent searching from content
			add_filter( 'posts_search', array( 'TrevorWP\Theme\ACF\Field_Group\Article_River', 'post_search_title_only', 500, 2 ) );
		}

		$query = new \WP_Query( $args );
		$posts = $query->posts;

		ob_start();
		?>
			<?php foreach ( $posts as $post ) : ?>
				<p>
					<a href="<?php echo get_the_permalink( $post ); ?>"><?php echo $post->post_title; ?></a>
				</p>
			<?php endforeach; ?>
		<?php
		return ob_get_clean();
	}

	/**
	 * @inheritDoc
	 * Reference: https://wordpress.stackexchange.com/questions/119422/search-with-filters-and-title
	 */
	public static function post_search_title_only( $search ) {
		var_dump( 'search' );
		var_dump( $search );

		// global $wpdb;

		// // Remove this filter
		remove_filter( 'posts_search', array( 'TrevorWP\Theme\ACF\Field_Group\Article_River', 'post_search_title_only' ) );

		// if ( empty( $search ) ) {
		// 	return $search; // skip processing - no search term in query
		// }

		// $q = $wp_query->query_vars;
		// $n = ! empty( $q['exact'] ) ? '' : '%';
		// $search = '';
		// $searchand = '';
		// foreach ( ( array ) $q['search_terms'] as $term) {
		// 	$term = esc_sql($wpdb->esc_like($term));
		// 	$search .= "{$searchand}($wpdb->posts.post_title LIKE '{$n}{$term}{$n}')";
		// 	$searchand = ' AND ';
		// }
		// if (!empty($search)) {
		// 	$search = " AND ({$search}) ";
		// 	if (!is_user_logged_in())
		// 		$search .= " AND ($wpdb->posts.post_password = '') ";
		// }

		// var_dump( $search );

		return $search;
	}

	/**
	 * @inheritDoc
	 */
	public static function render_block( $block, $content = '', $is_preview = false, $post_id = 0 ): void {
		echo static::render( $post_id, null, compact( 'is_preview' ) );
	}
}

// Remove excerpt and content matching from query
// $s = preg_replace(
// 	'/OR\s\(wp_posts\.post_(content|excerpt)\sLIKE\s[^)]+\)\s?/i',
// 	'',
// 	$search
// );
