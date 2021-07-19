<?php namespace TrevorWP\Theme\ACF\Field_Group;

use TrevorWP\Theme\ACF\Field\Color;

class Text_Only_Messaging_Block extends A_Field_Group implements I_Block, I_Renderable {
	const FIELD_BG_COLOR    = 'bg_color';
	const FIELD_TEXT_COLOR  = 'text_color';
	const FIELD_HEADER      = 'header';
	const FIELD_DESCRIPTION = 'description';

	/**
	 * @inheritDoc
	 */
	protected static function prepare_register_args(): array {
		$bg_color    = static::gen_field_key( static::FIELD_BG_COLOR );
		$text_color  = static::gen_field_key( static::FIELD_TEXT_COLOR );
		$header      = static::gen_field_key( static::FIELD_HEADER );
		$description = static::gen_field_key( static::FIELD_DESCRIPTION );

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

		$styles = 'bg-' . $bg_color . ' ' . 'text-' . $text_color;

		ob_start();
		?>
		<div class="container mx-auto <?php echo esc_attr( $styles ); ?>">
			<?php if ( ! empty( $header ) ) : ?>
				<p><?php echo esc_html( $header ); ?></p>
			<?php endif; ?>

			<?php if ( ! empty( $description ) ) : ?>
				<h3><?php echo esc_html( $description ); ?></h3>
			<?php endif; ?>
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
