<?php namespace TrevorWP\Theme\ACF\Field_Group;

use TrevorWP\Classy\Content;
use TrevorWP\CPT;
use TrevorWP\CPT\Donate\Partner_Prod;
use TrevorWP\Theme\Helper;
use TrevorWP\Theme\ACF\Util\Field_Val_Getter;
use TrevorWP\Util\Tools;
use \TrevorWP\Theme\Customizer\Social_Media_Accounts;

class Post_Grid extends A_Field_Group implements I_Block, I_Renderable {
	const FIELD_SOURCE            = 'source';
	const FIELD_HEADING           = 'heading';
	const FIELD_CAMPAIGN_ID       = 'campaign_id';
	const FIELD_QUERY_PTS         = 'post_query_pts'; // post types
	const FIELD_QUERY_TAXS        = 'post_query_taxs'; // taxonomies
	const FIELD_POST_ITEMS        = 'post_items';
	const SUBFIELD_TAXS_TAX       = self::FIELD_QUERY_TAXS . '_tax';
	const SUBFIELD_TAXS_TERMS     = self::FIELD_QUERY_TAXS . '_terms';
	const FIELD_NUM_COLS          = 'number_columns';
	const FIELD_PLACEHOLDER_IMG   = 'placeholder';
	const FIELD_NUM_DISPLAY_LIMIT = 'number_display_limit';
	const FIELD_WRAPPER_ATTR      = 'wrapper_attr';
	const FIELD_PAGINATION_STYLE  = 'pagination_style';
	const FIELD_SHOW_EMPTY        = 'show_empty';
	const FIELD_EMPTY_MESSAGE     = 'empty_message';

	const FIELD_CUSTOM_ITEMS      = 'custom_items';
	const FIELD_CUSTOM_ITEM_TITLE = 'custom_item_title';
	const FIELD_CUSTOM_ITEM_DESC  = 'custom_item_desc';
	const FIELD_CUSTOM_ITEM_CTA   = 'custom_item_cta';

	const PAGINATION_STYLE_OPTION_LOADMORE = 'load_more';
	const PAGINATION_STYLE_OPTION_PAGES    = 'pages';

	const SOURCE_QUERY           = 'query';
	const SOURCE_PICK            = 'pick';
	const SOURCE_CUSTOM          = 'custom';
	const SOURCE_TOP_INDIVIDUALS = 'top_individuals';
	const SOURCE_TOP_TEAMS       = 'top_teams';
	const SOURCE_UPCOMING_EVENTS = 'upcoming_events';
	const SOURCE_PAST_EVENTS     = 'past_events';

	const DEFAULT_NUM_DISPLAY_LIMIT = 6;

	public static $rendered_posts = array();

	/** @inheritDoc */
	public static function register(): bool {
		add_filter( 'acf/load_field/name=' . static::FIELD_QUERY_PTS, array( static::class, 'load_field_post_query_pts' ) );
		add_filter(
			'acf/load_field/name=' . static::SUBFIELD_TAXS_TAX,
			array(
				static::class,
				'load_field_post_query_taxs',
			)
		);

		return parent::register();
	}

	/** @inheritDoc */
	protected static function prepare_register_args(): array {
		$heading           = static::gen_field_key( static::FIELD_HEADING );
		$campaign_id       = static::gen_field_key( static::FIELD_CAMPAIGN_ID );
		$source            = static::gen_field_key( static::FIELD_SOURCE );
		$post_items        = static::gen_field_key( static::FIELD_POST_ITEMS );
		$q_pts             = static::gen_field_key( static::FIELD_QUERY_PTS );
		$q_taxs            = static::gen_field_key( static::FIELD_QUERY_TAXS );
		$placeholder_img   = static::gen_field_key( static::FIELD_PLACEHOLDER_IMG );
		$num_cols          = static::gen_field_key( static::FIELD_NUM_COLS );
		$num_display_limit = static::gen_field_key( static::FIELD_NUM_DISPLAY_LIMIT );
		$wrapper_attr      = static::gen_field_key( static::FIELD_WRAPPER_ATTR );
		$pagination_style  = static::gen_field_key( static::FIELD_PAGINATION_STYLE );
		$show_empty        = static::gen_field_key( static::FIELD_SHOW_EMPTY );
		$empty_message     = static::gen_field_key( static::FIELD_EMPTY_MESSAGE );

		$custom_items      = static::gen_field_key( static::FIELD_CUSTOM_ITEMS );
		$custom_item_title = static::gen_field_key( static::FIELD_CUSTOM_ITEM_TITLE );
		$custom_item_desc  = static::gen_field_key( static::FIELD_CUSTOM_ITEM_DESC );
		$custom_item_cta   = static::gen_field_key( static::FIELD_CUSTOM_ITEM_CTA );

		return array(
			'title'  => 'Post Grid',
			'fields' => array_merge(
				static::_gen_tab_field( 'General' ),
				array(
					static::FIELD_SOURCE            => array(
						'key'     => $source,
						'name'    => static::FIELD_SOURCE,
						'label'   => 'Source',
						'type'    => 'select',
						'choices' => array(
							static::SOURCE_PICK            => 'Hand Pick',
							static::SOURCE_QUERY           => 'Query',
							static::SOURCE_CUSTOM          => 'Custom',
							static::SOURCE_UPCOMING_EVENTS => 'Upcoming Events',
							static::SOURCE_PAST_EVENTS     => 'Past Events',
						),
						'wrapper' => array(
							'width' => '50%',
						),
					),
					static::FIELD_HEADING           => array(
						'key'   => $heading,
						'name'  => static::FIELD_HEADING,
						'label' => 'Heading',
						'type'  => 'text',
					),
					static::FIELD_CAMPAIGN_ID       => array(
						'key'               => $campaign_id,
						'name'              => static::FIELD_CAMPAIGN_ID,
						'label'             => 'Campaign ID',
						'type'              => 'text',
						'conditional_logic' => array(
							array(
								array(
									'field'    => $source,
									'operator' => '==',
									'value'    => static::SOURCE_TOP_INDIVIDUALS,
								),
							),
							array(
								array(
									'field'    => $source,
									'operator' => '==',
									'value'    => static::SOURCE_TOP_TEAMS,
								),
							),
						),
					),
					static::FIELD_NUM_COLS          => array(
						'key'           => $num_cols,
						'name'          => static::FIELD_NUM_COLS,
						'label'         => 'Number of Columns (Desktop)',
						'type'          => 'button_group',
						'required'      => true,
						'choices'       => array(
							3 => '3',
							4 => '4',
						),
						'default_value' => 4,
						'wrapper'       => array(
							'width' => '50%',
						),
					),
					static::FIELD_PLACEHOLDER_IMG   => array(
						'key'           => $placeholder_img,
						'name'          => static::FIELD_PLACEHOLDER_IMG,
						'label'         => 'Placeholder Image',
						'type'          => 'image',
						'required'      => false,
						'return_format' => 'array',
						'preview_size'  => 'small',
						'library'       => 'all',
						'wrapper'       => array(
							'width' => '50%',
						),
					),
					static::FIELD_NUM_DISPLAY_LIMIT => array(
						'key'          => $num_display_limit,
						'name'         => static::FIELD_NUM_DISPLAY_LIMIT,
						'label'        => 'Display Limit',
						'instructions' => 'Number of items to be displayed before clicking <i>Load More</i>',
						'type'         => 'number',
						'wrapper'      => array(
							'width' => '50%',
						),
					),
					static::FIELD_PAGINATION_STYLE  => array(
						'key'           => $pagination_style,
						'name'          => static::FIELD_PAGINATION_STYLE,
						'label'         => 'Pagination Style',
						'type'          => 'radio',
						'choices'       => array(
							static::PAGINATION_STYLE_OPTION_LOADMORE => 'Load More',
							static::PAGINATION_STYLE_OPTION_PAGES => 'Pages',
						),
						'default_value' => static::PAGINATION_STYLE_OPTION_LOADMORE,
						'layout'        => 'horizontal',
						'return_format' => 'value',
						'wrapper'       => array(
							'width' => '50%',
						),
					),
				),
				static::_gen_tab_field(
					'Posts',
					array(
						'conditional_logic' => array(
							array(
								array(
									'field'    => $source,
									'operator' => '==',
									'value'    => static::SOURCE_PICK,
								),
							),
							array(
								array(
									'field'    => $source,
									'operator' => '==',
									'value'    => static::SOURCE_QUERY,
								),
							),
							array(
								array(
									'field'    => $source,
									'operator' => '==',
									'value'    => static::SOURCE_CUSTOM,
								),
							),
						),
					)
				),
				array(
					static::FIELD_QUERY_PTS     => array(
						'key'               => $q_pts,
						'name'              => static::FIELD_QUERY_PTS,
						'label'             => 'Post Types',
						'type'              => 'select',
						'choices'           => array( /* @see load_field_post_query_pts() */ ),
						'multiple'          => true,
						'ui'                => true,
						'conditional_logic' => array(
							array(
								array(
									'field'    => $source,
									'operator' => '==',
									'value'    => static::SOURCE_QUERY,
								),
							),
						),
					),
					static::FIELD_QUERY_TAXS    => array(
						'key'               => $q_taxs,
						'name'              => static::FIELD_QUERY_TAXS,
						'label'             => 'Taxonomies',
						'type'              => 'repeater',
						'conditional_logic' => array(
							array(
								array(
									'field'    => $source,
									'operator' => '==',
									'value'    => static::SOURCE_QUERY,
								),
							),
						),
						'sub_fields'        => array(
							static::SUBFIELD_TAXS_TAX   => array(
								'key'     => self::gen_field_key( static::SUBFIELD_TAXS_TAX ),
								'name'    => static::SUBFIELD_TAXS_TAX,
								'type'    => 'select',
								'choices' => array(),
							),
							static::SUBFIELD_TAXS_TERMS => array(
								'key'         => self::gen_field_key( static::SUBFIELD_TAXS_TERMS ),
								'name'        => static::SUBFIELD_TAXS_TERMS,
								'type'        => 'text',
								'placeholder' => '123,456,780',
							),
						),
					),
					static::FIELD_POST_ITEMS    => array(
						'key'               => $post_items,
						'name'              => static::FIELD_POST_ITEMS,
						'label'             => 'Posts',
						'type'              => 'relationship',
						'required'          => true,
						'return_format'     => 'object',
						'filters'           => array(
							0 => 'search',
							1 => 'post_type',
							2 => 'taxonomy',
						),
						'elements'          => array(
							0 => 'featured_image',
						),
						'conditional_logic' => array(
							array(
								array(
									'field'    => $source,
									'operator' => '==',
									'value'    => static::SOURCE_PICK,
								),
							),
						),
					),
					static::FIELD_CUSTOM_ITEMS  => array(
						'key'               => $custom_items,
						'name'              => static::FIELD_CUSTOM_ITEMS,
						'label'             => 'Custom Items',
						'type'              => 'repeater',
						'required'          => true,
						'layout'            => 'block',
						'collapsed'         => $custom_item_title,
						'button_label'      => 'Add Item',
						'conditional_logic' => array(
							array(
								array(
									'field'    => $source,
									'operator' => '==',
									'value'    => static::SOURCE_CUSTOM,
								),
							),
						),
						'sub_fields'        => array(
							array(
								'key'   => $custom_item_title,
								'name'  => static::FIELD_CUSTOM_ITEM_TITLE,
								'label' => 'Title',
								'type'  => 'text',
							),
							array(
								'key'   => $custom_item_desc,
								'name'  => static::FIELD_CUSTOM_ITEM_DESC,
								'label' => 'Desc',
								'type'  => 'textarea',
							),
							Button::clone(
								array(
									'key'     => $custom_item_cta,
									'name'    => static::FIELD_CUSTOM_ITEM_CTA,
									'label'   => 'CTA',
									'display' => 'group',
								),
							),
							DOM_Attr::clone(
								array(
									'key'   => static::gen_field_key( 'item_attrs' ),
									'name'  => 'item_attrs',
									'label' => 'Item Ittributes',
								)
							),
						),
					),
					static::FIELD_SHOW_EMPTY    => array(
						'key'     => $show_empty,
						'name'    => static::FIELD_SHOW_EMPTY,
						'type'    => 'select',
						'label'   => 'Show Empty Posts Message',
						'choices' => array(
							0 => 'No',
							1 => 'Yes',
						),
						'layout'  => 'horizontal',
					),
					static::FIELD_EMPTY_MESSAGE => array(
						'key'               => $empty_message,
						'name'              => static::FIELD_EMPTY_MESSAGE,
						'type'              => 'text',
						'label'             => 'Empty Posts Message',
						'conditional_logic' => array(
							array(
								array(
									'field'    => $show_empty,
									'operator' => '==',
									'value'    => 1,
								),
							),
						),
					),
				),
				static::_gen_tab_field( 'Attributes' ),
				array(
					static::FIELD_WRAPPER_ATTR => DOM_Attr::clone(
						array(
							'key'  => $wrapper_attr,
							'name' => static::FIELD_WRAPPER_ATTR,
						)
					),
				)
			),
		);
	}

	/** @inheritDoc */
	public static function get_block_args(): array {
		return array(
			'name'       => static::get_key(),
			'title'      => 'Post Grid',
			'category'   => 'common',
			'icon'       => 'book-alt',
			'post_types' => array( 'page' ),
		);
	}

	/** @inheritDoc */
	public static function render( $post = false, array $data = null, array $options = array() ): ?string {
		$val             = new Field_Val_Getter( static::class, $post, $data );
		$source          = $val->get( static::FIELD_SOURCE );
		$num_cols        = $val->get( static::FIELD_NUM_COLS );
		$placeholder_img = $val->get( static::FIELD_PLACEHOLDER_IMG );
		$heading         = static::get_val( static::FIELD_HEADING );
		$posts           = static::_get_posts( $val );
		$display_limit   = (int) $val->get( static::FIELD_NUM_DISPLAY_LIMIT );
		$show_empty      = (int) $val->get( static::FIELD_SHOW_EMPTY );
		$empty_message   = $val->get( static::FIELD_EMPTY_MESSAGE );
		$cls             = array(
			'tile-grid-container mx-auto',
			'mb-px38 mt-px40',
			'md:mt-px30',
			'xl:mt-0',
		);

		$social_media_accounts = array(
			array(
				'url'  => Social_Media_Accounts::get_val( Social_Media_Accounts::SETTING_FACEBOOK_URL ),
				'icon' => 'trevor-ti-facebook',
			),
			array(
				'url'  => Social_Media_Accounts::get_val( Social_Media_Accounts::SETTING_TWITTER_URL ),
				'icon' => 'trevor-ti-twitter',
			),
			array(
				'url'  => Social_Media_Accounts::get_val( Social_Media_Accounts::SETTING_INSTAGRAM_URL ),
				'icon' => 'trevor-ti-instagram',
			),
		);

		if ( ! empty( $options['class'] ) ) {
			$cls = array_merge( $cls, $options['class'] );
		}

		if ( ! $num_cols || $num_cols < 3 ) {
			$num_cols = 3;
		}

		$tile_options = array(
			'accordion' => false,
			'class'     => ( ! empty( $options['tileClass'] ) ) ? $options['tileClass'] : array(),
		);

		if ( ! empty( $options['num_words'] ) ) {
			$tile_options['num_words'] = $options['num_words'];
		}

		if ( ! empty( $options['smAccordion'] ) ) {
			$tile_options['accordion'] = true;

			$cls[] = 'sm-accordion';
		}

		$tile_options['placeholder_image'] = ( ! empty( $placeholder_img ) ) ? $placeholder_img : null;

		if ( ! empty( $posts ) ) {
			if ( count( $posts ) < 3 ) {
				$cls[] = 'desktop-autofit-columns';
			} else {
				// number of columns on XL breakpoint
				$cls[] = "desktop-{$num_cols}-columns";
			}
		}

		$count = 0;

		$wrapper_attrs = DOM_Attr::get_attrs_of( static::get_val( static::FIELD_WRAPPER_ATTR ), $cls );

		if ( ! empty( $options['wrapper_attrs'] ) && ! empty( $options['wrapper_attrs']['class'] ) ) {
			$wrapper_attrs['class'] .= ' ' . $options['wrapper_attrs']['class'];
		}

		$id = ( ! empty( $wrapper_attrs['id'] ) ) ? $wrapper_attrs['id'] : uniqid( 'tile-container-' );

		$wrapper_attr['id'] = $id;

		# Build post grid classnames
		$post_grid_cls = 'post-grid';

		# Build heading classnames
		$heading_cls   = array( 'text-center text-teal-dark font-bold' );
		$heading_cls[] = 'mt-px80 text-px32 leading-px40 mb-px40';
		$heading_cls[] = 'md:pt-px72 md:mb-px40 md:leading-px42 md:mt-0';
		$heading_cls[] = 'xl:text-px46 xl:leading-px56 xl:tracking-em_001 xl:pt-0';
		$heading_cls   = implode( ' ', $heading_cls );

		# Build empty message classnames
		$empty_msg_cls   = array( 'post-grid-empty__message text-center' );
		$empty_msg_cls[] = 'text-px20 leading-px28 tracking-em001';
		$empty_msg_cls[] = 'md:px-px120';
		$empty_msg_cls[] = 'xl:text-px32 xl:leading-px52 xl:tracking-px_05 xl:px-px170';
		$empty_msg_cls   = implode( ' ', $empty_msg_cls );

		ob_start(); ?>
			<div class="<?php echo $post_grid_cls; ?>">
				<?php if ( ! empty( $heading ) ) : ?>
					<h2 class="<?php echo $heading_cls; ?>">
						<?php echo $heading; ?>
					</h2>
				<?php endif; ?>
				<?php if ( ! empty( $posts ) ) : ?>
					<?php if ( static::SOURCE_CUSTOM === $source ) : ?>
						<?php echo Helper\Tile_Grid::custom( $posts ); ?>
					<?php else : ?>
						<div <?php echo Tools::flat_attr( $wrapper_attrs ); ?>>
							<?php
							foreach ( $posts as $key => $post ) {
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
							}
							?>
						</div>
					<?php endif; ?>
				<?php elseif ( $show_empty && ! empty( $empty_message ) ) : ?>
					<div class="post-grid-empty mb-px40 xl:mt-px20">
						<div class="<?php echo $empty_msg_cls; ?>"><?php echo $empty_message; ?></div>
						<ul class="post-grid-empty__socials flex justify-center items-center mt-px50 md:mt-px20 xl:mt-px60">
							<?php
							foreach ( $social_media_accounts as $account ) :
								if ( ! empty( $account['url'] ) ) :
									?>
									<li class="w-px44 h-px44 mx-px10 flex justify-center items-center xl:w-px60 xl:h-px60">
										<a href="<?php echo esc_url( $account['url'] ); ?>">
											<i class="
											<?php
											echo esc_attr(
												implode(
													' ',
													array(
														$account['icon'],
														'text-teal-dark text-px30 xl:text-px40',
													)
												)
											);
											?>
														"></i>
										</a>
									</li>
									<?php
								endif;
							endforeach;
							?>
						</ul>
					</div>
				<?php endif; ?>

				<?php if ( $display_limit && $display_limit < count( $posts ) ) { ?>
					<div class="view-all-container text-center overflow-visible pb-2">
						<a class="view-all-cta wave-underline font-bold text-px24 leading-px34 md:text-px18 tracking-px05 xl:text-px26 xl:leading-px36 tracking-em001 border-b-2 text-teal-dark self-center"
						href="#!" data-tile-container="<?php echo $id; ?>">
							Load More
						</a>
					</div>
				<?php } ?>

			</div>
		<?php
		return ob_get_clean();
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

		switch ( $source ) {
			case static::SOURCE_QUERY:
				$q_args = array(
					'tax_query'      => array( 'relation' => 'OR' ),
					'posts_per_page' => $display_limit,
				);

				$post_type = $val->get( static::FIELD_QUERY_PTS );

				if ( ! empty( $post_type ) ) {
					$q_args['post_type'] = $post_type;

					if ( in_array( Partner_Prod::POST_TYPE, $post_type, true ) ) {
						$q_args['post__not_in'] = static::$rendered_posts;
					}
				}

				$taxs = $val->get( static::FIELD_QUERY_TAXS );

				if ( ! empty( $taxs ) ) {
					foreach ( $taxs as $tax ) {
						$q_args['tax_query'][] = array(
							'taxonomy' => $tax[ static::SUBFIELD_TAXS_TAX ],
							'terms'    => wp_parse_id_list( $tax[ static::SUBFIELD_TAXS_TERMS ] ),
						);
					}
				}

				$q     = new \WP_Query( $q_args );
				$posts = $q->posts;

				if ( ! empty( $post_type ) && in_array( Partner_Prod::POST_TYPE, $post_type, true ) ) {
					static::push_data_to_rendered_posts( $posts );
				}
				break;

			case static::SOURCE_CUSTOM:
				$posts = array();

				foreach ( static::get_val( static::FIELD_CUSTOM_ITEMS ) as $post ) {
					$cta_url = $post['custom_item_cta']['link'];

					$posts[] = array(
						'title'     => $post['custom_item_title'],
						'desc'      => $post['custom_item_desc'],
						'cta_txt'   => $post['custom_item_cta']['label'],
						'cta_url'   => ! empty( $post['custom_item_cta']['link'] ) ? $post['custom_item_cta']['link']['url'] : '',
						'cta_cls'   => array( $post['custom_item_cta']['button_attr']['class'] ),
						'tile_cls'  => array( $post['item_attrs_class'] ),
						'tile_attr' => DOM_Attr::get_attrs_of( $post['custom_item_cta']['button_attr'] ),
					);
				};
				break;

			case static::SOURCE_PAST_EVENTS:
			case static::SOURCE_UPCOMING_EVENTS:
					$order      = static::SOURCE_PAST_EVENTS === $source ? 'DESC' : 'ASC';
					$compare    = static::SOURCE_PAST_EVENTS === $source ? '<' : '>';
					$date_today = date( 'Ymd' );

					$args  = array(
						'posts_per_page' => $display_limit,
						'post_type'      => CPT\Event::POST_TYPE,
						'orderby'        => 'meta_value',
						'meta_key'       => Event::FIELD_DATE,
						'order'          => $order,
						'meta_query'     => array(
							'relation' => 'AND',
							array(
								'key'     => Event::FIELD_DATE,
								'value'   => $date_today,
								'compare' => $compare,
								'type'    => 'DATE',
							),
						),
					);
					$posts = get_posts( $args );
				break;

			default:
				$posts = $val->get( static::FIELD_POST_ITEMS );
				$posts = ! empty( $posts ) ? (array) $posts : array();
		}

		return $posts;
	}

	/**
	 * @param Field_Val_Getter $val
	 *
	 * @return array
	 */
	protected static function _get_fundraisers( Field_Val_Getter $val ): array {
		$posts         = array();
		$source        = $val->get( static::FIELD_SOURCE );
		$display_limit = ( ! empty( $val->get( static::FIELD_NUM_DISPLAY_LIMIT ) ) ) ? $val->get( static::FIELD_NUM_DISPLAY_LIMIT ) : 1;
		$campaign_id   = $val->get( static::FIELD_CAMPAIGN_ID );

		if ( static::SOURCE_TOP_INDIVIDUALS === $source && ! empty( $campaign_id ) ) {
			$posts = Content::get_fundraisers( $campaign_id, (int) $display_limit, true );
		} elseif ( static::SOURCE_TOP_TEAMS === $source && ! empty( $campaign_id ) ) {
			$posts = Content::get_fundraising_teams( $campaign_id, (int) $display_limit, true );
		}

		return $posts;
	}

	/**
	 * @inheritDoc
	 */
	public static function render_block( $block, $content = '', $is_preview = false, $post_id = 0 ): void {
		$data = (array) $block['data'];

		if ( have_rows( static::FIELD_QUERY_TAXS ) ) :
			$data[ static::FIELD_QUERY_TAXS ] = array();
			while ( have_rows( static::FIELD_QUERY_TAXS ) ) :
				the_row();
				$data[ static::FIELD_QUERY_TAXS ][] = array(
					static::SUBFIELD_TAXS_TAX   => get_sub_field( static::SUBFIELD_TAXS_TAX ),
					static::SUBFIELD_TAXS_TERMS => get_sub_field( static::SUBFIELD_TAXS_TERMS ),
				);
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
		if ( $field && ! empty( $field['key'] ) && static::gen_field_key( static::FIELD_QUERY_PTS ) === $field['key'] ) {
			$choices = array();
			foreach ( get_post_types( array(), 'objects' ) as $pt ) {
				$choices[ $pt->name ] = "{$pt->name}: {$pt->label}";
			}

			$field['choices'] = $choices;
		}

		return $field;
	}

	public static function load_field_post_query_taxs( $field ) {
		if ( $field && ! empty( $field['key'] ) && static::gen_field_key( static::SUBFIELD_TAXS_TAX ) === $field['key'] ) {
			$choices = &$field['choices'];
			foreach ( get_taxonomies( array(), 'object' ) as $tax ) {
				$choices[ $tax->name ] = "{$tax->name}: {$tax->label}";
			}
		}

		return $field;
	}

	protected static function push_data_to_rendered_posts( $posts ) {
		foreach ( $posts as $post ) {
			if ( ! in_array( $post->ID, static::$rendered_posts, true ) ) {
				array_push( static::$rendered_posts, $post->ID );
			}
		}
	}
}
