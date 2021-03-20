<?php namespace TrevorWP\Theme\Single_Page;

use TrevorWP\Theme\Customizer\Component;
use TrevorWP\Theme\Helper;

class Ally_Training extends Abstract_Single_Page {
	const PANEL_ID = self::ID_PREFIX . 'ally_training';

	/* Sections */
	const SECTION_INFO_BOXES = self::PANEL_ID . '_info_boxes';
	const SECTION_INFO_BOXES_2 = self::SECTION_INFO_BOXES . '_2';
	const SECTION_TESTIMONIALS = self::PANEL_ID . '_testimonials';
	const SECTION_CIRCULATION = self::PANEL_ID . '_circulation';

	/** @inheritdoc */
	protected static $_section_components = [
		self::SECTION_HEADER       => [
			Component\Header::class,
			[
				'options'  => [ 'type' => Component\Header::TYPE_TEXT ],
				'defaults' => [
					Component\Header::SETTING_TITLE => 'Ally Training',
					Component\Header::SETTING_DESC  => 'This training is designed to create dialogue around being an adult ally for LGBTQ youth by informing participants about common terminology, the “coming out” process, and challenges at home, in school, and the community.',
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
							'text' => 'K-12 school staff and educators',
							'desc' => 'Interdum congue nunc vitae dis lectus bibendum. Integer facilisi consectetur.'
						],
						[
							'text' => 'Healthcare providers',
							'desc' => 'Interdum congue nunc vitae dis lectus bibendum. Integer facilisi consectetur.'
						],
						[
							'text' => 'Higher education staff',
							'desc' => 'Interdum congue nunc vitae dis lectus bibendum. Integer facilisi consectetur.'
						],
						[
							'text' => 'Adults working with high-risk youth',
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
}
