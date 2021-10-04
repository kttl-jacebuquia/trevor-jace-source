<?php namespace TrevorWP\Theme\ACF\Field_Group;

class Center_Text_Full_Width_Image extends A_Field_Group implements I_Block, I_Renderable {
	const FIELD_TITLE       = 'title';
	const FIELD_DESCRIPTION = 'description';
	const FIELD_IMAGE       = 'image';
	const FIELD_CAPTION     = 'caption';
	const FIELD_BUTTON      = 'button';

	/**
	 * @inheritDoc
	 */
	protected static function prepare_register_args(): array {
		$title       = static::gen_field_key( static::FIELD_TITLE );
		$description = static::gen_field_key( static::FIELD_DESCRIPTION );
		$image       = static::gen_field_key( static::FIELD_IMAGE );
		$caption     = static::gen_field_key( static::FIELD_CAPTION );
		$button      = static::gen_field_key( static::FIELD_BUTTON );

		return array(
			'title'  => 'Static Image with Copy',
			'fields' => array(
				static::FIELD_TITLE       => array(
					'key'     => $title,
					'name'    => static::FIELD_TITLE,
					'label'   => 'Title',
					'type'    => 'text',
					'wrapper' => array(
						'width' => '50%',
					),
				),
				static::FIELD_DESCRIPTION => array(
					'key'     => $description,
					'name'    => static::FIELD_DESCRIPTION,
					'label'   => 'Description',
					'type'    => 'textarea',
					'wrapper' => array(
						'width' => '50%',
					),
				),
				static::FIELD_IMAGE       => array(
					'key'           => $image,
					'name'          => static::FIELD_IMAGE,
					'label'         => 'Image',
					'type'          => 'image',
					'required'      => 1,
					'return_format' => 'array',
					'preview_size'  => 'thumbnail',
					'library'       => 'all',
				),
				static::FIELD_CAPTION     => array(
					'key'         => $caption,
					'name'        => static::FIELD_CAPTION,
					'label'       => 'Caption (Mobile and Tablet only)',
					'type'        => 'text',
					'placeholder' => 'e.g., Pinch to zoom',
				),
				static::FIELD_BUTTON      => Button::clone(
					array(
						'key'           => $button,
						'name'          => static::FIELD_BUTTON,
						'label'         => 'Button',
						'return_format' => 'array',
						'display'       => 'group',
						'layout'        => 'block',
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
				'title'      => 'Static Image with Copy',
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
		$image       = static::get_val( static::FIELD_IMAGE );
		$button      = static::get_val( static::FIELD_BUTTON );
		$caption     = static::get_val( static::FIELD_CAPTION );

		ob_start();
		?>

		<div class="center-text-with-full-image block-spacer">
			<div class="center-text-with-full-image__container">
				<h2 class="center-text-with-full-image__title"><?php echo esc_html( $title ); ?></h2>
				<p class="center-text-with-full-image__description"><?php echo esc_html( $description ); ?></p>
				<?php if ( $image ) : ?>
					<figure class="center-text-with-full-image__figure">
						<img
							src="<?php echo esc_url( $image['url'] ); ?>""
							alt="<?php echo ( ! empty( $image['alt'] ) ) ? esc_attr( $image['alt'] ) : esc_attr( $title ); ?>"
							class="center-text-with-full-image__image" />
						<?php if ( ! empty( $caption ) ) : ?>
							<figcaption class="center-text-with-full-image__caption">
								<?php echo $caption; ?>
							</figcaption>
						<?php endif; ?>
					</figure>
				<?php endif; ?>

				<?php if ( ! empty( $button ) ) : ?>
					<div class="center-text-with-full-image__cta-wrap">
						<?php echo Button::render( false, $button, array( 'btn_cls' => array( 'center-text-with-full-image__cta' ) ) ); ?>
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
