<?php

namespace TrevorWP\Theme\ACF\Field_Group;

class Guiding_Principles extends A_Field_Group implements I_Block, I_Renderable {

	const FIELD_TITLE                 = 'title';
	const FIELD_DESCRIPTION           = 'description';
	const FIELD_PRINCIPLE_ENTRIES     = 'principle_entries';
	const FIELD_PRINCIPLE_ENTRY_TITLE = 'principle_entry_title';
	const FIELD_FILE                  = 'file';
	const FIELD_BUTTON_TEXT           = 'button_text';
	const FIELD_IMAGE                 = 'image';

	/**
	 * @inheritDoc
	 */
	protected static function prepare_register_args(): array {
		$title                 = static::gen_field_key( static::FIELD_TITLE );
		$description           = static::gen_field_key( static::FIELD_DESCRIPTION );
		$principle_entries     = static::gen_field_key( static::FIELD_PRINCIPLE_ENTRIES );
		$principle_entry_title = static::gen_field_key( static::FIELD_PRINCIPLE_ENTRY_TITLE );
		$file                  = static::gen_field_key( static::FIELD_FILE );
		$button_text           = static::gen_field_key( static::FIELD_BUTTON_TEXT );
		$image                 = static::gen_field_key( static::FIELD_IMAGE );

		return array(
			'title'  => 'Guiding Principles',
			'fields' => array(
				static::FIELD_TITLE             => array(
					'key'      => $title,
					'name'     => static::FIELD_TITLE,
					'label'    => 'Title',
					'type'     => 'text',
					'required' => 1,
					'wrapper'  => array(
						'width' => '50%',
					),
				),
				static::FIELD_DESCRIPTION       => array(
					'key'     => $description,
					'name'    => static::FIELD_DESCRIPTION,
					'label'   => 'Description',
					'type'    => 'textarea',
					'wrapper' => array(
						'width' => '50%',
					),
				),
				static::FIELD_PRINCIPLE_ENTRIES => array(
					'key'        => $principle_entries,
					'name'       => static::FIELD_PRINCIPLE_ENTRIES,
					'label'      => 'Principle Entries',
					'type'       => 'repeater',
					'layout'     => 'block',
					'sub_fields' => array(
						static::FIELD_PRINCIPLE_ENTRY_TITLE => array(
							'key'      => $principle_entry_title,
							'name'     => static::FIELD_PRINCIPLE_ENTRY_TITLE,
							'label'    => 'Title',
							'type'     => 'text',
							'required' => 1,
						),
					),
					'max'        => 6,
				),
				static::FIELD_FILE              => array(
					'key'           => $file,
					'name'          => static::FIELD_FILE,
					'label'         => 'File',
					'type'          => 'file',
					'return_format' => 'array',
					'library'       => 'all',
					'required'      => 1,
					'wrapper'       => array(
						'width' => '50%',
					),
				),
				static::FIELD_BUTTON_TEXT       => array(
					'key'      => $button_text,
					'name'     => static::FIELD_BUTTON_TEXT,
					'label'    => 'Button Text',
					'type'     => 'text',
					'required' => 1,
					'wrapper'  => array(
						'width' => '50%',
					),
				),
				static::FIELD_IMAGE             => array(
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
				'title'      => 'Guiding Principles',
				'post_types' => array( 'page' ),
			)
		);
	}

	/**
	 * @inheritDoc
	 */
	public static function render( $post = false, array $data = null, array $options = array() ): ?string {
		$title             = static::get_val( static::FIELD_TITLE );
		$description       = static::get_val( static::FIELD_DESCRIPTION );
		$principle_entries = static::get_val( static::FIELD_PRINCIPLE_ENTRIES );
		$file              = static::get_val( static::FIELD_FILE );
		$button_text       = static::get_val( static::FIELD_BUTTON_TEXT );
		$image             = static::get_val( static::FIELD_IMAGE );

		ob_start();
		// Next Step: FE
		?>
		<div class="guiding-principles">
			<div class="guiding-principles__container">
				<?php if ( ! empty( $image['url'] ) ) : ?>
					<figure class="guiding-principles__figure" data-aspectRatio="1:1" aria-hidden="true">
						<img class="guiding-principles__image" src="<?php echo esc_url( $image['url'] ); ?>" alt="<?php echo ( ! empty( $image['alt'] ) ) ? esc_attr( $image['alt'] ) : esc_attr( $title ); ?>">
					</figure>
				<?php endif; ?>
				<div class="guiding-principles__content">
					<h3 class="guiding-principles__title"><?php echo esc_html( $title ); ?></h3>
					<?php if ( ! empty( $description ) ) : ?>
						<p class="guiding-principles__description"><?php echo esc_html( $description ); ?></p>
					<?php endif; ?>
					<div class="guiding-principles__entries" role="list">
						<?php if ( ! empty( $principle_entries ) ) : ?>
							<?php foreach ( $principle_entries as $entry ) : ?>
								<span class="guiding-principles__entry" role="listitem">
									<?php if ( ! empty( $entry[ static::FIELD_PRINCIPLE_ENTRY_TITLE ] ) ) : ?>
										<?php echo esc_html( $entry[ static::FIELD_PRINCIPLE_ENTRY_TITLE ] ); ?></span>
									<?php endif; ?>
								</span>
							<?php endforeach; ?>
						<?php endif; ?>
					</div>
					<?php if ( ! empty( $file['url'] && ! empty( $button_text ) ) ) : ?>
						<div class="guiding-principles__cta-wrap">
							<a class="guiding-principles__cta" href="<?php echo esc_url( $file['url'] ); ?>" download><?php echo esc_html( $button_text ); ?></a>
						</div>
					<?php endif; ?>
				</div>
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
