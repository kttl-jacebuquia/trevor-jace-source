<?php namespace TrevorWP\Theme\ACF\Options_Page;

class API_Keys extends A_Options_Page {
	const FIELD_MAILCHIMP_API_KEY       = 'mailchimp_api_key';
	const FIELD_MAILCHIMP_LIST_ID       = 'mailchimp_list_id';
	const FIELD_MAILCHIMP_DATA_CENTER   = 'mailchimp_data_center';
	const FIELD_GOOGLE_SHEETS_MESSAGE   = 'google_sheets_message';
	const FIELD_GOOGLE_SHEETS_API_KEY   = 'google_sheets_api_key';
	const FIELD_GOOGLE_SHEETS_SHEET_KEY = 'google_sheets_sheet_key';

	/** @inheritDoc */
	protected static function prepare_page_register_args(): array {
		return array_merge(
			parent::prepare_page_register_args(),
			array(
				'parent_slug' => 'general-settings',
			)
		);
	}

	/** @inheritDoc */
	protected static function prepare_fields(): array {
		$mailchimp_api_key       = static::gen_field_key( static::FIELD_MAILCHIMP_API_KEY );
		$mailchimp_list_id       = static::gen_field_key( static::FIELD_MAILCHIMP_LIST_ID );
		$mailchimp_data_center   = static::gen_field_key( static::FIELD_MAILCHIMP_DATA_CENTER );
		$google_sheets_message   = static::gen_field_key( static::FIELD_GOOGLE_SHEETS_MESSAGE );
		$google_sheets_api_key   = static::gen_field_key( static::FIELD_GOOGLE_SHEETS_API_KEY );
		$google_sheets_sheet_key = static::gen_field_key( static::FIELD_GOOGLE_SHEETS_SHEET_KEY );

		return array_merge(
			static::_gen_tab_field( 'MailChimp' ),
			array(
				static::FIELD_MAILCHIMP_API_KEY     => array(
					'key'           => $mailchimp_api_key,
					'name'          => static::FIELD_MAILCHIMP_API_KEY,
					'label'         => 'API Key',
					'type'          => 'text',
					'required'      => 1,
					'default_value' => '5e5a44dd9691c2ba89abfaf8f1fcf175-us12',
				),
				static::FIELD_MAILCHIMP_LIST_ID     => array(
					'key'           => $mailchimp_list_id,
					'name'          => static::FIELD_MAILCHIMP_LIST_ID,
					'label'         => 'List ID',
					'type'          => 'text',
					'required'      => 1,
					'default_value' => 'e8d7ceff05',
				),
				static::FIELD_MAILCHIMP_DATA_CENTER => array(
					'key'           => $mailchimp_data_center,
					'name'          => static::FIELD_MAILCHIMP_DATA_CENTER,
					'label'         => 'Data Center',
					'type'          => 'text',
					'required'      => 1,
					'default_value' => 'us12',
				),
			),
			static::_gen_tab_field( 'Google Sheets' ),
			array(
				static::FIELD_GOOGLE_SHEETS_MESSAGE   => array(
					'key'     => $google_sheets_message,
					'name'    => static::FIELD_GOOGLE_SHEETS_MESSAGE,
					'message' => 'Credentials used for the Ending Conversion Map data source.',
					'type'    => 'message',
				),
				static::FIELD_GOOGLE_SHEETS_API_KEY   => array(
					'key'      => $google_sheets_api_key,
					'name'     => static::FIELD_GOOGLE_SHEETS_API_KEY,
					'label'    => 'API Key',
					'type'     => 'text',
					'required' => 1,
				),
				static::FIELD_GOOGLE_SHEETS_SHEET_KEY => array(
					'key'      => $google_sheets_sheet_key,
					'name'     => static::FIELD_GOOGLE_SHEETS_SHEET_KEY,
					'label'    => 'Spreadsheet Key',
					'type'     => 'text',
					'required' => 1,
				),
			),
		);
	}

	/**
	 * Gets all mailchimp values
	 */
	public static function get_mailchimp() {
		$data = array(
			'api_key'     => static::get_option( static::FIELD_MAILCHIMP_API_KEY ),
			'list_id'     => static::get_option( static::FIELD_MAILCHIMP_LIST_ID ),
			'data_center' => static::get_option( static::FIELD_MAILCHIMP_DATA_CENTER ),
		);

		return $data;
	}
}
