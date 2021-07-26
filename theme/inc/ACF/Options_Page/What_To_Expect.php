<?php namespace TrevorWP\Theme\ACF\Options_Page;

class What_To_Expect extends A_Options_Page {
	const FIELD_HEADLINE      = 'what_to_expect_headline';
	const FIELD_DESCRIPTION   = 'what_to_expect_description';
	const FIELD_ENTRIES       = 'what_to_expect_entries';
	const FIELD_ENTRY_TEXT    = 'what_to_expect_entry_text';
	const FIELD_TERMS         = 'what_to_expect_terms';
	const FIELD_TERMS_HEADING = 'what_to_expect_terms_heading';
	const FIELD_TERMS_CONTENT = 'what_to_expect_terms_content';

	/** @inheritDoc */
	protected static function prepare_page_register_args(): array {
		return array_merge(
			parent::prepare_page_register_args(),
			array(
				'page_title'  => 'What To Expect Popup',
				'parent_slug' => 'general-settings',
			)
		);
	}

	/** @inheritDoc */
	protected static function prepare_fields(): array {
		$headline      = static::gen_field_key( static::FIELD_HEADLINE );
		$description   = static::gen_field_key( static::FIELD_DESCRIPTION );
		$entries       = static::gen_field_key( static::FIELD_ENTRIES );
		$entry_text    = static::gen_field_key( static::FIELD_ENTRY_TEXT );
		$terms         = static::gen_field_key( static::FIELD_TERMS );
		$terms_heading = static::gen_field_key( static::FIELD_TERMS_HEADING );
		$terms_content = static::gen_field_key( static::FIELD_TERMS_CONTENT );

		return array_merge(
			array(
				static::FIELD_HEADLINE    => array(
					'key'      => $headline,
					'name'     => static::FIELD_HEADLINE,
					'label'    => 'Headline',
					'type'     => 'text',
					'required' => true,
				),
				static::FIELD_DESCRIPTION => array(
					'key'       => $description,
					'name'      => static::FIELD_DESCRIPTION,
					'label'     => 'Description',
					'type'      => 'textarea',
					'new_lines' => 'br',
				),
				static::FIELD_ENTRIES     => array(
					'key'          => $entries,
					'name'         => static::FIELD_ENTRIES,
					'label'        => 'Entries',
					'type'         => 'repeater',
					'min'          => 3,
					'max'          => 3,
					'layout'       => 'block',
					'button_label' => 'Add Entry',
					'sub_fields'   => array(
						array(
							'key'          => $entry_text,
							'name'         => static::FIELD_ENTRY_TEXT,
							'label'        => 'Entry Text',
							'type'         => 'wysiwyg',
							'tabs'         => 'visual',
							'toolbar'      => 'basic',
							'media_upload' => 0,
						),
					),
				),
				static::FIELD_TERMS       => array(
					'key'        => $terms,
					'name'       => static::FIELD_TERMS,
					'label'      => 'Terms of Service',
					'type'       => 'group',
					'layout'     => 'row',
					'sub_fields' => array(
						array(
							'key'   => $terms_heading,
							'name'  => static::FIELD_TERMS_HEADING,
							'label' => 'Heading',
							'type'  => 'text',
						),
						array(
							'key'       => $terms_content,
							'name'      => static::FIELD_TERMS_CONTENT,
							'label'     => 'Content',
							'type'      => 'textarea',
							'new_lines' => 'br',
						),
					),
				),
			),
		);
	}

	/**
	 * Renders the quick exit modal contents.
	 */
	public static function render(): string {
		$headline    = static::get_option( static::FIELD_HEADLINE );
		$description = static::get_option( static::FIELD_DESCRIPTION );
		$entries     = static::get_option( static::FIELD_ENTRIES );
		// $entry_text    = static::get_option( static::FIELD_ENTRY_TEXT );
		$terms         = static::get_option( static::FIELD_TERMS );
		$terms_heading = $terms[ static::FIELD_TERMS_HEADING ];
		$terms_content = $terms[ static::FIELD_TERMS_CONTENT ];

		ob_start();
		?>
			<div class="what-to-expect-modal__container">
				<div class="what-to-expect-modal__content">
					<h2 class="what-to-expect-modal__heading"><?php echo $headline; ?></h2>
					<p class="what-to-expect-modal__description"><?php echo $description; ?></p>
					<div class="what-to-expect-modal__entries" role="list">
						<?php foreach ( $entries as $key => $entry ) : ?>
							<?php if ( ! empty( $entry[ static::FIELD_ENTRY_TEXT ] ) ) : ?>
								<div class="what-to-expect-modal__entry" role="listitem">
									<span class="what-to-expect-modal__entry-number"><?php echo $key + 1; ?></span>
									<div class="what-to-expect-modal__entry-text"><?php echo $entry[ static::FIELD_ENTRY_TEXT ]; ?></div>
								</div>
							<?php endif; ?>
						<?php endforeach; ?>
					</div>
				</div>
				<div class="what-to-expect-modal__terms">
					<h3 class="what-to-expect-modal__terms-heading"><?php echo $terms_heading; ?></h3>
					<p class="what-to-expect-modal__terms-content"><?php echo $terms_content; ?></p>
				</div>
			</div>
		<?php
		return ob_get_clean();

	}
}
