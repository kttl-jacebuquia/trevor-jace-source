<?php namespace TrevorWP\Theme\ACF\Options_Page;

class Site_Banners extends A_Options_Page {
	const FIELD_LONG_WAIT_URL           = 'long_wait_url';
	const FIELD_LONG_WAIT_FORCE_SHOW    = 'force_show';
	const FIELD_LONG_WAIT_TITLE         = 'long_wait_title';
	const FIELD_LONG_WAIT_DESCRIPTION   = 'long_wait_description';
	const FIELD_CUSTOM_ENTRIES          = 'custom_entries';
	const FIELD_CUSTOM_ENTRY_ACTIVE     = 'custom_entry_active';
	const FIELD_CUSTOM_ENTRY_TITLE      = 'custom_entry_title';
	const FIELD_CUSTOM_ENTRY_MESSAGE    = 'custom_entry_message';
	const FIELD_CUSTOM_ENTRY_START_DATE = 'custom_entry_start_date';
	const FIELD_CUSTOM_ENTRY_END_DATE   = 'custom_entry_end_date';

	/** @inheritDoc */
	protected static function prepare_fields(): array {
		$long_wait_url           = static::gen_field_key( static::FIELD_LONG_WAIT_URL );
		$long_wait_force_show    = static::gen_field_key( static::FIELD_LONG_WAIT_FORCE_SHOW );
		$long_wait_title         = static::gen_field_key( static::FIELD_LONG_WAIT_TITLE );
		$long_wait_description   = static::gen_field_key( static::FIELD_LONG_WAIT_DESCRIPTION );
		$custom_entries          = static::gen_field_key( static::FIELD_CUSTOM_ENTRIES );
		$custom_entry_active     = static::gen_field_key( static::FIELD_CUSTOM_ENTRY_ACTIVE );
		$custom_entry_start_date = static::gen_field_key( static::FIELD_CUSTOM_ENTRY_START_DATE );
		$custom_entry_end_date   = static::gen_field_key( static::FIELD_CUSTOM_ENTRY_END_DATE );
		$custom_entry_title      = static::gen_field_key( static::FIELD_CUSTOM_ENTRY_TITLE );
		$custom_entry_message    = static::gen_field_key( static::FIELD_CUSTOM_ENTRY_MESSAGE );

		return array_merge(
			static::_gen_tab_field( 'Long Wait' ),
			array(
				static::FIELD_LONG_WAIT_URL         => array(
					'key'           => $long_wait_url,
					'name'          => static::FIELD_LONG_WAIT_URL,
					'label'         => 'Wait URL',
					'type'          => 'url',
					'default_value' => 'https://trevorproject.secure.force.com/services/apexrest/wait',
					'required'      => true,
					'wrapper'       => array(
						'width' => '50%',
					),
				),
				static::FIELD_LONG_WAIT_FORCE_SHOW  => array(
					'key'         => $long_wait_force_show,
					'name'        => static::FIELD_LONG_WAIT_FORCE_SHOW,
					'label'       => 'Force Show',
					'type'        => 'true_false',
					'ui'          => 1,
					'ui_on_text'  => '',
					'ui_off_text' => '',
					'wrapper'     => array(
						'width' => '50%',
					),
				),
				static::FIELD_LONG_WAIT_TITLE       => array(
					'key'      => $long_wait_title,
					'name'     => static::FIELD_LONG_WAIT_TITLE,
					'label'    => 'Title',
					'type'     => 'text',
					'required' => true,
					'wrapper'  => array(
						'width' => '50%',
					),
				),
				static::FIELD_LONG_WAIT_DESCRIPTION => array(
					'key'     => $long_wait_description,
					'name'    => static::FIELD_LONG_WAIT_DESCRIPTION,
					'label'   => 'Description',
					'type'    => 'text',
					'wrapper' => array(
						'width' => '50%',
					),
				),
			),
			static::_gen_tab_field( 'Custom' ),
			array(
				static::FIELD_CUSTOM_ENTRIES => array(
					'key'        => $custom_entries,
					'name'       => static::FIELD_CUSTOM_ENTRIES,
					'label'      => 'Custom Entries',
					'type'       => 'repeater',
					'layout'     => 'block',
					'sub_fields' => array(
						static::FIELD_CUSTOM_ENTRY_ACTIVE  => array(
							'key'         => $custom_entry_active,
							'name'        => static::FIELD_CUSTOM_ENTRY_ACTIVE,
							'label'       => 'Active',
							'type'        => 'true_false',
							'ui'          => 1,
							'ui_on_text'  => '',
							'ui_off_text' => '',
						),
						static::FIELD_CUSTOM_ENTRY_START_DATE => array(
							'key'            => $custom_entry_start_date,
							'name'           => static::FIELD_CUSTOM_ENTRY_START_DATE,
							'label'          => 'Start Date',
							'type'           => 'date_picker',
							'required'       => true,
							'wrapper'        => array(
								'width' => '50%',
							),
							'display_format' => 'M j, Y',
							'return_format'  => 'M j, Y',
							'first_day'      => 0,
						),
						static::FIELD_CUSTOM_ENTRY_END_DATE => array(
							'key'            => $custom_entry_end_date,
							'name'           => static::FIELD_CUSTOM_ENTRY_END_DATE,
							'label'          => 'End Date',
							'type'           => 'date_picker',
							'required'       => true,
							'wrapper'        => array(
								'width' => '50%',
							),
							'display_format' => 'M j, Y',
							'return_format'  => 'M j, Y',
							'first_day'      => 0,
						),
						static::FIELD_CUSTOM_ENTRY_TITLE   => array(
							'key'      => $custom_entry_title,
							'name'     => static::FIELD_CUSTOM_ENTRY_TITLE,
							'label'    => 'Title',
							'type'     => 'text',
							'required' => true,
							'wrapper'  => array(
								'width' => '50%',
							),
						),
						static::FIELD_CUSTOM_ENTRY_MESSAGE => array(
							'key'     => $custom_entry_message,
							'name'    => static::FIELD_CUSTOM_ENTRY_MESSAGE,
							'label'   => 'Message',
							'type'    => 'text',
							'wrapper' => array(
								'width' => '50%',
							),
						),
					),
				),
			)
		);
	}
}
