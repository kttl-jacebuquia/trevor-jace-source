<?php namespace TrevorWP\Theme\ACF\Options_Page;

class Quick_Exit extends A_Options_Page {
	const FIELD_HEADLINE                  = 'headline';
	const FIELD_DESCRIPTION_DESKTOP       = 'description_desktop';
	const FIELD_DESCRIPTION_TABLET_MOBILE = 'description_tablet_mobile';
	const FIELD_LINK_TEXT                 = 'link_text';

	/** @inheritDoc */
	protected static function prepare_fields(): array {
		$headline                  = static::gen_field_key( static::FIELD_HEADLINE );
		$description_desktop       = static::gen_field_key( static::FIELD_DESCRIPTION_DESKTOP );
		$description_tablet_mobile = static::gen_field_key( static::FIELD_DESCRIPTION_TABLET_MOBILE );
		$link_text                 = static::gen_field_key( static::FIELD_LINK_TEXT );

		return array_merge(
			array(
				static::FIELD_HEADLINE                  => array(
					'key'      => $headline,
					'name'     => static::FIELD_HEADLINE,
					'label'    => 'Headline',
					'type'     => 'text',
					'required' => true,
				),
				static::FIELD_DESCRIPTION_DESKTOP       => array(
					'key'   => $description_desktop,
					'name'  => static::FIELD_DESCRIPTION_DESKTOP,
					'label' => 'Description (Desktop)',
					'type'  => 'textarea',
				),
				static::FIELD_DESCRIPTION_TABLET_MOBILE => array(
					'key'   => $description_tablet_mobile,
					'name'  => static::FIELD_DESCRIPTION_TABLET_MOBILE,
					'label' => 'Description (Tablet / Mobile)',
					'type'  => 'textarea',
				),
				static::FIELD_LINK_TEXT                 => array(
					'key'           => $link_text,
					'name'          => static::FIELD_LINK_TEXT,
					'label'         => 'Link Text',
					'type'          => 'text',
					'required'      => true,
					'default_value' => 'Got it',
				),
			),
		);
	}

	/**
	 * Renders the quick exit modal contents.
	 */
	public static function render(): string {
		$headline                  = static::get_option( static::FIELD_HEADLINE );
		$description_desktop       = static::get_option( static::FIELD_DESCRIPTION_DESKTOP );
		$description_tablet_mobile = static::get_option( static::FIELD_DESCRIPTION_TABLET_MOBILE );
		$link_text                 = static::get_option( static::FIELD_LINK_TEXT );

		ob_start();
		?>
			<div class="quick-exit-modal__content">
				<h2 class="quick-exit-modal__heading"><?php echo esc_html( $headline ); ?></h2>
				<p class="quick-exit-modal__description quick-exit-modal__description--mobile">
					<?php echo esc_html( $description_tablet_mobile ); ?>
				</p>
				<p class="quick-exit-modal__description quick-exit-modal__description--desktop">
					<?php echo esc_html( $description_desktop ); ?>
				</p>
				<button aria-label="click here to close modal" class="quick-exit-modal__cta js-modal-close"><?php echo esc_html( $link_text ); ?></button>
			</div>
		<?php
		return ob_get_clean();

	}
}
