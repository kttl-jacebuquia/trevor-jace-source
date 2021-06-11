<?php namespace TrevorWP\Theme\ACF\Field_Group;

class Pillars extends A_Field_Group implements I_Block, I_Renderable {
	const FIELD_PILLAR_ENTRIES      = 'pillar_entries';
	const FIELD_PILLAR_ENTRY_HEADER = 'pillar_entry_header';
	const FIELD_PILLAR_ENTRY_BODY   = 'pillar_entry_body';

	/**
	 * @inheritDoc
	 */
	protected static function prepare_register_args(): array {
		$pillar_entries      = static::gen_field_key( static::FIELD_PILLAR_ENTRIES );
		$pillar_entry_header = static::gen_field_key( static::FIELD_PILLAR_ENTRY_HEADER );
		$pillar_entry_body   = static::gen_field_key( static::FIELD_PILLAR_ENTRY_BODY );

		return array(
			'title'  => 'Pillars',
			'fields' => array(
				static::FIELD_PILLAR_ENTRIES => array(
					'key'        => $pillar_entries,
					'name'       => static::FIELD_PILLAR_ENTRIES,
					'label'      => 'Pillar Entries',
					'type'       => 'repeater',
					'layout'     => 'block',
					'min'        => 1,
					'max'        => 3,
					'collapsed'  => $pillar_entry_header,
					'sub_fields' => array(
						static::FIELD_PILLAR_ENTRY_HEADER => array(
							'key'      => $pillar_entry_header,
							'name'     => static::FIELD_PILLAR_ENTRY_HEADER,
							'label'    => 'Header',
							'type'     => 'text',
							'required' => 1,
						),
						static::FIELD_PILLAR_ENTRY_BODY   => array(
							'key'      => $pillar_entry_body,
							'name'     => static::FIELD_PILLAR_ENTRY_BODY,
							'label'    => 'Body',
							'type'     => 'textarea',
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
				'title'      => 'Pillars',
				'post_types' => array( 'page' ),
			)
		);
	}

	/**
	 * @inheritDoc
	 */
	public static function render( $post = false, array $data = null, array $options = array() ): ?string {
		$pillar_entries = static::get_val( static::FIELD_PILLAR_ENTRIES );

		ob_start();
		// Next Step: FE
		?>
		<div class="pillars" role="list">
			<div class="pillars__content">
				<?php if ( ! empty( $pillar_entries ) ) : ?>
					<?php foreach ( $pillar_entries as $pillar ) : ?>
						<div class="pillars__pillar" role="listitem">
							<?php if ( ! empty( $pillar[ static::FIELD_PILLAR_ENTRY_HEADER ] ) ) : ?>
								<h3 class="pillars__heading"><?php echo esc_html( $pillar[ static::FIELD_PILLAR_ENTRY_HEADER ] ); ?></h3>
							<?php endif; ?>
							<?php if ( ! empty( $pillar[ static::FIELD_PILLAR_ENTRY_BODY ] ) ) : ?>
								<p class="pillars__body"><?php echo esc_html( $pillar[ static::FIELD_PILLAR_ENTRY_BODY ] ); ?></p>
							<?php endif; ?>
						</div>
					<?php endforeach; ?>
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
