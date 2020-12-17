<?php namespace TrevorWP\Theme\Customizer;

/**
 * External Script Settings
 */
class External_Scripts extends Abstract_Customizer {
	const SECTION_ID = self::ID_PREFIX . 'ext_scr';

	const SETTING_PREFIX = self::SECTION_ID . '_';
	const SETTING_HEAD_TOP = self::SETTING_PREFIX . 'head_top';
	const SETTING_HEAD_BTM = self::SETTING_PREFIX . 'head_btm';
	const SETTING_BODY_TOP = self::SETTING_PREFIX . 'body_top';
	const SETTING_BODY_BTM = self::SETTING_PREFIX . 'body_btm';

	const ALL_SETTINGS = [
		self::SETTING_HEAD_TOP,
		self::SETTING_HEAD_BTM,
		self::SETTING_BODY_TOP,
		self::SETTING_BODY_BTM,
	];

	/** @inheritDoc */
	protected function _register_sections(): void {
		$this->_manager->add_section( self::SECTION_ID, [
			'title' => 'External Scripts'
		] );
	}

	/** @inheritDoc */
	protected function _register_controls(): void {
		$this->_manager->add_control( self::SETTING_HEAD_TOP, [
			'setting'     => self::SETTING_HEAD_TOP,
			'section'     => self::SECTION_ID,
			'label'       => 'Head (Top)',
			'type'        => 'textarea',
			'description' => htmlspecialchars( 'Just after the <head> tag.' )
		] );

		$this->_manager->add_control( self::SETTING_HEAD_BTM, [
			'setting'     => self::SETTING_HEAD_BTM,
			'section'     => self::SECTION_ID,
			'label'       => 'Head (Bottom)',
			'type'        => 'textarea',
			'description' => htmlspecialchars( 'Just before the </head> tag.' )
		] );

		$this->_manager->add_control( self::SETTING_BODY_TOP, [
			'setting'     => self::SETTING_BODY_TOP,
			'section'     => self::SECTION_ID,
			'label'       => 'Body (Top)',
			'type'        => 'textarea',
			'description' => htmlspecialchars( 'Just after the <body> tag.' )
		] );

		$this->_manager->add_control( self::SETTING_BODY_BTM, [
			'setting'     => self::SETTING_BODY_BTM,
			'section'     => self::SECTION_ID,
			'label'       => 'Body (Bottom)',
			'type'        => 'textarea',
			'description' => htmlspecialchars( 'Just before the </body> tag.' )
		] );
	}
}
