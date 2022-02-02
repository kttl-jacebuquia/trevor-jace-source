<?php namespace TrevorWP\Theme\ACF\Field_Group;

use \TrevorWP\Theme\Helper;

class Image_Carousel extends A_Field_Group implements I_Block, I_Renderable {
	const FIELD_TITLE                 = 'title';
	const FIELD_GALLERY_ENTRIES       = 'gallery_entries';
	const FIELD_GALLERY_ENTRY_IMAGE   = 'img';
	const FIELD_GALLERY_ENTRY_CAPTION = 'caption';
	const FIELD_IMAGE_SIZE            = 'image_size';

	/**
	 * @inheritDoc
	 */
	protected static function prepare_register_args(): array {
		$title                 = static::gen_field_key( static::FIELD_TITLE );
		$gallery_entries       = static::gen_field_key( static::FIELD_GALLERY_ENTRIES );
		$gallery_entry_image   = static::gen_field_key( static::FIELD_GALLERY_ENTRY_IMAGE );
		$gallery_entry_caption = static::gen_field_key( static::FIELD_GALLERY_ENTRY_CAPTION );
		$image_size            = static::gen_field_key( static::FIELD_IMAGE_SIZE );

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
				static::FIELD_IMAGE_SIZE      => array(
					'key'           => $image_size,
					'name'          => static::FIELD_IMAGE_SIZE,
					'label'         => 'Image Size',
					'type'          => 'button_group',
					'required'      => 1,
					'default_value' => 'big',
					'choices'       => array(
						'big'   => 'Big',
						'small' => 'Small',
					),
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
		$image_size      = static::get_val( static::FIELD_IMAGE_SIZE );

		$class = implode(
			' ',
			array(
				'carousel-image',
				'carousel-image--' . $image_size,
			),
		);

		ob_start();
		?>
		<div class="<?php echo $class; ?>">
			<div class="carousel-image__container">
				<?php if ( ! empty( $gallery_entries ) ) : ?>
					<div class="carousel-image__content">
					<?php

					if ( 'big' === $image_size ) {
						echo Helper\Carousel::big_img(
							$gallery_entries,
							array(
								'title'  => $title,
								'class'  => array(),
								'swiper' => array(
									'centeredSlides' => true,
								),
							)
						);
					} else {
						echo Helper\Carousel::auto_width_img(
							$gallery_entries,
							array(
								'title' => $title,
								'class' => array(),
							)
						);
					}
					?>
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
