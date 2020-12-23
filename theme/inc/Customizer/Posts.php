<?php namespace TrevorWP\Theme\Customizer;

use TrevorWP\Theme\Helper\Content_Length;

/**
 * Post Settings
 */
class Posts extends Abstract_Customizer {
	/* Panels */
	const PANEL_ID = self::ID_PREFIX . 'posts';

	/* Sections */
	/* * Header */
	const SECTION_HEADER_PREFIX = self::PANEL_ID . '_header';
	const SECTION_HEADER_CONTENT_LENGTHS = self::SECTION_HEADER_PREFIX . 'content_lengths';

	/* Settings */
	/* * Header */
	const SETTING_HEADER_PREFIX = self::SECTION_HEADER_PREFIX . '_';
	const PREFIX_SETTING_HEADER_CONTENT_LENGTH = self::SETTING_HEADER_PREFIX . 'content_length_';

	/* All Defaults */
	const DEFAULTS = [
		self::PREFIX_SETTING_HEADER_CONTENT_LENGTH . Content_Length::OPTION_MEDIUM => Content_Length::DEFAULT_LEN_VALUES[ Content_Length::OPTION_MEDIUM ],
		self::PREFIX_SETTING_HEADER_CONTENT_LENGTH . Content_Length::OPTION_LONG   => Content_Length::DEFAULT_LEN_VALUES[ Content_Length::OPTION_LONG ],
	];

	/** @inheritDoc */
	protected function _register_panels(): void {
		$this->_manager->add_panel( self::PANEL_ID, [ 'title' => 'Posts' ] );
	}

	/** @inheritDoc */
	protected function _register_sections(): void {
		# Header
		## Content Lengths
		$this->_manager->add_section( self::SECTION_HEADER_CONTENT_LENGTHS, [
			'panel' => self::PANEL_ID,
			'title' => 'Content Lengths',
		] );
	}

	/** @inheritDoc */
	protected function _register_controls(): void {
		# Header
		## Content Lengths
		foreach ( Content_Length::SETTINGS as $key => $options ) {
			if ( $key == Content_Length::OPTION_SHORT || $key == Content_Length::OPTION_AUTO ) {
				continue; // short is always 0
			}

			$setting_id = self::PREFIX_SETTING_HEADER_CONTENT_LENGTH . $key;
			$this->_manager->add_setting( $setting_id, [ 'default' => self::DEFAULTS[ $setting_id ] ] );

			$this->_manager->add_control( $setting_id, [
				'setting'     => $setting_id,
				'section'     => self::SECTION_HEADER_CONTENT_LENGTHS,
				'allow_order' => true,
				'label'       => $options['name'],
				'type'        => 'number',
				'description' => 'Minimum word count.'
			] );
		}
	}
}
