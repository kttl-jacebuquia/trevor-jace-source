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
	const SETTING_HOME_CAROUSEL_POSTS = self::SETTING_HOME_PREFIX . 'carousel_posts';
}
