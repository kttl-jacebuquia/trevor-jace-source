<?php namespace TrevorWP\Theme\ACF\Field_Group;

class Image_Carousel extends A_Field_Group implements I_Block, I_Renderable {
	const FIELD_TITLE                 = 'title';
	const FIELD_GALLERY_ENTRIES       = 'gallery_entries';
	const FIELD_GALLERY_ENTRY_IMAGE   = 'gallery_entry_image';
	const FIELD_GALLERY_ENTRY_CAPTION = 'gallery_entry_caption';

	/**
	 * @inheritDoc
	 */
	protected static function prepare_register_args(): array {
		$title                 = static::gen_field_key( static::FIELD_TITLE );
		$gallery_entries       = static::gen_field_key( static::FIELD_GALLERY_ENTRIES );
		$gallery_entry_image   = static::gen_field_key( static::FIELD_GALLERY_ENTRY_IMAGE );
		$gallery_entry_caption = static::gen_field_key( static::FIELD_GALLERY_ENTRY_CAPTION );

		return array(
			'title'  => 'Image Carousel',
			'fields' => array(
				static::FIELD_TITLE           => array(
					'key'      => $title,
					'name'     => static::FIELD_TITLE,
					'label'    => 'Title',
					'type'     => 'text',
					'required' => 1,
				),
				static::FIELD_GALLERY_ENTRIES => array(
					'key'        => $gallery_entries,
					'name'       => static::FIELD_GALLERY_ENTRIES,
					'label'      => 'Gallery Entries',
					'type'       => 'repeater',
					'layout'     => 'block',
					'collapsed'  => $gallery_entry_image,
					'sub_fields' => array(
						static::FIELD_GALLERY_ENTRY_IMAGE => array(
							'key'           => $gallery_entry_image,
							'name'          => static::FIELD_GALLERY_ENTRY_IMAGE,
							'label'         => 'Image',
							'type'          => 'image',
							'required'      => 1,
							'return_format' => 'array',
							'preview_size'  => 'thumbnail',
							'library'       => 'all',
						),
						static::FIELD_GALLERY_ENTRY_CAPTION => array(
							'key'   => $gallery_entry_caption,
							'name'  => static::FIELD_GALLERY_ENTRY_CAPTION,
							'label' => 'Caption',
							'type'  => 'textarea',
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
				'title'      => 'Image Carousel',
				'post_types' => array( 'page' ),
			)
		);
	}

	/**
	 * @inheritDoc
	 */
	public static function render( $post = false, array $data = null, array $options = array() ): ?string {
		$title           = static::get_val( static::FIELD_TITLE );
		$gallery_entries = static::get_val( static::FIELD_GALLERY_ENTRIES );

		ob_start();
		// Next Step - FE
		?>
		<div class="container mx-auto">
			<h3><?php echo esc_html( $title ); ?></h3>
			<?php if ( ! empty( $gallery_entries ) ) : ?>
				<div>
					<?php foreach ( $gallery_entries as $entry ) : ?>
						<div>
							<?php if ( ! empty( $entry[ static::FIELD_GALLERY_ENTRY_IMAGE ]['url'] ) ) : ?>
								<div>
									<img src="<?php echo esc_url( $entry[ static::FIELD_GALLERY_ENTRY_IMAGE ]['url'] ); ?>" alt="<?php echo ( ! empty( $entry[ static::FIELD_GALLERY_ENTRY_IMAGE ]['alt'] ) ) ? esc_attr( $entry[ static::FIELD_GALLERY_ENTRY_IMAGE ]['alt'] ) : esc_attr( $title ); ?>">
								</div>
							<?php endif; ?>

							<?php if ( ! empty( $entry[ static::FIELD_GALLERY_ENTRY_CAPTION ] ) ) : ?>
								<div><?php echo esc_html( $entry[ static::FIELD_GALLERY_ENTRY_CAPTION ] ); ?></div>
							<?php endif; ?>
						</div>
					<?php endforeach; ?>
				</div>
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
