<?php namespace TrevorWP\Theme\ACF\Field_Group;

use TrevorWP\Theme\ACF\Field\Color;
use TrevorWP\CPT\Donate;
use TrevorWP\CPT\Get_Involved;
use TrevorWP\CPT\Research;
use TrevorWP\Theme\Helper;
use TrevorWP\Util\Tools;
use TrevorWP\Theme\ACF\Util\Field_Val_Getter;

class Topic_Cards extends A_Field_Group implements I_Block, I_Renderable {
	const FIELD_BG_COLOR                = 'bg_color';
	const FIELD_TEXT_COLOR              = 'text_color';
	const FIELD_TITLE                   = 'title';
	const FIELD_DESCRIPTION             = 'description';
	const FIELD_ENTRIES_SOURCE          = 'entries_source';
	const FIELD_TOPIC_ENTRIES           = 'topic_entries';
	const FIELD_TOPIC_ENTRY_TITLE       = 'topic_entry_title';
	const FIELD_TOPIC_ENTRY_DESCRIPTION = 'topic_entry_description';
	const FIELD_TOPIC_ENTRY_LINK        = 'topic_entry_link';
	const FIELD_BUTTON                  = 'button';
	const FIELD_MOBILE_LAYOUT           = 'show_mobile_accordion';
	const FIELD_ORDER                   = 'order';
	// Source dependent fields
	const FIELD_PRODUCTS         = 'products';
	const FIELD_PRODUCT_PARTNERS = 'product_partners';
	const FIELD_BILLS            = 'bills';
	const FIELD_LETTERS          = 'letters';
	const FIELD_SHOW_LOAD_MORE   = 'show_load_more';

	const DEFAULT_NUM_DISPLAY_LIMIT = 6;

	/**
	 * @inheritDoc
	 */
	protected static function prepare_register_args(): array {
		$bg_color                = static::gen_field_key( static::FIELD_BG_COLOR );
		$text_color              = static::gen_field_key( static::FIELD_TEXT_COLOR );
		$title                   = static::gen_field_key( static::FIELD_TITLE );
		$description             = static::gen_field_key( static::FIELD_DESCRIPTION );
		$entries_source          = static::gen_field_key( static::FIELD_ENTRIES_SOURCE );
		$topic_entries           = static::gen_field_key( static::FIELD_TOPIC_ENTRIES );
		$topic_entry_title       = static::gen_field_key( static::FIELD_TOPIC_ENTRY_TITLE );
		$topic_entry_description = static::gen_field_key( static::FIELD_TOPIC_ENTRY_DESCRIPTION );
		$topic_entry_link        = static::gen_field_key( static::FIELD_TOPIC_ENTRY_LINK );
		$button                  = static::gen_field_key( static::FIELD_BUTTON );
		$mobile_layout           = static::gen_field_key( static::FIELD_MOBILE_LAYOUT );
		$order                   = static::gen_field_key( static::FIELD_ORDER );
		$products                = static::gen_field_key( static::FIELD_PRODUCTS );
		$product_partners        = static::gen_field_key( static::FIELD_PRODUCT_PARTNERS );
		$bills                   = static::gen_field_key( static::FIELD_BILLS );
		$letters                 = static::gen_field_key( static::FIELD_LETTERS );
		$show_load_more          = static::gen_field_key( static::FIELD_SHOW_LOAD_MORE );

		return array(
			'title'  => 'Topic Cards',
			'fields' => array(
				static::FIELD_BG_COLOR         => Color::gen_args(
					$bg_color,
					static::FIELD_BG_COLOR,
					array(
						'label'   => 'Background Color',
						'default' => 'white',
						'wrapper' => array(
							'width' => '50%',
						),
					)
				),
				static::FIELD_TEXT_COLOR       => Color::gen_args(
					$text_color,
					static::FIELD_TEXT_COLOR,
					array(
						'label'   => 'Text Color',
						'default' => 'teal-dark',
						'wrapper' => array(
							'width' => '50%',
						),
					),
				),
				static::FIELD_TITLE            => array(
					'key'   => $title,
					'name'  => static::FIELD_TITLE,
					'label' => 'Title',
					'type'  => 'text',
				),
				static::FIELD_DESCRIPTION      => array(
					'key'   => $description,
					'name'  => static::FIELD_DESCRIPTION,
					'label' => 'Description',
					'type'  => 'textarea',
				),
				static::FIELD_MOBILE_LAYOUT    => array(
					'key'               => $mobile_layout,
					'name'              => static::FIELD_MOBILE_LAYOUT,
					'label'             => 'Mobile Layout',
					'type'              => 'radio',
					'choices'           => array(
						'drawers' => 'Drawers',
						'stacked' => 'Stacked',
					),
					'allow_null'        => 0,
					'other_choice'      => 0,
					'default_value'     => 'drawers',
					'layout'            => 'horizontal',
					'return_format'     => 'value',
					'save_other_choice' => 0,
					'conditional_logic' => array(
						array(
							array(
								'field'    => $entries_source,
								'operator' => '==',
								'value'    => 'custom',
							),
						),
					),
				),
				static::FIELD_ENTRIES_SOURCE   => array(
					'key'           => $entries_source,
					'name'          => static::FIELD_ENTRIES_SOURCE,
					'label'         => 'Entries Source',
					'type'          => 'select',
					'choices'       => array(
						'custom'          => 'Custom',
						'products'        => 'Products',
						'partners'        => 'Product Partners',
						'bills'           => 'Bills',
						'letters'         => 'Letters',
						'research_briefs' => 'Research Briefs',
					),
					'default_value' => 'custom',
					'return_format' => 'value',
					'ui'            => 1,
				),
				static::FIELD_ORDER            => array(
					'key'               => $order,
					'name'              => static::FIELD_ORDER,
					'label'             => 'Order',
					'type'              => 'button_group',
					'choices'           => array(
						'alphabetical' => 'Alphabetical',
						'custom'       => 'Custom',
					),
					'default_value'     => 'alphabetical',
					'conditional_logic' => array(
						array(
							array(
								'field'    => $entries_source,
								'operator' => '==',
								'value'    => 'products',
							),
						),
					),
				),
				static::FIELD_TOPIC_ENTRIES    => array(
					'key'               => $topic_entries,
					'name'              => static::FIELD_TOPIC_ENTRIES,
					'label'             => 'Topic Entries',
					'type'              => 'repeater',
					'layout'            => 'block',
					'min'               => 1,
					'collapsed'         => $topic_entry_title,
					'conditional_logic' => array(
						array(
							array(
								'field'    => $entries_source,
								'operator' => '==',
								'value'    => 'custom',
							),
						),
					),
					'sub_fields'        => array_merge(
						array(
							static::FIELD_TOPIC_ENTRY_TITLE => array(
								'key'   => $topic_entry_title,
								'name'  => static::FIELD_TOPIC_ENTRY_TITLE,
								'label' => 'Title',
								'type'  => 'text',
							),
							static::FIELD_TOPIC_ENTRY_DESCRIPTION => array(
								'key'   => $topic_entry_description,
								'name'  => static::FIELD_TOPIC_ENTRY_DESCRIPTION,
								'label' => 'Description',
								'type'  => 'textarea',
							),
							static::FIELD_TOPIC_ENTRY_LINK => array(
								'key'        => $topic_entry_link,
								'name'       => static::FIELD_TOPIC_ENTRY_LINK,
								'label'      => 'Link',
								'type'       => 'group',
								'layout'     => 'block',
								'sub_fields' => Advanced_Link::_get_fields(),
							),
						),
					),
				),
				static::FIELD_PRODUCTS         => array(
					'key'               => $products,
					'name'              => static::FIELD_PRODUCTS,
					'label'             => 'Products',
					'type'              => 'relationship',
					'post_type'         => array(
						Donate\Partner_Prod::POST_TYPE,
					),
					'taxonomy'          => array(),
					'min'               => 1,
					'return_format'     => 'object',
					'conditional_logic' => array(
						array(
							array(
								'field'    => $entries_source,
								'operator' => '==',
								'value'    => 'products',
							),
						),
					),
				),
				static::FIELD_PRODUCT_PARTNERS => array(
					'key'               => $product_partners,
					'name'              => static::FIELD_PRODUCT_PARTNERS,
					'label'             => 'Product Partners',
					'type'              => 'post_object',
					'post_type'         => array(
						Donate\Prod_Partner::POST_TYPE,
					),
					'multiple'          => 1,
					'return_format'     => 'object',
					'ui'                => 1,
					'conditional_logic' => array(
						array(
							array(
								'field'    => $entries_source,
								'operator' => '==',
								'value'    => 'partners',
							),
						),
					),
				),
				static::FIELD_BILLS            => array(
					'key'               => $bills,
					'name'              => static::FIELD_BILLS,
					'label'             => 'Bills',
					'type'              => 'post_object',
					'post_type'         => array(
						Get_Involved\Bill::POST_TYPE,
					),
					'multiple'          => 1,
					'return_format'     => 'object',
					'ui'                => 1,
					'conditional_logic' => array(
						array(
							array(
								'field'    => $entries_source,
								'operator' => '==',
								'value'    => 'bills',
							),
						),
					),
				),
				static::FIELD_LETTERS          => array(
					'key'               => $letters,
					'name'              => static::FIELD_LETTERS,
					'label'             => 'Letters',
					'type'              => 'post_object',
					'post_type'         => array(
						Get_Involved\Letter::POST_TYPE,
					),
					'multiple'          => 1,
					'return_format'     => 'object',
					'ui'                => 1,
					'conditional_logic' => array(
						array(
							array(
								'field'    => $entries_source,
								'operator' => '==',
								'value'    => 'letters',
							),
						),
					),
				),
				static::FIELD_BUTTON           => Button::clone(
					array(
						'key'     => $button,
						'name'    => static::FIELD_BUTTON,
						'label'   => 'Button',
						'display' => 'group',
						'layout'  => 'block',
					)
				),
				static::FIELD_SHOW_LOAD_MORE   => array(
					'key'               => $show_load_more,
					'name'              => static::FIELD_SHOW_LOAD_MORE,
					'label'             => 'Load More Button',
					'type'              => 'true_false',
					'return_format'     => 'value',
					'default_value'     => 0,
					'ui'                => 1,
					'ui_on_text'        => 'Show',
					'ui_off_text'       => 'Hide',
					'conditional_logic' => array(
						array(
							array(
								'field'    => $entries_source,
								'operator' => '!=',
								'value'    => 'custom',
							),
						),
					),
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
				'title'      => 'Topic Cards',
				'post_types' => array( 'page' ),
			)
		);
	}

	/**
	 * @inheritDoc
	 */
	public static function render( $post = false, array $data = null, array $options = array() ): ?string {
		$text_color     = ! empty( static::get_val( static::FIELD_TEXT_COLOR ) ) ? static::get_val( static::FIELD_TEXT_COLOR ) : 'teal-dark';
		$bg_color       = ! empty( static::get_val( static::FIELD_BG_COLOR ) ) ? static::get_val( static::FIELD_BG_COLOR ) : 'white';
		$title          = static::get_val( static::FIELD_TITLE );
		$description    = static::get_val( static::FIELD_DESCRIPTION );
		$entries_source = static::get_val( static::FIELD_ENTRIES_SOURCE );
		$button_link    = static::get_val( static::FIELD_BUTTON );
		$attr           = array(
			'class' => 'topic-cards bg-' . $bg_color . ' ' . 'text-' . $text_color,
		);

		if ( 'custom' !== $entries_source ) {
			switch ( $entries_source ) {
				case 'products':
					$attr['data-post-type'] = Donate\Partner_Prod::POST_TYPE;
					break;
				case 'partners':
					$attr['data-post-type'] = Donate\Prod_Partner::POST_TYPE;
					break;
				case 'bills':
					$attr['data-post-type'] = Get_Involved\Bill::POST_TYPE;
					break;
				case 'letters':
					$attr['data-post-type'] = Get_Involved\Letter::POST_TYPE;
					break;
				case 'research_briefs':
					$attr['data-post-type'] = Research::POST_TYPE;
					break;
			}
		}

		ob_start();
		?>
		<div <?php echo Tools::flat_attr( $attr ); ?>>
			<div class="topic-cards__container">
				<?php if ( ! empty( $title ) ) : ?>
					<h2 class="topic-cards__heading"><?php echo esc_html( $title ); ?></h2>
				<?php endif; ?>
				<?php if ( ! empty( $description ) ) : ?>
					<p class="topic-cards__description"><?php echo esc_html( $description ); ?></p>
				<?php endif; ?>

				<?php
				switch ( $entries_source ) {
					case 'products':
						$posts = static::get_val( static::FIELD_PRODUCTS );
						$order = static::get_val( static::FIELD_ORDER );

						if ( 'alphabetical' === $order && ! empty( $posts ) ) {
							usort(
								$posts,
								function ( $a, $b ) {
									return strcmp( $a->post_title, $b->post_title );
								}
							);
						}

						echo static::render_posts( $posts );
						break;
					case 'partners':
						$posts = static::get_val( static::FIELD_PRODUCT_PARTNERS );
						echo static::render_posts( $posts );
						break;
					case 'bills':
						$posts = static::get_val( static::FIELD_BILLS );
						echo static::render_posts( $posts );
						break;
					case 'letters':
						$posts = static::get_val( static::FIELD_LETTERS );
						echo static::render_posts( $posts );
						break;
					case 'research_briefs':
						$posts = static::get_latest_research_briefs();
						echo static::render_posts( $posts );
						break;
					default:
						echo static::render_entries();
						break;
				}
				?>

				<?php if ( ! empty( $button_link ) && '' !== $button_link['label'] && '' !== $button_link['action'] ) : ?>
					<div class="topic-cards__block-cta-wrap">
						<?php
							echo Button::render(
								null,
								$button_link,
								array(
									'btn_cls' => array(),
								)
							);
						?>
					</div>
				<?php endif; ?>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * @inheritDoc
	 */
	private static function render_posts( $posts = array() ): string {
		$posts          = static::filter_entries( $posts );
		$display_limit  = static::DEFAULT_NUM_DISPLAY_LIMIT;
		$show_load_more = static::get_val( static::FIELD_SHOW_LOAD_MORE )
			&& ! empty( $posts )
			&& count( $posts ) > $display_limit;
		$tile_options   = array(
			'class' => array( 'topic-cards__item' ),
			'attr'  => array(
				'role' => 'listitem',
			),
		);

		// Limit the display to 6 posts only if showing load more.
		if ( $show_load_more ) {
			$posts = array_slice( $posts, 0, $display_limit );
		}

		/**
		 * Double check whether to show_load_more
		 * if there are more items to show from the selected post_type
		 */
		if ( $show_load_more && array_key_exists( 'post_type', (array) $posts[0] ) ) {
			$post_type   = ( (array) $posts[0] )['post_type'];
			$posts_ids   = wp_list_pluck( $posts, 'ID' );
			$other_posts = get_posts(
				array(
					'numberposts' => 1,
					'exclude'     => $posts_ids,
					'post_type'   => $post_type,
					'post_status' => 'publish',
				),
			);

			// If there is no more available posts, no sense to show the loadmore.
			if ( empty( static::filter_entries( $other_posts ) ) ) {
				$show_load_more = false;
			}
		}

		ob_start();
		?>
			<?php if ( ! empty( $posts ) && count( $posts ) > 0 ) : ?>
				<div class="topic-cards__grid" role="list">
					<?php foreach ( $posts as $key => $post ) : ?>
						<?php echo Helper\Tile::post( $post, $key, $tile_options ); ?>
					<?php endforeach; ?>
				</div>
				<?php if ( $show_load_more && count( $posts ) > 0 ) : ?>
					<div class="topic-cards__block-cta-wrap">
						<button class="topic-cards__load-more wave-underline" type="button" aria-label="click to load more items">Load More</button>
					</div>
				<?php endif; ?>
			<?php endif; ?>
		<?php
		return ob_get_clean();
	}

	/**
	 * @inheritDoc
	 */
	private static function render_entries(): string {
		$entries       = static::get_val( static::FIELD_TOPIC_ENTRIES );
		$topic_entries = static::filter_entries( $entries );
		$mobile_layout = static::get_val( static::FIELD_MOBILE_LAYOUT );
		$grid_class    = array(
			'topic-cards__grid',
		);

		if ( 'drawers' === $mobile_layout ) {
			$grid_class[] = 'mobile:hidden';
		}

		$grid_class = implode( ' ', $grid_class );

		ob_start();
		?>
			<?php if ( ! empty( $topic_entries ) && 'drawers' === $mobile_layout ) : ?>
				<div class="topic-cards__accordion" role="list">
					<?php foreach ( $topic_entries as $topic ) : ?>
						<div class="topic-cards__accordion-item js-accordion" role="listitem">
							<div class="topic-cards__accordion-header">
								<?php if ( ! empty( $topic[ static::FIELD_TOPIC_ENTRY_TITLE ] ) ) : ?>
									<h3 class="topic-cards__item-title"><?php echo esc_html( $topic[ static::FIELD_TOPIC_ENTRY_TITLE ] ); ?></h3>
								<?php endif; ?>
								<button
									class="topic-cards__accordion-toggle accordion-button"
									aria-label="click to expand <?php echo esc_attr( $topic[ static::FIELD_TOPIC_ENTRY_TITLE ] ); ?>">
								</button>
							</div>
							<div class="topic-cards__accordion-content accordion-collapse">
								<?php if ( ! empty( $topic[ static::FIELD_TOPIC_ENTRY_DESCRIPTION ] ) ) : ?>
									<p class="topic-cards__item-description"><?php echo esc_html( $topic[ static::FIELD_TOPIC_ENTRY_DESCRIPTION ] ); ?></p>
								<?php endif; ?>
								<?php
									echo Advanced_Link::render(
										null,
										$topic[ static::FIELD_TOPIC_ENTRY_LINK ],
										array(
											'class'      => array( 'topic-cards__cta wave-underline' ),
											'attributes' => array(
												'aria-label' => 'click to learn more about ' . $topic[ static::FIELD_TOPIC_ENTRY_TITLE ],
											),
										)
									);
								?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<?php if ( ! empty( $topic_entries ) ) : ?>
				<div class="<?php echo $grid_class; ?>" role="list">
					<?php foreach ( $topic_entries as $topic ) : ?>
						<div class="topic-cards__item topic-cards__item--bordered" role="listitem">
							<?php if ( ! empty( $topic[ static::FIELD_TOPIC_ENTRY_TITLE ] ) ) : ?>
								<h2 class="topic-cards__item-title"><?php echo esc_html( $topic[ static::FIELD_TOPIC_ENTRY_TITLE ] ); ?></h2>
							<?php endif; ?>

							<?php if ( ! empty( $topic[ static::FIELD_TOPIC_ENTRY_DESCRIPTION ] ) ) : ?>
								<p class="topic-cards__item-description"><?php echo esc_html( $topic[ static::FIELD_TOPIC_ENTRY_DESCRIPTION ] ); ?></p>
							<?php endif; ?>

							<?php if ( ! empty( $topic[ static::FIELD_TOPIC_ENTRY_LINK ]['label'] ) ) : ?>
								<?php
									echo Advanced_Link::render(
										null,
										$topic[ static::FIELD_TOPIC_ENTRY_LINK ],
										array(
											'class'      => array( 'topic-cards__cta wave-underline' ),
											'attributes' => array(
												'aria-label' => 'click to learn more about ' . $topic[ static::FIELD_TOPIC_ENTRY_TITLE ],
											),
										)
									);
								?>
							<?php endif; ?>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		<?php
		return ob_get_clean();
	}

	public static function filter_entries( $entries ): array {
		$entries_source = static::get_val( static::FIELD_ENTRIES_SOURCE );

		// Filter entries only if Products, otherwise just return all entries
		if ( 'products' !== $entries_source ) {
			return is_array( $entries ) ? $entries : array();
		}

		// Filter to show only products that are within the current date
		$filtered_entries  = array();
		$current_date_unix = time();

		foreach ( $entries as $entry ) {
			$entry_val  = new Field_Val_Getter( Product::class, $entry );
			$start_date = $entry_val->get( Product::FIELD_PRODUCT_START_DATE );
			$end_date   = $entry_val->get( Product::FIELD_PRODUCT_END_DATE );

			$start_unix = strtotime( $start_date );
			$end_unix   = strtotime( $end_date );

			// If start and end dates range is within the current date,
			// include this entry to the rendered cards
			if ( $start_unix <= $current_date_unix && $end_unix >= $current_date_unix ) {
				$filtered_entries[] = $entry;
			}
		}

		return $filtered_entries;
	}

	public static function get_latest_research_briefs() {
		$data = get_posts(
			array(
				'post_type'   => Research::POST_TYPE,
				'numberposts' => 6,
				'orderby'     => 'date',
				'order'       => 'DESC',
			)
		);

		return $data;
	}

	/**
	 * @inheritDoc
	 */
	public static function render_block( $block, $content = '', $is_preview = false, $post_id = 0 ): void {
		echo static::render( $post_id, null, compact( 'is_preview' ) );
	}
}
