<?php namespace TrevorWP\Theme\ACF\Field_Group;

use TrevorWP\Theme\ACF\Field\Color;
use TrevorWP\CPT\Donate;
use TrevorWP\CPT\Get_Involved;
use TrevorWP\Theme\Helper;
use TrevorWP\Util\Tools;

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
	// Source dependent fields
	const FIELD_PRODUCTS         = 'products';
	const FIELD_PRODUCT_PARTNERS = 'product_partners';
	const FIELD_BILLS            = 'bills';
	const FIELD_LETTERS          = 'letters';
	const FIELD_SHOW_LOAD_MORE   = 'show_load_more';

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
						'custom'   => 'Custom',
						'products' => 'Products',
						'partners' => 'Product Partners',
						'bills'    => 'Bills',
						'letters'  => 'Letters',
					),
					'default_value' => 'custom',
					'return_format' => 'value',
					'ui'            => 1,
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
					'type'              => 'post_object',
					'post_type'         => array(
						Donate\Partner_Prod::POST_TYPE,
					),
					'multiple'          => 1,
					'return_format'     => 'object',
					'ui'                => 1,
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
				static::FIELD_BUTTON           => array(
					'key'               => $button,
					'name'              => static::FIELD_BUTTON,
					'label'             => 'Button',
					'type'              => 'link',
					'return_format'     => 'array',
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

		$attr = array(
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
					default:
						echo static::render_entries();
						break;
				}
				?>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}
	/**
	 * @inheritDoc
	 */
	private static function render_posts( $posts = array() ): string {
		$show_load_more = static::get_val( static::FIELD_SHOW_LOAD_MORE );
		$tile_options   = array(
			'class' => array( 'topic-cards__item' ),
			'attr'  => array(
				'role' => 'listitem',
			),
		);

		ob_start();
		?>
			<?php if ( ! empty( $posts ) && count( $posts ) > 0 ) : ?>
				<div class="topic-cards__grid" role="list">
					<?php foreach ( $posts as $key => $post ) : ?>
						<?php echo Helper\Tile::post( $post, $key, $tile_options ); ?>
					<?php endforeach; ?>
				</div>
				<?php if ( $show_load_more ) : ?>
					<div class="topic-cards__block-cta-wrap">
						<button class="topic-cards__load-more" type="button" aria-label="click to load more items">Load More</button>
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
		$topic_entries = static::get_val( static::FIELD_TOPIC_ENTRIES );
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

			<?php if ( ! empty( $button['url'] ) ) : ?>
				<div class="topic-cards__block-cta-wrap">
					<a class="topic-cards__block-cta" href="<?php echo esc_url( $button['url'] ); ?>" target="<?php echo esc_attr( $button['target'] ); ?>"><?php echo esc_html( $button['title'] ); ?></a>
				</div>
			<?php endif; ?>
		<?php
		return ob_get_clean();
	}

	/**
	 * @inheritDoc
	 */
	public static function render_block( $block, $content = '', $is_preview = false, $post_id = 0 ): void {
		echo static::render( $post_id, null, compact( 'is_preview' ) );
	}
}
