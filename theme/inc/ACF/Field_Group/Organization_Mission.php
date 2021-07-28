<?php

namespace TrevorWP\Theme\ACF\Field_Group;

class Organization_Mission extends A_Field_Group implements I_Block, I_Renderable {

	const FIELD_TITLE                   = 'title';
	const FIELD_CARDS                   = 'cards';
	const FIELD_CARDS_ENTRY_ICON        = 'cards_entry_icon';
	const FIELD_CARDS_ENTRY_HEADER      = 'cards_entry_header';
	const FIELD_CARDS_ENTRY_DESCRIPTION = 'cards_entry_description';
	const FIELD_CARDS_ENTRY_LINK        = 'cards_entry_link';
	const FIELD_BUTTON                  = 'button';

	/**
	 * @inheritDoc
	 */
	protected static function prepare_register_args(): array {
		$title                   = static::gen_field_key( static::FIELD_TITLE );
		$cards                   = static::gen_field_key( static::FIELD_CARDS );
		$cards_entry_icon        = static::gen_field_key( static::FIELD_CARDS_ENTRY_ICON );
		$cards_entry_header      = static::gen_field_key( static::FIELD_CARDS_ENTRY_HEADER );
		$cards_entry_description = static::gen_field_key( static::FIELD_CARDS_ENTRY_DESCRIPTION );
		$cards_entry_link        = static::gen_field_key( static::FIELD_CARDS_ENTRY_LINK );
		$button                  = static::gen_field_key( static::FIELD_BUTTON );

		return array(
			'title'  => 'Organization Mission Block',
			'fields' => array(
				static::FIELD_TITLE  => array(
					'key'   => $title,
					'name'  => static::FIELD_TITLE,
					'label' => 'Title',
					'type'  => 'text',
				),
				static::FIELD_CARDS  => array(
					'key'          => $cards,
					'name'         => static::FIELD_CARDS,
					'label'        => 'Cards',
					'type'         => 'repeater',
					'layout'       => 'block',
					'collapsed'    => $cards_entry_header,
					'button_label' => 'Add Card',
					'sub_fields'   => array(
						static::FIELD_CARDS_ENTRY_ICON   => array(
							'key'           => $cards_entry_icon,
							'name'          => static::FIELD_CARDS_ENTRY_ICON,
							'label'         => 'Icon',
							'type'          => 'image',
							'return_format' => 'array',
							'preview_size'  => 'thumbnail',
							'library'       => 'all',
						),
						static::FIELD_CARDS_ENTRY_HEADER => array(
							'key'   => $cards_entry_header,
							'name'  => static::FIELD_CARDS_ENTRY_HEADER,
							'label' => 'Header',
							'type'  => 'text',
						),
						static::FIELD_CARDS_ENTRY_DESCRIPTION => array(
							'key'   => $cards_entry_description,
							'name'  => static::FIELD_CARDS_ENTRY_DESCRIPTION,
							'label' => 'Description',
							'type'  => 'textarea',
						),
						static::FIELD_CARDS_ENTRY_LINK   => array(
							'key'           => $cards_entry_link,
							'name'          => static::FIELD_CARDS_ENTRY_LINK,
							'label'         => 'Link',
							'type'          => 'link',
							'return_format' => 'array',
						),
					),
				),
				static::FIELD_BUTTON => array(
					'key'           => $button,
					'name'          => static::FIELD_BUTTON,
					'label'         => 'Button',
					'type'          => 'link',
					'return_format' => 'array',
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
				'title'      => 'Organization Mission Block',
				'post_types' => array( 'page' ),
			)
		);
	}

	/**
	 * @inheritDoc
	 */
	public static function render( $post = false, array $data = null, array $options = array() ): ?string {
		$title  = static::get_val( static::FIELD_TITLE );
		$cards  = static::get_val( static::FIELD_CARDS );
		$button = static::get_val( static::FIELD_BUTTON );

		ob_start();
		?>
		<div>
			<?php if ( ! empty( $title ) ) : ?>
				<h3><?php echo esc_html( $title ); ?></h3>
			<?php endif; ?>

			<?php if ( ! empty( $cards ) ) : ?>
				<div>
					<?php foreach ( $cards as $card ) : ?>
						<div>
							<?php if ( ! empty( $card[ static::FIELD_CARDS_ENTRY_ICON ]['url'] ) ) : ?>
								<img src="<?php echo esc_url( $card[ static::FIELD_CARDS_ENTRY_ICON ]['url'] ); ?>" alt="<?php echo ( ! empty( $card[ static::FIELD_CARDS_ENTRY_ICON ]['alt'] ) ) ? esc_attr( $card[ static::FIELD_CARDS_ENTRY_ICON ]['alt'] ) : esc_attr( $title ); ?>">
							<?php endif; ?>

							<?php if ( ! empty( $card[ static::FIELD_CARDS_ENTRY_HEADER ] ) ) : ?>
								<h2><?php echo esc_html( $card[ static::FIELD_CARDS_ENTRY_HEADER ] ); ?></h2>
							<?php endif; ?>

							<?php if ( ! empty( $card[ static::FIELD_CARDS_ENTRY_DESCRIPTION ] ) ) : ?>
								<p><?php echo esc_html( $card[ static::FIELD_CARDS_ENTRY_DESCRIPTION ] ); ?></p>
							<?php endif; ?>

							<?php if ( ! empty( $card[ static::FIELD_CARDS_ENTRY_LINK ]['url'] ) ) : ?>
								<a href="<?php echo esc_url( $card[ static::FIELD_CARDS_ENTRY_LINK ]['url'] ); ?>" target="<?php echo esc_attr( $card[ static::FIELD_CARDS_ENTRY_LINK ]['target'] ); ?>"><?php echo esc_html( $card[ static::FIELD_CARDS_ENTRY_LINK ]['title'] ); ?></a>
							<?php endif; ?>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<?php if ( ! empty( $button['url'] ) && ! empty( $button['title'] ) ) : ?>
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
