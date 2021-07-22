<?php namespace TrevorWP\Theme\ACF\Field_Group;

class Header_Image_Grid extends A_Field_Group implements I_Block, I_Renderable {
	const FIELD_HEADER        = 'header';
	const FIELD_DESCRIPTION   = 'description';
	const FIELD_IMAGE_ENTRIES = 'image_entries';
	const FIELD_ENTRY_IMAGE   = 'entry_image';

	/**
	 * @inheritDoc
	 */
	protected static function prepare_register_args(): array {
		$header        = static::gen_field_key( static::FIELD_HEADER );
		$description   = static::gen_field_key( static::FIELD_DESCRIPTION );
		$image_entries = static::gen_field_key( static::FIELD_IMAGE_ENTRIES );
		$entry_image   = static::gen_field_key( static::FIELD_ENTRY_IMAGE );

		return array(
			'title'  => 'Header + Image Grid',
			'fields' => array(
				static::FIELD_HEADER        => array(
					'key'   => $header,
					'name'  => static::FIELD_HEADER,
					'label' => 'Title',
					'type'  => 'text',
				),
				static::FIELD_DESCRIPTION   => array(
					'key'   => $description,
					'name'  => static::FIELD_DESCRIPTION,
					'label' => 'Description',
					'type'  => 'textarea',
				),
				static::FIELD_IMAGE_ENTRIES => array(
					'key'        => $image_entries,
					'name'       => static::FIELD_IMAGE_ENTRIES,
					'label'      => 'Image Entries',
					'type'       => 'repeater',
					'layout'     => 'block',
					'sub_fields' => array(
						static::FIELD_ENTRY_IMAGE => array(
							'key'      => $entry_image,
							'name'     => static::FIELD_ENTRY_IMAGE,
							'label'    => 'Image',
							'type'     => 'image',
							'required' => 1,
                            'return_format' => 'array',
                            'preview_size'  => 'thumbnail',
                            'library'       => 'all',
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
				'title'      => 'Header + Image Grid',
				'post_types' => array( 'page' ),
			)
		);
	}

	/**
	 * @inheritDoc
	 */
	public static function render( $post = false, array $data = null, array $options = array() ): ?string {
		$header        = static::get_val( static::FIELD_HEADER );
		$description   = static::get_val( static::FIELD_DESCRIPTION );
		$image_entries = static::get_val( static::FIELD_IMAGE_ENTRIES );

		ob_start();
		// Next Step - FE
		?>
		<div class="container mx-auto">
			<?php if ( ! empty( $header ) ) : ?>
				<h3><?php echo esc_html( $header ); ?></h3>
			<?php endif; ?>

			<?php if ( ! empty( $description ) ) : ?>
				<p><?php echo esc_html( $description ); ?></p>
			<?php endif; ?>

			<?php if ( ! empty( $image_entries ) ) : ?>
				<div>
					<?php foreach ( $image_entries as $entry ) : ?>
						<?php if ( ! empty( $entry[ static::FIELD_ENTRY_IMAGE ]['url'] ) ) : ?>
							<img src="<?php echo esc_url( $entry[ static::FIELD_ENTRY_IMAGE ]['url'] ); ?>" alt="<?php echo ! empty( $entry[ static::FIELD_ENTRY_IMAGE ]['alt'] ) ? esc_attr( $entry[ static::FIELD_ENTRY_IMAGE ]['alt'] ) : esc_attr( $header ); ?>">
						<?php endif; ?>
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
