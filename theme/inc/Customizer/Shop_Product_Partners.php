<?php namespace TrevorWP\Theme\Customizer;


use TrevorWP\CPT\Donate\Partner_Prod;
use TrevorWP\CPT\Donate\Prod_Partner;

class Shop_Product_Partners extends Abstract_Customizer {
	/* Panels */
	const PANEL_ID = self::ID_PREFIX . 'shop_prod_partner';

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

	const SETTING_HOME_STORIES_TITLE = self::SETTING_HOME_PREFIX . 'stories_title';
	const SETTING_HOME_STORIES = self::SETTING_HOME_PREFIX . 'stories';

	const SETTING_HOME_ITEMS_TITLE = self::SETTING_HOME_PREFIX . 'items_title';
	const SETTING_HOME_ITEMS = self::SETTING_HOME_PREFIX . 'items';

	const SETTING_HOME_LIST_TITLE = self::SETTING_HOME_PREFIX . 'list_title';

	const SETTING_HOME_BANNER_TITLE = self::SETTING_HOME_PREFIX . 'banner_title';
	const SETTING_HOME_BANNER_DESC = self::SETTING_HOME_PREFIX . 'banner_desc';
	const SETTING_HOME_BANNER_CTA = self::SETTING_HOME_PREFIX . 'banner_cta';

	/* * * Circulation */
	const SETTING_HOME_CIRCULATION_TITLE = self::SETTING_HOME_PREFIX . 'circulation_title';

	const DEFAULTS = [
		self::SETTING_HOME_HERO_TITLE_TOP => 'PARTNER WITH US',
		self::SETTING_HOME_HERO_TITLE     => 'Buy a product. Help the mission.',
		self::SETTING_HOME_HERO_DESC      => 'We work with incredible brands to develop products that help celebrate LGBTQ pride and support our mission of ending suicide for LGBTQ young people all year long.',
		self::SETTING_HOME_STORIES_TITLE  => 'Feature Collections',
		self::SETTING_HOME_ITEMS_TITLE    => 'Some of our favorite items',
		self::SETTING_HOME_LIST_TITLE     => 'Current Partners',
		self::SETTING_HOME_BANNER_TITLE   => 'Join our family of product partners.',
		self::SETTING_HOME_BANNER_DESC    => 'Want to learn more about how to create a product with The Trevor Project?  See all of our current partners and how you can be one of them.',
		self::SETTING_HOME_BANNER_CTA     => 'Learn More',
	];

	/** @inheritDoc */
	protected function _register_panels(): void {
		$this->_manager->add_panel( self::PANEL_ID, [ 'title' => 'Shop our Product Partners' ] );
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
		// Hero
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
			'label'   => 'Hero Title Desc',
			'type'    => 'textarea',
		] );

		// Featured
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
			'label'       => 'Featured',
			'post_type'   => Prod_Partner::POST_TYPE,
		] ) );

		// Items
		$this->_manager->add_control( self::SETTING_HOME_ITEMS_TITLE, [
			'setting' => self::SETTING_HOME_ITEMS_TITLE,
			'section' => self::SECTION_HOME_GENERAL,
			'label'   => 'Featured Items Title',
			'type'    => 'text',
		] );

		$this->_manager->add_control( new Control\Post_Select( $this->_manager, self::SETTING_HOME_ITEMS, [
			'setting'     => self::SETTING_HOME_ITEMS,
			'section'     => self::SECTION_HOME_GENERAL,
			'allow_order' => true,
			'label'       => 'Featured Items',
			'post_type'   => Partner_Prod::POST_TYPE,
		] ) );

		// List Title
		$this->_manager->add_control( self::SETTING_HOME_LIST_TITLE, [
			'setting' => self::SETTING_HOME_LIST_TITLE,
			'section' => self::SECTION_HOME_GENERAL,
			'label'   => 'List Title',
			'type'    => 'text',
		] );

		// Banner
		$this->_manager->add_control( self::SETTING_HOME_BANNER_TITLE, [
			'setting' => self::SETTING_HOME_BANNER_TITLE,
			'section' => self::SECTION_HOME_GENERAL,
			'label'   => 'Banner Title',
			'type'    => 'text',
		] );
		$this->_manager->add_control( self::SETTING_HOME_BANNER_DESC, [
			'setting' => self::SETTING_HOME_BANNER_DESC,
			'section' => self::SECTION_HOME_GENERAL,
			'label'   => 'Banner Desc',
			'type'    => 'textarea',
		] );
		$this->_manager->add_control( self::SETTING_HOME_BANNER_CTA, [
			'setting' => self::SETTING_HOME_BANNER_CTA,
			'section' => self::SECTION_HOME_GENERAL,
			'label'   => 'Banner CTA',
			'type'    => 'text',
		] );
	}
}
