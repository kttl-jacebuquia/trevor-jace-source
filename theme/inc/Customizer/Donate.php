<?php namespace TrevorWP\Theme\Customizer;

/**
 * Donate Page
 */
class Donate extends Abstract_Customizer {
	/* Panels */
	const PANEL_ID = self::ID_PREFIX . 'donate';

	/* Sections */
	/* * Home */
	const SECTION_HOME_PREFIX = self::PANEL_ID . '_home';
	const SECTION_HOME_GENERAL = self::SECTION_HOME_PREFIX . '_general';
	const SECTION_HOME_FORM = self::SECTION_HOME_PREFIX . '_form';

	const SETTING_HOME_PREFIX = self::SECTION_HOME_PREFIX . '_';
	const SETTING_HOME_HERO_IMG = self::SETTING_HOME_PREFIX . 'hero_img';
	const SETTING_HOME_HERO_TITLE = self::SETTING_HOME_PREFIX . 'hero_title';
	const SETTING_HOME_HERO_DESC = self::SETTING_HOME_PREFIX . 'hero_desc';

	const SETTING_HOME_1_TITLE = self::SETTING_HOME_PREFIX . '1_title';
	const SETTING_HOME_1_DATA = self::SETTING_HOME_PREFIX . '1_data';

	const SETTING_HOME_2_TITLE = self::SETTING_HOME_PREFIX . '2_title';

	const SETTING_HOME_QUOTE_DATA = self::SETTING_HOME_PREFIX . 'quote_data';

	const SETTING_HOME_NAVIGATOR_TITLE = self::SETTING_HOME_PREFIX . 'nav_title';
	const SETTING_HOME_NAVIGATOR_DESC = self::SETTING_HOME_PREFIX . 'nav_desc';
	const SETTING_HOME_NAVIGATOR_DATA = self::SETTING_HOME_PREFIX . 'nav_data';

	const SETTING_HOME_FAQ_TITLE = self::SETTING_HOME_PREFIX . 'faq_title';
	const SETTING_HOME_FAQ_DATA = self::SETTING_HOME_PREFIX . 'faq_data';
	const SETTING_HOME_FAQ_FOOTER = self::SETTING_HOME_PREFIX . 'faq_footer';

	const SETTING_HOME_CIRCULATION_TITLE = self::SETTING_HOME_PREFIX . 'circulation_title';

	/* Defaults */
	const DEFAULTS = [
		self::SETTING_HOME_HERO_TITLE        => 'Your donation can <tilt>save lives.</tilt>',
		self::SETTING_HOME_HERO_DESC         => 'Every day, LGBTQ young people in crisis reach out hoping to receive the support of a warm community. It is vital we make sure our volunteers can continue to offer that support.',
		self::SETTING_HOME_1_TITLE           => 'How your money is used',
		self::SETTING_HOME_1_DATA            => [
			[ 'desc' => 'We plan to train a record number of crisis counselors. Every counselor can reach over 100 LGBTQ young people.' ],
			[ 'desc' => 'Help us to continue to provide all of our crisis services 24/7 and free of cost. Lorem ipsm dolor set. Vitae id accumsan.' ],
			[ 'desc' => 'Assist us in expanding our research and advocacy efforts. Et vehicula viverra facilisi nunc aliquet nunc eu quam. Etiam et.' ],
		],
		self::SETTING_HOME_2_TITLE           => 'More ways to give',
		self::SETTING_HOME_NAVIGATOR_TITLE   => 'Charity Navigator',
		self::SETTING_HOME_NAVIGATOR_DESC    => 'The Trevor Project is a 4-star rated charity.',
		self::SETTING_HOME_NAVIGATOR_DATA    => [
			[ 'name' => '100.00 Charity Navigator & Transparency Score' ],
			[ 'name' => 'A Charity watch grade' ],
			[ 'name' => 'Platinum-level GuideStar rating' ],
		],
		self::SETTING_HOME_FAQ_TITLE         => 'Donation FAQs',
		self::SETTING_HOME_FAQ_DATA          => [
			[ 'label' => 'Is this donation tax deductible?', 'content' => '' ],
		],
		self::SETTING_HOME_FAQ_FOOTER        => 'The Trevor Project is a 501(c)(3), tax-exempt organization. Our EIN is 53-0193519',
		self::SETTING_HOME_CIRCULATION_TITLE => 'There are other ways to help.',
	];

	/** @inheritDoc */
	protected function _register_panels(): void {
		$this->_manager->add_panel( self::PANEL_ID, [ 'title' => 'Donate' ] );
	}

	/** @inheritDoc */
	protected function _register_sections(): void {
		# Home
		## General
		$this->_manager->add_section( self::SECTION_HOME_GENERAL, [
			'panel' => self::PANEL_ID,
			'title' => '[Home] General',
		] );

		## Featured Bills & Letters
		$this->_manager->add_section( self::SECTION_HOME_FORM, [
			'panel' => self::PANEL_ID,
			'title' => '[Home] Form',
		] );
	}

	/** @inheritDoc */
	protected function _register_controls(): void {
		# Home
		$this->_manager->add_control( new \WP_Customize_Media_Control( $this->_manager, self::SETTING_HOME_HERO_IMG, [
			'setting'   => self::SETTING_HOME_HERO_IMG,
			'section'   => self::SECTION_HOME_GENERAL,
			'label'     => 'Hero Image',
			'mime_type' => 'image',
		] ) );

		$this->_manager->add_control( self::SETTING_HOME_HERO_TITLE, [
			'setting' => self::SETTING_HOME_HERO_TITLE,
			'section' => self::SECTION_HOME_GENERAL,
			'label'   => 'Hero Title',
			'type'    => 'text',
		] );

		$this->_manager->add_control( self::SETTING_HOME_HERO_DESC, [
			'setting' => self::SETTING_HOME_HERO_DESC,
			'section' => self::SECTION_HOME_GENERAL,
			'label'   => 'Hero Description',
			'type'    => 'text',
		] );

		## [1]
		$this->_manager->add_control( self::SETTING_HOME_1_TITLE, [
			'setting' => self::SETTING_HOME_1_TITLE,
			'section' => self::SECTION_HOME_GENERAL,
			'label'   => '[1] Title',
			'type'    => 'text',
		] );

		$this->_manager->add_control( new Control\Custom_List( $this->_manager, self::SETTING_HOME_1_DATA, [
			'setting' => self::SETTING_HOME_1_DATA,
			'section' => self::SECTION_HOME_GENERAL,
			'label'   => '[1] Data',
			'fields'  => [
				'img'  => [
					'type'      => Control\Custom_List::FIELD_TYPE_MEDIA,
					'label'     => 'Media',
					'mime_type' => 'image',
				],
				'desc' => [
					'type'  => Control\Custom_List::FIELD_TYPE_TEXTAREA,
					'label' => 'Description'
				],
			],
		] ) );

		## [2]
		$this->_manager->add_control( self::SETTING_HOME_1_TITLE, [
			'setting' => self::SETTING_HOME_1_TITLE,
			'section' => self::SECTION_HOME_GENERAL,
			'label'   => '[2] Title',
			'type'    => 'text',
		] );

		## Quote
		$this->_manager->add_control( new Control\Custom_List( $this->_manager, self::SETTING_HOME_QUOTE_DATA, [
			'setting' => self::SETTING_HOME_QUOTE_DATA,
			'section' => self::SECTION_HOME_GENERAL,
			'label'   => 'Quotes',
			'fields'  => Control\Custom_List::FIELDSET_QUOTE,
		] ) );

		## Navigator
		$this->_manager->add_control( self::SETTING_HOME_NAVIGATOR_TITLE, [
			'setting' => self::SETTING_HOME_NAVIGATOR_TITLE,
			'section' => self::SECTION_HOME_GENERAL,
			'label'   => 'Navigator Title',
			'type'    => 'text',
		] );

		$this->_manager->add_control( self::SETTING_HOME_NAVIGATOR_DESC, [
			'setting' => self::SETTING_HOME_NAVIGATOR_DESC,
			'section' => self::SECTION_HOME_GENERAL,
			'label'   => 'Navigator Description',
			'type'    => 'textarea',
		] );

		$this->_manager->add_control( new Control\Custom_List( $this->_manager, self::SETTING_HOME_NAVIGATOR_DATA, [
			'setting' => self::SETTING_HOME_NAVIGATOR_DATA,
			'section' => self::SECTION_HOME_GENERAL,
			'label'   => 'Navigator Data',
			'fields'  => [
				'img'  => [
					'type'      => Control\Custom_List::FIELD_TYPE_MEDIA,
					'label'     => 'Media',
					'mime_type' => 'image',
				],
				'name' => [
					'type'  => Control\Custom_List::FIELD_TYPE_INPUT,
					'label' => 'Name'
				],
			],
		] ) );

		## FAQ
		$this->_manager->add_control( self::SETTING_HOME_FAQ_TITLE, [
			'setting' => self::SETTING_HOME_FAQ_TITLE,
			'section' => self::SECTION_HOME_GENERAL,
			'label'   => 'FAQ Title',
			'type'    => 'text',
		] );

		$this->_manager->add_control( new Control\Custom_List( $this->_manager, self::SETTING_HOME_FAQ_DATA, [
			'setting' => self::SETTING_HOME_FAQ_DATA,
			'section' => self::SECTION_HOME_GENERAL,
			'label'   => 'FAQ Data',
			'fields'  => [
				'label'   => [
					'type'  => Control\Custom_List::FIELD_TYPE_INPUT,
					'label' => 'Label',
				],
				'content' => [
					'type'  => Control\Custom_List::FIELD_TYPE_TEXTAREA,
					'label' => 'Content'
				],
			],
		] ) );

		$this->_manager->add_control( self::SETTING_HOME_FAQ_FOOTER, [
			'setting' => self::SETTING_HOME_FAQ_FOOTER,
			'section' => self::SECTION_HOME_GENERAL,
			'label'   => 'FAQ Footer',
			'type'    => 'textarea',
		] );

		## Circulation
		$this->_manager->add_control( self::SETTING_HOME_CIRCULATION_TITLE, [
			'setting' => self::SETTING_HOME_CIRCULATION_TITLE,
			'section' => self::SECTION_HOME_GENERAL,
			'label'   => 'Circulation Title',
			'type'    => 'text',
		] );
	}
}