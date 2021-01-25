<?php namespace TrevorWP\Theme\Customizer;


class ECT extends Abstract_Customizer {
	/* Panels */
	const PANEL_ID = self::ID_PREFIX . 'ect';

	/* Sections */
	/* * Home */
	const SECTION_HOME_PREFIX = self::PANEL_ID . '_home';
	const SECTION_HOME_GENERAL = self::SECTION_HOME_PREFIX . '_general';
	const SECTION_HOME_MAP = self::SECTION_HOME_PREFIX . '_map';
	const SECTION_HOME_FORM = self::SECTION_HOME_PREFIX . '_form';

	const SETTING_HOME_PREFIX = self::SECTION_HOME_PREFIX . '_';
	const SETTING_HOME_HERO_IMG = self::SETTING_HOME_PREFIX . 'hero_img';
	const SETTING_HOME_HERO_TITLE_TOP = self::SETTING_HOME_PREFIX . 'hero_title_top';
	const SETTING_HOME_HERO_TITLE = self::SETTING_HOME_PREFIX . 'hero_title';
	const SETTING_HOME_HERO_DESC = self::SETTING_HOME_PREFIX . 'hero_desc';
	const SETTING_HOME_HERO_CTA = self::SETTING_HOME_PREFIX . 'hero_cta';

	const SETTING_HOME_1_TITLE = self::SETTING_HOME_PREFIX . '1_title';
	const SETTING_HOME_1_DESC = self::SETTING_HOME_PREFIX . '1_desc';
	const SETTING_HOME_2_TITLE = self::SETTING_HOME_PREFIX . '2_title';
	const SETTING_HOME_2_DESC = self::SETTING_HOME_PREFIX . '2_desc';
	const SETTING_HOME_2_IMG = self::SETTING_HOME_PREFIX . '2_img';
	const SETTING_HOME_CAROUSEL_TITLE = self::SETTING_HOME_PREFIX . 'carousel_title';
	const SETTING_HOME_CAROUSEL_DESC = self::SETTING_HOME_PREFIX . 'carousel_desc';
	const SETTING_HOME_CAROUSEL_TERMS = self::SETTING_HOME_PREFIX . 'carousel_term';

	/* Defaults */
	const DEFAULTS = [
		self::SETTING_HOME_HERO_TITLE_TOP => 'ADVOCATE FOR CHANGE',
		self::SETTING_HOME_HERO_TITLE     => 'Ending Conversion Therapy',
		self::SETTING_HOME_HERO_DESC      => 'We are the largest campaign in the world endeavoring to protect LGBTQ young people from conversion therapy in every state of the nation and countries around the world. Every day, we engage in legislation, litigation and public education to end these dangerous and discredited practices.',
		self::SETTING_HOME_HERO_CTA       => 'Take Action Now',
		self::SETTING_HOME_1_TITLE        => 'How we’re making an impact',
		self::SETTING_HOME_1_DESC         => 'We do the work through legislation, litigation and public education and partner with mental health associations, youth organizations, LGBTQ groups, student clubs, faith communities and educational institutions in every state to promote the submission and passage of meaningful legislation in their legislature. We then work with lawmakers in generating policy, providing survivor testimony and demonstrating public support.',
		self::SETTING_HOME_2_TITLE        => 'Protecting With Pride',
		self::SETTING_HOME_2_DESC         => 'Launched in 2020, the campaign will help educate key policymakers and local residents by holding a virtual series of educational town halls that highlight the dangers and abuses associated with conversion therapy.',
		self::SETTING_HOME_CAROUSEL_TITLE => 'Conversion therapy is an abusive practice.',
		self::SETTING_HOME_CAROUSEL_DESC  => 'It’s a practice that aims to change a person’s sexual orientation or gender identity.',
		self::SETTING_HOME_CAROUSEL_TERMS => 'Conversion Therapy',
	];

	/** @inheritDoc */
	protected function _register_panels(): void {
		$this->_manager->add_panel( self::PANEL_ID, [ 'title' => 'Ending Conversion Therapy' ] );
	}

	/** @inheritDoc */
	protected function _register_sections(): void {
		# Home
		## General
		$this->_manager->add_section( self::SECTION_HOME_GENERAL, [
			'panel' => self::PANEL_ID,
			'title' => '[Home] General',
		] );

		// TODO: Add the Map Section

	}

	/** @inheritDoc */
	protected function _register_controls(): void {
		# Home
		## Hero
		$this->_manager->add_control( new \WP_Customize_Media_Control( $this->_manager, self::SETTING_HOME_HERO_IMG, [
			'setting'   => self::SETTING_HOME_HERO_IMG,
			'section'   => self::SECTION_HOME_GENERAL,
			'label'     => '[Hero] Image',
			'mime_type' => 'image',
		] ) );

		$this->_manager->add_control( self::SETTING_HOME_HERO_TITLE_TOP, [
			'setting' => self::SETTING_HOME_HERO_TITLE_TOP,
			'section' => self::SECTION_HOME_GENERAL,
			'label'   => '[Hero] Title Top',
			'type'    => 'text',
		] );

		$this->_manager->add_control( self::SETTING_HOME_HERO_TITLE, [
			'setting' => self::SETTING_HOME_HERO_TITLE,
			'section' => self::SECTION_HOME_GENERAL,
			'label'   => '[Hero] Title',
			'type'    => 'text',
		] );

		$this->_manager->add_control( self::SETTING_HOME_HERO_DESC, [
			'setting' => self::SETTING_HOME_HERO_DESC,
			'section' => self::SECTION_HOME_GENERAL,
			'label'   => '[Hero] Description',
			'type'    => 'textarea',
		] );

		$this->_manager->add_control( self::SETTING_HOME_HERO_CTA, [
			'setting' => self::SETTING_HOME_HERO_CTA,
			'section' => self::SECTION_HOME_GENERAL,
			'label'   => '[Hero] CTA',
			'type'    => 'text',
		] );

		## 1
		$this->_manager->add_control( self::SETTING_HOME_1_TITLE, [
			'setting' => self::SETTING_HOME_1_TITLE,
			'section' => self::SECTION_HOME_GENERAL,
			'label'   => '[1] Title',
			'type'    => 'text',
		] );

		$this->_manager->add_control( self::SETTING_HOME_1_DESC, [
			'setting' => self::SETTING_HOME_1_DESC,
			'section' => self::SECTION_HOME_GENERAL,
			'label'   => '[1] Description',
			'type'    => 'textarea',
		] );

		## 2
		$this->_manager->add_control( self::SETTING_HOME_2_TITLE, [
			'setting' => self::SETTING_HOME_2_TITLE,
			'section' => self::SECTION_HOME_GENERAL,
			'label'   => '[2] Title',
			'type'    => 'text',
		] );

		$this->_manager->add_control( self::SETTING_HOME_2_DESC, [
			'setting' => self::SETTING_HOME_2_DESC,
			'section' => self::SECTION_HOME_GENERAL,
			'label'   => '[2] Description',
			'type'    => 'textarea',
		] );
		$this->_manager->add_control( new \WP_Customize_Media_Control( $this->_manager, self::SETTING_HOME_2_IMG, [
			'setting'   => self::SETTING_HOME_2_IMG,
			'section'   => self::SECTION_HOME_GENERAL,
			'label'     => '[2] Image',
			'mime_type' => 'image',
		] ) );

		## Carousel
		$this->_manager->add_control( self::SETTING_HOME_CAROUSEL_TITLE, [
			'setting' => self::SETTING_HOME_CAROUSEL_TITLE,
			'section' => self::SECTION_HOME_GENERAL,
			'label'   => '[Carousel] Title',
			'type'    => 'text',
		] );
		$this->_manager->add_control( self::SETTING_HOME_CAROUSEL_DESC, [
			'setting' => self::SETTING_HOME_CAROUSEL_DESC,
			'section' => self::SECTION_HOME_GENERAL,
			'label'   => '[Carousel] Description',
			'type'    => 'textarea',
		] );
		$this->_manager->add_control( self::SETTING_HOME_CAROUSEL_TERMS, [
			'setting' => self::SETTING_HOME_CAROUSEL_TERMS,
			'section' => self::SECTION_HOME_GENERAL,
			'label'   => '[Carousel] Search Term',
			'type'    => 'text',
		] );
	}
}
