<?php namespace TrevorWP\Theme\ACF\Field_Group;

use TrevorWP\CPT;
use TrevorWP\Theme\Helper;
use TrevorWP\Theme\ACF\Util\Field_Val_Getter;
use TrevorWP\Util\Tools;
use \TrevorWP\Theme\Customizer\Social_Media_Accounts;

class Post_Grid extends A_Field_Group implements I_Block, I_Renderable {
	const FIELD_SOURCE = 'source';
	const FIELD_HEADING = 'heading';
	const FIELD_QUERY_PTS = 'post_query_pts'; // post types
	const FIELD_QUERY_TAXS = 'post_query_taxs'; // taxonomies
	const FIELD_POST_ITEMS = 'post_items';
	const SUBFIELD_TAXS_TAX = self::FIELD_QUERY_TAXS . '_tax';
	const SUBFIELD_TAXS_TERMS = self::FIELD_QUERY_TAXS . '_terms';
	const FIELD_NUM_COLS = 'number_columns';
	const FIELD_PLACEHOLDER_IMG = 'placeholder';
	const FIELD_NUM_DISPLAY_LIMIT = 'number_display_limit';
	const FIELD_WRAPPER_ATTR = 'wrapper_attr';
	const FIELD_PAGINATION_STYLE = 'pagination_style';
	const FIELD_SHOW_EMPTY = 'show_empty';
	const FIELD_EMPTY_MESSAGE = 'empty_message';

	const PAGINATION_STYLE_OPTION_LOADMORE = 'load_more';
	const PAGINATION_STYLE_OPTION_PAGES = 'pages';

	const SOURCE_QUERY = 'query';
	const SOURCE_PICK = 'pick';

	const DEFAULT_NUM_DISPLAY_LIMIT = 6;

	/** @inheritDoc */
	public static function register(): bool {
		add_filter( 'acf/load_field/name=' . static::FIELD_QUERY_PTS, [ static::class, 'load_field_post_query_pts' ] );
		add_filter( 'acf/load_field/name=' . static::SUBFIELD_TAXS_TAX, [
				static::class,
				'load_field_post_query_taxs'
		] );

		return parent::register();
	}

	/** @inheritDoc */
	protected static function prepare_register_args(): array {
		$heading = static::gen_field_key( static::FIELD_HEADING );
		$source            = static::gen_field_key( static::FIELD_SOURCE );
		$post_items        = static::gen_field_key( static::FIELD_POST_ITEMS );
		$q_pts             = static::gen_field_key( static::FIELD_QUERY_PTS );
		$q_taxs            = static::gen_field_key( static::FIELD_QUERY_TAXS );
		$placeholder_img   = static::gen_field_key( static::FIELD_PLACEHOLDER_IMG );
		$num_cols          = static::gen_field_key( static::FIELD_NUM_COLS );
		$num_display_limit = static::gen_field_key( static::FIELD_NUM_DISPLAY_LIMIT );
		$wrapper_attr      = static::gen_field_key( static::FIELD_WRAPPER_ATTR );
		$pagination_style  = static::gen_field_key( static::FIELD_PAGINATION_STYLE );
		$show_empty 	   = static::gen_field_key( static::FIELD_SHOW_EMPTY );
		$empty_message 	   = static::gen_field_key( static::FIELD_EMPTY_MESSAGE );

		return [
				'title'  => 'Post Grid',
				'fields' => array_merge(
						static::_gen_tab_field( 'General' ),
						[
								static::FIELD_SOURCE            => [
										'key'     => $source,
										'name'    => static::FIELD_SOURCE,
										'label'   => 'Source',
										'type'    => 'select',
										'choices' => [
												static::SOURCE_PICK  => 'Hand Pick',
												static::SOURCE_QUERY => 'Query',
										],
										'wrapper' => [
												'width' => '50%',
										],
								],
								static::FIELD_HEADING => [
									'key'           => $heading,
									'name'          => static::FIELD_HEADING,
									'label'         => 'Heading',
									'type'          => 'text',
								],
								static::FIELD_NUM_COLS          => [
										'key'           => $num_cols,
										'name'          => static::FIELD_NUM_COLS,
										'label'         => 'Number of Columns (Desktop)',
										'type'          => 'button_group',
										'required'      => true,
										'choices'       => [
												3 => '3',
												4 => '4',
										],
										'default_value' => 4,
										'wrapper'       => [
												'width' => '50%',
										],
								],
								static::FIELD_PLACEHOLDER_IMG   => [
										'key'           => $placeholder_img,
										'name'          => static::FIELD_PLACEHOLDER_IMG,
										'label'         => 'Placeholder Image',
										'type'          => 'image',
										'required'      => false,
										'return_format' => 'array',
										'preview_size'  => 'small',
										'library'       => 'all',
										'wrapper'       => [
												'width' => '50%',
										],
								],
								static::FIELD_NUM_DISPLAY_LIMIT => [
										'key'          => $num_display_limit,
										'name'         => static::FIELD_NUM_DISPLAY_LIMIT,
										'label'        => 'Display Limit',
										'instructions' => 'Number of items to be displayed before clicking <i>Load More</i>',
										'type'         => 'number',
										'wrapper'      => [
												'width' => '50%',
										],
								],
								static::FIELD_PAGINATION_STYLE => [
									'key'               => $pagination_style,
									'name'              => static::FIELD_PAGINATION_STYLE,
									'label'             => 'Pagination Style',
									'type' 				=> 'radio',
									'choices' 			=> [
										static::PAGINATION_STYLE_OPTION_LOADMORE => 'Load More',
										static::PAGINATION_STYLE_OPTION_PAGES => 'Pages',
									],
									'default_value' 	=> static::PAGINATION_STYLE_OPTION_LOADMORE,
									'layout'        => 'horizontal',
                                    'return_format' => 'value',
									'wrapper'      => [
										'width' => '50%',
									],
								],
						],
						static::_gen_tab_field( 'Posts' ),
						[
								static::FIELD_QUERY_PTS  => [
										'key'               => $q_pts,
										'name'              => static::FIELD_QUERY_PTS,
										'label'             => 'Post Types',
										'type'              => 'select',
										'choices'           => [/* @see load_field_post_query_pts() */ ],
										'multiple'          => true,
										'ui'                => true,
										'conditional_logic' => [
												[
														[
																'field'    => $source,
																'operator' => '==',
																'value'    => static::SOURCE_QUERY,
														],
												]
										],
								],
								static::FIELD_QUERY_TAXS => [
										'key'               => $q_taxs,
										'name'              => static::FIELD_QUERY_TAXS,
										'label'             => 'Taxonomies',
										'type'              => 'repeater',
										'conditional_logic' => [
												[
														[
																'field'    => $source,
																'operator' => '==',
																'value'    => static::SOURCE_QUERY,
														],
												]
										],
										'sub_fields'        => [
												static::SUBFIELD_TAXS_TAX   => [
														'key'     => self::gen_field_key( static::SUBFIELD_TAXS_TAX ),
														'name'    => static::SUBFIELD_TAXS_TAX,
														'type'    => 'select',
														'choices' => [],
												],
												static::SUBFIELD_TAXS_TERMS => [
														'key'         => self::gen_field_key( static::SUBFIELD_TAXS_TERMS ),
														'name'        => static::SUBFIELD_TAXS_TERMS,
														'type'        => 'text',
														'placeholder' => '123,456,780'
												],
										],
								],
								static::FIELD_POST_ITEMS => [
										'key'               => $post_items,
										'name'              => static::FIELD_POST_ITEMS,
										'label'             => 'Posts',
										'type'              => 'relationship',
										'required'          => true,
										'return_format'     => 'object',
										'filters'           => [
												0 => 'search',
												1 => 'post_type',
												2 => 'taxonomy',
										],
										'elements'          => [
												0 => 'featured_image',
										],
										'conditional_logic' => [
												[
														[
																'field'    => $source,
																'operator' => '==',
																'value'    => static::SOURCE_PICK,
														],
												]
										],
								],
								static::FIELD_SHOW_EMPTY => [
									'key'               => $show_empty,
									'name'              => static::FIELD_SHOW_EMPTY,
									'type' 				=> 'select',
									'label'				=> 'Show Empty Posts Message',
									'choices'       => [
											0 => 'No',
											1 => 'Yes',
									],
									'layout'	=> 'horizontal'
								],
								static::FIELD_EMPTY_MESSAGE => [
									'key'               => $empty_message,
									'name'              => static::FIELD_EMPTY_MESSAGE,
									'type' 				=> 'text',
									'label'				=> 'Empty Posts Message',
									'conditional_logic' => [
										[
											[
												'field'    => $show_empty,
												'operator' => '==',
												'value'    => 1,
											],
										]
									],
								],
						],
						static::_gen_tab_field( 'Attributes' ),
						[
								static::FIELD_WRAPPER_ATTR => DOM_Attr::clone( [
										'key'  => $wrapper_attr,
										'name' => static::FIELD_WRAPPER_ATTR,
								] )
						]
				),
		];
	}

	/** @inheritDoc */
	public static function get_block_args(): array {
		return [
				'name'       => static::get_key(),
				'title'      => 'Post Grid',
				'category'   => 'common',
				'icon'       => 'book-alt',
				'post_types' => [ 'page' ],
		];
	}

	/** @inheritDoc */
	public static function render( $post = false, array $data = null, array $options = [] ): ?string {
		$val             = new Field_Val_Getter( static::class, $post, $data );
		$num_cols        = $val->get( static::FIELD_NUM_COLS );
		$placeholder_img = $val->get( static::FIELD_PLACEHOLDER_IMG );
		$heading = static::get_val( static::FIELD_HEADING );
		$posts           = static::_get_posts( $val );
		$display_limit   = (int) $val->get( static::FIELD_NUM_DISPLAY_LIMIT );
		$show_empty   	 = (int) $val->get( static::FIELD_SHOW_EMPTY );
		$empty_message   = $val->get( static::FIELD_EMPTY_MESSAGE );
		$cls             = [
				'tile-grid-container mx-auto',
				'mb-px38 mt-px40',
				'md:mt-px30',
				'xl:mt-0',
		];

		$social_media_accounts = [
			[
					'url'  => Social_Media_Accounts::get_val( Social_Media_Accounts::SETTING_FACEBOOK_URL ),
					'icon' => 'trevor-ti-facebook',
			],
			[
					'url'  => Social_Media_Accounts::get_val( Social_Media_Accounts::SETTING_TWITTER_URL ),
					'icon' => 'trevor-ti-twitter',
			],
			[
					'url'  => Social_Media_Accounts::get_val( Social_Media_Accounts::SETTING_INSTAGRAM_URL ),
					'icon' => 'trevor-ti-instagram',
			],
		];

		if ( ! empty( $options['class'] ) ) {
			$cls = array_merge( $cls, $options['class'] );
		}

		if ( ! $num_cols || $num_cols < 3 ) {
			$num_cols = 3;
		}

		$tile_options = [ 'accordion' => false, 'class' => $options['tileClass'] ?: [] ];

		if ( ! empty( $options['smAccordion'] ) ) {
			$tile_options['accordion'] = true;

			$cls[] = 'sm-accordion';
		}

		$tile_options['placeholder_image'] = $placeholder_img ?: null;

		if ( ! empty($posts) ) {
			if ( count( $posts ) < 3 ) {
				$cls[] = 'desktop-autofit-columns';
			} else {
				// number of columns on XL breakpoint
				$cls[] = "desktop-{$num_cols}-columns";
			}
		}

		$count = 0;

		$wrapper_attrs = DOM_Attr::get_attrs_of( static::get_val( static::FIELD_WRAPPER_ATTR ), $cls );

		$wrapper_attrs['id'] = $id = empty( $wrapper_attrs['id'] )
				? uniqid( 'tile-container-' )
				: $wrapper_attrs['id'];

		# Build post grid classnames
		$post_grid_cls   = 'post-grid px-px28 md:px-px50 xl:px-px140';

		# Build heading classnames
		$heading_cls   = [ 'text-center text-teal-dark font-bold' ];
		$heading_cls[] = 'mt-px80 text-px32 leading-px40 mb-px40';
		$heading_cls[] = 'md:pt-px72 md:mb-px40 md:leading-px42 md:mt-0';
		$heading_cls[] = 'xl:text-px46 xl:leading-px56 xl:tracking-em_001 xl:pt-0';
		$heading_cls   = implode( ' ', $heading_cls );

		# Build empty message classnames
		$empty_msg_cls   = [ 'post-grid-empty__message text-center' ];
		$empty_msg_cls[] = 'text-px20 leading-px28 tracking-em001';
		$empty_msg_cls[] = 'md:px-px120';
		$empty_msg_cls[] = 'xl:text-px32 xl:leading-px52 xl:tracking-px_05 xl:px-px170';
		$empty_msg_cls   = implode( ' ', $empty_msg_cls );

		ob_start(); ?>
			<div class="<?= $post_grid_cls ?>">
				<?php if ( ! empty( $heading ) ): ?>
					<h2 class="<?= $heading_cls ?>">
						<?= $heading ?>
					</h2>
				<?php endif; ?>
				<?php if ( ! empty( $posts ) ):  ?>
					<div <?= Tools::flat_attr( $wrapper_attrs ) ?>>
						<?php foreach ( $posts as $key => $post ) {
							$post = get_post( $post );

							$tile_options['hidden'] = $display_limit && ++ $count > $display_limit;

							switch ( get_post_type( $post ) ) {
								case CPT\Team::POST_TYPE:
									echo Helper\Tile::staff( $post, $key, $tile_options );
									break;
								case CPT\Financial_Report::POST_TYPE:
									echo Helper\Tile::financial_report( $post, $key, $tile_options );
									break;
								case CPT\Event::POST_TYPE:
									echo Helper\Tile::event( $post, $key, $tile_options );
									break;
								case CPT\Post::POST_TYPE:
								case CPT\RC\Post::POST_TYPE:
									echo Helper\Card::post( $post, $key, $tile_options );
									break;
								// TODO: Add other post types
								default:
									echo Helper\Tile::post( $post, $key, $tile_options );
							}
						} ?>
					</div>
				<?php elseif ( $show_empty && !empty( $empty_message ) ) : ?>
					<div class="post-grid-empty mb-px40 xl:mt-px20">
						<div class="<?= $empty_msg_cls?>"><?= $empty_message ?></div>
						<ul class="post-grid-empty__socials flex justify-center items-center mt-px50 md:mt-px20 xl:mt-px60">
							<?php foreach( $social_media_accounts as $account ):
								if ( ! empty( $account['url'] ) ) : ?>
									<li class="w-px44 h-px44 mx-px10 flex justify-center items-center xl:w-px60 xl:h-px60">
										<a href="<?= esc_url( $account['url'] ); ?>">
											<i class="<?= esc_attr( implode( ' ', [
													$account['icon'],
													'text-teal-dark text-px30 xl:text-px40'
											] ) ); ?>"></i>
										</a>
									</li>
								<?php endif;
							endforeach; ?>
						</ul>
					</div>
				<?php endif; ?>
				
				<?php if ( $display_limit && $display_limit < count( $posts ) ) { ?>
					<div class="view-all-container text-center overflow-visible pb-2">
						<a class="view-all-cta wave-underline font-bold text-px24 leading-px34 md:text-px18 tracking-px05 xl:text-px26 xl:leading-px36 tracking-em001 border-b-2 text-teal-dark self-center"
						href="#!" data-tile-container="<?= $id; ?>">
							Load More
						</a>
					</div>
				<?php } ?>

			</div>
		<?php return ob_get_clean();
	}

	/**
	 * @param Field_Val_Getter $val
	 *
	 * @return array
	 */
	protected static function _get_posts( Field_Val_Getter $val ): array {
		$source        = $val->get( static::FIELD_SOURCE );
		$display_limit = $val->get( static::FIELD_NUM_DISPLAY_LIMIT );
		$display_limit = ! empty( $display_limit ) ? (int) $display_limit : static::DEFAULT_NUM_DISPLAY_LIMIT;

		if ( $source == static::SOURCE_QUERY ) {
			$q_args = [ 'tax_query' => [ 'relation' => 'OR' ], 'posts_per_page' => $display_limit, ];

			if ( ! empty( $post_type = $val->get( static::FIELD_QUERY_PTS ) ) ) {
				$q_args['post_type'] = $post_type;
			}

			if ( ! empty( $taxs = $val->get( static::FIELD_QUERY_TAXS ) ) ) {
				foreach ( $taxs as $tax ) {
					$q_args['tax_query'][] = [
							'taxonomy' => $tax[ static::SUBFIELD_TAXS_TAX ],
							'terms'    => wp_parse_id_list( $tax[ static::SUBFIELD_TAXS_TERMS ] ),
					];
				}
			}

			$q     = new \WP_Query( $q_args );
			$posts = $q->posts;
		} else {
			$posts = $val->get( static::FIELD_POST_ITEMS );
			$posts = ! empty($posts) ? (array) $posts : [];
		}

		return $posts;
	}

	/**
	 * @inheritDoc
	 */
	public static function render_block( $block, $content = '', $is_preview = false, $post_id = 0 ): void {
		$data = (array) @$block['data'];

		if ( have_rows( static::FIELD_QUERY_TAXS ) ):
			$data[ static::FIELD_QUERY_TAXS ] = [];
			while ( have_rows( static::FIELD_QUERY_TAXS ) ) : the_row();
				$data[ static::FIELD_QUERY_TAXS ][] = [
						static::SUBFIELD_TAXS_TAX   => get_sub_field( static::SUBFIELD_TAXS_TAX ),
						static::SUBFIELD_TAXS_TERMS => get_sub_field( static::SUBFIELD_TAXS_TERMS ),
				];
			endwhile;
		endif;


		echo static::render( $post_id, $data, compact( 'is_preview' ) );
	}

	/**
	 * @param $field
	 *
	 * @return mixed
	 */
	public static function load_field_post_query_pts( $field ) {
		if ( $field && ! empty( $field['key'] ) && $field['key'] == static::gen_field_key( static::FIELD_QUERY_PTS ) ) {
			$choices = [];
			foreach ( get_post_types( [], 'objects' ) as $pt ) {
				$choices[ $pt->name ] = "{$pt->name}: {$pt->label}";
			}

			$field['choices'] = $choices;
		}

		return $field;
	}

	public static function load_field_post_query_taxs( $field ) {
		if ( $field && ! empty( $field['key'] ) && $field['key'] == static::gen_field_key( static::SUBFIELD_TAXS_TAX ) ) {
			$choices = &$field['choices'];
			foreach ( get_taxonomies( [], 'object' ) as $tax ) {
				$choices[ $tax->name ] = "{$tax->name}: {$tax->label}";
			}
		}

		return $field;
	}
}
