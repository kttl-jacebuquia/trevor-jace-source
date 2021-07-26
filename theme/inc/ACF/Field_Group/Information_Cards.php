<?php namespace TrevorWP\Theme\ACF\Field_Group;

use TrevorWP\Theme\ACF\Field\Color;

class Information_Cards extends A_Field_Group implements I_Block, I_Renderable {
	const FIELD_BG_COLOR           = 'bg_color';
	const FIELD_TEXT_COLOR         = 'text_color';
	const FIELD_TITLE              = 'title';
	const FIELD_DESCRIPTION        = 'description';
	const FIELD_CARDS              = 'cards_entries';
	const FIELD_CARDS_ENTRY_HEADER = 'cards_entry_header';
	const FIELD_CARDS_ENTRY_BODY   = 'cards_entry_body';
	const FIELD_CARDS_ENTRY_LINK   = 'cards_entry_link';
	const FIELD_BUTTON             = 'button';

	/**
	 * @inheritDoc
	 */
	protected static function prepare_register_args(): array {
		$bg_color           = static::gen_field_key( static::FIELD_BG_COLOR );
		$text_color         = static::gen_field_key( static::FIELD_TEXT_COLOR );
		$title              = static::gen_field_key( static::FIELD_TITLE );
		$description        = static::gen_field_key( static::FIELD_DESCRIPTION );
		$cards              = static::gen_field_key( static::FIELD_CARDS );
		$cards_entry_header = static::gen_field_key( static::FIELD_CARDS_ENTRY_HEADER );
		$cards_entry_body   = static::gen_field_key( static::FIELD_CARDS_ENTRY_BODY );
		$cards_entry_link   = static::gen_field_key( static::FIELD_CARDS_ENTRY_LINK );
		$button             = static::gen_field_key( static::FIELD_BUTTON );

		return array(
			'title'  => 'Copy components + Information cards + button',
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
				static::FIELD_TITLE       => array(
					'key'   => $title,
					'name'  => static::FIELD_TITLE,
					'label' => 'Title',
					'type'  => 'text',
				),
				static::FIELD_DESCRIPTION => array(
					'key'   => $description,
					'name'  => static::FIELD_DESCRIPTION,
					'label' => 'Description',
					'type'  => 'textarea',
				),
				static::FIELD_CARDS       => array(
					'key'        => $cards,
					'name'       => static::FIELD_CARDS,
					'label'      => 'Cards',
					'type'       => 'repeater',
					'layout'     => 'block',
					'min'        => 1,
					'max'        => 3,
					'collapsed'  => $cards_entry_header,
					'sub_fields' => array(
						static::FIELD_CARDS_ENTRY_HEADER => array(
							'key'      => $cards_entry_header,
							'name'     => static::FIELD_CARDS_ENTRY_HEADER,
							'label'    => 'Header',
							'type'     => 'text',
							'required' => 1,
						),
						static::FIELD_CARDS_ENTRY_BODY   => array(
							'key'      => $cards_entry_body,
							'name'     => static::FIELD_CARDS_ENTRY_BODY,
							'label'    => 'Body',
							'type'     => 'textarea',
							'required' => 1,
						),
						static::FIELD_CARDS_ENTRY_LINK   => array(
							'key'   => $cards_entry_link,
							'name'  => static::FIELD_CARDS_ENTRY_LINK,
							'label' => 'Link',
							'type'  => 'link',
						),
					),
				),
				static::FIELD_BUTTON      => array(
					'key'   => $button,
					'name'  => static::FIELD_BUTTON,
					'label' => 'Button',
					'type'  => 'link',
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
				'title'      => 'Copy components + Information cards + button',
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
		$title       = static::get_val( static::FIELD_TITLE );
		$description = static::get_val( static::FIELD_DESCRIPTION );
		$cards       = static::get_val( static::FIELD_CARDS );
		$button      = static::get_val( static::FIELD_BUTTON );

		$styles = 'bg-' . $bg_color . ' ' . 'text-' . $text_color;

		ob_start();
		// Next Step: FE
		?>
		<div class="<?php echo esc_attr( $styles ); ?>">
			<?php if ( ! empty( $title ) ) : ?>
				<h1><?php echo esc_html( $title ); ?></h1>
			<?php endif; ?>

			<?php if ( ! empty( $description ) ) : ?>
				<p><?php echo esc_html( $description ); ?></p>
			<?php endif; ?>

			<?php if ( ! empty( $cards ) ) : ?>
				<?php foreach ( $cards as $card ) : ?>
					<div>
						<?php if ( ! empty( $card[ static::FIELD_CARDS_ENTRY_HEADER ] ) ) : ?>
							<h2><?php echo esc_html( $card[ static::FIELD_CARDS_ENTRY_HEADER ] ); ?></h2>
						<?php endif; ?>

						<?php if ( ! empty( $card[ static::FIELD_CARDS_ENTRY_BODY ] ) ) : ?>
							<p><?php echo esc_html( $card[ static::FIELD_CARDS_ENTRY_BODY ] ); ?></p>
						<?php endif; ?>

						<?php if ( ! empty( $card[ static::FIELD_CARDS_ENTRY_LINK ]['url'] ) ) : ?>
							<a href="<?php echo esc_url( $card[ static::FIELD_CARDS_ENTRY_LINK ]['url'] ); ?>" target="<?php echo esc_attr( $card[ static::FIELD_CARDS_ENTRY_LINK ]['target'] ); ?>"><?php echo esc_html( $card[ static::FIELD_CARDS_ENTRY_LINK ]['title'] ); ?></a>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			<?php endif; ?>

			<?php if ( ! empty( $button['url'] ) ) : ?>
				<a href="<?php echo esc_url( $button['url'] ); ?>" target="<?php echo esc_attr( $button['target'] ); ?>"><?php echo esc_html( $button['title'] ); ?></a>
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
