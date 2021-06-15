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
}
