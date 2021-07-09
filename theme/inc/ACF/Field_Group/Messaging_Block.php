<?php namespace TrevorWP\Theme\ACF\Field_Group;

use TrevorWP\Theme\ACF\Field;

class Messaging_Block extends A_Field_Group implements I_Block, I_Renderable {
	const FIELD_TITLE            = 'title';
	const FIELD_DESCRIPTION      = 'description';
	const FIELD_BG_COLOR         = 'bg_color';
	const FIELD_TEXT_COLOR       = 'text_color';
	const FIELD_BUTTONS          = 'buttons';
	const FIELD_ENTRY_BUTTON     = 'entry_button';
	const FIELD_ENTRY_BG_COLOR   = 'entry_bg_color';
	const FIELD_ENTRY_TEXT_COLOR = 'entry_text_color';

	/**
	 * @inheritDoc
	 */
	protected static function prepare_register_args(): array {
		$title            = static::gen_field_key( static::FIELD_TITLE );
		$description      = static::gen_field_key( static::FIELD_DESCRIPTION );
		$bg_color         = static::gen_field_key( static::FIELD_BG_COLOR );
		$text_color       = static::gen_field_key( static::FIELD_TEXT_COLOR );
		$buttons          = static::gen_field_key( static::FIELD_BUTTONS );
		$entry_button     = static::gen_field_key( static::FIELD_ENTRY_BUTTON );
		$entry_bg_color   = static::gen_field_key( static::FIELD_ENTRY_BG_COLOR );
		$entry_text_color = static::gen_field_key( static::FIELD_ENTRY_TEXT_COLOR );

		return array(
			'title'  => 'Messaging Block',
			'fields' => array(
				static::FIELD_BG_COLOR    => Field\Color::gen_args(
					$bg_color,
					static::FIELD_BG_COLOR,
					array(
						'label'   => 'Background Color',
						'default' => 'white',
						'wrapper' => array(
							'width' => '50',
						),
					)
				),
				static::FIELD_TEXT_COLOR  => Field\Color::gen_args(
					$text_color,
					static::FIELD_TEXT_COLOR,
					array(
						'label'   => 'Text Color',
						'default' => 'teal-dark',
						'wrapper' => array(
							'width' => '50',
						),
					),
				),
				static::FIELD_TITLE       => array(
					'key'      => $title,
					'name'     => static::FIELD_TITLE,
					'label'    => 'Title',
					'type'     => 'text',
					'required' => 1,
				),
				static::FIELD_DESCRIPTION => array(
					'key'   => $description,
					'name'  => static::FIELD_DESCRIPTION,
					'label' => 'Description',
					'type'  => 'textarea',
				),
				static::FIELD_BUTTONS     => array(
					'key'          => $buttons,
					'name'         => static::FIELD_BUTTONS,
					'label'        => 'Buttons',
					'type'         => 'repeater',
					'layout'       => 'block',
					'collapsed'    => $entry_button,
					'button_label' => 'Add Button',
					'min'          => 0,
					'max'          => 2,
					'sub_fields'   => array(
						static::FIELD_ENTRY_BG_COLOR   => Field\Color::gen_args(
							$entry_bg_color,
							static::FIELD_ENTRY_BG_COLOR,
							array(
								'label'   => 'Background Color',
								'default' => 'white',
								'wrapper' => array(
									'width' => '50',
								),
							)
						),
						static::FIELD_ENTRY_TEXT_COLOR => Field\Color::gen_args(
							$entry_text_color,
							static::FIELD_ENTRY_TEXT_COLOR,
							array(
								'label'   => 'Text Color',
								'default' => 'teal-dark',
								'wrapper' => array(
									'width' => '50',
								),
							),
						),
						static::FIELD_ENTRY_BUTTON     => array(
							'key'      => $entry_button,
							'name'     => static::FIELD_ENTRY_BUTTON,
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
		$title       = static::get_val( static::FIELD_TITLE );
		$description = static::get_val( static::FIELD_DESCRIPTION );
		$bg_color    = static::get_val( static::FIELD_BG_COLOR );
		$text_color  = static::get_val( static::FIELD_TEXT_COLOR );
		$buttons     = static::get_val( static::FIELD_BUTTONS );

		$wrap_cls = array();

		if ( ! empty( $bg_color ) ) {
			$wrap_cls[] = "bg-{$bg_color}";
		}

		if ( ! empty( $text_color ) ) {
			$wrap_cls[] = "text-{$text_color}";
		}

		ob_start();
		?>
		<div class="container mx-auto <?php echo esc_attr( implode( ' ', $wrap_cls ) ); ?>">
			<?php if ( ! empty( $title ) ) : ?>
				<h3><?php echo $title; ?></h3>
			<?php endif; ?>

			<?php if ( ! empty( $description ) ) : ?>
				<p><?php echo esc_html( $description ); ?></p>
			<?php endif; ?>

			<div>
				<?php if ( ! empty( $buttons ) ) : ?>
					<?php foreach ( $buttons as $button ) : ?>
						<?php
							$button_wrap_cls = array();

						if ( ! empty( $button[ static::FIELD_ENTRY_BG_COLOR ] ) ) {
							$button_wrap_cls[] = "bg-{$button[ static::FIELD_ENTRY_BG_COLOR ]}";
						}

						if ( ! empty( $button[ static::FIELD_ENTRY_TEXT_COLOR ] ) ) {
							$button_wrap_cls[] = "text-{$button[ static::FIELD_ENTRY_TEXT_COLOR ]}";
						}
						?>
						<a class="<?php echo esc_attr( implode( ' ', $button_wrap_cls ) ); ?>" 
						href="<?php echo esc_url( $button[ static::FIELD_ENTRY_BUTTON ]['url'] ); ?>"
						target="<?php echo esc_attr( $button[ static::FIELD_ENTRY_BUTTON ]['target'] ); ?>">
							<?php echo esc_html( $button[ static::FIELD_ENTRY_BUTTON ]['title'] ); ?>
						</a>
					<?php endforeach; ?>
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
