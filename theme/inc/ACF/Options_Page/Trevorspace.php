<?php namespace TrevorWP\Theme\ACF\Options_Page;

class Trevorspace extends A_Options_Page {
	const FIELD_ACTIVE_COUNT_URL = 'trevorspace_active_count_url';

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
		$active_count_url = static::gen_field_key( static::FIELD_ACTIVE_COUNT_URL );

		return array(
			static::FIELD_ACTIVE_COUNT_URL => array(
				'key'           => $active_count_url,
				'name'          => static::FIELD_ACTIVE_COUNT_URL,
				'label'         => 'Active Count URL',
				'type'          => 'url',
				'required'      => 1,
				'default_value' => 'https://www.trevorspace.org/active-count/',
			),
		);
	}

	/**
	 * Gets all Trevorspace values
	 */
	public static function get_trevorspace() {
		$data = array(
			'active_count_url' => static::get_option( static::FIELD_ACTIVE_COUNT_URL ),
		);

		return $data;
	}
}
