<?php namespace TrevorWP\Theme\Customizer;


class Trevorspace extends Abstract_Customizer {
	/* Panels */
	const PANEL_ID = self::ID_PREFIX . 'trevorspace';

	/* Sections */
	const SECTION_COUNTER = 'online_counter';
	const SECTION_HOME    = 'home';

	/* * Counter */
	const SETTING_COUNTER_PREFIX              = self::SECTION_COUNTER . '_';
	const SETTING_COUNTER_URL                 = self::SETTING_COUNTER_PREFIX . 'url';
	const SETTING_COUNTER_ONLINE_TXT          = self::SETTING_COUNTER_PREFIX . 'online_txt';
	const SETTING_COUNTER_ONLINE_THRESHOLD    = self::SETTING_COUNTER_PREFIX . 'online_threshold';
	const SETTING_COUNTER_UNDER_THRESHOLD_MSG = self::SETTING_COUNTER_PREFIX . 'under_threshold_msg';

	/* * Home */
	const SETTING_HOME_PREFIX    = self::SECTION_HOME . '_';
	const SETTING_HOME_TITLE     = self::SETTING_HOME_PREFIX . 'title';
	const SETTING_HOME_DESC      = self::SETTING_HOME_PREFIX . 'desc';
	const SETTING_HOME_JOIN_CTA  = self::SETTING_HOME_PREFIX . 'join_cta';
	const SETTING_HOME_JOIN_URL  = self::SETTING_HOME_PREFIX . 'join_url';
	const SETTING_HOME_LOGIN_CTA = self::SETTING_HOME_PREFIX . 'login_cta';
	const SETTING_HOME_LOGIN_URL = self::SETTING_HOME_PREFIX . 'login_url';
	const SETTING_HOME_DATA      = self::SETTING_HOME_PREFIX . 'data';

	/* * * Circulation */
	const SETTING_HOME_CIRCULATION_TITLE = self::SETTING_HOME_PREFIX . 'circulation_title';
	const SETTING_HOME_CIRCULATION_DESC  = self::SETTING_HOME_PREFIX . 'circulation_desc';

	/* All Defaults */
	const DEFAULTS = array(
		/* * Counter */
		self::SETTING_COUNTER_URL                 => 'https://www.trevorspace.org/active-count/',
		self::SETTING_COUNTER_ONLINE_TXT          => '%s members currently online',
		self::SETTING_COUNTER_ONLINE_THRESHOLD    => '30',
		self::SETTING_COUNTER_UNDER_THRESHOLD_MSG => 'Join to our 100,000 members',

		/* * Home */
		self::SETTING_HOME_TITLE                  => 'Find your <tilt>community</tilt> at TrevorSpace.',
		self::SETTING_HOME_DESC                   => 'Get advice and support within an international community for LGBTQ young people ages 13–24. Sign up and start a conversation now.',
		self::SETTING_HOME_JOIN_CTA               => 'Join Now',
		self::SETTING_HOME_JOIN_URL               => 'https://www.trevorspace.org/register/',
		self::SETTING_HOME_LOGIN_CTA              => 'Log In',
		self::SETTING_HOME_LOGIN_URL              => 'https://www.trevorspace.org/login/',
		self::SETTING_HOME_DATA                   => array(
			array(
				'title_top' => 'An Understanding Community',
				'title'     => 'Say what’s on your mind.',
				'desc'      => 'Coming out, family challenges, relationships, and more. Start a conversation about what’s happening in your life right now.',
				'img'       => 0,
			),
			array(
				'title_top' => 'Start a Conversation',
				'title'     => 'Talk to someone who understands.',
				'desc'      => 'Get advice and support from other members in similar situations. And make some new friends along the way!',
				'img'       => 0,
			),
			array(
				'title_top' => 'Secure Space',
				'title'     => 'Your safety is our top priority.',
				'desc'      => 'This is a members-only, moderated, and affirming space for LGBTQ young people.',
				'img'       => 0,
			),
		),
		/* * * Circulation */
		self::SETTING_HOME_CIRCULATION_TITLE      => 'Looking for another kind of support?',
		self::SETTING_HOME_CIRCULATION_DESC       => 'Explore answers and information across a variety of topics, or connect to one of our trained counselors to receive immediate support.',
	);

	/** @inheritDoc */
	protected function _register_panels(): void {
		$this->_manager->add_panel( self::PANEL_ID, array( 'title' => 'Trevorspace' ) );
	}

	/** @inheritDoc */
	protected function _register_sections(): void {
		$this->_manager->add_section(
			self::SECTION_COUNTER,
			array(
				'panel' => self::PANEL_ID,
				'title' => 'Online Count',
			)
		);

		$this->_manager->add_section(
			self::SECTION_HOME,
			array(
				'panel' => self::PANEL_ID,
				'title' => 'General',
			)
		);
	}

	/** @inheritDoc */
	protected function _register_controls(): void {
		/* * Counter */
		$this->_manager->add_control(
			self::SETTING_COUNTER_URL,
			array(
				'setting' => self::SETTING_COUNTER_URL,
				'section' => self::SECTION_COUNTER,
				'label'   => 'URL',
				'type'    => 'url',
			)
		);
		$this->_manager->add_control(
			self::SETTING_COUNTER_ONLINE_TXT,
			array(
				'setting' => self::SETTING_COUNTER_ONLINE_TXT,
				'section' => self::SECTION_COUNTER,
				'label'   => 'Online Text',
				'type'    => 'text',
			)
		);
		$this->_manager->add_control(
			self::SETTING_COUNTER_ONLINE_THRESHOLD,
			array(
				'setting' => self::SETTING_COUNTER_ONLINE_THRESHOLD,
				'section' => self::SECTION_COUNTER,
				'label'   => 'Threshold',
				'type'    => 'number',
			)
		);
		$this->_manager->add_control(
			self::SETTING_COUNTER_UNDER_THRESHOLD_MSG,
			array(
				'setting' => self::SETTING_COUNTER_UNDER_THRESHOLD_MSG,
				'section' => self::SECTION_COUNTER,
				'label'   => 'Under Threshold Message',
				'type'    => 'text',
			)
		);

		/* * Home */
		$this->_manager->add_control(
			self::SETTING_HOME_TITLE,
			array(
				'setting' => self::SETTING_HOME_TITLE,
				'section' => self::SECTION_HOME,
				'label'   => 'Title',
				'type'    => 'text',
			)
		);
		$this->_manager->add_control(
			self::SETTING_HOME_DESC,
			array(
				'setting' => self::SETTING_HOME_DESC,
				'section' => self::SECTION_HOME,
				'label'   => 'Description',
				'type'    => 'textarea',
			)
		);
		$this->_manager->add_control(
			self::SETTING_HOME_JOIN_CTA,
			array(
				'setting' => self::SETTING_HOME_JOIN_CTA,
				'section' => self::SECTION_HOME,
				'label'   => 'Join CTA',
				'type'    => 'text',
			)
		);
		$this->_manager->add_control(
			self::SETTING_HOME_JOIN_URL,
			array(
				'setting' => self::SETTING_HOME_JOIN_URL,
				'section' => self::SECTION_HOME,
				'label'   => 'Join Url',
				'type'    => 'url',
			)
		);
		$this->_manager->add_control(
			self::SETTING_HOME_LOGIN_CTA,
			array(
				'setting' => self::SETTING_HOME_LOGIN_CTA,
				'section' => self::SECTION_HOME,
				'label'   => 'Login CTA',
				'type'    => 'text',
			)
		);
		$this->_manager->add_control(
			self::SETTING_HOME_LOGIN_URL,
			array(
				'setting' => self::SETTING_HOME_LOGIN_URL,
				'section' => self::SECTION_HOME,
				'label'   => 'Login Url',
				'type'    => 'url',
			)
		);
		$this->_manager->add_control(
			new Control\Custom_List(
				$this->_manager,
				self::SETTING_HOME_DATA,
				array(
					'setting' => self::SETTING_HOME_DATA,
					'section' => self::SECTION_HOME,
					'label'   => 'Data',
					'fields'  => array(
						'title_top' => array(
							'type'  => Control\Custom_List::FIELD_TYPE_INPUT,
							'label' => 'Title Top',
						),
						'title'     => array(
							'type'  => Control\Custom_List::FIELD_TYPE_INPUT,
							'label' => 'Title',
						),
						'desc'      => array(
							'type'  => Control\Custom_List::FIELD_TYPE_TEXTAREA,
							'label' => 'Description',
						),
						'img'       => array(
							'type'      => Control\Custom_List::FIELD_TYPE_MEDIA,
							'label'     => 'Media',
							'mime_type' => 'image',
						),
					),
				)
			)
		);
	}
}
