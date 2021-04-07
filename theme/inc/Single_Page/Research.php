<?php namespace TrevorWP\Theme\Single_Page;

use TrevorWP\Theme\Customizer\Component;
use TrevorWP\Theme\Helper;
use TrevorWP\CPT;
use TrevorWP\Theme\Customizer\Control;

class Research extends Abstract_Single_Page {
	const PANEL_ID = self::ID_PREFIX . 'research';

	/* Sections */
	const SECTION_HEADER = self::PANEL_ID . '_' . self::NAME_SECTION_HEADER;
	const SECTION_LATEST = self::PANEL_ID . '_latest';
	const SECTION_INFO_BOXES = self::PANEL_ID . '_info_boxes';
	const SECTION_STAFF = self::PANEL_ID . '_staff';
	const SECTION_CTA_BOX = self::PANEL_ID . '_cta_box';
	const SECTION_RECENT_NEWS = self::PANEL_ID . '_recent_news';

	/* Settings */
	/* * Staff */
	const SETTING_STAFF_LIST = self::SECTION_STAFF . '_list';

	/** @inheritdoc */
	protected static $_section_components = [
		self::SECTION_HEADER      => [
			Component\Header::class,
			[
				'options'  => [ 'type' => Component\Header::TYPE_SPLIT_IMG ],
				'defaults' => [
					Component\Header::SETTING_TITLE => 'Data makes a difference.',
					Component\Header::SETTING_DESC  => 'We believe that research has the opportunity to save lives and reveal problems that need solving.',
				]
			]
		],
		self::SECTION_LATEST      => [
			Component\Section::class,
			[
				'defaults' => [
					Component\Section::SETTING_TITLE => 'Latest Research',
					Component\Section::SETTING_DESC  => 'We are committed to producing innovative research that brings knowledge and clinical implications to the field of suicidology. To accomplish this we partner with external research organizations as well as monitor, analyze, and evaluate existing data collected from Trevor-served youth.',
				]
			]
		],
		self::SECTION_INFO_BOXES  => [
			Component\Info_Boxes::class,
			[
				'options'  => [
					'box_type'        => Helper\Info_Boxes::BOX_TYPE_TEXT,
					'break_behaviour' => Helper\Info_Boxes::BREAK_BEHAVIOUR_GRID_1_2_3,
				],
				'defaults' => [
					Component\Info_Boxes::SETTING_TITLE   => 'National Survey on LGBTQ Youth Mental Health',
					Component\Info_Boxes::SETTING_DESC    => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Bibendum scelerisque nulla et amet aliquam venenatis velit diam.',
					Component\Info_Boxes::SETTING_DATA    => [
						[
							'txt'  => '250',
							'desc' => 'Proin at imperdiet id lacinia amet, fermentum etiam sit.'
						],
						[
							'txt'  => '14,000',
							'desc' => 'Orci blandit feugiat non euismod ut erat euismod.'
						],
						[
							'txt'  => '1st',
							'desc' => 'Consectetur ullamcorper tempus, tortor, nec. Hac.'
						],
					],
					Component\Info_Boxes::SETTING_BUTTONS => [
						[
							'label' => 'See Latest Report',
						]
					]
				],
			]
		],
		self::SECTION_STAFF       => [
			Component\Post_Carousel::class,
			[
				'options'  => [
					'card_renderer'          => [ Helper\Tile::class, 'staff' ],
					'class'                  => 'post-carousel--staff',
				],
				'defaults' => [
					Component\Post_Carousel::SETTING_TITLE => 'Research Staff',
					Component\Post_Carousel::SETTING_DESC  => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Bibendum scelerisque lorem ipsum.',
				]
			]
		],
		self::SECTION_CTA_BOX     => [
			Component\Info_Card::class,
			[
				'defaults' => [
					Component\Info_Card::SETTING_TITLE   => 'Looking to partner with us?',
					Component\Info_Card::SETTING_DESC    => 'If your project focuses on suicide and LGBTQ youth under 25, please complete  our collaboration & data-sharing application, or schedule a time to speak with our Research & Data Manager.',
					Component\Info_Card::SETTING_BUTTONS => [
						[ 'label' => 'Email Us', 'href' => '#' ],
						[ 'label' => 'Complete Application', 'href' => '#' ],
					],
				]
			]
		],
		self::SECTION_RECENT_NEWS => [
			Component\Post_Carousel::class,
			[
				'defaults' => [
					Component\Post_Carousel::SETTING_TITLE => 'Recent News',
				]
			]
		],
	];

	/** @inheritDoc */
	protected function _register_panels(): void {
		parent::_register_panels();

		$this->_manager->add_panel( static::get_panel_id(), [ 'title' => 'Research' ] );
	}

	/** @inheritDoc */
	protected function _register_sections(): void {
		parent::_register_sections();

		$this->get_component( static::SECTION_LATEST )->register_section( [ 'title' => 'Latest Briefs' ] );
		$this->get_component( static::SECTION_INFO_BOXES )->register_section( [ 'title' => 'Info Boxes' ] );
		$this->get_component( static::SECTION_STAFF )->register_section( [ 'title' => 'Staff' ] );
		$this->get_component( static::SECTION_CTA_BOX )->register_section( [ 'title' => 'CTA Box' ] );
		$this->get_component( static::SECTION_RECENT_NEWS )->register_section( [ 'title' => 'News' ] );
	}

	/** @inheritdoc */
	protected function _register_controls(): void {
		parent::_register_controls();

		# Staff List
		$this->_manager->add_control( new Control\Post_Select( $this->_manager, self::SETTING_STAFF_LIST, [
			'setting'     => self::SETTING_STAFF_LIST,
			'section'     => self::SECTION_STAFF,
			'allow_order' => true,
			'label'       => 'Featured Staff',
			'post_type'   => CPT\Team::POST_TYPE,
		] ) );
	}
}
