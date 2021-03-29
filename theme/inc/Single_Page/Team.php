<?php namespace TrevorWP\Theme\Single_Page;

use TrevorWP\Theme\Customizer\Component;
use TrevorWP\Theme\Customizer\Control;

/**
 * Team Page Customizer
 */
class Team extends Abstract_Single_Page {
	const PANEL_ID = self::ID_PREFIX . 'team';

	/* Sections */
	const SECTION_HEADER = self::PANEL_ID . '_' . self::NAME_SECTION_HEADER;
	const SECTION_HISTORY = self::PANEL_ID . '_history';
	const SECTION_FOUNDERS = self::PANEL_ID . '_founders';
	const SECTION_BOARD = self::PANEL_ID . '_board';
	const SECTION_STAFF = self::PANEL_ID . '_staff';

	/* Settings */
	/* * General */
	const SETTING_GENERAL_PLACEHOLDER_IMG = self::PANEL_ID . '_' . self::NAME_SECTION_GENERAL . '_placeholder_img';
	/* * History */
	const SETTING_HISTORY_VIDEO = self::SECTION_HISTORY . '_video';
	/* * Founders */
	const SETTING_FOUNDERS_LIST = self::SECTION_FOUNDERS . '_list';
	/* * Board */
	const SETTING_BOARD_LIST = self::SECTION_BOARD . '_list';
	/* * Staff */
	const SETTING_STAFF_LIST = self::SECTION_STAFF . '_list';

	/** @inheritdoc */
	protected static $_section_components = [
		self::SECTION_HEADER   => [
			Component\Header::class,
			[
				'options'  => [ 'type' => Component\Header::TYPE_HORIZONTAL ],
				'defaults' => [
					Component\Header::SETTING_TITLE => 'Meet our team.',
					Component\Header::SETTING_DESC  => 'Get to know the faces and the stories behind The Trevor Project.',
				]
			]
		],
		self::SECTION_HISTORY  => [
			Component\Section::class,
			[
				'defaults' => [
					Component\Section::SETTING_TITLE => 'Our History',
					Component\Section::SETTING_DESC  => 'The Trevor Project began with an award-winning film that highlighted the urgent needs of a global health crisis.',
				]
			]
		],
		self::SECTION_FOUNDERS => [
			Component\Section::class,
			[
				'defaults' => [
					Component\Section::SETTING_TITLE => 'Our Founders',
				]
			]
		],
		self::SECTION_BOARD    => [
			Component\Section::class,
			[
				'defaults' => [
					Component\Section::SETTING_TITLE => 'Our Board',
				]
			]
		],
		self::SECTION_STAFF    => [
			Component\Section::class,
			[
				'defaults' => [
					Component\Section::SETTING_TITLE => 'Our Staff',
				]
			]
		],
	];

	/** @inheritDoc */
	protected function _register_panels(): void {
		parent::_register_panels();

		$this->_manager->add_panel( static::get_panel_id(), [ 'title' => 'Meet our team' ] );
	}

	/** @inheritDoc */
	protected function _register_sections(): void {
		parent::_register_sections();

		$this->get_component( static::SECTION_HISTORY )->register_section( [ 'title' => 'History' ] );
		$this->get_component( static::SECTION_FOUNDERS )->register_section( [ 'title' => 'Founders' ] );
		$this->get_component( static::SECTION_BOARD )->register_section( [ 'title' => 'Board' ] );
		$this->get_component( static::SECTION_STAFF )->register_section( [ 'title' => 'Staff' ] );
	}

	/** @inheritdoc */
	protected function _register_controls(): void {
		parent::_register_controls();
		$manager = $this->get_manager();

		# General
		$manager->add_control( new \WP_Customize_Media_Control( $this->_manager, self::SETTING_GENERAL_PLACEHOLDER_IMG, [
			'setting'   => self::SETTING_GENERAL_PLACEHOLDER_IMG,
			'section'   => static::get_section_id( static::NAME_SECTION_GENERAL ),
			'label'     => 'Placeholder Image',
			'mime_type' => 'image',
		] ) );

		# History
		$manager->add_control( new \WP_Customize_Media_Control( $this->_manager, self::SETTING_HISTORY_VIDEO, [
			'setting'   => self::SETTING_HISTORY_VIDEO,
			'section'   => self::SECTION_HISTORY,
			'label'     => 'Video',
			'mime_type' => 'video',
		] ) );

		# Founders
		$manager->add_control( new Control\Post_Select( $this->_manager, self::SETTING_FOUNDERS_LIST, [
			'setting'     => self::SETTING_FOUNDERS_LIST,
			'section'     => self::SECTION_FOUNDERS,
			'allow_order' => false,
			'label'       => 'List',
			'post_type'   => \TrevorWP\CPT\Team::POST_TYPE,
			'filter_args' => [ 'disable_solr' => 1 ],
		] ) );

		# Board
		$manager->add_control( new Control\Post_Select( $this->_manager, self::SETTING_BOARD_LIST, [
			'setting'     => self::SETTING_BOARD_LIST,
			'section'     => self::SECTION_BOARD,
			'allow_order' => false,
			'label'       => 'List',
			'post_type'   => \TrevorWP\CPT\Team::POST_TYPE,
			'filter_args' => [ 'disable_solr' => 1 ],
		] ) );

		# Staff
		$manager->add_control( new Control\Post_Select( $this->_manager, self::SETTING_STAFF_LIST, [
			'setting'     => self::SETTING_STAFF_LIST,
			'section'     => self::SECTION_STAFF,
			'allow_order' => false,
			'label'       => 'List',
			'post_type'   => \TrevorWP\CPT\Team::POST_TYPE,
			'filter_args' => [ 'disable_solr' => 1 ],
		] ) );
	}
}
