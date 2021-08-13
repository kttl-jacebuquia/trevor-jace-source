<?php namespace TrevorWP\Theme\ACF\Field_Group;

class Crisis_Services_Text_Block extends A_Field_Group implements I_Block, I_Renderable {
	const FIELD_TITLE       = 'title';
	const FIELD_DESCRIPTION = 'description';
	const FIELD_BUTTON      = 'button';
	const FIELD_IMAGE       = 'image';

	/**
	 * @inheritDoc
	 */
	protected static function prepare_register_args(): array {
		$title       = static::gen_field_key( static::FIELD_TITLE );
		$description = static::gen_field_key( static::FIELD_DESCRIPTION );
		$button      = static::gen_field_key( static::FIELD_BUTTON );
		$image       = static::gen_field_key( static::FIELD_IMAGE );

		return array(
			'title'  => 'Crisis Services Image + Text',
			'fields' => array(
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
				static::FIELD_BUTTON      => array(
					'key'   => $button,
					'name'  => static::FIELD_BUTTON,
					'label' => 'Button',
					'type'  => 'link',
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
				'title'      => 'Crisis Services Image + Text',
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

		ob_start();
		?>
		<div class="crisis-services-image-text">
			<div class="crisis-services-image-text__container">
				<div class="crisis-services-image-text__box">
					<?php if ( ! empty( $title ) ) : ?>
						<h3 class="crisis-services-image-text__heading"><?php echo $title; ?></h3>
					<?php endif; ?>

					<?php if ( ! empty( $description ) ) : ?>
						<p class="crisis-services-image-text__description"><?php echo esc_html( $description ); ?></p>
					<?php endif; ?>

					<?php if ( ! empty( $button['url'] ) ) : ?>
						<a class="crisis-services-image-text__cta" href="<?php echo esc_url( $button['url'] ); ?>" target="<?php echo esc_attr( $button['target'] ); ?>"><?php echo esc_html( $button['title'] ); ?></a>
					<?php endif; ?>
				</div>
				<?php if ( ! empty( $image['url'] ) ) : ?>
					<div class="crisis-services-image-text__image">
						<img src="<?php echo esc_url( $image['url'] ); ?>" class="block mx-auto" alt="<?php echo ( ! empty( $image['alt'] ) ) ? esc_attr( $image['alt'] ) : esc_attr( $title ); ?>">
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
