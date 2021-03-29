<?php namespace TrevorWP\Theme\Single_Page;

use TrevorWP\CPT;
use TrevorWP\Theme\Customizer\Control;
use TrevorWP\Theme\Customizer\Component;

class Meet_Our_Partners extends Abstract_Single_Page {
	const PANEL_ID = self::ID_PREFIX . 'meet_our_partners';

	/* Sections */
	const SECTION_HEADER = self::PANEL_ID . '_' . self::NAME_SECTION_HEADER;
	const SECTION_CORP = self::PANEL_ID . '_corp';
	const SECTION_INST = self::PANEL_ID . '_inst';
	const SECTION_ORG = self::PANEL_ID . '_org';
	const SECTION_CTA_BOX = self::PANEL_ID . '_cta_box';

	/* Settings */
	const SETTING_ORG_LIST = self::SECTION_ORG . '_list';

	/** @inheritdoc */
	protected static $_section_components = [
		self::SECTION_HEADER => [
			Component\Header::class,
			[
				'options'  => [ 'type' => Component\Header::TYPE_TEXT ],
				'defaults' => [
					Component\Header::SETTING_TITLE   => 'Meet Our Partners',
					Component\Header::SETTING_DESC    => 'We have  has officially partnered with the following organizations to ensure suicide prevention efforts forLGBTQ youth are coordinated across the country.',
					Component\Header::SETTING_CTA_TXT => 'Get In Touch',
					Component\Header::SETTING_CTA_URL => '#',

				]
			]
		],

		self::SECTION_CORP    => [
			Component\Section::class,
			[
				'options'  => [ 'type' => Component\Section::TYPE_HORIZONTAL ],
				'defaults' => [
					Component\Section::SETTING_TITLE   => 'Corporate Partners',
					Component\Section::SETTING_DESC    => 'Our partnerships are customized to align with our corporate partners’ priorities.',
					Component\Section::SETTING_BUTTONS => [
						[
							'label' => 'See All Partners',
							'href'  => '#',
						],
					],
				]
			]
		],
		self::SECTION_INST    => [
			Component\Section::class,
			[
				'options'  => [ 'type' => Component\Section::TYPE_HORIZONTAL ],
				'defaults' => [
					Component\Section::SETTING_TITLE   => 'Institutional Funders',
					Component\Section::SETTING_DESC    => 'Our life-saving programs are generously supported by the following foundations.',
					Component\Section::SETTING_BUTTONS => [
						[
							'label' => 'See All Funders',
							'href'  => '#',
						],
					],
				]
			]
		],
		self::SECTION_ORG     => [
			Component\Section::class,
			[
				'options'  => [ 'type' => Component\Section::TYPE_VERTICAL ],
				'defaults' => [
					Component\Section::SETTING_TITLE => 'Our Partner Organizations',
				]
			]
		],
		self::SECTION_CTA_BOX => [
			Component\Info_Card::class,
			[
				'defaults' => [
					Component\Info_Card::SETTING_TITLE   => 'Join our family of product partners.',
					Component\Info_Card::SETTING_DESC    => 'Want to learn more about how to create a product with The Trevor Project? See all of our current partners and how you can be one of them.',
					Component\Info_Card::SETTING_BUTTONS => [
						[
							'label' => 'Learn More',
							'href'  => '#',
						],
					],
				]
			]
		],
	];

	/** @inheritDoc */
	protected function _register_panels(): void {
		parent::_register_panels();

		$this->_manager->add_panel( static::get_panel_id(), [ 'title' => 'Meet Our Partners' ] );
	}

	/** @inheritDoc */
	protected function _register_sections(): void {
		parent::_register_sections();

		$this->get_component( static::SECTION_CORP )->register_section( [ 'title' => 'Corporate Partners' ] );
		$this->get_component( static::SECTION_INST )->register_section( [ 'title' => 'Institutional Funders' ] );
		$this->get_component( static::SECTION_CTA_BOX )->register_section( [ 'title' => 'CTA Box' ] );
	}

	/** @inheritdoc */
	protected function _register_controls(): void {
		parent::_register_controls();

		# Partner Org
		$this->_manager->add_control( new Control\Post_Select( $this->_manager, self::SETTING_ORG_LIST, [
			'setting'     => self::SETTING_ORG_LIST,
			'section'     => self::SECTION_ORG,
			'allow_order' => true,
			'label'       => 'Featured Organizations',
			'post_type'   => CPT\Donate\Prod_Partner::POST_TYPE,
		] ) );
	}
}
