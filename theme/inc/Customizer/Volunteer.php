<?php namespace TrevorWP\Theme\Customizer;


class Volunteer extends Abstract_Customizer {
	/* Panels */
	const PANEL_ID = self::ID_PREFIX . 'volunteer';

	/* Sections */
	/* * Home */
	const SECTION_HOME_PREFIX = self::PANEL_ID . '_home';
	const SECTION_HOME_GENERAL = self::SECTION_HOME_PREFIX . '_general';

	/* Settings */
	/* * Home */
	const SETTING_HOME_PREFIX = self::SECTION_HOME_PREFIX . '_';
	const SETTING_HOME_HERO_IMG = self::SETTING_HOME_PREFIX . 'hero_img';
	const SETTING_HOME_HERO_TITLE = self::SETTING_HOME_PREFIX . 'hero_title';
	const SETTING_HOME_HERO_CTA = self::SETTING_HOME_PREFIX . 'hero_cta';

	/* * * 1 */
	const SETTING_HOME_1_IMG = self::SETTING_HOME_PREFIX . '1_img';
	const SETTING_HOME_1_DESC = self::SETTING_HOME_PREFIX . '1_desc';
	/* * * 2 */
	const SETTING_HOME_2_IMG = self::SETTING_HOME_PREFIX . '2_img';
	const SETTING_HOME_2_DESC = self::SETTING_HOME_PREFIX . '2_desc';
	/* * * 3 */
	const SETTING_HOME_3_IMG = self::SETTING_HOME_PREFIX . '3_img';
	const SETTING_HOME_3_DESC = self::SETTING_HOME_PREFIX . '3_desc';

	/* * * Quotes */
	const SETTING_HOME_QUOTE_PREFIX = self::SETTING_HOME_PREFIX . 'quote_';
	const SETTING_HOME_QUOTE_COUNT = self::SETTING_HOME_QUOTE_PREFIX . 'count';
	const SETTING_HOME_QUOTE_IMG_PREFIX = self::SETTING_HOME_QUOTE_PREFIX . 'quote_img_';
	const SETTING_HOME_QUOTE_TXT_PREFIX = self::SETTING_HOME_QUOTE_PREFIX . 'quote_txt_';
	const SETTING_HOME_QUOTE_CITE_PREFIX = self::SETTING_HOME_QUOTE_PREFIX . 'quote_cite_';

	/* * * Counselor */
	const SETTING_HOME_COUNSELOR_PREFIX = self::SECTION_HOME_PREFIX . 'counselor_';
	const SETTING_HOME_COUNSELOR_TITLE = self::SETTING_HOME_COUNSELOR_PREFIX . '';
	const SETTING_HOME_COUNSELOR_DESC = self::SETTING_HOME_COUNSELOR_PREFIX . '';
	const SETTING_HOME_COUNSELOR_CTA = self::SETTING_HOME_COUNSELOR_PREFIX . '';
	/* * * * Counselor Specs */
	const SETTING_HOME_COUNSELOR_SPECS_TITLE = self::SETTING_HOME_COUNSELOR_PREFIX . '';
	const SETTING_HOME_COUNSELOR_SPECS_DESC = self::SETTING_HOME_COUNSELOR_PREFIX . '';
	/* * * * Counselor Apply */
	const SETTING_HOME_COUNSELOR_APPLY_TITLE = self::SETTING_HOME_COUNSELOR_PREFIX . '';
	const SETTING_HOME_COUNSELOR_APPLY_DESC = self::SETTING_HOME_COUNSELOR_PREFIX . '';

	/* * * Reasons */
	const SETTING_HOME_REASONS_TITLE = self::SETTING_HOME_PREFIX . '';
	const SETTING_HOME_REASONS_DESC = self::SETTING_HOME_PREFIX . '';
	const SETTING_HOME_REASONS_TXT_1 = self::SETTING_HOME_PREFIX . '';
	const SETTING_HOME_REASONS_TXT_2 = self::SETTING_HOME_PREFIX . '';
	const SETTING_HOME_REASONS_TXT_3 = self::SETTING_HOME_PREFIX . '';
	const SETTING_HOME_REASONS_TXT_4 = self::SETTING_HOME_PREFIX . '';

	/* * * Circulation */
	const SETTING_HOME_CIRCULATION_TITLE = self::SETTING_HOME_PREFIX . 'circulation_title';
}
