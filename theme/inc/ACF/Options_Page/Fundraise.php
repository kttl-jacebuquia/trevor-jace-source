<?php namespace TrevorWP\Theme\ACF\Options_Page;

class Fundraise extends A_Options_Page {
	const FIELD_TOP_LIST_CAMPAIGN_ID = 'fundraise_top_list_campaign_id';
	const FIELD_TOP_LIST_COUNT       = 'fundraise_top_list_count';

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
		$top_list_campaign_id = static::gen_field_key( static::FIELD_TOP_LIST_CAMPAIGN_ID );
		$top_list_count       = static::gen_field_key( static::FIELD_TOP_LIST_COUNT );

		return array_merge(
			static::_gen_tab_field( 'Top List' ),
			array(
				static::FIELD_TOP_LIST_CAMPAIGN_ID => array(
					'key'           => $top_list_campaign_id,
					'name'          => static::FIELD_TOP_LIST_CAMPAIGN_ID,
					'label'         => 'Campaign ID',
					'type'          => 'text',
					'required'      => 1,
					'default_value' => '24399',
				),
				static::FIELD_TOP_LIST_COUNT       => array(
					'key'           => $top_list_count,
					'name'          => static::FIELD_TOP_LIST_COUNT,
					'label'         => 'Count',
					'type'          => 'number',
					'required'      => 1,
					'default_value' => '10',
				),
			),
		);
	}

	/**
	 * Gets all Fundraise values
	 */
	public static function get_fundraise() {
		$data = array(
			'top_list' => array(
				'campaign_id' => static::get_option( static::FIELD_TOP_LIST_CAMPAIGN_ID ),
				'count'       => static::get_option( static::FIELD_TOP_LIST_COUNT ),
			),
		);

		return $data;
	}
}
