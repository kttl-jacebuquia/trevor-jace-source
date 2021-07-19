<?php namespace TrevorWP\Theme\ACF\Field_Group;

class Text_Icon extends A_Field_Group implements I_Block, I_Renderable {
	const FIELD_TITLE       = 'title';
	const FIELD_DESCRIPTION = 'description';
	const FIELD_ENTRIES     = 'entries';
	const FIELD_ENTRY_ICON  = 'entry_icon';
	const FIELD_ENTRY_TITLE = 'entry_title';

	/**
	 * @inheritDoc
	 */
	protected static function prepare_register_args(): array {
		$title       = static::gen_field_key( static::FIELD_TITLE );
		$description = static::gen_field_key( static::FIELD_DESCRIPTION );
		$entries     = static::gen_field_key( static::FIELD_ENTRIES );
		$entry_icon  = static::gen_field_key( static::FIELD_ENTRY_ICON );
		$entry_title = static::gen_field_key( static::FIELD_ENTRY_TITLE );

		return array(
			'title'  => 'Text + Icon',
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
				static::FIELD_ENTRIES     => array(
					'key'        => $entries,
					'name'       => static::FIELD_ENTRIES,
					'label'      => 'Entries',
					'type'       => 'repeater',
					'layout'     => 'block',
					'min'        => 1,
					'max'        => 4,
					'collapsed'  => $entry_title,
					'sub_fields' => array(
						static::FIELD_ENTRY_ICON  => array(
							'key'           => $entry_icon,
							'name'          => static::FIELD_ENTRY_ICON,
							'label'         => 'Image',
							'type'          => 'image',
							'required'      => 1,
							'return_format' => 'array',
							'preview_size'  => 'thumbnail',
							'library'       => 'all',
						),
						static::FIELD_ENTRY_TITLE => array(
							'key'      => $entry_title,
							'name'     => static::FIELD_ENTRY_TITLE,
							'label'    => 'Title',
							'type'     => 'text',
							'required' => 1,
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
				'title'      => 'Text + Icon',
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
		$entries     = static::get_val( static::FIELD_ENTRIES );

		ob_start();
		?>
		<div class="text-icon">
			<div class="text-icon__container">
				<?php if ( ! empty( $title ) ) : ?>
					<h3 class="text-icon__heading"><?php echo esc_html( $title ); ?></h3>
				<?php endif; ?>

				<?php if ( ! empty( $description ) ) : ?>
					<p class="text-icon__description"><?php echo esc_html( $description ); ?></p>
				<?php endif; ?>

				<?php if ( ! empty( $entries ) ) : ?>
				<div class="text-icon__content">
					<div class="text-icon__entries" role="list">
						<?php foreach ( $entries as $entry ) : ?>
							<div class="text-icon__entry" role="listitem">
								<div class="text-icon__entry-image" aria-hidden="true">
								<?php if ( ! empty( $entry[ static::FIELD_ENTRY_ICON ]['url'] ) ) : ?>
										<img src="<?php echo esc_url( $entry[ static::FIELD_ENTRY_ICON ]['url'] ); ?>" alt="<?php echo ( ! empty( $image['alt'] ) ) ? esc_attr( $image['alt'] ) : esc_attr( $title ); ?>">
								<?php endif; ?>
								</div>

								<?php if ( ! empty( $entry[ static::FIELD_ENTRY_TITLE ] ) ) : ?>
									<h2 class="text-icon__entry-title"><?php echo $entry[ static::FIELD_ENTRY_TITLE ]; ?></h2>
								<?php endif; ?>
							</div>
						<?php endforeach; ?>
					</div>
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
