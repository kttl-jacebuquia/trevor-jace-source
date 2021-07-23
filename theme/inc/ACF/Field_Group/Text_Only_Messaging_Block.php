<?php namespace TrevorWP\Theme\ACF\Field_Group;

use TrevorWP\Theme\ACF\Field\Color;

class Text_Only_Messaging_Block extends A_Field_Group implements I_Block, I_Renderable {
	const FIELD_BG_COLOR    = 'bg_color';
	const FIELD_TEXT_COLOR  = 'text_color';
	const FIELD_HEADER      = 'header';
	const FIELD_DESCRIPTION = 'description';
	const FIELD_CTA         = 'cta';

	/**
	 * @inheritDoc
	 */
	protected static function prepare_register_args(): array {
		$bg_color    = static::gen_field_key( static::FIELD_BG_COLOR );
		$text_color  = static::gen_field_key( static::FIELD_TEXT_COLOR );
		$header      = static::gen_field_key( static::FIELD_HEADER );
		$description = static::gen_field_key( static::FIELD_DESCRIPTION );
		$cta         = static::gen_field_key( static::FIELD_CTA );

		return array(
			'title'  => 'Text Only Messaging Block',
			'fields' => array(
				static::FIELD_BG_COLOR    => Color::gen_args(
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
				static::FIELD_TEXT_COLOR  => Color::gen_args(
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
				static::FIELD_HEADER      => array(
					'key'   => $header,
					'name'  => static::FIELD_HEADER,
					'label' => 'Header',
					'type'  => 'text',
				),
				static::FIELD_DESCRIPTION => array(
					'key'   => $description,
					'name'  => static::FIELD_DESCRIPTION,
					'label' => 'Description',
					'type'  => 'textarea',
				),
				static::FIELD_CTA         => Button::clone(
					array(
						'key'     => $cta,
						'name'    => static::FIELD_CTA,
						'label'   => 'CTA',
						'display' => 'group',
						'layout'  => 'block',
					)
				)
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
				'title'      => 'Text Only Messaging Block',
				'post_types' => array( 'page' ),
			)
		);
	}

	/**
	 * @inheritDoc
	 */
	public static function render( $post = false, array $data = null, array $options = array() ): ?string {
		$text_color  = ! empty( static::get_val( static::FIELD_TEXT_COLOR ) ) ? static::get_val( static::FIELD_TEXT_COLOR ) : 'teal-dark';
		$bg_color    = ! empty( static::get_val( static::FIELD_BG_COLOR ) ) ? static::get_val( static::FIELD_BG_COLOR ) : 'white';
		$header      = static::get_val( static::FIELD_HEADER );
		$description = static::get_val( static::FIELD_DESCRIPTION );
		$cta         = static::get_val( static::FIELD_CTA );

		$styles = 'bg-' . $bg_color . ' ' . 'text-' . $text_color;

		$cta_options = array(
			'btn_cls' => array( 'text-only-messaging__cta' ),
		);

		ob_start();
		?>
		<div class="text-only-messaging">
			<div class="text-only-messaging__container">
				<div class="text-only-messaging__box <?php echo esc_attr( $styles ); ?>">
					<?php if ( ! empty( $header ) ) : ?>
						<h2 class="text-only-messaging__heading"><?php echo esc_html( $header ); ?></h2>
					<?php endif; ?>

					<?php if ( ! empty( $description ) ) : ?>
						<p class="text-only-messaging__body"><?php echo esc_html( $description ); ?></p>
					<?php endif; ?>

					<?php if ( ! empty( $cta ) ) : ?>
						<div class="text-only-messaging__cta-wrap">
							<?php echo Button::render( false, $cta, $cta_options ); ?>
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
