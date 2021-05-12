<?php namespace TrevorWP\Theme\Customizer;

/**
 * Social Media Accounts Customizer
 */
class Social_Media_Accounts extends Abstract_Customizer {
	/**
	 * Sections
	 */
	const SECTION_ID = self::ID_PREFIX . 'social_urls';

	/**
	 * Settings
	 */
	const SETTING_PREFIX        = self::SECTION_ID . '_';
	const SETTING_FACEBOOK_URL  = self::SETTING_PREFIX . 'facebook';
	const SETTING_TWITTER_URL   = self::SETTING_PREFIX . 'twitter';
	const SETTING_LINKEDIN_URL  = self::SETTING_PREFIX . 'linkedin';
	const SETTING_TIKTOK_URL    = self::SETTING_PREFIX . 'tiktok';
	const SETTING_YOUTUBE_URL   = self::SETTING_PREFIX . 'youtube';
	const SETTING_INSTAGRAM_URL = self::SETTING_PREFIX . 'instagram';

	/** @inheritDoc */
	protected function _register_sections(): void {
		$this->_manager->add_section(
			self::SECTION_ID,
			array(
				'title' => 'Social Media Accounts',
			)
		);
	}

	/** @inheritDoc */
	protected function _register_controls(): void {
		$this->_manager->add_control(
			self::SETTING_FACEBOOK_URL,
			array(
				'setting'     => self::SETTING_FACEBOOK_URL,
				'section'     => self::SECTION_ID,
				'label'       => 'Facebook URL',
				'type'        => 'url',
				'input_attrs' => array(
					'placeholder' => 'https://',
				),
			)
		);
		$this->_manager->add_control(
			self::SETTING_TWITTER_URL,
			array(
				'setting'     => self::SETTING_TWITTER_URL,
				'section'     => self::SECTION_ID,
				'label'       => 'Twitter URL',
				'type'        => 'url',
				'input_attrs' => array(
					'placeholder' => 'https://',
				),
			)
		);
		$this->_manager->add_control(
			self::SETTING_INSTAGRAM_URL,
			array(
				'setting'     => self::SETTING_INSTAGRAM_URL,
				'section'     => self::SECTION_ID,
				'label'       => 'Instagram URL',
				'type'        => 'url',
				'input_attrs' => array(
					'placeholder' => 'https://',
				),
			)
		);
		$this->_manager->add_control(
			self::SETTING_TIKTOK_URL,
			array(
				'setting'     => self::SETTING_TIKTOK_URL,
				'section'     => self::SECTION_ID,
				'label'       => 'TikTok URL',
				'type'        => 'url',
				'input_attrs' => array(
					'placeholder' => 'https://',
				),
			)
		);
		$this->_manager->add_control(
			self::SETTING_YOUTUBE_URL,
			array(
				'setting'     => self::SETTING_YOUTUBE_URL,
				'section'     => self::SECTION_ID,
				'label'       => 'YouTube URL',
				'type'        => 'url',
				'input_attrs' => array(
					'placeholder' => 'https://',
				),
			)
		);
		$this->_manager->add_control(
			self::SETTING_LINKEDIN_URL,
			array(
				'setting'     => self::SETTING_LINKEDIN_URL,
				'section'     => self::SECTION_ID,
				'label'       => 'LinkedIn URL',
				'type'        => 'url',
				'input_attrs' => array(
					'placeholder' => 'https://',
				),
			)
		);
	}
}
