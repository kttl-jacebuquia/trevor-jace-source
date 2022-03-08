<?php

namespace TrevorWP\Theme\ACF\Field_Group;

class Alternating_Image_Text extends A_Field_Group implements I_Block, I_Renderable {
	const FIELD_ANCHOR_ID                = 'anchor_id';
	const FIELD_HEADLINE                 = 'headline';
	const FIELD_DESCRIPTION              = 'description';
	const FIELD_ALTERNATE_TEXT_ALIGNMENT = 'alternate_text_alignment';
	const FIELD_ENTRIES                  = 'entries';
	const FIELD_ENTRY_IMAGE              = 'entry_image';
	const FIELD_ENTRY_EYEBROW            = 'entry_eyebrow';
	const FIELD_ENTRY_HEADER             = 'entry_header';
	const FIELD_ENTRY_HEADER_SIZE        = 'entry_header_size';
	const FIELD_ENTRY_BODY               = 'entry_body';
	const FIELD_ENTRY_SHOW_CTA           = 'entry_show_cta';
	const FIELD_ENTRY_CTA_BUTTON         = 'entry_cta_button';
	const FIELD_ENTRY_CTA_LINK           = 'entry_cta_link';
	const FIELD_BUTTON                   = 'button';
	const FIELD_ITEM_ALIGNMENT           = 'item_alignment';
	const PLACEHOLDER_IMAGE              = '/wp-content/themes/trevor/static/media/generic-placeholder.png';

	/**
	 * @inheritDoc
	 */
	protected static function prepare_register_args(): array {
		$anchor_id                = static::gen_field_key( static::FIELD_ANCHOR_ID );
		$headline                 = static::gen_field_key( static::FIELD_HEADLINE );
		$description              = static::gen_field_key( static::FIELD_DESCRIPTION );
		$alternate_text_alignment = static::gen_field_key( static::FIELD_ALTERNATE_TEXT_ALIGNMENT );
		$entries                  = static::gen_field_key( static::FIELD_ENTRIES );
		$entry_image              = static::gen_field_key( static::FIELD_ENTRY_IMAGE );
		$entry_eyebrow            = static::gen_field_key( static::FIELD_ENTRY_EYEBROW );
		$entry_header             = static::gen_field_key( static::FIELD_ENTRY_HEADER );
		$entry_header_size        = static::gen_field_key( static::FIELD_ENTRY_HEADER_SIZE );
		$entry_body               = static::gen_field_key( static::FIELD_ENTRY_BODY );
		$entry_show_cta           = static::gen_field_key( static::FIELD_ENTRY_SHOW_CTA );
		$entry_cta_button         = static::gen_field_key( static::FIELD_ENTRY_CTA_BUTTON );
		$entry_cta_link           = static::gen_field_key( static::FIELD_ENTRY_CTA_LINK );
		$button                   = static::gen_field_key( static::FIELD_BUTTON );
		$item_alignment           = static::gen_field_key( static::FIELD_ITEM_ALIGNMENT );

		return array(
			'title'  => 'Alternating Image + Text Lockup',
			'fields' => array(
				static::FIELD_ANCHOR_ID                => array(
					'key'          => $anchor_id,
					'name'         => static::FIELD_ANCHOR_ID,
					'label'        => 'Anchor ID',
					'type'         => 'text',
					'instructions' => 'Please use dash (-) and lowercase letters (a-z) only.',
				),
				static::FIELD_HEADLINE                 => array(
					'key'       => $headline,
					'name'      => static::FIELD_HEADLINE,
					'label'     => 'Headline',
					'type'      => 'textarea',
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
				static::FIELD_ALTERNATE_TEXT_ALIGNMENT => array(
					'key'           => $alternate_text_alignment,
					'name'          => static::FIELD_ALTERNATE_TEXT_ALIGNMENT,
					'label'         => 'Item Text Alignment',
					'type'          => 'button_group',
					'required'      => 1,
					'choices'       => array(
						'left'   => 'Left',
						'center' => 'Center',
					),
					'default_value' => 'left',
				),
				static::FIELD_ITEM_ALIGNMENT           => array(
					'key'           => $item_alignment,
					'name'          => static::FIELD_ITEM_ALIGNMENT,
					'label'         => 'First Item Alignment',
					'type'          => 'select',
					'required'      => true,
					'choices'       => array(
						'image_first' => 'Image First',
						'text_first'  => 'Text First',
					),
					'default_value' => 'image_first',
					'return_format' => 'value',
				),
				static::FIELD_ENTRY_HEADER_SIZE        => array(
					'key'           => $entry_header_size,
					'name'          => static::FIELD_ENTRY_HEADER_SIZE,
					'label'         => 'Item Heading Size',
					'type'          => 'button_group',
					'default_value' => 'default',
					'choices'       => array(
						'default' => 'Default',
						'large'   => 'Large',
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
							'key'           => $entry_image,
							'name'          => static::FIELD_ENTRY_IMAGE,
							'label'         => 'Image',
							'type'          => 'image',
							'required'      => 1,
							'return_format' => 'array',
							'preview_size'  => 'thumbnail',
							'library'       => 'all',
						),
						static::FIELD_ENTRY_EYEBROW    => array(
							'key'   => $entry_eyebrow,
							'name'  => static::FIELD_ENTRY_EYEBROW,
							'label' => 'Eyebrow',
							'type'  => 'text',
						),
						static::FIELD_ENTRY_HEADER     => array(
							'key'      => $entry_header,
							'name'     => static::FIELD_ENTRY_HEADER,
							'label'    => 'Heading',
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
						static::FIELD_ENTRY_SHOW_CTA   => array(
							'key'   => $entry_show_cta,
							'name'  => static::FIELD_ENTRY_SHOW_CTA,
							'label' => 'Show CTAs',
							'type'  => 'true_false',
							'ui'    => 1,
						),
						static::FIELD_ENTRY_CTA_BUTTON => Button::clone(
							array(
								'key'               => $entry_cta_button,
								'name'              => static::FIELD_ENTRY_CTA_BUTTON,
								'label'             => 'CTA Button',
								'return_format'     => 'array',
								'display'           => 'group',
								'layout'            => 'block',
								'conditional_logic' => array(
									array(
										array(
											'field'    => $entry_show_cta,
											'operator' => '==',
											'value'    => 1,
										),
									),
								),
							)
						),
						static::FIELD_ENTRY_CTA_LINK   => Button::clone(
							array(
								'key'               => $entry_cta_link,
								'name'              => static::FIELD_ENTRY_CTA_LINK,
								'label'             => 'CTA Link',
								'type'              => 'link',
								'return_format'     => 'array',
								'display'           => 'group',
								'layout'            => 'block',
								'conditional_logic' => array(
									array(
										array(
											'field'    => $entry_show_cta,
											'operator' => '==',
											'value'    => 1,
										),
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
		$anchor_id                = static::get_val( static::FIELD_ANCHOR_ID );
		$headline                 = static::get_val( static::FIELD_HEADLINE );
		$description              = static::get_val( static::FIELD_DESCRIPTION );
		$alternate_text_alignment = static::get_val( static::FIELD_ALTERNATE_TEXT_ALIGNMENT ) ?? 'left';
		$entries                  = static::get_val( static::FIELD_ENTRIES );
		$button                   = static::get_val( static::FIELD_BUTTON );
		$item_alignment           = static::get_val( static::FIELD_ITEM_ALIGNMENT );
		$item_heading_size        = static::get_val( static::FIELD_ENTRY_HEADER_SIZE );

		$alignment_class = '';

		if ( 'left' === $alternate_text_alignment ) {
			$alignment_class .= 'md:text-left xl:text-left alternating-image-text__body--left';
		} elseif ( 'center' === $alternate_text_alignment ) {
			$alignment_class .= 'md:text-center xl:text-center alternating-image-text__body--center';
		}

		$cta_btn_options  = array(
			'btn_cls' => array( 'alternating-image-text__cta-button' ),
		);
		$cta_link_options = array(
			'btn_cls' => array( 'alternating-image-text__cta-link' ),
		);

		$classname = implode(
			' ',
			array(
				'alternating-image-text',
				'alternating-image-text--' . $item_alignment,
				'alternating-image-text--item-heading-' . $item_heading_size,
			)
		);

		$item_heading_level = ! empty( $headline ) ? 3 : 2;

		ob_start();
		?>
		<div id="<?php echo esc_attr( esc_html( $anchor_id ) ); ?>" tabindex="0" class="<?php echo $classname; ?>">
			<div class="alternating-image-text__container">
				<?php if ( ! empty( $headline ) ) : ?>
					<h2 class="alternating-image-text__heading"><?php echo $headline; ?></h3>
				<?php endif; ?>
				<?php if ( ! empty( $description ) ) : ?>
					<div class="alternating-image-text__description"><?php echo $description; ?></div>
				<?php endif; ?>
				<?php if ( ! empty( $entries ) ) : ?>
					<div class="alternating-image-text__items" role="list">
						<?php foreach ( $entries as $entry ) : ?>
							<div class="alternating-image-text__item" role="listitem">
								<?php if ( ! empty( $entry[ static::FIELD_ENTRY_IMAGE ]['url'] ) ) : ?>
									<figure class="alternating-image-text__item-figure" aria-hidden="true">
										<?php if ( ! empty( $entry[ static::FIELD_ENTRY_IMAGE ]['url'] ) ) : ?>
											<img src="<?php echo esc_url( $entry[ static::FIELD_ENTRY_IMAGE ]['url'] ); ?>" alt="<?php echo ( ! empty( $entry[ static::FIELD_ENTRY_IMAGE ]['alt'] ) ) ? esc_attr( $entry[ static::FIELD_ENTRY_IMAGE ]['alt'] ) : esc_attr( $headline ); ?>">
										<?php else : ?>
											<img src="<?php echo static::PLACEHOLDER_IMAGE; ?>" alt="">
										<?php endif; ?>
									</figure>
								<?php endif; ?>
								<div class="alternating-image-text__body <?php echo esc_attr( $alignment_class ); ?>">
									<?php if ( ! empty( $entry[ static::FIELD_ENTRY_EYEBROW ] ) ) : ?>
										<p class="alternating-image-text__eyebrow"><?php echo esc_html( $entry[ static::FIELD_ENTRY_EYEBROW ] ); ?></p>
									<?php endif; ?>
									<?php
									if ( ! empty( $entry[ static::FIELD_ENTRY_HEADER ] ) ) {
										echo static::render_item_heading( $entry[ static::FIELD_ENTRY_HEADER ], $item_heading_level );
									}
									?>
									<?php if ( ! empty( $entry[ static::FIELD_ENTRY_BODY ] ) ) : ?>
										<div class="alternating-image-text__item-content"><?php echo $entry[ static::FIELD_ENTRY_BODY ]; ?></div>
									<?php endif; ?>
									<?php if ( ! empty( $entry[ static::FIELD_ENTRY_CTA_BUTTON ] ) ) : ?>
										<?php echo Button::render( false, $entry[ static::FIELD_ENTRY_CTA_BUTTON ], $cta_btn_options ); ?>
									<?php endif; ?>
									<?php if ( ! empty( $entry[ static::FIELD_ENTRY_CTA_LINK ] ) && ! empty( $entry[ static::FIELD_ENTRY_CTA_LINK ]['label'] ) ) : ?>
										<br />
										<?php echo Button::render( false, $entry[ static::FIELD_ENTRY_CTA_LINK ], $cta_link_options ); ?>
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

	public static function render_item_heading( string $heading = '', int $level = 3 ): string {
		$heading_tag = "h{$level}";

		ob_start();
		?>
		<<?php echo $heading_tag; ?>
				class="alternating-image-text__item-title"><?php echo esc_html( $heading ); ?>
		</<?php echo $heading_tag; ?>>
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
