<?php namespace TrevorWP\Theme\Customizer;


class PWU extends Abstract_Customizer {
	/* Panels */
	const PANEL_ID = self::ID_PREFIX . 'partner-with-us';

	/* Sections */
	/* * Home */
	const SECTION_HOME_PREFIX = self::PANEL_ID . '_home';
	const SECTION_HOME_GENERAL = self::SECTION_HOME_PREFIX . '_general';

	/* Settings */
	/* * Home */
	const SETTING_HOME_PREFIX = self::SECTION_HOME_PREFIX . '_';
	const SETTING_HOME_HERO_TITLE = self::SETTING_HOME_PREFIX . 'hero_title';
	const SETTING_HOME_HERO_DESC = self::SETTING_HOME_PREFIX . 'hero_desc';
	const SETTING_HOME_HERO_CTA = self::SETTING_HOME_PREFIX . 'hero_cta';
	const SETTING_HOME_HERO_CAROUSEL_DATA = self::SETTING_HOME_PREFIX . 'carousel_data';
	const SETTING_HOME_OUR_PHILOSOPHY_TITLE = self::SETTING_HOME_PREFIX . 'our_philosophy_title';
	const SETTING_HOME_OUR_PHILOSOPHY_DESC = self::SETTING_HOME_PREFIX . 'our_philosophy_desc';
	const SETTING_HOME_OUR_PARTNERSHIP_OFFERINGS_TITLE = self::SETTING_HOME_PREFIX . 'our_partnership_title';
	const SETTING_HOME_OUR_PARTNERSHIP_OFFERINGS_DESC = self::SETTING_HOME_PREFIX . 'our_partnership_desc';

	/* * * Circulation */
	const SETTING_HOME_CIRCULATION_TITLE = self::SETTING_HOME_PREFIX . 'circulation_title';

	const DEFAULTS = [
		self::SETTING_HOME_HERO_TITLE                      => 'Partner With Us',
		self::SETTING_HOME_HERO_DESC                       => 'We have a sophisticated partnership model that is unique, impactful, and customizable to meet our business and cause goals.',
		self::SETTING_HOME_HERO_CTA                        => 'Join Us',
		self::SETTING_HOME_OUR_PHILOSOPHY_TITLE            => 'Our Philosophy',
		self::SETTING_HOME_OUR_PHILOSOPHY_DESC             => 'Corporate partnerships empower us to serve every LGBTQ young person that needs our support.',
		self::SETTING_HOME_OUR_PARTNERSHIP_OFFERINGS_TITLE => 'Our Partnership Offerings',
		self::SETTING_HOME_OUR_PARTNERSHIP_OFFERINGS_DESC  => 'These are many ways we can work together to save young LGBTQ lives.',
	];

	/** @inheritDoc */
	protected function _register_panels(): void {
		$this->_manager->add_panel( self::PANEL_ID, [ 'title' => 'Partner With Us' ] );
	}

	/** @inheritDoc */
	protected function _register_sections(): void {
		# Home
		## General
		$this->_manager->add_section( self::SECTION_HOME_GENERAL, [
			'panel' => self::PANEL_ID,
			'title' => '[Home] General',
		] );
	}

	/** @inheritDoc */
	protected function _register_controls(): void {
		$this->_manager->add_control( self::SETTING_HOME_HERO_TITLE, [
			'setting' => self::SETTING_HOME_HERO_TITLE,
			'section' => self::SECTION_HOME_GENERAL,
			'label'   => 'Hero Title',
			'type'    => 'text',
		] );
		$this->_manager->add_control( self::SETTING_HOME_HERO_CTA, [
			'setting' => self::SETTING_HOME_HERO_CTA,
			'section' => self::SECTION_HOME_GENERAL,
			'label'   => 'Hero CTA',
			'type'    => 'text',
		] );
		$this->_manager->add_control( self::SETTING_HOME_HERO_DESC, [
			'setting' => self::SETTING_HOME_HERO_DESC,
			'section' => self::SECTION_HOME_GENERAL,
			'label'   => 'Hero Description',
			'type'    => 'text',
		] );
		$this->_manager->add_control( new Control\Custom_List( $this->_manager, self::SETTING_HOME_HERO_CAROUSEL_DATA, [
			'setting' => self::SETTING_HOME_HERO_CAROUSEL_DATA,
			'section' => self::SECTION_HOME_GENERAL,
			'label'   => 'Hero Carousel Data',
			'fields'  => Control\Custom_List::FIELDSET_CAROUSEL,
		] ) );

		// our philosophy section
		$this->_manager->add_control( self::SETTING_HOME_OUR_PHILOSOPHY_TITLE, [
			'setting' => self::SETTING_HOME_OUR_PHILOSOPHY_TITLE,
			'section' => self::SECTION_HOME_GENERAL,
			'label'   => 'Our Philosophy Title',
			'type'    => 'text',
		] );

		$this->_manager->add_control( self::SETTING_HOME_OUR_PHILOSOPHY_DESC, [
			'setting' => self::SETTING_HOME_OUR_PHILOSOPHY_DESC,
			'section' => self::SECTION_HOME_GENERAL,
			'label'   => 'Our Philosophy Description',
			'type'    => 'text',
		] );

		//our partnership offerings
		$this->_manager->add_control( self::SETTING_HOME_OUR_PARTNERSHIP_OFFERINGS_TITLE, [
			'setting' => self::SETTING_HOME_OUR_PARTNERSHIP_OFFERINGS_TITLE,
			'section' => self::SECTION_HOME_GENERAL,
			'label'   => 'Our Partnership Title',
			'type'    => 'text',
		] );

		$this->_manager->add_control( self::SETTING_HOME_OUR_PARTNERSHIP_OFFERINGS_DESC, [
			'setting' => self::SETTING_HOME_OUR_PARTNERSHIP_OFFERINGS_DESC,
			'section' => self::SECTION_HOME_GENERAL,
			'label'   => 'Our Partnership Description',
			'type'    => 'text',
		] );
	}

}
