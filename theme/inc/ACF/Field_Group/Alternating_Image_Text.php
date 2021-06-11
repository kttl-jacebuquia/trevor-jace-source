<?php

namespace TrevorWP\Theme\ACF\Field_Group;

class Alternating_Image_Text extends A_Field_Group implements I_Block, I_Renderable {

	const FIELD_HEADLINE     = 'headline';
	const FIELD_DESCRIPTION  = 'description';
	const FIELD_ENTRIES      = 'entries';
	const FIELD_ENTRY_IMAGE  = 'entry_image';
	const FIELD_ENTRY_HEADER = 'entry_header';
	const FIELD_ENTRY_BODY   = 'entry_body';
	const FIELD_BUTTON       = 'button';
	const PLACEHOLDER_IMAGE  = '/wp-content/themes/trevor/static/media/generic-placeholder.png';

	/**
	 * @inheritDoc
	 */
	protected static function prepare_register_args(): array {
		$headline     = static::gen_field_key( static::FIELD_HEADLINE );
		$description  = static::gen_field_key( static::FIELD_DESCRIPTION );
		$entries      = static::gen_field_key( static::FIELD_ENTRIES );
		$entry_image  = static::gen_field_key( static::FIELD_ENTRY_IMAGE );
		$entry_header = static::gen_field_key( static::FIELD_ENTRY_HEADER );
		$entry_body   = static::gen_field_key( static::FIELD_ENTRY_BODY );
		$button       = static::gen_field_key( static::FIELD_BUTTON );

		return array(
			'title'  => 'Alternating Image + Text Lockup',
			'fields' => array(
				static::FIELD_HEADLINE    => array(
					'key'       => $headline,
					'name'      => static::FIELD_HEADLINE,
					'label'     => 'Headline',
					'type'      => 'textarea',
					'required'  => 1,
					'new_lines' => 'br',
				),
				static::FIELD_DESCRIPTION => array(
					'key'          => $description,
					'name'         => static::FIELD_DESCRIPTION,
					'label'        => 'Description',
					'type'         => 'wysiwyg',
					'toolbar'      => 'basic',
					'media_upload' => 0,
				),
				static::FIELD_ENTRIES     => array(
					'key'        => $entries,
					'name'       => static::FIELD_ENTRIES,
					'label'      => 'Entries',
					'type'       => 'repeater',
					'layout'     => 'block',
					'collapsed'  => $entry_header,
					'sub_fields' => array(
						static::FIELD_ENTRY_IMAGE  => array(
							'key'           => $entry_image,
							'name'          => static::FIELD_ENTRY_IMAGE,
							'label'         => 'Image',
							'type'          => 'image',
							'required'      => 1,
							'return_format' => 'array',
							'preview_size'  => 'thumbnail',
							'library'       => 'all',
						),
						static::FIELD_ENTRY_HEADER => array(
							'key'      => $entry_header,
							'name'     => static::FIELD_ENTRY_HEADER,
							'label'    => 'Header',
							'type'     => 'text',
							'required' => 1,
						),
						static::FIELD_ENTRY_BODY   => array(
							'key'          => $entry_body,
							'name'         => static::FIELD_ENTRY_BODY,
							'label'        => 'Body',
							'type'         => 'wysiwyg',
							'toolbar'      => 'basic',
							'media_upload' => 0,
						),
					),
				),
				static::FIELD_BUTTON      => array(
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
		$headline    = static::get_val( static::FIELD_HEADLINE );
		$description = static::get_val( static::FIELD_DESCRIPTION );
		$entries     = static::get_val( static::FIELD_ENTRIES );
		$button      = static::get_val( static::FIELD_BUTTON );

		ob_start();
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
							<div class="alternating-image-text__item">
								<figure class="alternating-image-text__item-figure" aria-hidden="true">
									<?php if ( ! empty( $entry[ static::FIELD_ENTRY_IMAGE ]['url'] ) ) : ?>
										<img src="<?php echo esc_url( $entry[ static::FIELD_ENTRY_IMAGE ]['url'] ); ?>" alt="<?php echo ( ! empty( $entry[ static::FIELD_ENTRY_IMAGE ]['alt'] ) ) ? esc_attr( $entry[ static::FIELD_ENTRY_IMAGE ]['alt'] ) : esc_attr( $headline ); ?>">
									<?php else : ?>
										<img src="<?php echo static::PLACEHOLDER_IMAGE; ?>" alt="">
									<?php endif; ?>
								</figure>
								<div class="alternating-image-text__body">
									<?php if ( ! empty( $entry[ static::FIELD_ENTRY_HEADER ] ) ) : ?>
										<h3 class="alternating-image-text__item-title"><?php echo esc_html( $entry[ static::FIELD_ENTRY_HEADER ] ); ?></h3>
									<?php endif; ?>
									<?php if ( ! empty( $entry[ static::FIELD_ENTRY_BODY ] ) ) : ?>
										<div class="alternating-image-text__item-content"><?php echo $entry[ static::FIELD_ENTRY_BODY ]; ?></div>
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
