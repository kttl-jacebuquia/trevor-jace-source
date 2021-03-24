<?php namespace TrevorWP\Theme\Single_Page;

use TrevorWP\Theme\Customizer\Component;
use TrevorWP\Theme\Helper;

class Careers extends Abstract_Single_Page {
	const PANEL_ID = self::ID_PREFIX . 'careers';

	/* Sections */
	const SECTION_HEADER = self::PANEL_ID . '_' . self::NAME_SECTION_HEADER;
	const SECTION_INFO_BOXES = self::PANEL_ID . '_info_boxes';
	const SECTION_CIRCULATION = self::PANEL_ID . '_circulation';

	/* Settings */
	const SETTING_INFO_BOXES_CTA_TEXT = self::SECTION_INFO_BOXES . '_cta_text';
	const SETTING_INFO_BOXES_CTA_URL = self::SECTION_INFO_BOXES . '_cta_url';

	const DEFAULTS = [
		self::SETTING_INFO_BOXES_CTA_TEXT => 'See Open Positions',
		self::SETTING_INFO_BOXES_CTA_URL  => '#',
	];

	/** @inheritdoc */
	protected static $_section_components = [
		self::SECTION_HEADER      => [
			Component\Header::class,
			[
				'options'  => [ 'type' => Component\Header::TYPE_HORIZONTAL ],
				'defaults' => [
					Component\Header::SETTING_TITLE => 'Wake up every day <tiltb>with purpose.</tiltb>',
					Component\Header::SETTING_DESC  => 'If you’re looking for the opportunity to work with a talented team that is dedicated to lifting up LGBTQ young people, look no further. ',
				]
			]
		],
		self::SECTION_INFO_BOXES  => [
			Component\Info_Boxes::class,
			[
				'options'  => [
					'box_type'        => Helper\Info_Boxes::BOX_TYPE_BOTH,
					'break_behaviour' => Helper\Info_Boxes::BREAK_BEHAVIOUR_GRID_1_2_3,
				],
				'defaults' => [
					Component\Info_Boxes::SETTING_TITLE => 'Our Culture',
					Component\Info_Boxes::SETTING_DESC  => 'Erat etiam scelerisque eu netus viverra cum nulla leo facilisis. Id tellus adipiscing nulla morbi adipiscing faucibus lectus. Pellentesque turpis ultrices pulvinar purus risus vitae. Id urna euismod risus faucibus arcu praesent eget.',
					Component\Info_Boxes::SETTING_DATA  => [
						[
							'txt'  => 'Pillar 1',
							'desc' => 'Vitae ultrices pulvinar gravida diam, facilisi tincidunt.'
						],
						[
							'txt'  => 'Pillar 2',
							'desc' => 'Vitae ultrices pulvinar gravida diam, facilisi tincidunt.'
						],
						[
							'txt'  => 'Pillar 3',
							'desc' => 'Vitae ultrices pulvinar gravida diam, facilisi tincidunt.'
						],
					]
				],
			]
		],
		self::SECTION_CIRCULATION => [
			Component\Circulation::class,
			[
				'options'  => [
					'cards' => [
						Helper\Circulation_Card::TYPE_COUNSELOR,
						Helper\Circulation_Card::TYPE_DONATION,
					],
				],
				'defaults' => [
					Component\Circulation::SETTING_TITLE => 'There are other ways to help.',
				]
			]
		]
	];

	/** @inheritDoc */
	protected function _register_panels(): void {
		parent::_register_panels();

		$this->_manager->add_panel( static::get_panel_id(), array( 'title' => 'Careers' ) );
	}

	/** @inheritDoc */
	protected function _register_sections(): void {
		parent::_register_sections();

		# Info Boxes
		$this->get_component( static::SECTION_INFO_BOXES )->register_section();

		# Circulation
		$this->get_component( static::SECTION_CIRCULATION )->register_section();
	}

	/** @inheritdoc */
	protected function _register_controls(): void {
		parent::_register_controls();

		$this->_manager->add_control(
			static::SETTING_INFO_BOXES_CTA_TEXT,
			[
				'setting' => static::SETTING_INFO_BOXES_CTA_TEXT,
				'section' => static::SECTION_INFO_BOXES,
				'label'   => 'CTA Text',
				'type'    => 'text',
			]
		);

		$this->_manager->add_control(
			static::SETTING_INFO_BOXES_CTA_URL,
			[
				'setting' => static::SETTING_INFO_BOXES_CTA_URL,
				'section' => static::SECTION_INFO_BOXES,
				'label'   => 'CTA URL',
				'type'    => 'text',
			]
		);
	}
}
