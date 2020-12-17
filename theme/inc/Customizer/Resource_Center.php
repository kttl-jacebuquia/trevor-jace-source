<?php namespace TrevorWP\Theme\Customizer;

use TrevorWP\CPT;

/**
 * Resources Center Settings
 */
class Resource_Center extends Abstract_Customizer {
	/* Panels */
	const PANEL_ID = self::ID_PREFIX . 'resources_center';

	/* Sections */
	/* * Home */
	const SECTION_HOME_PREFIX = self::PANEL_ID . '_home';
	const SECTION_HOME_GENERAL = self::SECTION_HOME_PREFIX . '_general';
	const SECTION_HOME_FEATURED_POSTS = self::SECTION_HOME_PREFIX . '_featured_posts';
	const SECTION_HOME_GLOSSARY = self::SECTION_HOME_PREFIX . '_glossary';
	const SECTION_HOME_GUIDES = self::SECTION_HOME_PREFIX . '_guides';
	const SECTION_HOME_FEATURED_CATS = self::SECTION_HOME_PREFIX . '_featured_cats';

	/* Settings */
	/* * Home */
	const SETTING_HOME_PREFIX = self::SECTION_HOME_PREFIX . '_';
	const SETTING_HOME_FEATURED = self::SETTING_HOME_PREFIX . 'featured';
	const SETTING_HOME_CATS = self::SETTING_HOME_PREFIX . 'cats';
	const SETTING_HOME_GUIDES = self::SETTING_HOME_PREFIX . 'guides';
	const SETTING_HOME_GLOSSARY = self::SETTING_HOME_PREFIX . 'glossary';
	const SETTING_HOME_CARD_NUM = self::SETTING_HOME_PREFIX . 'card_num';
	const PREFIX_SETTING_HOME_CAT_POSTS = self::SETTING_HOME_PREFIX . 'cat_posts_';

	/* All Default */
	const DEFAULTS = [
		self::SETTING_HOME_CARD_NUM => 10,
	];

	/** @inheritDoc */
	protected function _register_panels(): void {
		$this->_manager->add_panel( self::PANEL_ID, [ 'title' => 'Resource Center' ] );
	}

	/** @inheritDoc */
	protected function _register_sections(): void {
		# Home
		## General
		$this->_manager->add_section( self::SECTION_HOME_GENERAL, [
			'panel' => self::PANEL_ID,
			'title' => '[Home] General',
		] );

		## Featured Posts
		$this->_manager->add_section( self::SECTION_HOME_FEATURED_POSTS, [
			'panel' => self::PANEL_ID,
			'title' => '[Home] Featured Posts',
		] );

		## Featured Categories
		$this->_manager->add_section( self::SECTION_HOME_FEATURED_CATS, [
			'panel' => self::PANEL_ID,
			'title' => '[Home] Featured Categories',
		] );

		## Guides
		$this->_manager->add_section( self::SECTION_HOME_GUIDES, [
			'panel' => self::PANEL_ID,
			'title' => '[Home] Guides',
		] );

		## Glossary
		$this->_manager->add_section( self::SECTION_HOME_GLOSSARY, [
			'panel' => self::PANEL_ID,
			'title' => '[Home] Glossary',
		] );
	}

	/** @inheritDoc */
	protected function _register_controls(): void {
		# Home

		## General
		$this->_manager->add_control( self::SETTING_HOME_CARD_NUM, [
			'setting'     => self::SETTING_HOME_CARD_NUM,
			'section'     => self::SECTION_HOME_GENERAL,
			'label'       => 'Number of Cards',
			'type'        => 'number',
			'description' => 'Cards per carousel.'
		] );

		## Featured Posts
		$this->_manager->add_control( new Control\Post_Select( $this->_manager, self::SETTING_HOME_FEATURED, [
			'setting'     => self::SETTING_HOME_FEATURED,
			'section'     => self::SECTION_HOME_FEATURED_POSTS,
			'allow_order' => true,
			'label'       => 'Featured Posts',
			'post_type'   => CPT\RC\RC_Object::$PUBLIC_POST_TYPES,
		] ) );

		## Categories
		$this->_manager->add_control( new Control\Taxonomy_Select( $this->_manager, self::SETTING_HOME_CATS, [
			'setting'     => self::SETTING_HOME_CATS,
			'section'     => self::SECTION_HOME_FEATURED_CATS,
			'allow_order' => true,
			'label'       => 'Featured Categories',
			'taxonomy'    => CPT\RC\RC_Object::TAXONOMY_CATEGORY,
			'parent'      => 0
		] ) );

		foreach (
			get_terms( [
				'taxonomy'   => CPT\RC\RC_Object::TAXONOMY_CATEGORY,
				'hide_empty' => false,
				'parent'     => 0
			] ) as $cat
		) {
			// TODO: ORDER THEM
			$setting_id = self::PREFIX_SETTING_HOME_CAT_POSTS . $cat->term_id;
			$this->_manager->add_setting( $setting_id );
			$this->_manager->add_control( new Control\Post_Select( $this->_manager, $setting_id, [
				'setting'         => $setting_id,
				'section'         => self::SECTION_HOME_FEATURED_CATS,
				'allow_order'     => true,
				'label'           => "[{$cat->name}] Posts",
				'post_type'       => CPT\RC\RC_Object::$PUBLIC_POST_TYPES,
				'taxonomy'        => [ CPT\RC\RC_Object::TAXONOMY_CATEGORY => $cat->term_id ],
				'active_callback' => function ( Control\Post_Select $control ) use ( $cat ) {
					$list = array_map( 'absint', explode( ',', $this::get_val( self::SETTING_HOME_CATS ) ) );

					if ( empty( $list ) || ! is_array( $list ) ) {
						return false;
					}

					return in_array( $cat->term_id, $list );
				}
			] ) );
		}

		## Guides
		$this->_manager->add_control( new Control\Post_Select( $this->_manager, self::SETTING_HOME_GUIDES, [
			'setting'     => self::SETTING_HOME_GUIDES,
			'section'     => self::SECTION_HOME_GUIDES,
			'allow_order' => true,
			'label'       => 'Guides',
			'post_type'   => CPT\RC\Guide::POST_TYPE,
		] ) );

		## Glossary
		$this->_manager->add_control( new Control\Post_Select( $this->_manager, self::SETTING_HOME_GLOSSARY, [
			'setting'     => self::SETTING_HOME_GLOSSARY,
			'section'     => self::SECTION_HOME_GLOSSARY,
			'allow_order' => true,
			'label'       => 'Glossary Entries',
			'post_type'   => CPT\RC\Glossary::POST_TYPE,
		] ) );
	}
}
