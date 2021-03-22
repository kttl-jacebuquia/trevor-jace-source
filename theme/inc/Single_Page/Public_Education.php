<?php namespace TrevorWP\Theme\Single_Page;

use TrevorWP\Theme\Customizer\Component;
use TrevorWP\Theme\Helper;

/**
 * Public Education Page
 */
class Public_Education extends Abstract_Single_Page {
	const PANEL_ID = self::ID_PREFIX . 'public_education';

	/* Sections */
	const SECTION_HEADER = self::PANEL_ID . '_' . self::NAME_SECTION_HEADER;
	const SECTION_INFO_BOXES = self::PANEL_ID . '_info_boxes';
	const SECTION_OFFERINGS = self::PANEL_ID . '_offerings';
	const SECTION_TESTIMONIALS = self::PANEL_ID . '_testimonials';
	const SECTION_CIRCULATION = self::PANEL_ID . '_circulation';

	/* Sub Components */
	const SUB_COMPONENT_OFFERINGS_CARD_ALY = self::SECTION_OFFERINGS . '_card_aly';
	const SUB_COMPONENT_OFFERINGS_CARD_CARE = self::SECTION_OFFERINGS . '_card_care';

	/* Settings */
	const SETTING_OFFERINGS_TITLE = self::SECTION_OFFERINGS . '_title';
	const SETTING_OFFERINGS_DESC = self::SECTION_OFFERINGS . '_desc';

	const DEFAULTS = [
		self::SETTING_OFFERINGS_TITLE => 'Our program offerings',
		self::SETTING_OFFERINGS_DESC  => 'Designed for youth-serving professionals, these training offer tools to help prevent suicide amongst LGBTQ young people.',
	];

	/** @inheritdoc */
	protected static $_section_components = [
		self::SECTION_HEADER       => [
			Component\Header::class,
			[
				'options'  => [ 'type' => Component\Header::TYPE_SPLIT_IMG ],
				'defaults' => [
					Component\Header::SETTING_TITLE => 'Knowledge that has the power <tilt>the power</tilt>',
					Component\Header::SETTING_DESC  => 'Competent suicide-prevention starts with how we educate ourselves and each other. We offer tools and resources to give everyone the ability to help.',
				]
			]
		],
		self::SECTION_INFO_BOXES   => [
			Component\Info_Boxes::class,
			[
				'options'  => [
					'box_type'        => Helper\Info_Boxes::BOX_TYPE_TEXT,
					'break_behaviour' => Helper\Info_Boxes::BREAK_BEHAVIOUR_CAROUSEL,
				],
				'defaults' => [
					Component\Info_Boxes::SETTING_TITLE => 'We’ve doubed our efforts in education.',
					Component\Info_Boxes::SETTING_DESC  => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Bibendum scelerisque nulla et amet aliquam venenatis velit diam.',
					Component\Info_Boxes::SETTING_DATA  => [
						[
							'txt' => '250',
							'desc' => 'Volunteers have been trained within the last year.'
						],
						[
							'txt' => '14,000',
							'desc' => 'Youth and youth-serving reached through our Care, ALLY, and Lifeguard workshops.'
						],
						[
							'txt' => '1st',
							'desc' => 'In the nation to launch a partnership with the NY Department of Education.'
						],
					]
				],
			]
		],
		self::SECTION_TESTIMONIALS => [
			Component\Testimonials_Carousel::class,
			[]
		],
		self::SECTION_CIRCULATION  => [
			Component\Circulation::class,
			[
				'options'  => [
					'cards' => [
						'donation',
						'fundraiser',
					],
				],
				'defaults' => [
					Component\Circulation::SETTING_TITLE => 'There are other ways to help.',
				]
			]
		]
	];

	/**
	 * @var array[]
	 */
	protected static $_sub_components = [
		self::SUB_COMPONENT_OFFERINGS_CARD_ALY  => [
			Component\Info_Card::class,
			self::SECTION_OFFERINGS,
			[
				'defaults' => [
					Component\Info_Card::SETTING_TITLE   => 'Ally Training',
					Component\Info_Card::SETTING_DESC    => 'This provides a basic framework for understanding lesbian, LGBTQ youth,and the unique challenges they often face.',
					Component\Info_Card::SETTING_BUTTONS => [
						[
							'label' => 'Learn More',
						]
					],
				]
			]
		],
		self::SUB_COMPONENT_OFFERINGS_CARD_CARE => [
			Component\Info_Card::class,
			self::SECTION_OFFERINGS,
			[
				'defaults' => [
					Component\Info_Card::SETTING_TITLE   => 'CARE Training',
					Component\Info_Card::SETTING_DESC    => 'An interactive training that outlines the different environmental stressors putting LGBTQ youth at higher risk for suicide.',
					Component\Info_Card::SETTING_BUTTONS => [
						[
							'label' => 'Learn More',
						]
					],
				]
			]
		],
	];

	/** @inheritDoc */
	protected function _register_panels(): void {
		parent::_register_panels();

		$this->_manager->add_panel( static::get_panel_id(), array( 'title' => 'Public Education' ) );
	}

	/** @inheritDoc */
	protected function _register_sections(): void {
		parent::_register_sections();

		# Info Boxes
		$this->get_component( static::SECTION_INFO_BOXES )->register_section();

		# Offerings
		$this->_manager->add_section(
			static::SECTION_OFFERINGS,
			array(
				'panel' => static::get_panel_id(),
				'title' => 'Offerings',
			)
		);

		# Testimonials
		$this->get_component( static::SECTION_TESTIMONIALS )->register_section();

		# Circulation
		$this->get_component( static::SECTION_CIRCULATION )->register_section();
	}

	/** @inheritdoc */
	protected function _register_controls(): void {
		parent::_register_controls();

		$this->_manager->add_control(
			static::SETTING_OFFERINGS_TITLE,
			[
				'setting' => static::SETTING_OFFERINGS_TITLE,
				'section' => static::SECTION_OFFERINGS,
				'label'   => 'Title',
				'type'    => 'text',
			]
		);

		$this->_manager->add_control(
			static::SETTING_OFFERINGS_DESC,
			[
				'setting' => static::SETTING_OFFERINGS_DESC,
				'section' => static::SECTION_OFFERINGS,
				'label'   => 'Description',
				'type'    => 'text',
			]
		);

		static::get_sub_component( static::SUB_COMPONENT_OFFERINGS_CARD_ALY )->set_customizer( $this )->register_controls();
		static::get_sub_component( static::SUB_COMPONENT_OFFERINGS_CARD_CARE )->set_customizer( $this )->register_controls();
	}
}
