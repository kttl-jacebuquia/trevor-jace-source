<?php 
/**
 * @file
 * Creates the customizer for the Social Media Accounts URLs
 */
namespace TrevorWP\Theme\Customizer;


/**
 * Child customizer class for the Social Media Accounts URLs
 */
class Social_Media_Accounts extends Abstract_Customizer
{
    /** 
     * Panels
    */
    const PANEL_ID = self::ID_PREFIX . 'social_media_accounts';

    /** 
     * Sections
    */
    const SECTION_HOME_PREFIX = self::PANEL_ID . '_home';
    const SECTION_HOME_GENERAL = self::SECTION_HOME_PREFIX . '_general';

    /** 
     * Settings
    */
    const SETTING_HOME_PREFIX = self::SECTION_HOME_PREFIX . '_';
    const SETTING_HOME_FACEBOOK_URL = self::SETTING_HOME_PREFIX . 'facebook_url';
    const SETTING_HOME_TWITTER_URL = self::SETTING_HOME_PREFIX . 'twitter_url';
    const SETTING_HOME_LINKEDIN_URL = self::SETTING_HOME_PREFIX . 'linkedin_url';
    const SETTING_HOME_TIKTOK_URL = self::SETTING_HOME_PREFIX . 'tiktok_url';
    const SETTING_HOME_YOUTUBE_URL = self::SETTING_HOME_PREFIX. 'youtube_url';
    const SETTING_HOME_INSTAGRAM_URL = self::SETTING_HOME_PREFIX . 'instagram_url';

    /** 
     * @inheritDoc
     */
    protected function _register_panels(): void
    {
        $this->_manager->add_panel(self::PANEL_ID, [ 'title' => 'Social Media Accounts' ]);
    }

    /** 
     * @inheritDoc
     */
    protected function _register_sections(): void
    {
        // Home
        // General
        $this->_manager->add_section(
            self::SECTION_HOME_GENERAL, [
            'panel' => self::PANEL_ID,
            'title' => '[Home] General',
             ] 
        );
    }

    /** 
     * @inheritDoc
     */
    protected function _register_controls(): void
    {
        $this->_manager->add_control(
            self::SETTING_HOME_FACEBOOK_URL, [
            'setting' => self::SETTING_HOME_FACEBOOK_URL,
            'section' => self::SECTION_HOME_GENERAL,
            'label'   => 'Facebook URL',
            'type'    => 'url',
            'input_attrs' => [
            'placeholder' => 'https://',
            ],
            ] 
        );
        $this->_manager->add_control(
            self::SETTING_HOME_TWITTER_URL, [
            'setting' => self::SETTING_HOME_TWITTER_URL,
            'section' => self::SECTION_HOME_GENERAL,
            'label'   => 'Twitter URL',
            'type'    => 'url',
            'input_attrs' => [
            'placeholder' => 'https://',
            ],
            ] 
        );
        $this->_manager->add_control(
            self::SETTING_HOME_INSTAGRAM_URL, [
            'setting' => self::SETTING_HOME_INSTAGRAM_URL,
            'section' => self::SECTION_HOME_GENERAL,
            'label'   => 'Instagram URL',
            'type'    => 'url',
            'input_attrs' => [
            'placeholder' => 'https://',
            ],
            ] 
        );
        $this->_manager->add_control(
            self::SETTING_HOME_TIKTOK_URL, [
            'setting' => self::SETTING_HOME_TIKTOK_URL,
            'section' => self::SECTION_HOME_GENERAL,
            'label'   => 'TikTok URL',
            'type'    => 'url',
            'input_attrs' => [
            'placeholder' => 'https://',
            ],
            ] 
        );
        $this->_manager->add_control(
            self::SETTING_HOME_YOUTUBE_URL, [
            'setting' => self::SETTING_HOME_YOUTUBE_URL,
            'section' => self::SECTION_HOME_GENERAL,
            'label'   => 'YouTube URL',
            'type'    => 'url',
            'input_attrs' => [
            'placeholder' => 'https://',
            ],
            ] 
        );
        $this->_manager->add_control(
            self::SETTING_HOME_LINKEDIN_URL, [
            'setting' => self::SETTING_HOME_LINKEDIN_URL,
            'section' => self::SECTION_HOME_GENERAL,
            'label'   => 'LinkedIn URL',
            'type'    => 'url',
            'input_attrs' => [
            'placeholder' => 'https://',
            ],
            ] 
        );
    }
}