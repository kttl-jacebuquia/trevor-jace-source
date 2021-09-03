<?php namespace TrevorWP\Theme\ACF\Field_Group;

use TrevorWP\CPT;
use TrevorWP\Theme\ACF\Util\Field_Val_Getter;

class What_To_Expect_Popup extends A_Field_Group {
	const FIELD_HEADLINE        = 'expect_headline';
	const FIELD_DESCRIPTION     = 'expect_description';
	const FIELD_ENTRIES         = 'expect_entries';
	const FIELD_ENTRY_TEXT      = 'expect_entry_text';
	const FIELD_TERMS           = 'expect_terms';
	const FIELD_TERMS_HEADING   = 'expect_terms_heading';
	const FIELD_TERMS_CONTENT   = 'expect_terms_content';
	const MODAL_SELECTOR_PREFIX = 'js-what-to-expect-modal';

	/** @inheritDoc */
	public static function prepare_register_args(): array {
		$headline      = static::gen_field_key( static::FIELD_HEADLINE );
		$description   = static::gen_field_key( static::FIELD_DESCRIPTION );
		$entries       = static::gen_field_key( static::FIELD_ENTRIES );
		$entry_text    = static::gen_field_key( static::FIELD_ENTRY_TEXT );
		$terms         = static::gen_field_key( static::FIELD_TERMS );
		$terms_heading = static::gen_field_key( static::FIELD_TERMS_HEADING );
		$terms_content = static::gen_field_key( static::FIELD_TERMS_CONTENT );

		return array(
			'title'    => 'What to Expect',
			'fields'   => array(
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
			'location' => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => CPT\What_To_Expect_Popup::POST_TYPE,
					),
				),
			),
		);
	}

	/**
	 * Renders the what to expect popup modal contents.
	 */
	public static function render( $post = null ): string {
		$val           = new Field_Val_Getter( static::class, $post );
		$headline      = $val->get( static::FIELD_HEADLINE );
		$description   = $val->get( static::FIELD_DESCRIPTION );
		$entries       = $val->get( static::FIELD_ENTRIES );
		$terms         = $val->get( static::FIELD_TERMS );
		$terms_heading = '';
		$terms_content = '';

		if ( ! empty( $terms ) ) {
			$terms_heading = $terms[ static::FIELD_TERMS_HEADING ];
			$terms_content = $terms[ static::FIELD_TERMS_CONTENT ];
		}

		ob_start();
		?>
			<div class="what-to-expect-modal__container">
				<div class="what-to-expect-modal__content">
					<h2 class="what-to-expect-modal__heading"><?php echo $headline; ?></h2>
					<p class="what-to-expect-modal__description"><?php echo $description; ?></p>
					<?php if ( ! empty( $entries ) ) : ?>
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
					<?php endif; ?>
				</div>
				<div class="what-to-expect-modal__terms">
					<h3 class="what-to-expect-modal__terms-heading"><?php echo $terms_heading; ?></h3>
					<p class="what-to-expect-modal__terms-content"><?php echo $terms_content; ?></p>
				</div>
			</div>
		<?php
		return ob_get_clean();
	}

	public static function gen_modal_id( $id ) {
		if ( ! empty( $id ) ) {
			return static::MODAL_SELECTOR_PREFIX . '-' . $id;
		}

		return static::MODAL_SELECTOR_PREFIX;
	}
}
