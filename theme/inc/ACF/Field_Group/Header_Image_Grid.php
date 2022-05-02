<?php namespace TrevorWP\Theme\ACF\Field_Group;

class Header_Image_Grid extends A_Field_Group implements I_Block, I_Renderable {
	const FIELD_HEADER            = 'header';
	const FIELD_DESCRIPTION       = 'description';
	const FIELD_IMAGE_ENTRIES     = 'image_entries';
	const FIELD_ENTRY_IMAGE       = 'entry_image';
	const FIELD_ENTRY_IMAGE_LINK  = 'entry_image_link';
	const FIELD_ENTRY_IMAGE_TITLE = 'entry_image_title';
	const FIELD_LINKS             = 'link_entries';
	const FIELD_ENTRY_LABEL       = 'entry_label';
	const FIELD_ENTRY_LINK        = 'entry_link';

	/**
	 * @inheritDoc
	 */
	protected static function prepare_register_args(): array {
		$header            = static::gen_field_key( static::FIELD_HEADER );
		$description       = static::gen_field_key( static::FIELD_DESCRIPTION );
		$image_entries     = static::gen_field_key( static::FIELD_IMAGE_ENTRIES );
		$entry_image       = static::gen_field_key( static::FIELD_ENTRY_IMAGE );
		$entry_image_title = static::gen_field_key( static::FIELD_ENTRY_IMAGE_TITLE );
		$entry_image_link  = static::gen_field_key( static::FIELD_ENTRY_IMAGE_LINK );
		$links             = static::gen_field_key( static::FIELD_LINKS );
		$entry_label       = static::gen_field_key( static::FIELD_ENTRY_LABEL );
		$entry_link        = static::gen_field_key( static::FIELD_ENTRY_LINK );

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
						static::FIELD_ENTRY_IMAGE       => array(
							'key'           => $entry_image,
							'name'          => static::FIELD_ENTRY_IMAGE,
							'label'         => 'Image',
							'type'          => 'image',
							'required'      => 1,
							'return_format' => 'array',
							'preview_size'  => 'thumbnail',
							'library'       => 'all',
						),
						static::FIELD_ENTRY_IMAGE_TITLE => array(
							'key'      => $entry_image_title,
							'name'     => static::FIELD_ENTRY_IMAGE_TITLE,
							'label'    => 'Image Title',
							'type'     => 'text',
							'required' => 1,
						),
						static::FIELD_ENTRY_IMAGE_LINK  => array(
							'key'      => $entry_image_link,
							'name'     => static::FIELD_ENTRY_IMAGE_LINK,
							'label'    => 'Image Link',
							'type'     => 'url',
							'required' => 0,
						),
					),
				),
				static::FIELD_LINKS         => array(
					'key'          => $links,
					'label'        => 'Links',
					'name'         => static::FIELD_LINKS,
					'type'         => 'repeater',
					'collapsed'    => $entry_label,
					'layout'       => 'row',
					'button_label' => 'Add Link',
					'sub_fields'   => array(
						static::FIELD_ENTRY_LABEL => array(
							'key'      => $entry_label,
							'name'     => static::FIELD_ENTRY_LABEL,
							'label'    => 'Label',
							'type'     => 'text',
							'required' => 1,
						),
						static::FIELD_ENTRY_LINK  => array(
							'key'          => $entry_link,
							'label'        => 'URL',
							'name'         => static::FIELD_ENTRY_LINK,
							'type'         => 'url',
							'instructions' => '',
							'required'     => 1,
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
		$link_entries  = static::get_val( static::FIELD_LINKS );

		$class_names = array( 'header-image-grid' );

		if ( ! empty( $link_entries ) ) {
			$class_names[] = 'header-image-grid--with-link';
		}

		ob_start();
		// Next Step - FE
		?>
		<div <?php echo self::render_attrs( $class_names ); ?>>
			<div class="header-image-grid__container">
				<?php if ( ! empty( $header ) ) : ?>
					<h2 class="header-image-grid__heading"><?php echo esc_html( $header ); ?></h2>
				<?php endif; ?>
				<?php if ( ! empty( $description ) ) : ?>
					<div class="header-image-grid__description"><?php echo esc_html( $description ); ?></div>
				<?php endif; ?>
				<?php if ( ! empty( $image_entries ) ) : ?>
					<div class="header-image-grid__grid" role="list">
						<?php foreach ( $image_entries as $entry ) : ?>
							<?php if ( ! empty( $entry[ static::FIELD_ENTRY_IMAGE ]['url'] ) ) : ?>
								<div class="header-image-grid__item" role="listitem">
									<?php
										$image_wrapper_attrs = array(
											'class' => 'header-image-grid__image',
											'href'  => 'javascript:;',
										);

										if ( ! empty( $entry[ static::FIELD_ENTRY_IMAGE_LINK ] ) ) {
											$image_wrapper_attrs['href']       = $entry[ static::FIELD_ENTRY_IMAGE_LINK ];
											$image_wrapper_attrs['target']     = '_blank';
											$image_wrapper_attrs['aria-label'] = 'click to go to ' . ( $entry[ static::FIELD_ENTRY_IMAGE_TITLE ] ? $entry[ static::FIELD_ENTRY_IMAGE_TITLE ] : ' the image link' );
										}
										?>
									<a <?php echo self::render_attrs( array(), $image_wrapper_attrs ); ?>>
										<img
											class="header-image-grid__img"
											src="<?php echo esc_url( $entry[ static::FIELD_ENTRY_IMAGE ]['url'] ); ?>"
											alt="<?php echo ! empty( $entry[ static::FIELD_ENTRY_IMAGE ]['alt'] ) ? esc_attr( $entry[ static::FIELD_ENTRY_IMAGE ]['alt'] ) : esc_attr( $header ); ?>"
										/>
									</a>
								</div>
							<?php endif; ?>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
				<?php if ( ! empty( $link_entries ) ) : ?>
					<div class="header-image-grid__links" role="list">
						<?php foreach ( $link_entries as $entry ) : ?>
							<?php if ( ! empty( $entry[ static::FIELD_ENTRY_LINK ] ) ) : ?>
								<div class="header-image-grid__link-item" role="listitem">
									<a
										href="<?php echo $entry[ static::FIELD_ENTRY_LINK ]; ?>"
										class="header-image-grid__link"
										target="_blank"
									>
										<?php echo $entry[ static::FIELD_ENTRY_LABEL ]; ?>
									</a>
								</div>
							<?php endif; ?>
						<?php endforeach; ?>
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
