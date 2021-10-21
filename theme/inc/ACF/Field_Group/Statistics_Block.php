<?php namespace TrevorWP\Theme\ACF\Field_Group;

use TrevorWP\Theme\ACF\Field\Color;

class Statistics_Block extends A_Field_Group implements I_Block, I_Renderable {
	const FIELD_BG_COLOR                = 'bg_color';
	const FIELD_TEXT_COLOR              = 'text_color';
	const FIELD_TITLE                   = 'title';
	const FIELD_DESCRIPTION             = 'description';
	const FIELD_STATISTICS_ENTRIES      = 'statistics_entries';
	const FIELD_STATISTICS_ENTRY_HEADER = 'statistics_entry_header';
	const FIELD_STATISTICS_ENTRY_BODY   = 'statistics_entry_body';
	const FIELD_BUTTON                  = 'button';
	const FIELD_CAROUSEL_LAYOUT         = 'carousel_layout';

	/**
	 * @inheritDoc
	 */
	protected static function prepare_register_args(): array {
		$bg_color                = static::gen_field_key( static::FIELD_BG_COLOR );
		$text_color              = static::gen_field_key( static::FIELD_TEXT_COLOR );
		$title                   = static::gen_field_key( static::FIELD_TITLE );
		$description             = static::gen_field_key( static::FIELD_DESCRIPTION );
		$statistics_entries      = static::gen_field_key( static::FIELD_STATISTICS_ENTRIES );
		$statistics_entry_header = static::gen_field_key( static::FIELD_STATISTICS_ENTRY_HEADER );
		$statistics_entry_body   = static::gen_field_key( static::FIELD_STATISTICS_ENTRY_BODY );
		$button                  = static::gen_field_key( static::FIELD_BUTTON );
		$carousel_layout         = static::gen_field_key( static::FIELD_CAROUSEL_LAYOUT );

		return array(
			'title'  => 'Statistics Block',
			'fields' => array(
				static::FIELD_BG_COLOR           => Color::gen_args(
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
				static::FIELD_TEXT_COLOR         => Color::gen_args(
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
				static::FIELD_TITLE              => array(
					'key'   => $title,
					'name'  => static::FIELD_TITLE,
					'label' => 'Title',
					'type'  => 'text',
				),
				static::FIELD_DESCRIPTION        => array(
					'key'   => $description,
					'name'  => static::FIELD_DESCRIPTION,
					'label' => 'Description',
					'type'  => 'textarea',
				),
				static::FIELD_CAROUSEL_LAYOUT         => array(
					'key'           => $carousel_layout,
					'name'          => static::FIELD_CAROUSEL_LAYOUT,
					'label'         => 'Carousel Layout',
					'type'          => 'button_group',
					'default_value' => 'mobile_only',
					'choices'       => array(
						'mobile_only'       => 'Mobile Only',
						'mobile_and_tablet' => 'Mobile and Tablet',
					),
				),
				static::FIELD_STATISTICS_ENTRIES => array(
					'key'        => $statistics_entries,
					'name'       => static::FIELD_STATISTICS_ENTRIES,
					'label'      => 'Statistics Entries',
					'type'       => 'repeater',
					'layout'     => 'block',
					'min'        => 1,
					'max'        => 3,
					'collapsed'  => $statistics_entry_header,
					'sub_fields' => array(
						static::FIELD_STATISTICS_ENTRY_HEADER => array(
							'key'      => $statistics_entry_header,
							'name'     => static::FIELD_STATISTICS_ENTRY_HEADER,
							'label'    => 'Header',
							'type'     => 'text',
							'required' => 1,
						),
						static::FIELD_STATISTICS_ENTRY_BODY   => array(
							'key'      => $statistics_entry_body,
							'name'     => static::FIELD_STATISTICS_ENTRY_BODY,
							'label'    => 'Body',
							'type'     => 'textarea',
							'required' => 1,
						),
					),
				),
				static::FIELD_BUTTON             => array(
					'key'        => $button,
					'name'       => static::FIELD_BUTTON,
					'label'      => 'Button',
					'type'       => 'group',
					'layout'     => 'block',
					'sub_fields' => Advanced_Link::_get_fields(),
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
				'title'      => 'Statistics Block',
				'post_types' => array( 'page' ),
			)
		);
	}

	/**
	 * @inheritDoc
	 */
	public static function render( $post = false, array $data = null, array $options = array() ): ?string {
		$text_color         = ! empty( static::get_val( static::FIELD_TEXT_COLOR ) ) ? static::get_val( static::FIELD_TEXT_COLOR ) : 'teal-dark';
		$bg_color           = ! empty( static::get_val( static::FIELD_BG_COLOR ) ) ? static::get_val( static::FIELD_BG_COLOR ) : 'white';
		$title              = static::get_val( static::FIELD_TITLE );
		$description        = static::get_val( static::FIELD_DESCRIPTION );
		$button             = static::get_val( static::FIELD_BUTTON );
		$statistics_entries = static::get_val( static::FIELD_STATISTICS_ENTRIES );
		$carousel_layout    = static::get_val( static::FIELD_CAROUSEL_LAYOUT );

		$class = implode(
			' ',
			array(
				'statistics',
				'bg-' . $bg_color,
				'text-' . $text_color,
			)
		);

		ob_start();
		// Next Step: FE
		?>
		<div class="<?php echo $class; ?>" data-carousel-layout="<?php echo $carousel_layout; ?>">
			<div class="statistics__container">
				<?php if ( ! empty( $title ) ) : ?>
					<h2 class="statistics__heading"><?php echo esc_html( $title ); ?></h2>
				<?php endif; ?>

				<?php if ( ! empty( $description ) ) : ?>
					<div class="statistics__description"><?php echo esc_html( $description ); ?></div>
				<?php endif; ?>
				<?php if ( ! empty( $statistics_entries ) ) : ?>
					<div class="statistics__cards-container swiper-container">
						<div class="swiper-wrapper statistics__cards-wrapper">
							<?php foreach ( $statistics_entries as $statistic ) : ?>
								<div class="statistics__card swiper-slide">
									<div>
										<?php if ( ! empty( $statistic[ static::FIELD_STATISTICS_ENTRY_HEADER ] ) ) : ?>
											<h2 class="statistics__card-heading"><?php echo esc_html( $statistic[ static::FIELD_STATISTICS_ENTRY_HEADER ] ); ?></h2>
										<?php endif; ?>

										<?php if ( ! empty( $statistic[ static::FIELD_STATISTICS_ENTRY_BODY ] ) ) : ?>
											<div class="statistics__card-body"><?php echo esc_html( $statistic[ static::FIELD_STATISTICS_ENTRY_BODY ] ); ?></div>
										<?php endif; ?>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
						<div class="swiper-pagination"></div>
					</div>
				<?php endif; ?>
				<?php if ( ! empty( $button ) ) : ?>
					<div class="statistics__cta-wrap">
						<?php
							echo Advanced_Link::render(
								null,
								$button,
								array(
									'class' => array( 'statistics__cta' ),
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
	public static function render_block( $block, $content = '', $is_preview = false, $post_id = 0 ): void {
		echo static::render( $post_id, null, compact( 'is_preview' ) );
	}
}
