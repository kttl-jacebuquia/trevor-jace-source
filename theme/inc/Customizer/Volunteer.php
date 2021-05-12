<?php namespace TrevorWP\Theme\Customizer;


class Volunteer extends Abstract_Customizer {
	/* Panels */
	const PANEL_ID = self::ID_PREFIX . 'volunteer';

	/* Sections */
	/* * Home */
	const SECTION_HOME_PREFIX  = self::PANEL_ID . '_home';
	const SECTION_HOME_GENERAL = self::SECTION_HOME_PREFIX . '_general';

	/* Settings */
	/* * Home */
	const SETTING_HOME_PREFIX     = self::SECTION_HOME_PREFIX . '_';
	const SETTING_HOME_HERO_IMG   = self::SETTING_HOME_PREFIX . 'hero_img';
	const SETTING_HOME_HERO_TITLE = self::SETTING_HOME_PREFIX . 'hero_title';
	const SETTING_HOME_HERO_CTA   = self::SETTING_HOME_PREFIX . 'hero_cta';

	/* * * Header */
	const SETTING_HOME_TITLE = self::SETTING_HOME_PREFIX . 'header_title';
	const SETTING_HOME_DESC  = self::SETTING_HOME_PREFIX . 'header_desc';

	/* * * 1 */
	const SETTING_HOME_1_IMG  = self::SETTING_HOME_PREFIX . '1_img';
	const SETTING_HOME_1_DESC = self::SETTING_HOME_PREFIX . '1_desc';
	/* * * 2 */
	const SETTING_HOME_2_IMG  = self::SETTING_HOME_PREFIX . '2_img';
	const SETTING_HOME_2_DESC = self::SETTING_HOME_PREFIX . '2_desc';
	/* * * 3 */
	const SETTING_HOME_3_IMG  = self::SETTING_HOME_PREFIX . '3_img';
	const SETTING_HOME_3_DESC = self::SETTING_HOME_PREFIX . '3_desc';

	/* * * Testimonials */
	const SETTING_HOME_TESTIMONIALS = self::SETTING_HOME_PREFIX . 'testimonials';

	/* * * Counselor */
	const SETTING_HOME_COUNSELOR_PREFIX = self::SECTION_HOME_PREFIX . 'counselor_';
	const SETTING_HOME_COUNSELOR_TITLE  = self::SETTING_HOME_COUNSELOR_PREFIX . 'title';
	const SETTING_HOME_COUNSELOR_DESC   = self::SETTING_HOME_COUNSELOR_PREFIX . 'desc';
	const SETTING_HOME_COUNSELOR_CTA    = self::SETTING_HOME_COUNSELOR_PREFIX . 'cta';
	/* * * * Counselor Specs */
	const SETTING_HOME_COUNSELOR_SPECS_TITLE = self::SETTING_HOME_COUNSELOR_PREFIX . 'specs_title';
	const SETTING_HOME_COUNSELOR_SPECS_DESC  = self::SETTING_HOME_COUNSELOR_PREFIX . 'specs_desc';
	/* * * * Counselor Apply */
	const SETTING_HOME_COUNSELOR_APPLY_TITLE = self::SETTING_HOME_COUNSELOR_PREFIX . 'apply_title';
	const SETTING_HOME_COUNSELOR_APPLY_DESC  = self::SETTING_HOME_COUNSELOR_PREFIX . 'apply_desc';

	/* * * Reasons */
	const SETTING_HOME_REASONS_PREFIX = self::SETTING_HOME_PREFIX . 'reasons_';
	const SETTING_HOME_REASONS_TITLE  = self::SETTING_HOME_REASONS_PREFIX . 'title';
	const SETTING_HOME_REASONS_DESC   = self::SETTING_HOME_REASONS_PREFIX . 'desc';
	const SETTING_HOME_REASONS_TXT_1  = self::SETTING_HOME_REASONS_PREFIX . 'txt_1';
	const SETTING_HOME_REASONS_TXT_2  = self::SETTING_HOME_REASONS_PREFIX . 'txt_2';
	const SETTING_HOME_REASONS_TXT_3  = self::SETTING_HOME_REASONS_PREFIX . 'txt_3';
	const SETTING_HOME_REASONS_TXT_4  = self::SETTING_HOME_REASONS_PREFIX . 'txt_4';

	/* * * Circulation */
	const SETTING_HOME_CIRCULATION_TITLE = self::SETTING_HOME_PREFIX . 'circulation_title';

	const DEFAULTS = array(
		self::SETTING_HOME_HERO_TITLE            => '<tilt>Hope</tilt> starts with a volunteer',
		self::SETTING_HOME_HERO_CTA              => 'Apply Today',
		self::SETTING_HOME_TITLE                 => 'Trevor counselors save lives everyday.',
		self::SETTING_HOME_DESC                  => 'As a volunteer you are on the front lines. Working one on one with LGBTQ young people to help them navigate tough times, and prevent suicide.',
		self::SETTING_HOME_1_DESC                => 'LGBTQ young people with one accepting adult in their life were 40% less likely to attempt suicide.',
		self::SETTING_HOME_2_DESC                => 'One volunteer can help support over 100 young people that wouldn’t be supported otherwise.',
		self::SETTING_HOME_3_DESC                => 'In 2019, volunteers helped us answer almost 100,000 calls, chats and texts.',
		self::SETTING_HOME_COUNSELOR_TITLE       => 'Becoming a crisis support counselor.',
		self::SETTING_HOME_COUNSELOR_DESC        => 'When you become a trained Trevor counselor, you will be supporting LGBTQ young people via text, chat, and phone.',
		self::SETTING_HOME_COUNSELOR_CTA         => 'Apply Today',
		self::SETTING_HOME_COUNSELOR_SPECS_TITLE => 'Who we’re looking for',
		self::SETTING_HOME_COUNSELOR_SPECS_DESC  => 'You don’t need a degree or a license to become a trained Trevor counselor, but the role does require passion and commitment.',
		self::SETTING_HOME_COUNSELOR_APPLY_TITLE => 'How to apply',
		self::SETTING_HOME_COUNSELOR_APPLY_DESC  => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sit massa elit risus venenatis adipiscing pretium, faucibus.',
		self::SETTING_HOME_REASONS_TITLE         => 'Reasons to volunteer',
		self::SETTING_HOME_REASONS_DESC          => 'We don’t have to tell you that becoming a Trevor counselor is a rewarding experience. But we will anyway.',
		self::SETTING_HOME_REASONS_TXT_1         => '400+ Community Network',
		self::SETTING_HOME_REASONS_TXT_2         => 'Gain soft skills',
		self::SETTING_HOME_REASONS_TXT_3         => 'World class training',
		self::SETTING_HOME_REASONS_TXT_4         => 'Perks and swag',
	);

	/** @inheritDoc */
	protected function _register_panels(): void {
		$this->_manager->add_panel( self::PANEL_ID, array( 'title' => 'Volunteer' ) );
	}

	/** @inheritDoc */
	protected function _register_sections(): void {
		# Home
		## General
		$this->_manager->add_section(
			self::SECTION_HOME_GENERAL,
			array(
				'panel' => self::PANEL_ID,
				'title' => '[Home] General',
			)
		);
	}

	/** @inheritDoc */
	protected function _register_controls(): void {
		$this->_manager->add_control(
			new \WP_Customize_Media_Control(
				$this->_manager,
				self::SETTING_HOME_HERO_IMG,
				array(
					'setting'   => self::SETTING_HOME_HERO_IMG,
					'section'   => self::SECTION_HOME_GENERAL,
					'label'     => 'Hero Image',
					'mime_type' => 'image',
				)
			)
		);
		$this->_manager->add_control(
			self::SETTING_HOME_HERO_TITLE,
			array(
				'setting' => self::SETTING_HOME_HERO_TITLE,
				'section' => self::SECTION_HOME_GENERAL,
				'label'   => 'Hero Title',
				'type'    => 'text',
			)
		);
		$this->_manager->add_control(
			self::SETTING_HOME_HERO_CTA,
			array(
				'setting' => self::SETTING_HOME_HERO_CTA,
				'section' => self::SECTION_HOME_GENERAL,
				'label'   => 'Hero CTA',
				'type'    => 'text',
			)
		);

		$this->_manager->add_control(
			self::SETTING_HOME_TITLE,
			array(
				'setting' => self::SETTING_HOME_TITLE,
				'section' => self::SECTION_HOME_GENERAL,
				'label'   => 'Title',
				'type'    => 'text',
			)
		);
		$this->_manager->add_control(
			self::SETTING_HOME_DESC,
			array(
				'setting' => self::SETTING_HOME_DESC,
				'section' => self::SECTION_HOME_GENERAL,
				'label'   => 'Description',
				'type'    => 'textarea',
			)
		);

		## 1
		$this->_manager->add_control(
			new \WP_Customize_Media_Control(
				$this->_manager,
				self::SETTING_HOME_1_IMG,
				array(
					'setting'   => self::SETTING_HOME_1_IMG,
					'section'   => self::SECTION_HOME_GENERAL,
					'label'     => '[1] Image',
					'mime_type' => 'image',
				)
			)
		);
		$this->_manager->add_control(
			self::SETTING_HOME_1_DESC,
			array(
				'setting' => self::SETTING_HOME_1_DESC,
				'section' => self::SECTION_HOME_GENERAL,
				'label'   => '[1] Description',
				'type'    => 'textarea',
			)
		);

		## 2
		$this->_manager->add_control(
			new \WP_Customize_Media_Control(
				$this->_manager,
				self::SETTING_HOME_2_IMG,
				array(
					'setting'   => self::SETTING_HOME_2_IMG,
					'section'   => self::SECTION_HOME_GENERAL,
					'label'     => '[2] Image',
					'mime_type' => 'image',
				)
			)
		);
		$this->_manager->add_control(
			self::SETTING_HOME_2_DESC,
			array(
				'setting' => self::SETTING_HOME_2_DESC,
				'section' => self::SECTION_HOME_GENERAL,
				'label'   => '[2] Description',
				'type'    => 'textarea',
			)
		);

		## 3
		$this->_manager->add_control(
			new \WP_Customize_Media_Control(
				$this->_manager,
				self::SETTING_HOME_3_IMG,
				array(
					'setting'   => self::SETTING_HOME_3_IMG,
					'section'   => self::SECTION_HOME_GENERAL,
					'label'     => '[3] Image',
					'mime_type' => 'image',
				)
			)
		);
		$this->_manager->add_control(
			self::SETTING_HOME_3_DESC,
			array(
				'setting' => self::SETTING_HOME_3_DESC,
				'section' => self::SECTION_HOME_GENERAL,
				'label'   => '[3] Description',
				'type'    => 'textarea',
			)
		);

		$this->_manager->add_control(
			new Control\Custom_List(
				$this->_manager,
				self::SETTING_HOME_TESTIMONIALS,
				array(
					'setting' => self::SETTING_HOME_TESTIMONIALS,
					'section' => self::SECTION_HOME_GENERAL,
					'label'   => 'Testimonials',
					'fields'  => Control\Custom_List::FIELDSET_QUOTE,
				)
			)
		);
	}

}
