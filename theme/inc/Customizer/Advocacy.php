<?php namespace TrevorWP\Theme\Customizer;

use TrevorWP\CPT\Get_Involved\Bill;
use TrevorWP\CPT\Get_Involved\Corp_Partner;
use TrevorWP\CPT\Get_Involved\Letter;

/**
 * Trevor Advocacy Pages
 */
class Advocacy extends Abstract_Customizer {
	/* Panels */
	const PANEL_ID = self::ID_PREFIX . 'advocacy';

	/* Sections */
	/* * Home */
	const SECTION_HOME_PREFIX = self::PANEL_ID . '_home';
	const SECTION_HOME_GENERAL = self::SECTION_HOME_PREFIX . '_general';
	const SECTION_HOME_FEATURED_POSTS = self::SECTION_HOME_PREFIX . '_featured_posts';

	/* Settings */
	/* * Home */
	const SETTING_HOME_PREFIX = self::SECTION_HOME_PREFIX . '_';
	const SETTING_HOME_HERO_IMG = self::SETTING_HOME_PREFIX . 'hero_img';
	const SETTING_HOME_HERO_TITLE = self::SETTING_HOME_PREFIX . 'hero_title';
	const SETTING_HOME_HERO_DESC = self::SETTING_HOME_PREFIX . 'hero_desc';
	const SETTING_HOME_HERO_CTA = self::SETTING_HOME_PREFIX . 'hero_cta';
	const SETTING_HOME_CAROUSEL_TITLE = self::SETTING_HOME_PREFIX . 'carousel_title';
	const SETTING_HOME_CAROUSEL_DATA = self::SETTING_HOME_PREFIX . 'carousel_data';
	const SETTING_HOME_OUR_WORK_TITLE = self::SETTING_HOME_PREFIX . 'our_work_title';
	const SETTING_HOME_OUR_WORK_DESC = self::SETTING_HOME_PREFIX . 'our_work_desc';
	const SETTING_HOME_QUOTE_BG = self::SETTING_HOME_PREFIX . 'quote_bg';
	const SETTING_HOME_QUOTE_DATA = self::SETTING_HOME_PREFIX . 'quote_data'; //
	const SETTING_HOME_BILL_TITLE = self::SETTING_HOME_PREFIX . 'bill_title';
	const SETTING_HOME_BILL_DESC = self::SETTING_HOME_PREFIX . 'bill_desc';
	const SETTING_HOME_LETTER_TITLE = self::SETTING_HOME_PREFIX . 'letter_title';
	const SETTING_HOME_LETTER_DESC = self::SETTING_HOME_PREFIX . 'letter_desc';
	const SETTING_HOME_TAN_CTA = self::SETTING_HOME_PREFIX . 'tan_cta';
	const SETTING_HOME_PARTNER_ORG_TITLE = self::SETTING_HOME_PREFIX . 'partner_org_title';
	const SETTING_HOME_PARTNER_ORG_DESC = self::SETTING_HOME_PREFIX . 'partner_org_desc';
	const SETTING_HOME_PARTNER_ORG_LIST = self::SETTING_HOME_PREFIX . 'partner_org_list';
	const SETTING_HOME_FEATURED_BILLS = self::SETTING_HOME_PREFIX . 'featured_bills';
	const SETTING_HOME_FEATURED_LETTERS = self::SETTING_HOME_PREFIX . 'featured_letters';

	/* All Defaults */
	const DEFAULTS = [
		self::SETTING_HOME_HERO_TITLE        => 'Creating a more <tilt>inclusive world.</tilt>',
		self::SETTING_HOME_HERO_DESC         => 'Through legislation, litigation and public education, The Trevor Project is the leading advocate for LGBTQ youth in preventative efforts that address the discrimination, stigma and other factors that place LGBTQ youth at significantly higher risk of suicide.',
		self::SETTING_HOME_HERO_CTA          => 'Take Action Now',
		self::SETTING_HOME_CAROUSEL_TITLE    => 'Making the difference.',
		self::SETTING_HOME_OUR_WORK_TITLE    => 'Our Work',
		self::SETTING_HOME_BILL_TITLE        => 'Our Federal Priorities',
		self::SETTING_HOME_BILL_DESC         => 'At The Trevor Project, we have a few priorities that we want to let members of congress know that we care about.',
		self::SETTING_HOME_LETTER_TITLE      => 'Our State Priorities',
		self::SETTING_HOME_LETTER_DESC       => 'Letters that The Trevor Project sent to lawmakers in support of or opposition to bills federal and state. Lorem ipsum dolor sit.',
		self::SETTING_HOME_TAN_CTA           => 'Take Action Now',
		self::SETTING_HOME_PARTNER_ORG_TITLE => 'Our Partner Organizations',
		self::SETTING_HOME_PARTNER_ORG_DESC  => 'The Trevor Project works with the following organizations to further suicide prevention efforts among LGBTQ young people across the country.',
	];


	/** @inheritDoc */
	protected function _register_panels(): void {
		$this->_manager->add_panel( self::PANEL_ID, [ 'title' => 'Advocate For Change' ] );
	}

	/** @inheritDoc */
	protected function _register_sections(): void {
		# Home
		## General
		$this->_manager->add_section( self::SECTION_HOME_GENERAL, [
			'panel' => self::PANEL_ID,
			'title' => '[Home] General',
		] );

		## Featured Bills & Letters
		$this->_manager->add_section( self::SECTION_HOME_FEATURED_POSTS, [
			'panel' => self::PANEL_ID,
			'title' => '[Home] Featured Bills & Letters',
		] );
	}

	/** @inheritDoc */
	protected function _register_controls(): void {
		# Home
		$this->_manager->add_control( new \WP_Customize_Media_Control( $this->_manager, self::SETTING_HOME_HERO_IMG, [
			'setting'   => self::SETTING_HOME_HERO_IMG,
			'section'   => self::SECTION_HOME_GENERAL,
			'label'     => 'Hero Image',
			'mime_type' => 'image',
		] ) );

		$this->_manager->add_control( self::SETTING_HOME_HERO_TITLE, [
			'setting' => self::SETTING_HOME_HERO_TITLE,
			'section' => self::SECTION_HOME_GENERAL,
			'label'   => 'Hero Title',
			'type'    => 'text',
		] );

		$this->_manager->add_control( self::SETTING_HOME_HERO_DESC, [
			'setting' => self::SETTING_HOME_HERO_DESC,
			'section' => self::SECTION_HOME_GENERAL,
			'label'   => 'Hero Description',
			'type'    => 'text',
		] );

		$this->_manager->add_control( self::SETTING_HOME_HERO_CTA, [
			'setting' => self::SETTING_HOME_HERO_CTA,
			'section' => self::SECTION_HOME_GENERAL,
			'label'   => 'Hero CTA',
			'type'    => 'text',
		] );

		$this->_manager->add_control( self::SETTING_HOME_CAROUSEL_TITLE, [
			'setting' => self::SETTING_HOME_CAROUSEL_TITLE,
			'section' => self::SECTION_HOME_GENERAL,
			'label'   => 'Carousel Title',
			'type'    => 'text',
		] );

		// TODO: SETTING_HOME_CAROUSEL_DATA


		$this->_manager->add_control( self::SETTING_HOME_OUR_WORK_TITLE, [
			'setting' => self::SETTING_HOME_OUR_WORK_TITLE,
			'section' => self::SECTION_HOME_GENERAL,
			'label'   => 'Our Work Title',
			'type'    => 'text',
		] );

		$this->_manager->add_control( self::SETTING_HOME_OUR_WORK_DESC, [
			'setting' => self::SETTING_HOME_OUR_WORK_DESC,
			'section' => self::SECTION_HOME_GENERAL,
			'label'   => 'Our Work Description',
			'type'    => 'text',
		] );

		$this->_manager->add_control( new \WP_Customize_Media_Control( $this->_manager, self::SETTING_HOME_QUOTE_BG, [
			'setting'   => self::SETTING_HOME_QUOTE_BG,
			'section'   => self::SECTION_HOME_GENERAL,
			'label'     => 'Quote BG',
			'mime_type' => 'image',
		] ) );

		// TODO: SETTING_HOME_QUOTE_DATA

		$this->_manager->add_control( self::SETTING_HOME_BILL_TITLE, [
			'setting' => self::SETTING_HOME_BILL_TITLE,
			'section' => self::SECTION_HOME_GENERAL,
			'label'   => 'Letters Description',
			'type'    => 'text',
		] );

		$this->_manager->add_control( self::SETTING_HOME_BILL_DESC, [
			'setting' => self::SETTING_HOME_BILL_DESC,
			'section' => self::SECTION_HOME_GENERAL,
			'label'   => 'Letters Description',
			'type'    => 'text',
		] );

		$this->_manager->add_control( self::SETTING_HOME_LETTER_TITLE, [
			'setting' => self::SETTING_HOME_LETTER_TITLE,
			'section' => self::SECTION_HOME_GENERAL,
			'label'   => 'Letters Title',
			'type'    => 'text',
		] );

		$this->_manager->add_control( self::SETTING_HOME_LETTER_DESC, [
			'setting' => self::SETTING_HOME_LETTER_DESC,
			'section' => self::SECTION_HOME_GENERAL,
			'label'   => 'Letters Description',
			'type'    => 'text',
		] );

		$this->_manager->add_control( self::SETTING_HOME_TAN_CTA, [
			'setting' => self::SETTING_HOME_TAN_CTA,
			'section' => self::SECTION_HOME_GENERAL,
			'label'   => 'Take Action CTA',
			'type'    => 'text',
		] );

		$this->_manager->add_control( self::SETTING_HOME_PARTNER_ORG_TITLE, [
			'setting' => self::SETTING_HOME_PARTNER_ORG_TITLE,
			'section' => self::SECTION_HOME_GENERAL,
			'label'   => 'Partners Title',
			'type'    => 'text',
		] );

		$this->_manager->add_control( self::SETTING_HOME_PARTNER_ORG_TITLE, [
			'setting' => self::SETTING_HOME_PARTNER_ORG_TITLE,
			'section' => self::SECTION_HOME_GENERAL,
			'label'   => 'Partners Description',
			'type'    => 'text',
		] );

		$this->_manager->add_control( new Control\Post_Select( $this->_manager, self::SETTING_HOME_PARTNER_ORG_LIST, [
			'setting'     => self::SETTING_HOME_PARTNER_ORG_LIST,
			'section'     => self::SECTION_HOME_GENERAL,
			'allow_order' => true,
			'label'       => 'Featured Partners',
			'post_type'   => Corp_Partner::POST_TYPE,
		] ) );

		# Featured
		$this->_manager->add_control( new Control\Post_Select( $this->_manager, self::SETTING_HOME_FEATURED_BILLS, [
			'setting'     => self::SETTING_HOME_FEATURED_BILLS,
			'section'     => self::SECTION_HOME_FEATURED_POSTS,
			'allow_order' => true,
			'label'       => 'Featured Bills',
			'post_type'   => Bill::POST_TYPE,
		] ) );

		$this->_manager->add_control( new Control\Post_Select( $this->_manager, self::SETTING_HOME_FEATURED_LETTERS, [
			'setting'     => self::SETTING_HOME_FEATURED_LETTERS,
			'section'     => self::SECTION_HOME_FEATURED_POSTS,
			'allow_order' => true,
			'label'       => 'Featured Letters',
			'post_type'   => Letter::POST_TYPE,
		] ) );
	}
}
