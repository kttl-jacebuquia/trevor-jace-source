<?php namespace TrevorWP\Theme\Single_Page;

use TrevorWP\Theme\Customizer\Component;
use TrevorWP\Theme\Helper;

class Ally_Training extends Abstract_Single_Page {
	const PANEL_ID = self::ID_PREFIX . 'ally_training';

	/* Sections */
	const SECTION_HEADER = self::PANEL_ID . '_' . self::NAME_SECTION_HEADER;
	const SECTION_INFO_BOXES = self::PANEL_ID . '_info_boxes';
	const SECTION_INFO_BOXES_2 = self::SECTION_INFO_BOXES . '_2';
	const SECTION_OTHER_TRAINING = self::PANEL_ID . '_other_training';
	const SECTION_TESTIMONIALS = self::PANEL_ID . '_testimonials';
	const SECTION_CIRCULATION = self::PANEL_ID . '_circulation';

	/* Sub Components */
	const SUB_COMPONENT_OTHER_TRAINING_CONTACT = self::SECTION_OTHER_TRAINING . '_contact';
	const SUB_COMPONENT_OTHER_TRAINING_CARE = self::SECTION_OTHER_TRAINING . '_care';

	/** @inheritdoc */
	protected static $_section_components = [
		self::SECTION_HEADER       => [
			Component\Header::class,
			[
				'options'  => [ 'type' => Component\Header::TYPE_TEXT ],
				'defaults' => [
					Component\Header::SETTING_TITLE => 'Ally Training',
					Component\Header::SETTING_DESC  => 'This training is designed to create dialogue around being an adult ally for LGBTQ youth by informing participants about common terminology, the “coming out” process, and challenges at home, in school, and the community.',
					Component\Header::SETTING_CTA_TXT => 'Contact Our Training Team',
					Component\Header::SETTING_CTA_URL => '#',

				]
			]
		],
		self::SECTION_INFO_BOXES   => [
			Component\Info_Boxes::class,
			[
				'options'  => [
					'box_type'        => Helper\Info_Boxes::BOX_TYPE_TEXT,
					'break_behaviour' => Helper\Info_Boxes::BREAK_BEHAVIOUR_GRID_1_2_2,
				],
				'defaults' => [
					Component\Info_Boxes::SETTING_TITLE => 'We educate youth-serving professionals on the challenges faced by LGBTQ young people.',
					Component\Info_Boxes::SETTING_DESC  => 'In addition to the audiences below, Ally training has been presented to adults within the foster care system, those working with adjudicated, homeless or runaway youth, with spiritual or faith leaders, and many more.',
					Component\Info_Boxes::SETTING_DATA  => [
						[
							'txt'  => 'K-12 school staff and educators',
							'desc' => 'Interdum congue nunc vitae dis lectus bibendum. Integer facilisi consectetur.'
						],
						[
							'txt'  => 'Healthcare providers',
							'desc' => 'Interdum congue nunc vitae dis lectus bibendum. Integer facilisi consectetur.'
						],
						[
							'txt'  => 'Higher education staff',
							'desc' => 'Interdum congue nunc vitae dis lectus bibendum. Integer facilisi consectetur.'
						],
						[
							'txt'  => 'Adults working with high-risk youth',
							'desc' => 'Interdum congue nunc vitae dis lectus bibendum. Integer facilisi consectetur.'
						],
					]
				],
			]
		],
		self::SECTION_INFO_BOXES_2 => [
			Component\Info_Boxes::class,
			[
				'options'  => [
					'box_type'        => Helper\Info_Boxes::BOX_TYPE_IMG,
					'break_behaviour' => Helper\Info_Boxes::BREAK_BEHAVIOUR_GRID_1_2_4,
				],
				'defaults' => [
					Component\Info_Boxes::SETTING_TITLE => 'What you will learn',
					Component\Info_Boxes::SETTING_DESC  => 'Through activities, participants are encouraged to explore their biases, build knowledge, and develop empathy. Participants will be able to:',
					Component\Info_Boxes::SETTING_DATA  => [
						[
							'desc' => 'Describe various terminology within LGBTQ communities.'
						],
						[
							'desc' => 'Explain the unique challenges facing LGBTQ youth.'
						],
						[
							'desc' => 'Identify ways to create safer environments.'
						],
						[
							'desc' => 'Discuss the services offered by The Trevor Project.'
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

	/** @inheritdoc */
	protected static $_sub_components = [
		self::SUB_COMPONENT_OTHER_TRAINING_CONTACT => [
			Component\Info_Card::class,
			self::SECTION_OTHER_TRAINING,
			[
				'defaults' => [
					Component\Info_Card::SETTING_TITLE   => 'Another training you might find helpful. ',
					Component\Info_Card::SETTING_DESC    => 'We have a few different trainings for youth-serving professionals. If you are interested in Ally training, try this one too.',
					Component\Info_Card::SETTING_BUTTONS => [
						[
							'label' => 'Contact Our Training Team',
						]
					],
				]
			]
		],
		self::SUB_COMPONENT_OTHER_TRAINING_CARE    => [
			Component\Info_Card::class,
			self::SECTION_OTHER_TRAINING,
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
		]
	];

	/** @inheritDoc */
	protected function _register_panels(): void {
		parent::_register_panels();

		$this->_manager->add_panel( static::get_panel_id(), array( 'title' => 'Ally Training' ) );
	}

	/** @inheritDoc */
	protected function _register_sections(): void {
		parent::_register_sections();

		# Info Boxes
		$this->get_component( static::SECTION_INFO_BOXES )->register_section();

		# Info Boxes 2
		$this->get_component( static::SECTION_INFO_BOXES_2 )->register_section();
		$this->get_manager()->get_section( static::SECTION_INFO_BOXES_2 )->title = 'Info Boxes 2';

		# Other Training
		$this->_manager->add_section(
			static::SECTION_OTHER_TRAINING,
			array(
				'panel' => static::get_panel_id(),
				'title' => 'Other Training',
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

		static::get_sub_component( static::SUB_COMPONENT_OTHER_TRAINING_CONTACT )->set_customizer( $this )->register_controls();
		static::get_sub_component( static::SUB_COMPONENT_OTHER_TRAINING_CARE )->set_customizer( $this )->register_controls();
	}
}
