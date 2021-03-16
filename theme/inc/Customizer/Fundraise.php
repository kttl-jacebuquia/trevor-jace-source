<?php namespace TrevorWP\Theme\Customizer;

use TrevorWP\Theme\Customizer\Component\Header;

/**
 * Fundraise  Settings
 */
class Fundraise extends Abstract_Customizer {
	/** Panels */
	const PANEL_ID = self::ID_PREFIX . 'fundraise';

	/** Sections */
	const SECTION_HEADER = self::PANEL_ID . '_header';
	const SECTION_FEATURED_TEXT = self::PANEL_ID . '_featured_text';
	const SECTION_THREE = self::PANEL_ID . '_three_column';
	const SECTION_ONE = self::PANEL_ID . '_one_column';
	const SECTION_LINK = self::PANEL_ID . '_links';
	const SECTION_PARTNER = self::PANEL_ID . '_partner';
	const SECTION_SUCCESS_STORIES = self::PANEL_ID . '_success_stories';
	const SECTION_TOP_LIST = self::PANEL_ID . '_top_list';
	const SECTION_QUESTIONS = self::PANEL_ID . '_question';
	const SECTION_OTHER = self::PANEL_ID . '_other';

	/** Settings */

	/** Featured Text */
	const PREFIX_SETTING_FEATURED_TEXT = self::SECTION_FEATURED_TEXT . '_';
	const SETTING_FEATURED_TEXT_TITLE = self::PREFIX_SETTING_FEATURED_TEXT . 'featured_title';
	const SETTING_FEATURED_TEXT_DESC = self::PREFIX_SETTING_FEATURED_TEXT . 'featured_desc';

	/** Three Column Text */
	const PREFIX_SETTING_THREE = self::SECTION_THREE . '_';
	const SETTING_THREE_TITLE = self::PREFIX_SETTING_THREE . 'three_title';
	const SETTING_THREE_DATA = self::PREFIX_SETTING_THREE . 'three_data';

	/** One Column Text */
	const PREFIX_SETTING_ONE = self::SECTION_ONE . '_';
	const SETTING_ONE_DATA = self::PREFIX_SETTING_ONE . 'one_data';

	/** Three Column Text */
	const PREFIX_SETTING_LINKS = self::SECTION_LINK . '_';
	const SETTING_LINK_TITLE = self::PREFIX_SETTING_LINKS . 'link_title';
	const SETTING_LINK_DESC = self::PREFIX_SETTING_LINKS . 'link_desc';
	const SETTING_LINK_DATA = self::PREFIX_SETTING_THREE . 'link_data';

	/** Become a Partner */
	const PREFIX_SETTING_PARTNER = self::SECTION_PARTNER . '_';
	const SETTING_PARTNER_TITLE = self::PREFIX_SETTING_PARTNER . 'link_title';
	const SETTING_PARTNER_DESC = self::PREFIX_SETTING_PARTNER . 'link_desc';
	const SETTING_PARTNER_CTA = self::PREFIX_SETTING_PARTNER . 'link_cta';
	const SETTING_PARTNER_CTA_LINK = self::PREFIX_SETTING_PARTNER . 'link_url';

	/** Success Stories */
	const PREFIX_SUCCESS_STORIES = self::SECTION_SUCCESS_STORIES . '_';
	const SETTING_SUCCESS_STORIES_TITLE = self::PREFIX_SUCCESS_STORIES . 'title';
	const SETTING_SUCCESS_STORIES_DESC = self::PREFIX_SUCCESS_STORIES . 'desc';

	/** Top List */
	const PREFIX_TOP_LIST = self::SECTION_TOP_LIST . '_';
	const SETTING_TOP_LIST_TITLE = self::PREFIX_TOP_LIST . 'title';
	const SETTING_TOP_LIST_DESC = self::PREFIX_TOP_LIST . 'desc';
	const SETTING_TOP_LIST_CAMPAIGN_ID = self::PREFIX_TOP_LIST . 'campaign_id';
	const SETTING_TOP_LIST_COUNT = self::PREFIX_TOP_LIST . 'count';
	const SETTING_TOP_LIST_PLACEHOLDER_LOGO = self::PREFIX_TOP_LIST . 'placeholder_logo';

	/** Questions */
	const PREFIX_QUESTIONS = self::SECTION_QUESTIONS . '_';
	const SETTING_QUESTIONS_TITLE = self::PREFIX_QUESTIONS . 'link_title';
	const SETTING_QUESTIONS_DESC = self::PREFIX_QUESTIONS . 'link_desc';
	const SETTING_QUESTIONS_CTA = self::PREFIX_QUESTIONS . 'link_cta';
	const SETTING_QUESTIONS_CTA_LINK = self::PREFIX_QUESTIONS . 'link_url';

	/** All Defaults */
	const DEFAULTS = array(
		self::SETTING_FEATURED_TEXT_TITLE => 'THE POWER OF FUNDRAISING',
		self::SETTING_FEATURED_TEXT_DESC  => 'The impact of fundraising has raised <b>$100,000</b> by individuals and <b>$200,000</b> by teams since <b>20XX.</b>',

		self::SETTING_THREE_TITLE => 'How your money is used',
		self::SETTING_THREE_DATA  => array(
			array( 'desc' => 'We plan to train a record number of crisis counselors. Every counselor can reach over 100 LGBTQ young people.' ),
			array( 'desc' => 'Help us to continue to provide all of our crisis services 24/7 and free of cost. Lorem ipsm dolor set. Vitae id accumsan.' ),
			array( 'desc' => 'Assist us in expanding our research and advocacy efforts. Et vehicula viverra facilisi nunc aliquet nunc eu quam. Etiam et.' ),
		),

		self::SETTING_ONE_DATA => array(
			array(
				'title'     => 'Fundraise as an individual.',
				'desc'      => 'Personalize the page with information about you and why you’re fundraising to save young LGBTQ lives.',
				'cta_label' => 'Create Your Own Page',
				'cta_link'  => '#own',
			),
			array(
				'title'     => 'Fundraise as a team.',
				'desc'      => 'Team fundraising pages allow members of the team to have individual fundraising pages that are a part of a bigger team goal.',
				'cta_label' => 'Start a Fundraiser',
				'cta_link'  => '#start',
			),
		),

		self::SETTING_LINK_TITLE => 'Get your fundraiser started now.',
		self::SETTING_LINK_DESC  => 'Here are some helpful tips and tools to help you get started on your fundraiser.',
		self::SETTING_LINK_DATA  => array(
			array(
				'label' => 'How To Talk Trevor',
				'link'  => '#',
			),
			array(
				'label' => 'Scripts',
				'link'  => '#',
			),
			array(
				'label' => 'Templates',
				'link'  => '#',
			),
			array(
				'label' => 'Tips & Checklists',
				'link'  => '#',
			),
			array(
				'label' => 'Helpful Links',
				'link'  => '#',
			),
		),

		self::SETTING_PARTNER_TITLE         => '<tilt>Become</tilt>&nbsp;&nbsp;a<br>corporate partner.',
		self::SETTING_PARTNER_DESC          => 'Our corporate partners help us create lasting change at scale and reach out to more and more LGBTQ young people every year.',
		self::SETTING_PARTNER_CTA           => 'Learn More',
		self::SETTING_PARTNER_CTA_LINK      => '#',

		/* Success Stories */
		self::SETTING_SUCCESS_STORIES_TITLE => 'Fundraiser Success Stories',
		self::SETTING_SUCCESS_STORIES_DESC  => 'These fundraisers made a huge impact on our ability to provide life-saving services.',

		/* Top List */
		self::SETTING_TOP_LIST_TITLE        => 'Featured Fundraisers',
		self::SETTING_TOP_LIST_DESC         => 'Check out some other notable fundraisers that have helped us save lives.',
		self::SETTING_TOP_LIST_CAMPAIGN_ID  => '24399',
		self::SETTING_TOP_LIST_COUNT        => 10,

		self::SETTING_QUESTIONS_TITLE    => 'Have questions?',
		self::SETTING_QUESTIONS_DESC     => 'Please reach out to us and a member of our Development team will get back to you.',
		self::SETTING_QUESTIONS_CTA      => 'Reach Out',
		self::SETTING_QUESTIONS_CTA_LINK => '#',
	);

	/** @inheritdoc */
	protected static $_section_components = [
		self::SECTION_HEADER => [
			Component\Header::class,
			[
				'options'  => [ 'type' => Header::TYPE_SPLIT_IMG ],
				'defaults' => [
					Component\Header::SETTING_TITLE   => 'Start a fundraiser. <tilt>Change the world.</tilt>',
					Component\Header::SETTING_DESC    => 'Join our amazing community of online fundraisers, and start saving young LGBTQ lives today.',
					Component\Header::SETTING_CTA_TXT => 'Become a Fundraiser',
				]
			]
		],
		self::SECTION_OTHER  => [
			Component\Circulation::class,
			[
				'defaults' => [
					Component\Circulation::SETTING_TITLE => 'Other ways to help',
				]
			]
		]
	];

	/** @inheritDoc */
	protected function _register_panels(): void {
		$this->_manager->add_panel( self::PANEL_ID, array( 'title' => 'Fundraise' ) );
	}

	/** @inheritDoc */
	protected function _register_sections(): void {
		// Header
		$this->get_component( static::SECTION_HEADER )->register_section();

		// Featured text
		$this->_manager->add_section(
			self::SECTION_FEATURED_TEXT,
			array(
				'panel' => self::PANEL_ID,
				'title' => 'Featured Text',
			)
		);

		// How your Money is Used
		$this->_manager->add_section(
			self::SECTION_THREE,
			array(
				'panel' => self::PANEL_ID,
				'title' => 'How your Money is Used',
			)
		);

		// Featured Content
		$this->_manager->add_section(
			self::SECTION_ONE,
			array(
				'panel' => self::PANEL_ID,
				'title' => 'Featured Content',
			)
		);

		// Featured Links
		$this->_manager->add_section(
			self::SECTION_LINK,
			array(
				'panel' => self::PANEL_ID,
				'title' => 'Featured Links',
			)
		);

		// Become a Partner
		$this->_manager->add_section(
			self::SECTION_PARTNER,
			array(
				'panel' => self::PANEL_ID,
				'title' => 'Become a Partner',
			)
		);

		// Fundraiser Success Stories
		$this->_manager->add_section(
			self::SECTION_SUCCESS_STORIES,
			[
				'panel' => self::PANEL_ID,
				'title' => 'Success Stories',
			]
		);

		// Featured Fundraisers
		$this->_manager->add_section(
			self::SECTION_TOP_LIST,
			[
				'panel' => self::PANEL_ID,
				'title' => 'Featured Fundraisers'
			]
		);

		// Questions
		$this->_manager->add_section(
			self::SECTION_QUESTIONS,
			array(
				'panel' => self::PANEL_ID,
				'title' => 'Questions',
			)
		);

		// Other Ways to Help
		$this->get_component( static::SECTION_OTHER )->register_section();
	}

	/** @inheritDoc */
	protected function _register_controls(): void {
		# Header
		$this->get_component( static::SECTION_HEADER )->register_controls();

		// FEATURED TEXT
		$this->_manager->add_control(
			self::SETTING_FEATURED_TEXT_TITLE,
			array(
				'setting' => self::SETTING_FEATURED_TEXT_TITLE,
				'section' => self::SECTION_FEATURED_TEXT,
				'label'   => 'Title',
				'type'    => 'text',
			)
		);

		$this->_manager->add_control(
			self::SETTING_FEATURED_TEXT_DESC,
			array(
				'setting' => self::SETTING_FEATURED_TEXT_DESC,
				'section' => self::SECTION_FEATURED_TEXT,
				'label'   => 'Description',
				'type'    => 'text',
			)
		);

		// How your money is used
		$this->_manager->add_control(
			self::SETTING_THREE_TITLE,
			array(
				'setting' => self::SETTING_THREE_TITLE,
				'section' => self::SECTION_THREE,
				'label'   => 'Title',
				'type'    => 'text',
			)
		);

		$this->_manager->add_control(
			new Control\Custom_List(
				$this->_manager,
				self::SETTING_THREE_DATA,
				array(
					'setting' => self::SETTING_THREE_DATA,
					'section' => self::SECTION_THREE,
					'label'   => 'Entries',
					'fields'  => array(
						'img'  => array(
							'type'      => Control\Custom_List::FIELD_TYPE_MEDIA,
							'label'     => 'Media',
							'mime_type' => 'image',
						),
						'desc' => array(
							'type'  => Control\Custom_List::FIELD_TYPE_TEXTAREA,
							'label' => 'Description',
						),
					),
				)
			)
		);

		// Fundraiser Calls
		$this->_manager->add_control(
			new Control\Custom_List(
				$this->_manager,
				self::SETTING_ONE_DATA,
				array(
					'setting' => self::SETTING_ONE_DATA,
					'section' => self::SECTION_ONE,
					'label'   => 'Entries',
					'fields'  => array(
						'img'       => array(
							'type'      => Control\Custom_List::FIELD_TYPE_MEDIA,
							'label'     => 'Media',
							'mime_type' => 'image',
						),
						'title'     => array(
							'type'  => Control\Custom_List::FIELD_TYPE_INPUT,
							'label' => 'Title',
						),
						'desc'      => array(
							'type'  => Control\Custom_List::FIELD_TYPE_TEXTAREA,
							'label' => 'Description',
						),
						'cta_label' => array(
							'type'  => Control\Custom_List::FIELD_TYPE_INPUT,
							'label' => 'CTA Label',
						),
						'cta_link'  => array(
							'type'  => Control\Custom_List::FIELD_TYPE_INPUT,
							'label' => 'Link',
						),
					),
				)
			)
		);

		// Fundraiser Links
		$this->_manager->add_control(
			self::SETTING_LINK_TITLE,
			array(
				'setting' => self::SETTING_LINK_TITLE,
				'section' => self::SECTION_LINK,
				'label'   => 'Title',
				'type'    => 'text',
			)
		);

		$this->_manager->add_control(
			self::SETTING_LINK_DESC,
			array(
				'setting' => self::SETTING_LINK_DESC,
				'section' => self::SECTION_LINK,
				'label'   => 'Description',
				'type'    => 'textarea',
			)
		);

		$this->_manager->add_control(
			new Control\Custom_List(
				$this->_manager,
				self::SETTING_LINK_DATA,
				array(
					'setting' => self::SETTING_LINK_DATA,
					'section' => self::SECTION_LINK,
					'label'   => 'Links',
					'fields'  => array(
						'label' => array(
							'type'  => Control\Custom_List::FIELD_TYPE_INPUT,
							'label' => 'Label',
						),
						'link'  => array(
							'type'  => Control\Custom_List::FIELD_TYPE_INPUT,
							'label' => 'URL',
						),
					),
				)
			)
		);

		// Become a Partner
		$this->_manager->add_control(
			self::SETTING_PARTNER_TITLE,
			array(
				'setting' => self::SETTING_PARTNER_TITLE,
				'section' => self::SECTION_PARTNER,
				'label'   => 'Title',
				'type'    => 'text',
			)
		);

		$this->_manager->add_control(
			self::SETTING_PARTNER_DESC,
			array(
				'setting' => self::SETTING_PARTNER_DESC,
				'section' => self::SECTION_PARTNER,
				'label'   => 'Description',
				'type'    => 'textarea',
			)
		);

		$this->_manager->add_control(
			self::SETTING_PARTNER_CTA,
			array(
				'setting' => self::SETTING_PARTNER_CTA,
				'section' => self::SECTION_PARTNER,
				'label'   => 'CTA Label',
				'type'    => 'text',
			)
		);

		$this->_manager->add_control(
			self::SETTING_PARTNER_CTA_LINK,
			array(
				'setting' => self::SETTING_PARTNER_CTA_LINK,
				'section' => self::SECTION_PARTNER,
				'label'   => 'CTA Link',
				'type'    => 'text',
			)
		);

		// Success Stories
		$this->_manager->add_control(
			self::SETTING_SUCCESS_STORIES_TITLE,
			array(
				'setting' => self::SETTING_SUCCESS_STORIES_TITLE,
				'section' => self::SECTION_SUCCESS_STORIES,
				'label'   => 'Title',
				'type'    => 'text',
			)
		);

		$this->_manager->add_control(
			self::SETTING_SUCCESS_STORIES_DESC,
			array(
				'setting' => self::SETTING_SUCCESS_STORIES_DESC,
				'section' => self::SECTION_SUCCESS_STORIES,
				'label'   => 'Description',
				'type'    => 'textarea',
			)
		);

		// Top List
		$this->_manager->add_control(
			self::SETTING_TOP_LIST_TITLE,
			array(
				'setting' => self::SETTING_TOP_LIST_TITLE,
				'section' => self::SECTION_TOP_LIST,
				'label'   => 'Title',
				'type'    => 'text',
			)
		);

		$this->_manager->add_control(
			self::SETTING_TOP_LIST_DESC,
			array(
				'setting' => self::SETTING_TOP_LIST_DESC,
				'section' => self::SECTION_TOP_LIST,
				'label'   => 'Description',
				'type'    => 'textarea',
			)
		);

		$this->_manager->add_control(
			self::SETTING_TOP_LIST_CAMPAIGN_ID,
			array(
				'setting' => self::SETTING_TOP_LIST_CAMPAIGN_ID,
				'section' => self::SECTION_TOP_LIST,
				'label'   => 'Campaign Id',
				'type'    => 'text',
			)
		);

		$this->_manager->add_control(
			self::SETTING_TOP_LIST_COUNT,
			array(
				'setting' => self::SETTING_TOP_LIST_COUNT,
				'section' => self::SECTION_TOP_LIST,
				'label'   => 'Count',
				'type'    => 'number',
			)
		);

		$this->_manager->add_control( new \WP_Customize_Media_Control( $this->_manager, self::SETTING_TOP_LIST_PLACEHOLDER_LOGO, [
			'setting'   => self::SETTING_TOP_LIST_PLACEHOLDER_LOGO,
			'section'   => self::SECTION_TOP_LIST,
			'label'     => 'Placeholder Logo',
			'mime_type' => 'image',
		] ) );

		// Questions
		$this->_manager->add_control(
			self::SETTING_QUESTIONS_TITLE,
			array(
				'setting' => self::SETTING_QUESTIONS_TITLE,
				'section' => self::SECTION_QUESTIONS,
				'label'   => 'Title',
				'type'    => 'text',
			)
		);

		$this->_manager->add_control(
			self::SETTING_QUESTIONS_DESC,
			array(
				'setting' => self::SETTING_QUESTIONS_DESC,
				'section' => self::SECTION_QUESTIONS,
				'label'   => 'Description',
				'type'    => 'textarea',
			)
		);

		$this->_manager->add_control(
			self::SETTING_QUESTIONS_CTA,
			array(
				'setting' => self::SETTING_QUESTIONS_CTA,
				'section' => self::SECTION_QUESTIONS,
				'label'   => 'CTA Label',
				'type'    => 'text',
			)
		);

		$this->_manager->add_control(
			self::SETTING_QUESTIONS_CTA_LINK,
			array(
				'setting' => self::SETTING_QUESTIONS_CTA_LINK,
				'section' => self::SECTION_QUESTIONS,
				'label'   => 'CTA Link',
				'type'    => 'text',
			)
		);

		// Circulation
		$this->get_component( static::SECTION_OTHER )->register_controls();
	}
}
