<?php namespace TrevorWP\Theme\Single_Page;

use TrevorWP\Theme\Customizer\Component;
use TrevorWP\Theme\Customizer\Control;
use TrevorWP\Theme\Helper;

class Strategic_Plan extends Abstract_Single_Page {
	const PANEL_ID = self::ID_PREFIX . 'strategic_plan';

	/* Sections */
	const SECTION_HEADER = self::PANEL_ID . '_' . self::NAME_SECTION_HEADER;
	const SECTION_INFO_BOXES = self::PANEL_ID . '_info_boxes';
	const SECTION_KEY_PROGRAMS = self::PANEL_ID . '_key_programs';
	const SECTION_PRINCIPLES = self::PANEL_ID . '_principles';

	/* Settings */
	/* * Header: Strategic Plan */
	const SETTING_HEADER_STRATEGIC_PLAN_FILE = self::PANEL_ID . '_header_strategic_plan_file';
	/* * Key Programs */
	const SETTING_KEY_PROGRAMS_IMG = self::SECTION_KEY_PROGRAMS . '_img';
	const SETTING_KEY_PROGRAMS_TITLE = self::SECTION_KEY_PROGRAMS . '_title';
	const SETTING_KEY_PROGRAMS_DESC = self::SECTION_KEY_PROGRAMS . '_desc';
	const SETTING_KEY_PROGRAMS_DATA = self::SECTION_KEY_PROGRAMS . '_data';
	/* * Principles */
	const SETTING_PRINCIPLES_IMG = self::SECTION_PRINCIPLES . '_img';
	const SETTING_PRINCIPLES_TITLE = self::SECTION_PRINCIPLES . '_title';
	const SETTING_PRINCIPLES_DESC = self::SECTION_PRINCIPLES . '_desc';
	const SETTING_PRINCIPLES_DATA = self::SECTION_PRINCIPLES . '_data';

	const DEFAULTS = [
		self::SETTING_KEY_PROGRAMS_TITLE => 'Our Five Key Programs',
		self::SETTING_KEY_PROGRAMS_DESC  => 'We aim to achieve this through our core group of programs.',
		self::SETTING_KEY_PROGRAMS_DATA  => [
			[
				'title' => 'Crisis Services',
				'desc'  => 'Direct suicide prevention services for via phone, text, and chat.',
				'href'  => '#'
			],
			[
				'title' => 'Peer Support',
				'desc'  => 'The world’s largest, safe-space social networking community.',
				'href'  => '#'
			],
			[
				'title' => 'Research',
				'desc'  => 'Evaluations that significantly improve our services and field.',
				'href'  => '#'
			],
			[
				'title' => 'Public Education',
				'desc'  => 'Educating around issues relevant to LGBTQ youth and allies.',
				'href'  => '#'
			],
			[
				'title' => 'Advocacy',
				'desc'  => 'Fighting for policies and laws that protect LGBTQ youth',
				'href'  => '#'
			],
		],
		self::SETTING_PRINCIPLES_TITLE   => 'Guiding Principles',
		self::SETTING_PRINCIPLES_DESC    => 'There are a few words that light our way forward as we continue to learn and grow as an organization.',
		self::SETTING_PRINCIPLES_DATA    => [
			[
				'name' => 'Diversity & Inclusion',
			],
			[
				'name' => 'Growth',
			],
			[
				'name' => 'Best in class team',
			],
			[
				'name' => 'Youth-First',
			],
			[
				'name' => 'Quality',
			],
			[
				'name' => 'Innovation',
			],
		],
	];

	/** @inheritdoc */
	protected static $_section_components = [
		self::SECTION_HEADER     => [
			Component\Header::class,
			[
				'options'  => [ 'type' => Component\Header::TYPE_TEXT ],
				'defaults' => [
					Component\Header::SETTING_TITLE   => 'Two decades ago, we responded to a health crisis.',
					Component\Header::SETTING_DESC    => 'LGBTQ young people are four times more likely to attempt suicide, and suicide remains the second leading cause of death among all young people in the U.S. Since then, we’ve become the leading global organization responding to the crisis.',
					Component\Header::SETTING_CTA_TXT => 'Download our Strategic Plan',
				]
			]
		],
		self::SECTION_INFO_BOXES => [
			Component\Info_Boxes::class,
			[
				'options'  => [
					'box_type'        => Helper\Info_Boxes::BOX_TYPE_TEXT,
					'break_behaviour' => Helper\Info_Boxes::BREAK_BEHAVIOUR_GRID_1_2_3,
				],
				'defaults' => [
					Component\Info_Boxes::SETTING_DATA => [
						[
							'txt'  => 'Our Mission',
							'desc' => 'To end suicide among lesbian, gay, bisexual, transgender, queer & questioning young people.'
						],
						[
							'txt'  => 'Our Vision',
							'desc' => 'A world where all LGBTQ young people see a bright future for themselves.'
						],
						[
							'txt'  => 'Our Goal',
							'desc' => 'To serve 1.8 million crisis contacts annually, by the end of our 25th year, while continuing to innovate on our core services.'
						],
					]
				],
			]
		],
	];


	/** @inheritDoc */
	protected function _register_panels(): void {
		parent::_register_panels();

		$this->_manager->add_panel( static::get_panel_id(), array( 'title' => 'Strategic Plan' ) );
	}

	/** @inheritDoc */
	protected function _register_sections(): void {
		parent::_register_sections();

		# Key Programs
		$this->_manager->add_section(
			static::SECTION_KEY_PROGRAMS,
			[
				'panel' => static::get_panel_id(),
				'title' => 'Key Programs',
			]
		);

		# Principles
		$this->_manager->add_section(
			static::SECTION_PRINCIPLES,
			[
				'panel' => static::get_panel_id(),
				'title' => 'Principles',
			]
		);
	}

	/** @inheritdoc */
	protected function _register_controls(): void {
		parent::_register_controls();
		$manager = $this->get_manager();

		# Header: Strategic Plan File
		$this->_manager->add_control( new \WP_Customize_Media_Control( $this->_manager, self::SETTING_HEADER_STRATEGIC_PLAN_FILE, [
			'setting'   => self::SETTING_HEADER_STRATEGIC_PLAN_FILE,
			'section'   => self::SECTION_HEADER,
			'label'     => 'Strategic Plan File',
			'mime_type' => 'application/pdf',
		] ) );

		# Key Programs
		$this->_manager->add_control( new \WP_Customize_Media_Control( $this->_manager, self::SETTING_KEY_PROGRAMS_IMG, [
			'setting'   => self::SETTING_KEY_PROGRAMS_IMG,
			'section'   => self::SECTION_KEY_PROGRAMS,
			'label'     => 'Image',
			'mime_type' => 'image',
		] ) );
		$manager->add_control(
			self::SETTING_KEY_PROGRAMS_TITLE,
			[
				'setting' => self::SETTING_KEY_PROGRAMS_TITLE,
				'section' => self::SECTION_KEY_PROGRAMS,
				'label'   => 'Title',
				'type'    => 'text',
			]
		);
		$manager->add_control(
			self::SETTING_KEY_PROGRAMS_DESC,
			[
				'setting' => self::SETTING_KEY_PROGRAMS_DESC,
				'section' => self::SECTION_KEY_PROGRAMS,
				'label'   => 'Description',
				'type'    => 'textarea',
			]
		);
		$manager->add_control( new Control\Custom_List( $manager, self::SETTING_KEY_PROGRAMS_DATA, [
			'setting' => self::SETTING_KEY_PROGRAMS_DATA,
			'section' => self::SECTION_KEY_PROGRAMS,
			'label'   => 'Data',
			'fields'  => [
				'title' => [
					'type'       => Control\Custom_List::FIELD_TYPE_INPUT,
					'input_type' => 'text',
					'label'      => 'Title',
				],
				'desc'  => [
					'type'       => Control\Custom_List::FIELD_TYPE_INPUT,
					'input_type' => 'text',
					'label'      => 'Description',
				],
				'href'  => [
					'type'       => Control\Custom_List::FIELD_TYPE_INPUT,
					'input_type' => 'text',
					'label'      => 'URL',
				]
			],
		] ) );

		# Principles
		$this->_manager->add_control( new \WP_Customize_Media_Control( $this->_manager, self::SETTING_PRINCIPLES_IMG, [
			'setting'   => self::SETTING_PRINCIPLES_IMG,
			'section'   => self::SECTION_PRINCIPLES,
			'label'     => 'Image',
			'mime_type' => 'image',
		] ) );
		$manager->add_control(
			self::SETTING_PRINCIPLES_TITLE,
			[
				'setting' => self::SETTING_PRINCIPLES_TITLE,
				'section' => self::SECTION_PRINCIPLES,
				'label'   => 'Title',
				'type'    => 'text',
			]
		);
		$manager->add_control(
			self::SETTING_PRINCIPLES_DESC,
			[
				'setting' => self::SETTING_PRINCIPLES_DESC,
				'section' => self::SECTION_PRINCIPLES,
				'label'   => 'Description',
				'type'    => 'textarea',
			]
		);
		$manager->add_control( new Control\Custom_List( $manager, self::SETTING_PRINCIPLES_DATA, [
			'setting' => self::SETTING_PRINCIPLES_DATA,
			'section' => self::SECTION_PRINCIPLES,
			'label'   => 'Items',
			'fields'  => [
				'name' => [
					'type'       => Control\Custom_List::FIELD_TYPE_INPUT,
					'input_type' => 'text',
					'label'      => 'Item',
				],
			],
		] ) );
	}
}
