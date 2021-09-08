<?php namespace TrevorWP\Theme\ACF\Field_Group;

use TrevorWP\Theme\ACF\Field\Color;

class Messaging_Block extends A_Field_Group implements I_Block, I_Renderable {
	const FIELD_TITLE          = 'title';
	const FIELD_HEADER         = 'header';
	const FIELD_DESCRIPTION    = 'description';
	const FIELD_LARGE_TEXT     = 'large_text';
	const FIELD_BLOCK_STYLES   = 'block_styles';
	const FIELD_BG_COLOR       = 'bg_color';
	const FIELD_BOX_COLOR      = 'box_color';
	const FIELD_TEXT_COLOR     = 'text_color';
	const FIELD_EXTEND_PADDING = 'extend_padding';
	const FIELD_BUTTONS        = 'buttons';
	const FIELD_BUTTON         = 'button';
	const FIELD_TYPE           = 'type';

	/**
	 * @inheritDoc
	 */
	protected static function prepare_register_args(): array {
		$title          = static::gen_field_key( static::FIELD_TITLE );
		$header         = static::gen_field_key( static::FIELD_HEADER );
		$description    = static::gen_field_key( static::FIELD_DESCRIPTION );
		$large_text     = static::gen_field_key( static::FIELD_LARGE_TEXT );
		$block_styles   = static::gen_field_key( static::FIELD_BLOCK_STYLES );
		$box_color      = static::gen_field_key( static::FIELD_BOX_COLOR );
		$extend_padding = static::gen_field_key( static::FIELD_EXTEND_PADDING );
		$buttons        = static::gen_field_key( static::FIELD_BUTTONS );
		$type           = static::gen_field_key( static::FIELD_TYPE );

		return array(
			'title'  => 'Messaging Block',
			'fields' => array_merge(
				array(
					static::FIELD_BLOCK_STYLES => Block_Styles::clone(
						array(
							'key'     => $block_styles,
							'name'    => static::FIELD_BLOCK_STYLES,
							'label'   => 'Block Styles',
							'display' => 'seamless',
							'layout'  => 'block',
						)
					),
					static::FIELD_TYPE         => array(
						'key'           => $type,
						'name'          => static::FIELD_TYPE,
						'label'         => 'Type',
						'type'          => 'button_group',
						'default_value' => 'boxed',
						'choices'       => array(
							'boxed'      => 'Boxed',
							'large_text' => 'Large Text',
						),
						'ui'            => 1,
					),
				),
				static::_gen_conditional_fields(
					array(
						'field'    => $type,
						'operator' => '==',
						'value'    => 'boxed',
					),
					array(
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
									'wrapper'       => array(
										'width' => '50',
									),
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
									'wrapper'       => array(
										'width' => '50',
									),
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
							'key'       => $description,
							'name'      => static::FIELD_DESCRIPTION,
							'label'     => 'Description',
							'type'      => 'textarea',
							'new_lines' => 'br',
						),
						static::FIELD_BUTTONS        => Button_Group::clone(
							array(
								'key'     => $buttons,
								'name'    => static::FIELD_BUTTONS,
								'label'   => 'Buttons',
								'display' => 'group',
								'layout'  => 'block',
							)
						),
					),
				),
				static::_gen_conditional_fields(
					array(
						'field'    => $type,
						'operator' => '==',
						'value'    => 'large_text',
					),
					array(
						static::FIELD_LARGE_TEXT => array(
							'key'   => $large_text,
							'name'  => static::FIELD_LARGE_TEXT,
							'label' => 'Large Text',
							'type'  => 'textarea',
						),
					)
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
		$type                          = static::get_val( static::FIELD_TYPE );
		$extend_padding                = static::get_val( static::FIELD_EXTEND_PADDING );
		$extend_padding_top            = $extend_padding['top'] ?? 'no';
		$extend_padding_bottom         = $extend_padding['bottom'] ?? 'no';
		$block_styles                  = static::get_val( static::FIELD_BLOCK_STYLES );
		list( $bg_color, $text_color ) = array_values( $block_styles );

		$class = array( 'messaging', "messaging--{$type}", "bg-{$bg_color}", "text-{$text_color}" );

		if ( 'boxed' === $type ) {
			if ( 'yes' === $extend_padding_top ) {
				$class[] = ' extend-top';
			}
			if ( 'yes' === $extend_padding_bottom ) {
				$class[] = ' extend-bottom';
			}
		}

		ob_start();
		?>
		<div <?php echo static::render_attrs( $class ); ?>>
			<div class="messaging__container">
				<?php
				switch ( $type ) {
					case 'boxed':
						echo static::render_boxed_content( $post );
						break;
					case 'large_text':
						echo static::render_large_text();
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
	public static function render_boxed_content( $post ): string {
		$title       = static::get_val( static::FIELD_TITLE );
		$header      = static::get_val( static::FIELD_HEADER );
		$description = static::get_val( static::FIELD_DESCRIPTION );
		$box_color   = static::get_val( static::FIELD_BOX_COLOR );
		$buttons     = static::get_val( static::FIELD_BUTTONS );

		$box_styles = "bg-{$box_color}";

		ob_start();
		?>
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

			<?php if ( ! empty( $buttons['buttons'] ) ) : ?>
				<div class="messaging__buttons">
					<?php
					foreach ( $buttons['buttons'] as $button ) {
						echo Button::render( $post, $button['button'] );
					}
					?>
				</div>
			<?php endif; ?>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * @inheritDoc
	 */
	public static function render_large_text(): string {
		$large_text = static::get_val( static::FIELD_LARGE_TEXT );

		ob_start();
		?>
		<?php if ( ! empty( $large_text ) ) : ?>
			<p class="messaging__large-text"><?php echo $large_text; ?></p>
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
