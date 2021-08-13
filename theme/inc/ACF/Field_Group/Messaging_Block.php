<?php namespace TrevorWP\Theme\ACF\Field_Group;

use TrevorWP\Theme\ACF\Field\Color;

class Messaging_Block extends A_Field_Group implements I_Block, I_Renderable {
	const FIELD_TITLE               = 'title';
	const FIELD_HEADER              = 'header';
	const FIELD_DESCRIPTION         = 'description';
	const FIELD_BLOCK_STYLES        = 'block_styles';
	const FIELD_BG_COLOR            = 'bg_color';
	const FIELD_BOX_COLOR           = 'box_color';
	const FIELD_TEXT_COLOR          = 'text_color';
	const FIELD_EXTEND_PADDING      = 'extend_padding';
	const FIELD_BUTTONS             = 'buttons';
	const FIELD_BUTTON              = 'button';
	const FIELD_BUTTON_BG_COLOR     = 'button_bg_color';
	const FIELD_BUTTON_TEXT_COLOR   = 'button_text_color';
	const FIELD_BUTTON_BORDER_STYLE = 'button_border_style';

	/**
	 * @inheritDoc
	 */
	protected static function prepare_register_args(): array {
		$title               = static::gen_field_key( static::FIELD_TITLE );
		$header              = static::gen_field_key( static::FIELD_HEADER );
		$description         = static::gen_field_key( static::FIELD_DESCRIPTION );
		$block_styles        = static::gen_field_key( static::FIELD_BLOCK_STYLES );
		$box_color           = static::gen_field_key( static::FIELD_BOX_COLOR );
		$extend_padding      = static::gen_field_key( static::FIELD_EXTEND_PADDING );
		$buttons             = static::gen_field_key( static::FIELD_BUTTONS );
		$button              = static::gen_field_key( static::FIELD_BUTTON );
		$button_bg_color     = static::gen_field_key( static::FIELD_BUTTON_BG_COLOR );
		$button_text_color   = static::gen_field_key( static::FIELD_BUTTON_TEXT_COLOR );
		$button_border_style = static::gen_field_key( static::FIELD_BUTTON_BORDER_STYLE );

		return array(
			'title'  => 'Messaging Block',
			'fields' => array(
				static::FIELD_BLOCK_STYLES   => Block_Styles::clone(
					array(
						'key'     => $block_styles,
						'name'    => static::FIELD_BLOCK_STYLES,
						'label'   => 'Block Styles',
						'display' => 'seamless',
						'layout'  => 'block',
					)
				),
				static::FIELD_BOX_COLOR      => Color::gen_args(
					$box_color,
					static::FIELD_BOX_COLOR,
					array(
						'label'   => 'Inner Box Color',
						'default' => 'gray-light',
						'wrapper' => array(
							'width' => '50%',
						),
					)
				),
				static::FIELD_EXTEND_PADDING => array(
					'key'        => $extend_padding,
					'name'       => static::FIELD_EXTEND_PADDING,
					'label'      => 'Extend Padding',
					'type'       => 'group',
					'layout'     => 'block',
					'sub_fields' => array(

						'top'    => array(
							'key'           => 'top',
							'name'          => 'top',
							'label'         => 'Top',
							'type'          => 'button_group',
							'choices'       => array(
								'yes' => 'Yes',
								'no'  => 'No',
							),
							'default_value' => 'no',
						),
						'bottom' => array(
							'key'           => 'bottom',
							'name'          => 'bottom',
							'label'         => 'Bottom ',
							'type'          => 'button_group',
							'choices'       => array(
								'yes' => 'Yes',
								'no'  => 'No',
							),
							'default_value' => 'no',
						),
					),
				),
				static::FIELD_TITLE          => array(
					'key'   => $title,
					'name'  => static::FIELD_TITLE,
					'label' => 'Title',
					'type'  => 'text',
				),
				static::FIELD_HEADER         => array(
					'key'   => $header,
					'name'  => static::FIELD_HEADER,
					'label' => 'Header',
					'type'  => 'text',
				),
				static::FIELD_DESCRIPTION    => array(
					'key'   => $description,
					'name'  => static::FIELD_DESCRIPTION,
					'label' => 'Description',
					'type'  => 'textarea',
				),
				static::FIELD_BUTTONS        => array(
					'key'          => $buttons,
					'name'         => static::FIELD_BUTTONS,
					'label'        => 'Buttons',
					'type'         => 'repeater',
					'layout'       => 'block',
					'collapsed'    => $button,
					'button_label' => 'Add Button',
					'min'          => 0,
					'max'          => 2,
					'sub_fields'   => array(
						static::FIELD_BUTTON_BG_COLOR     => Color::gen_args(
							$button_bg_color,
							static::FIELD_BUTTON_BG_COLOR,
							array(
								'label'   => 'Background Color',
								'default' => 'white',
								'wrapper' => array(
									'width' => '50',
								),
							)
						),
						static::FIELD_BUTTON_TEXT_COLOR   => Color::gen_args(
							$button_text_color,
							static::FIELD_BUTTON_TEXT_COLOR,
							array(
								'label'   => 'Text Color',
								'default' => 'teal-dark',
								'wrapper' => array(
									'width' => '50',
								),
							),
						),
						static::FIELD_BUTTON_BORDER_STYLE => array(
							'key'           => $button_border_style,
							'name'          => static::FIELD_BUTTON_BORDER_STYLE,
							'label'         => 'Button Style',
							'type'          => 'button_group',
							'choices'       => array(
								'filled'   => 'Filled',
								'outlined' => 'Outlined',
								'link'     => 'Link',
							),
							'default_value' => 'filled',
						),
						static::FIELD_BUTTON              => array(
							'key'      => $button,
							'name'     => static::FIELD_BUTTON,
							'label'    => 'Button',
							'type'     => 'link',
							'required' => 1,
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
				'title'      => 'Messaging Block',
				'post_types' => array( 'page' ),
			)
		);
	}

	/**
	 * @inheritDoc
	 */
	public static function render( $post = false, array $data = null, array $options = array() ): ?string {
		$title                         = static::get_val( static::FIELD_TITLE );
		$header                        = static::get_val( static::FIELD_HEADER );
		$description                   = static::get_val( static::FIELD_DESCRIPTION );
		$block_styles                  = static::get_val( static::FIELD_BLOCK_STYLES );
		$box_color                     = static::get_val( static::FIELD_BOX_COLOR );
		$extend_padding                = static::get_val( static::FIELD_EXTEND_PADDING );
		$extend_padding_top            = $extend_padding['top'] ?? 'no';
		$extend_padding_bottom         = $extend_padding['bottom'] ?? 'no';
		$buttons                       = static::get_val( static::FIELD_BUTTONS );
		list( $bg_color, $text_color ) = array_values( $block_styles );

		$wrapper_styles = "bg-{$bg_color}";
		if ( 'yes' === $extend_padding_top ) {
			$wrapper_styles .= ' extend-top';
		}
		if ( 'yes' === $extend_padding_bottom ) {
			$wrapper_styles .= ' extend-bottom';
		}

		$box_styles = "bg-{$box_color} text-{$text_color}";

		ob_start();
		?>
		<div class="messaging <?php echo esc_attr( $wrapper_styles ); ?>">
			<div class="messaging__container">
				<div class="messaging__content <?php echo esc_attr( $box_styles ); ?>">
					<?php if ( ! empty( $title ) ) : ?>
						<h2 class="messaging__title"><?php echo $title; ?></h2>
					<?php endif; ?>

					<?php if ( ! empty( $header ) ) : ?>
						<h4 class="messaging__header"><?php echo $header; ?></h4>
					<?php endif; ?>

					<?php if ( ! empty( $description ) ) : ?>
						<p class="messaging__description"><?php echo $description; ?></p>
					<?php endif; ?>

					<?php if ( ! empty( $buttons ) ) : ?>
						<div class="messaging__buttons">
							<?php foreach ( $buttons as $button ) : ?>
								<?php
								$button_wrap_cls = array();

								$border_style      = ! empty( $button[ static::FIELD_BUTTON_BG_COLOR ] ) ? $button[ static::FIELD_BUTTON_BORDER_STYLE ] : 'none';
								$button_wrap_cls[] = "messaging__button--{$button[ static::FIELD_BUTTON_BORDER_STYLE ]}";
								if ( 'outlined' === $border_style ) {
									$button_wrap_cls[] = "border-2 border-{$button[ static::FIELD_BUTTON_TEXT_COLOR ]}";
								} elseif ( 'link' === $border_style ) {
									$button_wrap_cls[] = "border-b-2 border-{$button[ static::FIELD_BUTTON_TEXT_COLOR ]}";
								}

								if ( ! empty( $button[ static::FIELD_BUTTON_BG_COLOR ] && 'link' !== $border_style ) ) {
									$button_wrap_cls[] = "bg-{$button[ static::FIELD_BUTTON_BG_COLOR ]}";
								}

								if ( ! empty( $button[ static::FIELD_BUTTON_TEXT_COLOR ] ) ) {
									$button_wrap_cls[] = "text-{$button[ static::FIELD_BUTTON_TEXT_COLOR ]}";
								}

								?>
								<a class="messaging__button <?php echo esc_attr( implode( ' ', $button_wrap_cls ) ); ?>"
								href="<?php echo esc_url( $button[ static::FIELD_BUTTON ]['url'] ); ?>"
								target="<?php echo esc_attr( $button[ static::FIELD_BUTTON ]['target'] ); ?>">
									<?php echo esc_html( $button[ static::FIELD_BUTTON ]['title'] ); ?>
								</a>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</div>
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
