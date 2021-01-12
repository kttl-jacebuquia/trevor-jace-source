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
	/* * Pagination */
	const SECTION_PAGINATION = self::PANEL_ID . '_page';
	/* * Get Help */
	const SECTION_GET_HELP = self::PANEL_ID . '_get_help';
	/* * TrevorSpace */
	const SECTION_TREVORSPACE = self::PANEL_ID . '_trevorspace';


	/* Settings */
	/* * Home */
	const SETTING_HOME_PREFIX = self::SECTION_HOME_PREFIX . '_';
	const SETTING_HOME_FEATURED = self::SETTING_HOME_PREFIX . 'featured';
	const SETTING_HOME_CATS = self::SETTING_HOME_PREFIX . 'cats';
	const SETTING_HOME_GUIDES = self::SETTING_HOME_PREFIX . 'guides';
	const SETTING_HOME_GLOSSARY = self::SETTING_HOME_PREFIX . 'glossary';
	const SETTING_HOME_GLOSSARY_BG_IMG = self::SETTING_HOME_PREFIX . 'glossary_bg_img';
	const SETTING_HOME_CARD_NUM = self::SETTING_HOME_PREFIX . 'card_num';
	const PREFIX_SETTING_HOME_CAT_POSTS = self::SETTING_HOME_PREFIX . 'cat_posts_';

	/* * Pagination */
	const SETTING_PAGINATION_PREFIX = self::SECTION_PAGINATION . '_';
	const SETTING_PAGINATION_TAX_ARCHIVE = self::SETTING_PAGINATION_PREFIX . 'tax';
	const SETTING_PAGINATION_SEARCH_RESULTS = self::SETTING_PAGINATION_PREFIX . 'search';

	/* * Get Help */
	const SETTING_GET_HELP_PREFIX = self::SECTION_GET_HELP . '_';
	const SETTING_GET_HELP_1_TITLE = self::SETTING_GET_HELP_PREFIX . '1_title';
	const SETTING_GET_HELP_1_DESC = self::SETTING_GET_HELP_PREFIX . '1_desc';
	const SETTING_GET_HELP_2_TITLE = self::SETTING_GET_HELP_PREFIX . '2_title';
	const SETTING_GET_HELP_2_DESC = self::SETTING_GET_HELP_PREFIX . '2_desc';
	/* * * Text */
	const SETTING_GET_HELP_TEXT_TITLE = self::SETTING_GET_HELP_PREFIX . 'text_title';
	const SETTING_GET_HELP_TEXT_DESC = self::SETTING_GET_HELP_PREFIX . 'text_desc';
	const SETTING_GET_HELP_TEXT_CTA = self::SETTING_GET_HELP_PREFIX . 'text_cta';
	const SETTING_GET_HELP_TEXT_IMG = self::SETTING_GET_HELP_PREFIX . 'text_img';
	const SETTING_GET_HELP_TEXT_BTN_CTA = self::SETTING_GET_HELP_PREFIX . 'text_btn_cta';
	/* * * Call */
	const SETTING_GET_HELP_CALL_TITLE = self::SETTING_GET_HELP_PREFIX . 'call_title';
	const SETTING_GET_HELP_CALL_DESC = self::SETTING_GET_HELP_PREFIX . 'call_desc';
	const SETTING_GET_HELP_CALL_CTA = self::SETTING_GET_HELP_PREFIX . 'call_cta';
	const SETTING_GET_HELP_CALL_IMG = self::SETTING_GET_HELP_PREFIX . 'call_img';
	const SETTING_GET_HELP_CALL_BTN_CTA = self::SETTING_GET_HELP_PREFIX . 'call_btn_cta';
	/* * * Chat */
	const SETTING_GET_HELP_CHAT_TITLE = self::SETTING_GET_HELP_PREFIX . 'chat_title';
	const SETTING_GET_HELP_CHAT_DESC = self::SETTING_GET_HELP_PREFIX . 'chat_desc';
	const SETTING_GET_HELP_CHAT_CTA = self::SETTING_GET_HELP_PREFIX . 'chat_cta';
	const SETTING_GET_HELP_CHAT_IMG = self::SETTING_GET_HELP_PREFIX . 'chat_img';
	const SETTING_GET_HELP_CHAT_BTN_CTA = self::SETTING_GET_HELP_PREFIX . 'chat_btn_cta';
	/* * * Notification */
	const SETTING_GET_HELP_NOTIFICATION_TXT = self::SETTING_GET_HELP_PREFIX . 'notification_txt';
	const SETTING_GET_HELP_NOTIFICATION_URL = self::SETTING_GET_HELP_PREFIX . 'notification_url';
	/* * * Exercise */
	const SETTING_GET_HELP_EXERCISE_TITLE = self::SETTING_GET_HELP_PREFIX . 'exercise_title';
	const SETTING_GET_HELP_EXERCISE_DESC = self::SETTING_GET_HELP_PREFIX . 'exercise_desc';
	const SETTING_GET_HELP_EXERCISE_CTA = self::SETTING_GET_HELP_PREFIX . 'exercise_cta';
	/* * * Circulation */
	const SETTING_GET_HELP_CIRCULATION_TITLE = self::SETTING_GET_HELP_PREFIX . 'circulation_title';
	const SETTING_GET_HELP_CIRCULATION_DESC = self::SETTING_GET_HELP_PREFIX . 'circulation_desc';

	/* * TrevorSpace */
	const SETTING_TREVORSPACE_PREFIX = self::SECTION_TREVORSPACE . '_';
	const SETTING_TREVORSPACE_TITLE = self::SETTING_TREVORSPACE_PREFIX . 'title';
	const SETTING_TREVORSPACE_DESC = self::SETTING_TREVORSPACE_PREFIX . 'desc';
	const SETTING_TREVORSPACE_JOIN_CTA = self::SETTING_TREVORSPACE_PREFIX . 'join_cta';
	const SETTING_TREVORSPACE_JOIN_URL = self::SETTING_TREVORSPACE_PREFIX . 'join_url';
	const SETTING_TREVORSPACE_LOGIN_CTA = self::SETTING_TREVORSPACE_PREFIX . 'login_cta';
	const SETTING_TREVORSPACE_LOGIN_URL = self::SETTING_TREVORSPACE_PREFIX . 'login_url';
	/* * * 1 */
	const SETTING_TREVORSPACE_1_TITLE_TOP = self::SETTING_TREVORSPACE_PREFIX . '1_title_top';
	const SETTING_TREVORSPACE_1_TITLE = self::SETTING_TREVORSPACE_PREFIX . '1_title';
	const SETTING_TREVORSPACE_1_DESC = self::SETTING_TREVORSPACE_PREFIX . '1_desc';
	const SETTING_TREVORSPACE_1_IMG = self::SETTING_TREVORSPACE_PREFIX . '1_img';
	/* * * 2 */
	const SETTING_TREVORSPACE_2_TITLE_TOP = self::SETTING_TREVORSPACE_PREFIX . '2_title_top';
	const SETTING_TREVORSPACE_2_TITLE = self::SETTING_TREVORSPACE_PREFIX . '2_title';
	const SETTING_TREVORSPACE_2_DESC = self::SETTING_TREVORSPACE_PREFIX . '2_desc';
	const SETTING_TREVORSPACE_2_IMG = self::SETTING_TREVORSPACE_PREFIX . '2_img';
	/* * * 3 */
	const SETTING_TREVORSPACE_3_TITLE_TOP = self::SETTING_TREVORSPACE_PREFIX . '3_title_top';
	const SETTING_TREVORSPACE_3_TITLE = self::SETTING_TREVORSPACE_PREFIX . '3_title';
	const SETTING_TREVORSPACE_3_DESC = self::SETTING_TREVORSPACE_PREFIX . '3_desc';
	const SETTING_TREVORSPACE_3_IMG = self::SETTING_TREVORSPACE_PREFIX . '3_img';
	/* * * Circulation */
	const SETTING_TREVORSPACE_CIRCULATION_TITLE = self::SETTING_TREVORSPACE_PREFIX . 'circulation_title';
	const SETTING_TREVORSPACE_CIRCULATION_DESC = self::SETTING_TREVORSPACE_PREFIX . 'circulation_desc';


	/* All Defaults */
	const DEFAULTS = [
		self::SETTING_HOME_CARD_NUM                 => 10,
		self::SETTING_PAGINATION_TAX_ARCHIVE        => 6,
		self::SETTING_PAGINATION_SEARCH_RESULTS     => 6,
		/* * Get Help */
		self::SETTING_GET_HELP_1_TITLE              => 'We’re here <tilt>for you.</tilt>',
		self::SETTING_GET_HELP_1_DESC               => 'If you are thinking about harming yourself — get immediate support. Connect to a Trevor counselor 24/7, 365 days a year, from anywhere in the U.S. It is 100% confidential, and 100% free.',
		self::SETTING_GET_HELP_2_TITLE              => 'Our trained counselors understand the challenges LGBTQ young people face.',
		self::SETTING_GET_HELP_2_DESC               => 'They will listen, reflect back, and will not judge you. All of your conversations are anonymous, and you can share as much or as little as you’d like.',
		/* * * Text */
		self::SETTING_GET_HELP_TEXT_TITLE           => 'Text us from anywhere, anytime.',
		self::SETTING_GET_HELP_TEXT_DESC            => 'Standard text messaging rates may apply.',
		self::SETTING_GET_HELP_TEXT_CTA             => 'Text ‘START’ to 678-678',
		self::SETTING_GET_HELP_TEXT_BTN_CTA         => 'Text Us',
		/* * * Call */
		self::SETTING_GET_HELP_CALL_TITLE           => 'Reach out to hear a live voice on the line.',
		self::SETTING_GET_HELP_CALL_DESC            => '',
		self::SETTING_GET_HELP_CALL_CTA             => 'Call us at 1-866-488-7336',
		self::SETTING_GET_HELP_CALL_BTN_CTA         => 'Call Us',
		/* * * Chat */
		self::SETTING_GET_HELP_CHAT_TITLE           => 'At your computer? Send us a message.',
		self::SETTING_GET_HELP_CHAT_DESC            => '',
		self::SETTING_GET_HELP_CHAT_CTA             => 'Start Chat',
		self::SETTING_GET_HELP_CHAT_BTN_CTA         => 'Chat With Us',
		/* * * Notification */
		self::SETTING_GET_HELP_NOTIFICATION_TXT     => 'In very specific instances of abuse or a clear concern of an in-progress or imminent suicide, Trevor counselors may need to contact a child welfare agency or emergency service.',
		self::SETTING_GET_HELP_NOTIFICATION_URL     => '',
		/* * * Exercise */
		self::SETTING_GET_HELP_EXERCISE_TITLE       => 'Not ready to talk?',
		self::SETTING_GET_HELP_EXERCISE_DESC        => 'It’s not easy to say how you’re feeling. Try a few of these calming exercises that will help you relax and focus.',
		self::SETTING_GET_HELP_EXERCISE_CTA         => 'Start ',
		/* * * Circulation */
		self::SETTING_GET_HELP_CIRCULATION_TITLE    => 'Looking for another kind of support?',
		self::SETTING_GET_HELP_CIRCULATION_DESC     => 'Explore answers and information across a variety of topics, or connect to one of our trained counselors to receive immediate support.',

		/* * TrevorSpace */
		self::SETTING_TREVORSPACE_TITLE             => 'Find your <tilt>community</tilt> at TrevorSpace.',
		self::SETTING_TREVORSPACE_DESC              => 'Get advice and support within an international community for LGBTQ young people ages 13–24. Sign up and start a conversation now.',
		self::SETTING_TREVORSPACE_JOIN_CTA          => 'Join Now',
		self::SETTING_TREVORSPACE_JOIN_URL          => 'https://www.trevorspace.org/register/',
		self::SETTING_TREVORSPACE_LOGIN_CTA         => 'Log In',
		self::SETTING_TREVORSPACE_LOGIN_URL         => 'https://www.trevorspace.org/login/',
		/* * * 1 */
		self::SETTING_TREVORSPACE_1_TITLE_TOP       => 'An Understanding Community',
		self::SETTING_TREVORSPACE_1_TITLE           => 'Say what’s on your mind.',
		self::SETTING_TREVORSPACE_1_DESC            => 'Coming out, family challenges, relationships, and more. Start a conversation about what’s happening in your life right now.',
		/* * * 2 */
		self::SETTING_TREVORSPACE_2_TITLE_TOP       => 'Start a Conversation',
		self::SETTING_TREVORSPACE_2_TITLE           => 'Talk to someone who understands.',
		self::SETTING_TREVORSPACE_2_DESC            => 'Get advice and support from other members in similar situations. And make some new friends along the way!',
		/* * * 3 */
		self::SETTING_TREVORSPACE_3_TITLE_TOP       => 'Secure Space',
		self::SETTING_TREVORSPACE_3_TITLE           => 'Your safety is our top priority.',
		self::SETTING_TREVORSPACE_3_DESC            => 'This is a members-only, moderated, and affirming space for LGBTQ young people.',
		/* * * Circulation */
		self::SETTING_TREVORSPACE_CIRCULATION_TITLE => 'Looking for another kind of support?',
		self::SETTING_TREVORSPACE_CIRCULATION_DESC  => 'Explore answers and information across a variety of topics, or connect to one of our trained counselors to receive immediate support.',
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

		# Pagination
		$this->_manager->add_section( self::SECTION_PAGINATION, [
			'panel' => self::PANEL_ID,
			'title' => 'Pagination',
		] );

		# Get Help
		$this->_manager->add_section( self::SECTION_GET_HELP, [
			'panel' => self::PANEL_ID,
			'title' => 'Get Help',
		] );

		# Trevorspace
		$this->_manager->add_section( self::SECTION_TREVORSPACE, [
			'panel' => self::PANEL_ID,
			'title' => 'Trevorspace',
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
			'description' => 'Suggested post count: 3'
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
			// TODO: Sort them by the order of categories
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

		$this->_manager->add_control( new \WP_Customize_Media_Control( $this->_manager, self::SETTING_HOME_GLOSSARY_BG_IMG, [
			'setting'   => self::SETTING_HOME_GLOSSARY_BG_IMG,
			'section'   => self::SETTING_HOME_GLOSSARY,
			'label'     => 'Background Image',
			'mime_type' => 'image',
		] ) );

		## Glossary
		$this->_manager->add_control( new Control\Post_Select( $this->_manager, self::SETTING_HOME_GLOSSARY, [
			'setting'     => self::SETTING_HOME_GLOSSARY,
			'section'     => self::SECTION_HOME_GLOSSARY,
			'allow_order' => true,
			'label'       => 'Glossary Entries',
			'post_type'   => CPT\RC\Glossary::POST_TYPE,
		] ) );

		# Pagination
		## Taxonomy Archive
		$this->_manager->add_control( self::SETTING_PAGINATION_TAX_ARCHIVE, [
			'setting'     => self::SETTING_PAGINATION_TAX_ARCHIVE,
			'section'     => self::SECTION_PAGINATION,
			'label'       => 'Taxonomy Pages',
			'type'        => 'number',
			'description' => 'Posts per page.'
		] );

		## Search Results
		$this->_manager->add_control( self::SETTING_PAGINATION_SEARCH_RESULTS, [
			'setting'     => self::SETTING_PAGINATION_SEARCH_RESULTS,
			'section'     => self::SECTION_PAGINATION,
			'label'       => 'Search Results',
			'type'        => 'number',
			'description' => 'Posts per page.'
		] );

		# Get Help
		$this->_manager->add_control( self::SETTING_GET_HELP_1_TITLE, [
			'setting' => self::SETTING_GET_HELP_1_TITLE,
			'section' => self::SECTION_GET_HELP,
			'label'   => '[1] Title',
			'type'    => 'text',
		] );
		$this->_manager->add_control( self::SETTING_GET_HELP_1_DESC, [
			'setting' => self::SETTING_GET_HELP_1_DESC,
			'section' => self::SECTION_GET_HELP,
			'label'   => '[1] Description',
			'type'    => 'textarea',
		] );
		$this->_manager->add_control( self::SETTING_GET_HELP_2_TITLE, [
			'setting' => self::SETTING_GET_HELP_2_TITLE,
			'section' => self::SECTION_GET_HELP,
			'label'   => '[2] Title',
			'type'    => 'text',
		] );
		$this->_manager->add_control( self::SETTING_GET_HELP_2_DESC, [
			'setting' => self::SETTING_GET_HELP_2_DESC,
			'section' => self::SECTION_GET_HELP,
			'label'   => '[2] Description',
			'type'    => 'textarea',
		] );

		## Text
		$this->_manager->add_control( self::SETTING_GET_HELP_TEXT_TITLE, [
			'setting' => self::SETTING_GET_HELP_TEXT_TITLE,
			'section' => self::SECTION_GET_HELP,
			'label'   => '[Text] Title',
			'type'    => 'text',
		] );
		$this->_manager->add_control( self::SETTING_GET_HELP_TEXT_DESC, [
			'setting' => self::SETTING_GET_HELP_TEXT_DESC,
			'section' => self::SECTION_GET_HELP,
			'label'   => '[Text] Description',
			'type'    => 'textarea',
		] );
		$this->_manager->add_control( self::SETTING_GET_HELP_TEXT_CTA, [
			'setting' => self::SETTING_GET_HELP_TEXT_CTA,
			'section' => self::SECTION_GET_HELP,
			'label'   => '[Text] CTA',
			'type'    => 'text',
		] );
		$this->_manager->add_control( new \WP_Customize_Media_Control( $this->_manager, self::SETTING_GET_HELP_TEXT_IMG, [
			'setting'   => self::SETTING_GET_HELP_TEXT_IMG,
			'section'   => self::SECTION_GET_HELP,
			'label'     => '[Text] Image',
			'mime_type' => 'image',
		] ) );
		$this->_manager->add_control( self::SETTING_GET_HELP_TEXT_BTN_CTA, [
			'setting' => self::SETTING_GET_HELP_TEXT_BTN_CTA,
			'section' => self::SECTION_GET_HELP,
			'label'   => '[Text] Button CTA',
			'type'    => 'text',
		] );

		## Call
		$this->_manager->add_control( self::SETTING_GET_HELP_CALL_TITLE, [
			'setting' => self::SETTING_GET_HELP_CALL_TITLE,
			'section' => self::SECTION_GET_HELP,
			'label'   => '[Call] Title',
			'type'    => 'text',
		] );
		$this->_manager->add_control( self::SETTING_GET_HELP_CALL_DESC, [
			'setting' => self::SETTING_GET_HELP_CALL_DESC,
			'section' => self::SECTION_GET_HELP,
			'label'   => '[Call] Description',
			'type'    => 'textarea',
		] );
		$this->_manager->add_control( self::SETTING_GET_HELP_CALL_CTA, [
			'setting' => self::SETTING_GET_HELP_CALL_CTA,
			'section' => self::SECTION_GET_HELP,
			'label'   => '[Call] CTA',
			'type'    => 'text',
		] );
		$this->_manager->add_control( new \WP_Customize_Media_Control( $this->_manager, self::SETTING_GET_HELP_CALL_IMG, [
			'setting'   => self::SETTING_GET_HELP_CALL_IMG,
			'section'   => self::SECTION_GET_HELP,
			'label'     => '[Call] Image',
			'mime_type' => 'image',
		] ) );
		$this->_manager->add_control( self::SETTING_GET_HELP_CALL_BTN_CTA, [
			'setting' => self::SETTING_GET_HELP_CALL_BTN_CTA,
			'section' => self::SECTION_GET_HELP,
			'label'   => '[Call] Button CTA',
			'type'    => 'text',
		] );

		## Chat
		$this->_manager->add_control( self::SETTING_GET_HELP_CHAT_TITLE, [
			'setting' => self::SETTING_GET_HELP_CHAT_TITLE,
			'section' => self::SECTION_GET_HELP,
			'label'   => '[Chat] Title',
			'type'    => 'text',
		] );
		$this->_manager->add_control( self::SETTING_GET_HELP_CHAT_DESC, [
			'setting' => self::SETTING_GET_HELP_CHAT_DESC,
			'section' => self::SECTION_GET_HELP,
			'label'   => '[Chat] Description',
			'type'    => 'textarea',
		] );
		$this->_manager->add_control( self::SETTING_GET_HELP_CHAT_CTA, [
			'setting' => self::SETTING_GET_HELP_CHAT_CTA,
			'section' => self::SECTION_GET_HELP,
			'label'   => '[Chat] CTA',
			'type'    => 'text',
		] );
		$this->_manager->add_control( new \WP_Customize_Media_Control( $this->_manager, self::SETTING_GET_HELP_CHAT_IMG, [
			'setting'   => self::SETTING_GET_HELP_CHAT_IMG,
			'section'   => self::SECTION_GET_HELP,
			'label'     => '[Chat] Image',
			'mime_type' => 'image',
		] ) );
		$this->_manager->add_control( self::SETTING_GET_HELP_CHAT_BTN_CTA, [
			'setting' => self::SETTING_GET_HELP_CHAT_BTN_CTA,
			'section' => self::SECTION_GET_HELP,
			'label'   => '[Chat] Button CTA',
			'type'    => 'text',
		] );

		## Notification
		$this->_manager->add_control( self::SETTING_GET_HELP_NOTIFICATION_TXT, [
			'setting' => self::SETTING_GET_HELP_NOTIFICATION_TXT,
			'section' => self::SECTION_GET_HELP,
			'label'   => '[Notification] Text',
			'type'    => 'textarea',
		] );
		$this->_manager->add_control( self::SETTING_GET_HELP_NOTIFICATION_URL, [
			'setting' => self::SETTING_GET_HELP_NOTIFICATION_URL,
			'section' => self::SECTION_GET_HELP,
			'label'   => '[Notification] Url',
			'type'    => 'url',
		] );

		## Exercise
		$this->_manager->add_control( self::SETTING_GET_HELP_EXERCISE_TITLE, [
			'setting' => self::SETTING_GET_HELP_EXERCISE_TITLE,
			'section' => self::SECTION_GET_HELP,
			'label'   => '[Exercise] Title',
			'type'    => 'text',
		] );
		$this->_manager->add_control( self::SETTING_GET_HELP_EXERCISE_DESC, [
			'setting' => self::SETTING_GET_HELP_EXERCISE_DESC,
			'section' => self::SECTION_GET_HELP,
			'label'   => '[Exercise] Description',
			'type'    => 'textarea',
		] );
		$this->_manager->add_control( self::SETTING_GET_HELP_EXERCISE_CTA, [
			'setting' => self::SETTING_GET_HELP_EXERCISE_CTA,
			'section' => self::SECTION_GET_HELP,
			'label'   => '[Exercise] CTA',
			'type'    => 'text',
		] );

		## Circulation
		$this->_manager->add_control( self::SETTING_GET_HELP_CIRCULATION_TITLE, [
			'setting' => self::SETTING_GET_HELP_CIRCULATION_TITLE,
			'section' => self::SECTION_GET_HELP,
			'label'   => '[Circulation] Title',
			'type'    => 'text',
		] );
		$this->_manager->add_control( self::SETTING_GET_HELP_CIRCULATION_DESC, [
			'setting' => self::SETTING_GET_HELP_CIRCULATION_DESC,
			'section' => self::SECTION_GET_HELP,
			'label'   => '[Circulation] Description',
			'type'    => 'textarea',
		] );


		# TrevorSpace
		$this->_manager->add_control( self::SETTING_TREVORSPACE_TITLE, [
			'setting' => self::SETTING_TREVORSPACE_TITLE,
			'section' => self::SECTION_TREVORSPACE,
			'label'   => 'Title',
			'type'    => 'text',
		] );
		$this->_manager->add_control( self::SETTING_TREVORSPACE_DESC, [
			'setting' => self::SETTING_TREVORSPACE_DESC,
			'section' => self::SECTION_TREVORSPACE,
			'label'   => 'Description',
			'type'    => 'textarea',
		] );
		$this->_manager->add_control( self::SETTING_TREVORSPACE_JOIN_CTA, [
			'setting' => self::SETTING_TREVORSPACE_JOIN_CTA,
			'section' => self::SECTION_TREVORSPACE,
			'label'   => 'Join CTA',
			'type'    => 'text',
		] );
		$this->_manager->add_control( self::SETTING_TREVORSPACE_JOIN_URL, [
			'setting' => self::SETTING_TREVORSPACE_JOIN_URL,
			'section' => self::SECTION_TREVORSPACE,
			'label'   => 'Join Url',
			'type'    => 'url',
		] );
		$this->_manager->add_control( self::SETTING_TREVORSPACE_LOGIN_CTA, [
			'setting' => self::SETTING_TREVORSPACE_LOGIN_CTA,
			'section' => self::SECTION_TREVORSPACE,
			'label'   => 'Login CTA',
			'type'    => 'text',
		] );
		$this->_manager->add_control( self::SETTING_TREVORSPACE_LOGIN_URL, [
			'setting' => self::SETTING_TREVORSPACE_LOGIN_URL,
			'section' => self::SECTION_TREVORSPACE,
			'label'   => 'Login Url',
			'type'    => 'url',
		] );

		## 1
		$this->_manager->add_control( self::SETTING_TREVORSPACE_1_TITLE_TOP, [
			'setting' => self::SETTING_TREVORSPACE_1_TITLE_TOP,
			'section' => self::SECTION_TREVORSPACE,
			'label'   => '[1] Title Top',
			'type'    => 'text',
		] );
		$this->_manager->add_control( self::SETTING_TREVORSPACE_1_TITLE, [
			'setting' => self::SETTING_TREVORSPACE_1_TITLE,
			'section' => self::SECTION_TREVORSPACE,
			'label'   => '[1] Title',
			'type'    => 'text',
		] );
		$this->_manager->add_control( self::SETTING_TREVORSPACE_1_DESC, [
			'setting' => self::SETTING_TREVORSPACE_1_DESC,
			'section' => self::SECTION_TREVORSPACE,
			'label'   => '[1] Description',
			'type'    => 'textarea',
		] );
		$this->_manager->add_control( new \WP_Customize_Media_Control( $this->_manager, self::SETTING_TREVORSPACE_1_IMG, [
			'setting'   => self::SETTING_TREVORSPACE_1_IMG,
			'section'   => self::SECTION_TREVORSPACE,
			'label'     => '[1] Image',
			'mime_type' => 'image',
		] ) );

		## 2
		$this->_manager->add_control( self::SETTING_TREVORSPACE_2_TITLE_TOP, [
			'setting' => self::SETTING_TREVORSPACE_2_TITLE_TOP,
			'section' => self::SECTION_TREVORSPACE,
			'label'   => '[2] Title Top',
			'type'    => 'text',
		] );
		$this->_manager->add_control( self::SETTING_TREVORSPACE_2_TITLE, [
			'setting' => self::SETTING_TREVORSPACE_2_TITLE,
			'section' => self::SECTION_TREVORSPACE,
			'label'   => '[2] Title',
			'type'    => 'text',
		] );
		$this->_manager->add_control( self::SETTING_TREVORSPACE_2_DESC, [
			'setting' => self::SETTING_TREVORSPACE_2_DESC,
			'section' => self::SECTION_TREVORSPACE,
			'label'   => '[2] Description',
			'type'    => 'textarea',
		] );
		$this->_manager->add_control( new \WP_Customize_Media_Control( $this->_manager, self::SETTING_TREVORSPACE_2_IMG, [
			'setting'   => self::SETTING_TREVORSPACE_2_IMG,
			'section'   => self::SECTION_TREVORSPACE,
			'label'     => '[2] Image',
			'mime_type' => 'image',
		] ) );

		## 3
		$this->_manager->add_control( self::SETTING_TREVORSPACE_3_TITLE_TOP, [
			'setting' => self::SETTING_TREVORSPACE_3_TITLE_TOP,
			'section' => self::SECTION_TREVORSPACE,
			'label'   => '[3] Title Top',
			'type'    => 'text',
		] );
		$this->_manager->add_control( self::SETTING_TREVORSPACE_3_TITLE, [
			'setting' => self::SETTING_TREVORSPACE_3_TITLE,
			'section' => self::SECTION_TREVORSPACE,
			'label'   => '[3] Title',
			'type'    => 'text',
		] );
		$this->_manager->add_control( self::SETTING_TREVORSPACE_3_DESC, [
			'setting' => self::SETTING_TREVORSPACE_3_DESC,
			'section' => self::SECTION_TREVORSPACE,
			'label'   => '[3] Description',
			'type'    => 'textarea',
		] );
		$this->_manager->add_control( new \WP_Customize_Media_Control( $this->_manager, self::SETTING_TREVORSPACE_3_IMG, [
			'setting'   => self::SETTING_TREVORSPACE_3_IMG,
			'section'   => self::SECTION_TREVORSPACE,
			'label'     => '[3] Image',
			'mime_type' => 'image',
		] ) );

		## Circulation
		$this->_manager->add_control( self::SETTING_TREVORSPACE_CIRCULATION_TITLE, [
			'setting' => self::SETTING_TREVORSPACE_CIRCULATION_TITLE,
			'section' => self::SECTION_TREVORSPACE,
			'label'   => '[Circulation] Title',
			'type'    => 'text',
		] );
		$this->_manager->add_control( self::SETTING_TREVORSPACE_CIRCULATION_DESC, [
			'setting' => self::SETTING_TREVORSPACE_CIRCULATION_DESC,
			'section' => self::SECTION_TREVORSPACE,
			'label'   => '[Circulation] Description',
			'type'    => 'textarea',
		] );
	}
}
