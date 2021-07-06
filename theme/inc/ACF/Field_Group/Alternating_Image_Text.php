<?php

namespace TrevorWP\Theme\ACF\Field_Group;

class Alternating_Image_Text extends A_Field_Group implements I_Block, I_Renderable {

	const FIELD_HEADLINE                 = 'headline';
	const FIELD_DESCRIPTION              = 'description';
	const FIELD_ALTERNATE_TYPE           = 'alternate_type';
	const FIELD_ALTERNATE_TEXT_ALIGNMENT = 'alternate_text_alignment';
	const FIELD_ENTRIES                  = 'entries';
	const FIELD_ENTRY_IMAGE              = 'entry_image';
	const FIELD_ENTRY_EYEBROW            = 'entry_eyebrow';
	const FIELD_ENTRY_HEADER             = 'entry_header';
	const FIELD_ENTRY_BODY               = 'entry_body';
	const FIELD_ENTRY_CTA_BUTTON         = 'entry_cta_button';
	const FIELD_ENTRY_CTA_LINK           = 'entry_cta_link';
	const FIELD_BUTTON                   = 'button';
	const PLACEHOLDER_IMAGE              = '/wp-content/themes/trevor/static/media/generic-placeholder.png';

	/**
	 * @inheritDoc
	 */
	protected static function prepare_register_args(): array {
		$headline                 = static::gen_field_key( static::FIELD_HEADLINE );
		$description              = static::gen_field_key( static::FIELD_DESCRIPTION );
		$alternate_type           = static::gen_field_key( static::FIELD_ALTERNATE_TYPE );
		$alternate_text_alignment = static::gen_field_key( static::FIELD_ALTERNATE_TEXT_ALIGNMENT );
		$entries                  = static::gen_field_key( static::FIELD_ENTRIES );
		$entry_image              = static::gen_field_key( static::FIELD_ENTRY_IMAGE );
		$entry_eyebrow            = static::gen_field_key( static::FIELD_ENTRY_EYEBROW );
		$entry_header             = static::gen_field_key( static::FIELD_ENTRY_HEADER );
		$entry_body               = static::gen_field_key( static::FIELD_ENTRY_BODY );
		$entry_cta_button         = static::gen_field_key( static::FIELD_ENTRY_CTA_BUTTON );
		$entry_cta_link           = static::gen_field_key( static::FIELD_ENTRY_CTA_LINK );
		$button                   = static::gen_field_key( static::FIELD_BUTTON );

		return array(
			'title'  => 'Alternating Image + Text Lockup',
			'fields' => array(
				static::FIELD_HEADLINE                 => array(
					'key'       => $headline,
					'name'      => static::FIELD_HEADLINE,
					'label'     => 'Headline',
					'type'      => 'textarea',
					'required'  => 1,
					'new_lines' => 'br',
				),
				static::FIELD_DESCRIPTION              => array(
					'key'          => $description,
					'name'         => static::FIELD_DESCRIPTION,
					'label'        => 'Description',
					'type'         => 'wysiwyg',
					'toolbar'      => 'basic',
					'media_upload' => 0,
				),
				static::FIELD_ALTERNATE_TYPE           => array(
					'key'           => $alternate_type,
					'name'          => static::FIELD_ALTERNATE_TYPE,
					'label'         => 'Alternate Type',
					'type'          => 'button_group',
					'required'      => 1,
					'choices'       => array(
						'color' => 'Color',
						'image' => 'Image',
					),
					'default_value' => 'image',
				),
				static::FIELD_ALTERNATE_TEXT_ALIGNMENT => array(
					'key'               => $alternate_text_alignment,
					'name'              => static::FIELD_ALTERNATE_TEXT_ALIGNMENT,
					'label'             => 'Alternate Text Alignment',
					'type'              => 'button_group',
					'required'          => 1,
					'choices'           => array(
						'left'   => 'Left',
						'center' => 'Center',
					),
					'default_value'     => 'left',
					'conditional_logic' => array(
						array(
							array(
								'field'    => $alternate_type,
								'operator' => '==',
								'value'    => 'color',
							),
						),
					),
				),
				static::FIELD_ENTRIES                  => array(
					'key'        => $entries,
					'name'       => static::FIELD_ENTRIES,
					'label'      => 'Entries',
					'type'       => 'repeater',
					'layout'     => 'block',
					'collapsed'  => $entry_header,
					'sub_fields' => array(
						static::FIELD_ENTRY_IMAGE      => array(
							'key'               => $entry_image,
							'name'              => static::FIELD_ENTRY_IMAGE,
							'label'             => 'Image',
							'type'              => 'image',
							'required'          => 1,
							'return_format'     => 'array',
							'preview_size'      => 'thumbnail',
							'library'           => 'all',
							'conditional_logic' => array(
								array(
									array(
										'field'    => $alternate_type,
										'operator' => '==',
										'value'    => 'image',
									),
								),
							),
						),
						static::FIELD_ENTRY_EYEBROW    => array(
							'key'               => $entry_eyebrow,
							'name'              => static::FIELD_ENTRY_EYEBROW,
							'label'             => 'Eyebrow',
							'type'              => 'text',
							'conditional_logic' => array(
								array(
									array(
										'field'    => $alternate_type,
										'operator' => '==',
										'value'    => 'color',
									),
								),
							),
						),
						static::FIELD_ENTRY_HEADER     => array(
							'key'      => $entry_header,
							'name'     => static::FIELD_ENTRY_HEADER,
							'label'    => 'Header',
							'type'     => 'text',
							'required' => 1,
						),
						static::FIELD_ENTRY_BODY       => array(
							'key'          => $entry_body,
							'name'         => static::FIELD_ENTRY_BODY,
							'label'        => 'Body',
							'type'         => 'wysiwyg',
							'toolbar'      => 'basic',
							'media_upload' => 0,
						),
						static::FIELD_ENTRY_CTA_BUTTON => array(
							'key'               => $entry_cta_button,
							'name'              => static::FIELD_ENTRY_CTA_BUTTON,
							'label'             => 'CTA Button',
							'type'              => 'link',
							'return_format'     => 'array',
							'conditional_logic' => array(
								array(
									array(
										'field'    => $alternate_type,
										'operator' => '==',
										'value'    => 'color',
									),
								),
							),
						),
						static::FIELD_ENTRY_CTA_LINK   => array(
							'key'               => $entry_cta_link,
							'name'              => static::FIELD_ENTRY_CTA_LINK,
							'label'             => 'CTA Link',
							'type'              => 'link',
							'return_format'     => 'array',
							'conditional_logic' => array(
								array(
									array(
										'field'    => $alternate_type,
										'operator' => '==',
										'value'    => 'color',
									),
								),
							),
						),
					),
				),
				static::FIELD_BUTTON                   => array(
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
				'title'      => 'Alternating Image + Text Lockup',
				'post_types' => array( 'page' ),
			)
		);
	}

	/**
	 * @inheritDoc
	 */
	public static function render( $post = false, array $data = null, array $options = array() ): ?string {
		$headline                 = static::get_val( static::FIELD_HEADLINE );
		$description              = static::get_val( static::FIELD_DESCRIPTION );
		$alternate_type           = static::get_val( static::FIELD_ALTERNATE_TYPE ) ?? 'image';
		$alternate_text_alignment = static::get_val( static::FIELD_ALTERNATE_TEXT_ALIGNMENT ) ?? 'left';
		$entries                  = static::get_val( static::FIELD_ENTRIES );
		$button                   = static::get_val( static::FIELD_BUTTON );

		$alignment_class = '';

		if ( 'left' === $alternate_text_alignment ) {
			// Generate class for text align left
		} elseif ( 'center' === $alternate_text_alignment ) {
			// Generate class for text align center
		}

		ob_start();
		// Next Step - FE (apply variation)
		?>
		<div class="alternating-image-text">
			<div class="alternating-image-text__container">
				<h2 class="alternating-image-text__heading"><?php echo $headline; ?></h3>
				<?php if ( ! empty( $description ) ) : ?>
					<div class="alternating-image-text__description"><?php echo $description; ?></div>
				<?php endif; ?>
				<?php if ( ! empty( $entries ) ) : ?>
					<div class="alternating-image-text__items" role="list">
						<?php foreach ( $entries as $entry ) : ?>
							<div class="alternating-image-text__item" role="listitem">
								<?php if ( 'image' === $alternate_type ) : ?>
									<figure class="alternating-image-text__item-figure" aria-hidden="true">
										<?php if ( ! empty( $entry[ static::FIELD_ENTRY_IMAGE ]['url'] ) ) : ?>
											<img src="<?php echo esc_url( $entry[ static::FIELD_ENTRY_IMAGE ]['url'] ); ?>" alt="<?php echo ( ! empty( $entry[ static::FIELD_ENTRY_IMAGE ]['alt'] ) ) ? esc_attr( $entry[ static::FIELD_ENTRY_IMAGE ]['alt'] ) : esc_attr( $headline ); ?>">
										<?php else : ?>
											<img src="<?php echo static::PLACEHOLDER_IMAGE; ?>" alt="">
										<?php endif; ?>
									</figure>
								<?php elseif ( 'color' === $alternate_type ) : ?>
									<!-- alternate type color here -->
								<?php endif; ?>
								<div class="alternating-image-text__body <?php echo esc_attr( $alignment_class ); ?>">
									<?php if ( ! empty( $entry[ static::FIELD_ENTRY_EYEBROW ] ) ) : ?>
										<p><?php echo esc_html( $entry[ static::FIELD_ENTRY_EYEBROW ] ); ?></p>
									<?php endif; ?>
									<?php if ( ! empty( $entry[ static::FIELD_ENTRY_HEADER ] ) ) : ?>
										<h3 class="alternating-image-text__item-title"><?php echo esc_html( $entry[ static::FIELD_ENTRY_HEADER ] ); ?></h3>
									<?php endif; ?>
									<?php if ( ! empty( $entry[ static::FIELD_ENTRY_BODY ] ) ) : ?>
										<div class="alternating-image-text__item-content"><?php echo $entry[ static::FIELD_ENTRY_BODY ]; ?></div>
									<?php endif; ?>
									<?php if ( 'color' === $alternate_type ) : ?>
										<?php if ( ! empty( $entry[ static::FIELD_ENTRY_CTA_BUTTON ]['url'] ) ) : ?>
											<a href="<?php echo esc_url( $entry[ static::FIELD_ENTRY_CTA_BUTTON ]['url'] ); ?>" target="<?php echo esc_attr( $entry[ static::FIELD_ENTRY_CTA_BUTTON ]['target'] ); ?>"><?php echo esc_html( $entry[ static::FIELD_ENTRY_CTA_BUTTON ]['title'] ); ?></a>
										<?php endif; ?>
										<?php if ( ! empty( $entry[ static::FIELD_ENTRY_CTA_LINK ]['url'] ) ) : ?>
											<a href="<?php echo esc_url( $entry[ static::FIELD_ENTRY_CTA_LINK ]['url'] ); ?>" target="<?php echo esc_attr( $entry[ static::FIELD_ENTRY_CTA_LINK ]['target'] ); ?>"><?php echo esc_html( $entry[ static::FIELD_ENTRY_CTA_LINK ]['title'] ); ?></a>
										<?php endif; ?>
									<?php endif; ?>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
				<?php if ( ! empty( $button['url'] ) && ! empty( $button['title'] ) ) : ?>
					<div class="alternating-image-text__cta-wrap">
						<a class="alternating-image-text__cta" href="<?php echo esc_url( $button['url'] ); ?>" target="<?php echo esc_attr( $button['target'] ); ?>"><?php echo esc_html( $button['title'] ); ?></a>
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
