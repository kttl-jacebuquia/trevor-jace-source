<?php

namespace TrevorWP\Theme\ACF\Field_Group;

class Checkmark_Text extends A_Field_Group implements I_Block, I_Renderable {

	const FIELD_HEADLINE             = 'headline';
	const FIELD_DESCRIPTION          = 'description';
	const FIELD_CHECKMARK_ENTRIES    = 'checkmark_entries';
	const FIELD_CHECKMARK_ENTRY_TEXT = 'checkmark_entry_text';
	const FIELD_BUTTON               = 'button';

	/**
	 * @inheritDoc
	 */
	protected static function prepare_register_args(): array {
		$headline             = static::gen_field_key( static::FIELD_HEADLINE );
		$description          = static::gen_field_key( static::FIELD_DESCRIPTION );
		$checkmark_entries    = static::gen_field_key( static::FIELD_CHECKMARK_ENTRIES );
		$checkmark_entry_text = static::gen_field_key( static::FIELD_CHECKMARK_ENTRY_TEXT );
		$button               = static::gen_field_key( static::FIELD_BUTTON );

		return array(
			'title'  => 'Checkmark + Text',
			'fields' => array(
				static::FIELD_HEADLINE          => array(
					'key'      => $headline,
					'name'     => static::FIELD_HEADLINE,
					'label'    => 'Headline',
					'type'     => 'text',
					'required' => 1,
				),
				static::FIELD_DESCRIPTION       => array(
					'key'          => $description,
					'name'         => static::FIELD_DESCRIPTION,
					'label'        => 'Description',
					'type'         => 'wysiwyg',
					'toolbar'      => 'basic',
					'media_upload' => 0,
				),
				static::FIELD_CHECKMARK_ENTRIES => array(
					'key'        => $checkmark_entries,
					'name'       => static::FIELD_CHECKMARK_ENTRIES,
					'label'      => 'Checkmark Entries',
					'type'       => 'repeater',
					'layout'     => 'block',
					'sub_fields' => array(
						static::FIELD_CHECKMARK_ENTRY_TEXT => array(
							'key'      => $checkmark_entry_text,
							'name'     => static::FIELD_CHECKMARK_ENTRY_TEXT,
							'label'    => 'Text',
							'type'     => 'text',
							'required' => 1,
						),
					),
				),
				static::FIELD_BUTTON            => array(
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
				'title'      => 'Checkmark + Text',
				'post_types' => array( 'page' ),
			)
		);
	}

	/**
	 * @inheritDoc
	 */
	public static function render( $post = false, array $data = null, array $options = array() ): ?string {
		$headline          = static::get_val( static::FIELD_HEADLINE );
		$description       = static::get_val( static::FIELD_DESCRIPTION );
		$checkmark_entries = static::get_val( static::FIELD_CHECKMARK_ENTRIES );
		$button            = static::get_val( static::FIELD_BUTTON );

		ob_start();
		// Next Step: FE
		?>
		<div class="container mx-auto">
			<h3><?php echo esc_html( $headline ); ?></h3>
			<?php if ( ! empty( $description ) ) : ?>
				<div><?php echo $description; ?></div>
			<?php endif; ?>
			<div>
				<?php if ( ! empty( $checkmark_entries ) ) : ?>
					<ul>
						<?php foreach ( $checkmark_entries as $entry ) : ?>
							<li>
								<?php if ( ! empty( $entry[ static::FIELD_CHECKMARK_ENTRY_TEXT ] ) ) : ?>
									<p><?php echo esc_html( $entry[ static::FIELD_CHECKMARK_ENTRY_TEXT ] ); ?></p>
								<?php endif; ?>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
				<?php if ( ! empty( $button['url'] ) && ! empty( $button['title'] ) ) : ?>
					<a href="<?php echo esc_url( $button['url'] ); ?>" target="<?php echo esc_attr( $button['target'] ); ?>"><?php echo esc_html( $button['title'] ); ?></a>
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
