<?php namespace TrevorWP\Theme\ACF\Field_Group;

class Side_Text_Image extends A_Field_Group implements I_Block, I_Renderable {
	const FIELD_TITLE       = 'title';
	const FIELD_DESCRIPTION = 'description';
	const FIELD_BUTTON      = 'button';
	const FIELD_IMAGE       = 'image';
	const FIELD_STYLES      = 'styles';

	/**
	 * @inheritDoc
	 */
	protected static function prepare_register_args(): array {
		$title       = static::gen_field_key( static::FIELD_TITLE );
		$description = static::gen_field_key( static::FIELD_DESCRIPTION );
		$button      = static::gen_field_key( static::FIELD_BUTTON );
		$image       = static::gen_field_key( static::FIELD_IMAGE );
		$styles      = static::gen_field_key( static::FIELD_STYLES );

		return array(
			'title'  => 'Side by Side Text + Image',
			'fields' => array(
				static::FIELD_STYLES      => Block_Styles::clone(
					array(
						'key'  => $styles,
						'name' => static::FIELD_STYLES,
					)
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
				static::FIELD_BUTTON      => array(
					'key'           => $button,
					'name'          => static::FIELD_BUTTON,
					'label'         => 'Button',
					'type'          => 'link',
					'return_format' => 'array',
				),
				static::FIELD_IMAGE       => array(
					'key'           => $image,
					'name'          => static::FIELD_IMAGE,
					'label'         => 'Image',
					'type'          => 'image',
					'return_format' => 'array',
					'preview_size'  => 'thumbnail',
					'library'       => 'all',
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
				'title'      => 'Side by Side Text + Image',
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
		$button      = static::get_val( static::FIELD_BUTTON );
		$image       = static::get_val( static::FIELD_IMAGE );
		$styles      = static::get_val( static::FIELD_STYLES );

		list(
			$bg_color,
			$text_color,
		) = array_values( $styles );

		$text_container_class = array(
			'side-text-image',
			'bg-' . $bg_color,
			'text-' . $text_color,
		);

		ob_start();
		?>
		<div <?php echo static::render_attrs( $text_container_class ); ?>>
			<div class="side-text-image__container">
				<div class="side-text-image__text">
					<?php if ( ! empty( $title ) ) : ?>
						<h2 class="side-text-image__heading"><?php echo esc_html( $title ); ?></h2>
					<?php endif; ?>

					<?php if ( ! empty( $description ) ) : ?>
						<p class="side-text-image__description"><?php echo esc_html( $description ); ?></p>
					<?php endif; ?>

					<?php if ( ! empty( $button['url'] ) ) : ?>
						<a class="side-text-image__cta" href="<?php echo esc_url( $button['url'] ); ?>" target="<?php echo esc_attr( $button['target'] ); ?>"><?php echo esc_html( $button['title'] ); ?></a>
					<?php endif; ?>
				</div>

				<?php if ( ! empty( $image['url'] ) ) : ?>
					<div class="side-text-image__image">
						<img class="side-text-image__img" src="<?php echo esc_url( $image['url'] ); ?>" alt="<?php echo ( ! empty( $image['alt'] ) ) ? esc_attr( $image['alt'] ) : esc_attr( $title ); ?>">
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
