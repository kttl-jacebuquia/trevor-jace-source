<?php namespace TrevorWP\Theme\Single_Page;

use TrevorWP\Theme\Customizer\Component;

/**
 * Public Education Page
 */
class Public_Education extends Abstract_Single_Page {
	const PANEL_ID = self::ID_PREFIX . 'public_education';

	/* Sections */
	const SECTION_TESTIMONIALS = self::PANEL_ID . '_testimonials';
	const SECTION_CIRCULATION = self::PANEL_ID . '_circulation';

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

	/** @inheritDoc */
	protected function _register_panels(): void {
		parent::_register_panels();

		$this->_manager->add_panel( self::PANEL_ID, array( 'title' => 'Public Education' ) );
	}

	/** @inheritDoc */
	protected function _register_sections(): void {
		parent::_register_sections();

		# Testimonials
		$this->get_component( static::SECTION_TESTIMONIALS )->register_section();

		# Circulation
		$this->get_component( static::SECTION_CIRCULATION )->register_section();
	}

	/** @inheritdoc */
	protected function _register_controls(): void {
		parent::_register_controls();
	}
}
