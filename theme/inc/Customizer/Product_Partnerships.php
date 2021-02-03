<?php namespace TrevorWP\Theme\Customizer;


use TrevorWP\CPT\Donate\Prod_Partner;

class Product_Partnerships extends Abstract_Customizer {
	/* Panels */
	const PANEL_ID = self::ID_PREFIX . 'prod_partner';

	/* Sections */
	/* * Home */
	const SECTION_HOME_PREFIX = self::PANEL_ID . '_home';
	const SECTION_HOME_GENERAL = self::SECTION_HOME_PREFIX . '_general';

	/* Settings */
	/* * Home */
	const SETTING_HOME_PREFIX = self::SECTION_HOME_PREFIX . '_';
	const SETTING_HOME_HERO_TITLE_TOP = self::SETTING_HOME_PREFIX . 'hero_title_top';
	const SETTING_HOME_HERO_TITLE = self::SETTING_HOME_PREFIX . 'hero_title';
	const SETTING_HOME_HERO_DESC = self::SETTING_HOME_PREFIX . 'hero_desc';
	const SETTING_HOME_HERO_CTA = self::SETTING_HOME_PREFIX . 'hero_cta';
	const SETTING_HOME_STORIES_TITLE = self::SETTING_HOME_PREFIX . 'stories_title';
	const SETTING_HOME_STORIES = self::SETTING_HOME_PREFIX . 'stories';
	const SETTING_HOME_CURRENTS_TITLE = self::SETTING_HOME_PREFIX . 'currents_title';
	const SETTING_HOME_SHOP_TITLE = self::SETTING_HOME_PREFIX . 'shop_title';
	const SETTING_HOME_SHOP_DESC = self::SETTING_HOME_PREFIX . 'shop_desc';
	const SETTING_HOME_SHOP_CTA = self::SETTING_HOME_PREFIX . 'shop_cta';

	/* * * Circulation */
	const SETTING_HOME_CIRCULATION_TITLE = self::SETTING_HOME_PREFIX . 'circulation_title';

	const DEFAULTS = [
		self::SETTING_HOME_HERO_TITLE_TOP => 'PARTNER WITH US',
		self::SETTING_HOME_HERO_TITLE     => 'Show your Pride with a product.',
		self::SETTING_HOME_HERO_DESC      => 'We work with incredible brands to develop products that help celebrate LGBTQ pride and support our mission of ending suicide for LGBTQ young people all year long.',
		self::SETTING_HOME_HERO_CTA       => 'Become A Partner',
		self::SETTING_HOME_STORIES_TITLE  => 'Featured Stories',
		self::SETTING_HOME_CURRENTS_TITLE => 'Current Partners',
		self::SETTING_HOME_SHOP_TITLE     => 'Check out our latest products.',
		self::SETTING_HOME_SHOP_DESC      => 'Visit our product shop to see all of our current featured products we’ve made with our partners. Pick one up as a gift for yourself or someone else.',
		self::SETTING_HOME_SHOP_CTA       => 'Shop Now',
	];

	/** @inheritDoc */
	protected function _register_panels(): void {
		$this->_manager->add_panel( self::PANEL_ID, [ 'title' => 'Product Partnerships' ] );
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
		$this->_manager->add_control( self::SETTING_HOME_HERO_TITLE_TOP, [
			'setting' => self::SETTING_HOME_HERO_TITLE_TOP,
			'section' => self::SECTION_HOME_GENERAL,
			'label'   => 'Hero Title Top',
			'type'    => 'text',
		] );

		$this->_manager->add_control( self::SETTING_HOME_HERO_TITLE, [
			'setting' => self::SETTING_HOME_HERO_TITLE,
			'section' => self::SECTION_HOME_GENERAL,
			'label'   => 'Hero Title',
			'type'    => 'text',
		] );

		$this->_manager->add_control( self::SETTING_HOME_HERO_DESC, [
			'setting' => self::SETTING_HOME_HERO_DESC,
			'section' => self::SECTION_HOME_GENERAL,
			'label'   => 'Hero Title Description',
			'type'    => 'text',
		] );

		$this->_manager->add_control( self::SETTING_HOME_STORIES_TITLE, [
			'setting' => self::SETTING_HOME_STORIES_TITLE,
			'section' => self::SECTION_HOME_GENERAL,
			'label'   => 'Featured Title',
			'type'    => 'text',
		] );

		$this->_manager->add_control( new Control\Post_Select( $this->_manager, self::SETTING_HOME_STORIES, [
			'setting'     => self::SETTING_HOME_STORIES,
			'section'     => self::SECTION_HOME_GENERAL,
			'allow_order' => true,
			'label'       => 'Featured Stories',
			'post_type'   => Prod_Partner::POST_TYPE,
		] ) );

		$this->_manager->add_control( self::SETTING_HOME_CURRENTS_TITLE, [
			'setting' => self::SETTING_HOME_CURRENTS_TITLE,
			'section' => self::SECTION_HOME_GENERAL,
			'label'   => 'Partners Title',
			'type'    => 'text',
		] );

		$this->_manager->add_control( self::SETTING_HOME_SHOP_TITLE, [
			'setting' => self::SETTING_HOME_SHOP_TITLE,
			'section' => self::SECTION_HOME_GENERAL,
			'label'   => 'Shop Title',
			'type'    => 'text',
		] );

		$this->_manager->add_control( self::SETTING_HOME_SHOP_DESC, [
			'setting' => self::SETTING_HOME_SHOP_DESC,
			'section' => self::SECTION_HOME_GENERAL,
			'label'   => 'Shop Description',
			'type'    => 'textarea',
		] );

		$this->_manager->add_control( self::SETTING_HOME_SHOP_CTA, [
			'setting' => self::SETTING_HOME_SHOP_CTA,
			'section' => self::SECTION_HOME_GENERAL,
			'label'   => 'Shop CTA',
			'type'    => 'text',
		] );
	}
}
