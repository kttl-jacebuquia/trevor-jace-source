<?php namespace TrevorWP\Theme\ACF\Field_Group;

class Text_Only_Two_Up extends A_Field_Group implements I_Block, I_Renderable {
	const FIELD_TITLE        = 'title';
	const FIELD_DESCRIPTION  = 'description';
	const FIELD_ENTRIES      = 'entries';
	const FIELD_ENTRY_HEADER = 'entry_header';
	const FIELD_ENTRY_BODY   = 'entry_body';

	/**
	 * @inheritDoc
	 */
	protected static function prepare_register_args(): array {
		$title        = static::gen_field_key( static::FIELD_TITLE );
		$description  = static::gen_field_key( static::FIELD_DESCRIPTION );
		$entries      = static::gen_field_key( static::FIELD_ENTRIES );
		$entry_header = static::gen_field_key( static::FIELD_ENTRY_HEADER );
		$entry_body   = static::gen_field_key( static::FIELD_ENTRY_BODY );

		return array(
			'title'  => 'Text Only 2-up',
			'fields' => array(
				static::FIELD_TITLE       => array(
					'key'   => $title,
					'name'  => static::FIELD_TITLE,
					'label' => 'Title',
					'type'  => 'text',
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
					'collapsed'  => $entry_header,
					'sub_fields' => array(
						static::FIELD_ENTRY_HEADER => array(
							'key'      => $entry_header,
							'name'     => static::FIELD_ENTRY_HEADER,
							'label'    => 'Header',
							'type'     => 'text',
							'required' => 1,
						),
						static::FIELD_ENTRY_BODY   => array(
							'key'   => $entry_body,
							'name'  => static::FIELD_ENTRY_BODY,
							'label' => 'Body',
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
				'title'      => 'Text Only 2-up',
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
		// Next Step: FE
		?>
		<div>
			<?php if ( ! empty( $title ) ) : ?>
				<h2><?php echo esc_html( $title ); ?></h2>
			<?php endif; ?>

			<?php if ( ! empty( $description ) ) : ?>
				<p><?php echo esc_html( $description ); ?></p>
			<?php endif; ?>

			<?php if ( ! empty( $entries ) ) : ?>
				<?php foreach ( $entries as $entry ) : ?>
					<div>
						<?php if ( ! empty( $entry[ static::FIELD_ENTRY_HEADER ] ) ) : ?>
							<h2><?php echo esc_html( $entry[ static::FIELD_ENTRY_HEADER ] ); ?></h2>
						<?php endif; ?>
						<?php if ( ! empty( $entry[ static::FIELD_ENTRY_BODY ] ) ) : ?>
							<p><?php echo esc_html( $entry[ static::FIELD_ENTRY_BODY ] ); ?></p>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
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
