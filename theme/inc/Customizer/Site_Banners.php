<?php namespace TrevorWP\Theme\Customizer;

use TrevorWP\Theme\Customizer\Control\Custom_List;

/**
 * Site Banners Customizer
 */
class Site_Banners extends Abstract_Customizer {
	const PANEL_ID = self::ID_PREFIX . 'site_banners';

	/**
	 * Sections
	 */
	const SECTION_LONG_WAIT = self::PANEL_ID . '_long_wait';
	const SECTION_CUSTOM    = self::PANEL_ID . '_custom';

	/**
	 * Settings
	 */
	const SETTING_LONG_WAIT_URL   = self::SECTION_LONG_WAIT . '_url';
	const SETTING_LONG_WAIT_FORCE = self::SECTION_LONG_WAIT . '_force';
	const SETTING_LONG_WAIT_TITLE = self::SECTION_LONG_WAIT . '_title';
	const SETTING_LONG_WAIT_DESC  = self::SECTION_LONG_WAIT . '_desc';

	const SETTING_CUSTOM_DATA = self::SECTION_CUSTOM . '_data';

	const DEFAULTS = array(
		self::SETTING_LONG_WAIT_URL   => 'https://trevorproject.secure.force.com/services/apexrest/wait',
		self::SETTING_LONG_WAIT_FORCE => false,
		self::SETTING_LONG_WAIT_TITLE => 'Wait times to reach a counselor are higher than usual.',
		self::SETTING_LONG_WAIT_DESC  => 'Thanks for your patience, we’ll be with you soon.',
		self::SETTING_CUSTOM_DATA     => array(
			array(
				'title'  => 'Our support lines are experiencing outages.',
				'desc'   => 'If you are having trouble reaching a counselor, we’ll be back online soon.',
				'active' => false,
			),
		),
	);

	/** @inheritDoc */
	protected function _register_panels(): void {
		$this->_manager->add_panel( self::PANEL_ID, array( 'title' => 'Site Banners' ) );
	}


	/** @inheritDoc */
	protected function _register_sections(): void {
		$this->_manager->add_section(
			self::SECTION_LONG_WAIT,
			array(
				'panel' => self::PANEL_ID,
				'title' => 'Long Wait',
			)
		);

		$this->_manager->add_section(
			self::SECTION_CUSTOM,
			array(
				'panel' => self::PANEL_ID,
				'title' => 'Custom',
			)
		);
	}

	/** @inheritDoc */
	protected function _register_controls(): void {
		# Long Wait
		$this->_manager->add_control(
			self::SETTING_LONG_WAIT_URL,
			array(
				'setting' => self::SETTING_LONG_WAIT_URL,
				'section' => self::SECTION_LONG_WAIT,
				'label'   => 'Wait Info URL',
				'type'    => 'url',
			)
		);

		$this->_manager->add_control(
			self::SETTING_LONG_WAIT_FORCE,
			array(
				'setting' => self::SETTING_LONG_WAIT_FORCE,
				'section' => self::SECTION_LONG_WAIT,
				'label'   => 'Force Show',
				'type'    => 'checkbox',
			)
		);

		$this->_manager->add_control(
			self::SETTING_LONG_WAIT_TITLE,
			array(
				'setting' => self::SETTING_LONG_WAIT_TITLE,
				'section' => self::SECTION_LONG_WAIT,
				'label'   => 'Title',
				'type'    => 'text',
			)
		);

		$this->_manager->add_control(
			self::SETTING_LONG_WAIT_DESC,
			array(
				'setting' => self::SETTING_LONG_WAIT_DESC,
				'section' => self::SECTION_LONG_WAIT,
				'label'   => 'Description',
				'type'    => 'text',
			)
		);

		# Custom
		$this->_manager->add_control(
			new Custom_List(
				$this->_manager,
				self::SETTING_CUSTOM_DATA,
				array(
					'setting' => self::SETTING_CUSTOM_DATA,
					'section' => self::SECTION_CUSTOM,
					'label'   => 'Carousel Data',
					'fields'  => array(
						'title'  => array(
							'type'  => Custom_List::FIELD_TYPE_INPUT,
							'label' => 'Title',
						),
						'desc'   => array(
							'type'  => Custom_List::FIELD_TYPE_TEXTAREA,
							'label' => 'Message',
						),
						'active' => array(
							'type'       => Custom_List::FIELD_TYPE_INPUT,
							'label'      => 'Active',
							'input_type' => 'checkbox',
						),
					),
				)
			)
		);
	}
}
