<?php namespace TrevorWP\Theme\Single_Page;

use TrevorWP\Theme\Customizer\Component;
use TrevorWP\Theme\Customizer\Control;

class Contact_Us extends Abstract_Single_Page {
	const PANEL_ID = self::ID_PREFIX . 'contact_us';

	/* Sections */
	const SECTION_HEADER = self::PANEL_ID . '_' . self::NAME_SECTION_HEADER;
	const SECTION_ADDRESSES = self::PANEL_ID . '_addresses';
	const SECTION_PHONES = self::PANEL_ID . '_phones';
	const SECTION_INFO_CARD = self::PANEL_ID . '_info_card';
	const SECTION_FOOTNOTE = self::PANEL_ID . '_footnote';

	/* Sub Components */
	const SUB_COMPONENT_INFO_CARD = self::SECTION_INFO_CARD . '_card';

	/* Settings */
	/* * Addresses */
	const SETTING_ADDRESSES_TITLE = self::SECTION_ADDRESSES . '_title';
	const SETTING_ADDRESSES_DATA = self::SECTION_ADDRESSES . '_data';
	/* * Phones */
	const SETTING_PHONES_TITLE = self::SECTION_PHONES . '_title';
	const SETTING_PHONES_DATA = self::SECTION_PHONES . '_data';
	/* * Footnote */
	const SETTING_FOOTNOTE_NOTE = self::SECTION_FOOTNOTE . '_note';

	const DEFAULTS = [
		self::SETTING_ADDRESSES_TITLE => 'Address',
		self::SETTING_ADDRESSES_DATA  => [
			[
				'name'  => 'The Trevor Project',
				'line1' => 'PO Box 69232',
				'line2' => 'West Hollywood, CA 90069',
			],
		],
		self::SETTING_PHONES_TITLE    => 'Phone Numbers',
		self::SETTING_PHONES_DATA     => [
			[
				'name'   => 'West Hollywood Office',
				'number' => '(370) 271-8845',
			],
			[
				'name'   => 'New York Office',
				'number' => '(212) 695-8650',
			],
			[
				'name'   => 'Development Office',
				'number' => '(212) 695-8650 | Ext. 500',
			],
		],
		self::SETTING_FOOTNOTE_NOTE   => 'The Trevor Project is a 501(c)(3), tax-exempt organization. Our EIN is 53-0193519.'
	];

	/** @inheritdoc */
	protected static $_section_components = [
		self::SECTION_HEADER => [
			Component\Header::class,
			[
				'options'  => [ 'type' => Component\Header::TYPE_TEXT ],
				'defaults' => [
					Component\Header::SETTING_TITLE    => 'Contact Us',
					Component\Header::SETTING_DESC     => 'If you are thinking about suicide or are feeling alone and need someone to talk to, reach out to one of our counselors. It’s free, confidential and available 24/7.',
					Component\Header::SETTING_CTA_TXT  => 'Make Donations',
					Component\Header::SETTING_CTA_URL  => '#',
					Component\Header::SETTING_CTA2_TXT => 'Press Inquiries',
					Component\Header::SETTING_CTA2_URL => '#',
				]
			]
		],
	];

	/** @inheritdoc */
	protected static $_sub_components = [
		self::SUB_COMPONENT_INFO_CARD => [
			Component\Info_Card::class,
			self::SECTION_INFO_CARD,
			[
				'defaults' => [
					Component\Info_Card::SETTING_TITLE   => 'Request a Speaker',
					Component\Info_Card::SETTING_DESC    => 'Ullamcorper lorem placerat tristique nec suspendisse aliquet id sagittis morbi. Eget massa eu convallis dictumst arcu. Eget tincidunt laoreet a, blandit diam, purus dolor justo. Elementum.',
					Component\Info_Card::SETTING_BUTTONS => [
						[
							'label' => 'Request A Speaker',
							'href'  => '#',
						],
					],
				]
			]
		]
	];

	/** @inheritDoc */
	protected function _register_panels(): void {
		parent::_register_panels();

		$this->_manager->add_panel( static::get_panel_id(), [ 'title' => 'Contact Us' ] );
	}

	/** @inheritDoc */
	protected function _register_sections(): void {
		parent::_register_sections();

		# Addresses
		$this->_manager->add_section(
			static::SECTION_ADDRESSES,
			[
				'panel' => static::get_panel_id(),
				'title' => 'Addresses',
			]
		);

		# Phones
		$this->_manager->add_section(
			static::SECTION_PHONES,
			[
				'panel' => static::get_panel_id(),
				'title' => 'Phones',
			]
		);

		# Info Card
		$this->_manager->add_section(
			static::SECTION_INFO_CARD,
			[
				'panel' => static::get_panel_id(),
				'title' => 'Info Card',
			]
		);

		# Footnote
		$this->_manager->add_section(
			static::SECTION_FOOTNOTE,
			[
				'panel' => static::get_panel_id(),
				'title' => 'Footnote',
			]
		);
	}

	/** @inheritdoc */
	protected function _register_controls(): void {
		parent::_register_controls();
		$manager = $this->get_manager();

		# Addresses
		$this->_manager->add_control(
			static::SETTING_ADDRESSES_TITLE,
			[
				'setting' => static::SETTING_ADDRESSES_TITLE,
				'section' => static::SECTION_ADDRESSES,
				'label'   => 'Title',
				'type'    => 'text',
			]
		);
		$manager->add_control( new Control\Custom_List( $manager, self::SETTING_ADDRESSES_DATA, [
			'setting' => self::SETTING_ADDRESSES_DATA,
			'section' => self::SECTION_ADDRESSES,
			'label'   => 'Addresses',
			'fields'  => [
				'name'  => [
					'type'       => Control\Custom_List::FIELD_TYPE_INPUT,
					'input_type' => 'text',
					'label'      => 'Name',
				],
				'line1' => [
					'type'       => Control\Custom_List::FIELD_TYPE_INPUT,
					'input_type' => 'text',
					'label'      => 'Line 1',
				],
				'line2' => [
					'type'       => Control\Custom_List::FIELD_TYPE_INPUT,
					'input_type' => 'text',
					'label'      => 'Line 1',
				],
			],
		] ) );

		# Phones
		$this->_manager->add_control(
			static::SETTING_PHONES_TITLE,
			[
				'setting' => static::SETTING_PHONES_TITLE,
				'section' => static::SECTION_PHONES,
				'label'   => 'Title',
				'type'    => 'text',
			]
		);
		$manager->add_control( new Control\Custom_List( $manager, self::SETTING_PHONES_DATA, [
			'setting' => self::SETTING_PHONES_DATA,
			'section' => self::SECTION_PHONES,
			'label'   => 'Addresses',
			'fields'  => [
				'name'   => [
					'type'       => Control\Custom_List::FIELD_TYPE_INPUT,
					'input_type' => 'text',
					'label'      => 'Name',
				],
				'number' => [
					'type'       => Control\Custom_List::FIELD_TYPE_INPUT,
					'input_type' => 'tel',
					'label'      => 'Phone Number',
				],
			],
		] ) );

		static::get_sub_component( static::SUB_COMPONENT_INFO_CARD )->set_customizer( $this )->register_controls();
	}
}
