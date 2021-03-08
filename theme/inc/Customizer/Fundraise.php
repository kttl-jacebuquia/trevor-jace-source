<?php namespace TrevorWP\Theme\Customizer;

use TrevorWP\CPT;

/**
 * Fundraise  Settings
 */
class Fundraise extends Abstract_Customizer {
	/** Panels */
	const PANEL_ID = self::ID_PREFIX . 'fundraise';

	/** Sections */

	/** Header */
	const SECTION_HEADER = self::PANEL_ID . '_header';

	/** Featured Text */
	const SECTION_FEATURED_TEXT = self::PANEL_ID . '_featured_text';

	/** Three Column */
	const SECTION_THREE = self::PANEL_ID . '_three_column';

	/** One Column */
	const SECTION_ONE = self::PANEL_ID . '_one_column';

	/** Featured Links */
	const SECTION_LINK = self::PANEL_ID . '_links';

	/** Become a Partner */
	const SECTION_PARTNER = self::PANEL_ID . '_partner';

	/** Success Stories */
	const SECTION_SUCCESS_STORIES = self::PANEL_ID . '_success_stories';

	/** QUESTIONS */
	const SECTION_QUESTIONS = self::PANEL_ID . '_question';

	/** OTHER WAYS */
	const SECTION_OTHER = self::PANEL_ID . '_other';

	/** TrevorSpace */
	const SECTION_TREVORSPACE = self::PANEL_ID . '_trevorspace';


	/** Settings */

	/** Header */
	const SETTING_HEADER_PREFIX = self::SECTION_HEADER . '_';
	const SETTING_HOME_HERO_IMG = self::SETTING_HEADER_PREFIX . 'hero_img';
	const SETTING_HOME_HERO_TITLE = self::SETTING_HEADER_PREFIX . 'hero_title';
	const SETTING_HOME_HERO_DESC = self::SETTING_HEADER_PREFIX . 'hero_desc';
	const SETTING_HOME_HERO_CTA = self::SETTING_HEADER_PREFIX . 'hero_cta';
	const SETTING_HOME_HERO_CTA_LINK = self::SETTING_HEADER_PREFIX . 'hero_cta_link';

	/** Featured Text */
	const SETTING_FEATURED_TEXT_PREFIX = self::SECTION_FEATURED_TEXT . '_';
	const SETTING_FEATURED_TEXT_TITLE = self::SETTING_FEATURED_TEXT_PREFIX . 'featured_title';
	const SETTING_FEATURED_TEXT_DESC = self::SETTING_FEATURED_TEXT_PREFIX . 'featured_desc';

	/** Three Column Text */
	const SETTING_THREE_PREFIX = self::SECTION_THREE . '_';
	const SETTING_THREE_TITLE = self::SETTING_THREE_PREFIX . 'three_title';
	const SETTING_THREE_DATA = self::SETTING_THREE_PREFIX . 'three_data';

	/** One Column Text */
	const SETTING_ONE_PREFIX = self::SECTION_ONE . '_';
	const SETTING_ONE_DATA = self::SETTING_ONE_PREFIX . 'one_data';

	/** Three Column Text */
	const SETTING_LINKS_PREFIX = self::SECTION_LINK . '_';
	const SETTING_LINK_TITLE = self::SETTING_LINKS_PREFIX . 'link_title';
	const SETTING_LINK_DESC = self::SETTING_LINKS_PREFIX . 'link_desc';
	const SETTING_LINK_DATA = self::SETTING_THREE_PREFIX . 'link_data';

	/** Become a Partner */
	const SETTING_PARTNER_PREFIX = self::SECTION_PARTNER . '_';
	const SETTING_PARTNER_TITLE = self::SETTING_PARTNER_PREFIX . 'link_title';
	const SETTING_PARTNER_DESC = self::SETTING_PARTNER_PREFIX . 'link_desc';
	const SETTING_PARTNER_CTA = self::SETTING_PARTNER_PREFIX . 'link_cta';
	const SETTING_PARTNER_CTA_LINK = self::SETTING_PARTNER_PREFIX . 'link_url';

	/** Success Stories */
	const SETTING_SUCCESS_STORIES_PREFIX = self::SECTION_SUCCESS_STORIES . '_';
	const SETTING_SUCCESS_STORIES_TITLE = self::SETTING_SUCCESS_STORIES_PREFIX . 'title';
	const SETTING_SUCCESS_STORIES_DESC = self::SETTING_SUCCESS_STORIES_PREFIX . 'desc';

	/** Questions */
	const SETTING_QUESTIONS_PREFIX = self::SECTION_QUESTIONS . '_';
	const SETTING_QUESTIONS_TITLE = self::SETTING_QUESTIONS_PREFIX . 'link_title';
	const SETTING_QUESTIONS_DESC = self::SETTING_QUESTIONS_PREFIX . 'link_desc';
	const SETTING_QUESTIONS_CTA = self::SETTING_QUESTIONS_PREFIX . 'link_cta';
	const SETTING_QUESTIONS_CTA_LINK = self::SETTING_QUESTIONS_PREFIX . 'link_url';

	/** TrevorSpace */
	const SETTING_TREVORSPACE_PREFIX = self::SECTION_TREVORSPACE . '_';
	
	/** Circulation */
	const SETTING_OTHER_PREFIX = self::SECTION_OTHER . '_';
	const SETTING_TREVORSPACE_CIRCULATION_TITLE = self::SETTING_TREVORSPACE_PREFIX . 'circulation_title';
	const SETTING_TREVORSPACE_CIRCULATION_DESC = self::SETTING_TREVORSPACE_PREFIX . 'circulation_desc';

	/** All Defaults */
	const DEFAULTS = array(
		self::SETTING_HOME_HERO_TITLE    => 'Start a fundraiser. <tilt>Change the world.</tilt>',
		self::SETTING_HOME_HERO_DESC     => 'Join our amazing community of online fundraisers, and start saving young LGBTQ lives today.',
		self::SETTING_HOME_HERO_CTA      => 'Become a Fundraiser',
		self::SETTING_HOME_HERO_CTA_LINK => '#',

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

		self::SETTING_PARTNER_TITLE    => '<tilt>Become</tilt>&nbsp;&nbsp;a<br>corporate partner.',
		self::SETTING_PARTNER_DESC     => 'Our corporate partners help us create lasting change at scale and reach out to more and more LGBTQ young people every year.',
		self::SETTING_PARTNER_CTA      => 'Learn More',
		self::SETTING_PARTNER_CTA_LINK => '#',

		/** Success Stories */
		self::SETTING_SUCCESS_STORIES_TITLE => 'Fundraiser Success Stories',
		self::SETTING_SUCCESS_STORIES_DESC  => 'These fundraisers made a huge impact on our ability to provide life-saving services.',

		self::SETTING_QUESTIONS_TITLE    => 'Have questions?',
		self::SETTING_QUESTIONS_DESC     => 'Please reach out to us and a member of our Development team will get back to you.',
		self::SETTING_QUESTIONS_CTA      => 'Reach Out',
		self::SETTING_QUESTIONS_CTA_LINK => '#',

		self::SETTING_TREVORSPACE_CIRCULATION_TITLE => 'Other ways to help',


	);

	/** @inheritDoc */
	protected function _register_panels(): void {
		$this->_manager->add_panel( self::PANEL_ID, array( 'title' => 'Fundraise' ) );
	}

	/** @inheritDoc */
	protected function _register_sections(): void {
		// Home

		// Header
		$this->_manager->add_section(
			self::SECTION_HEADER,
			array(
				'panel' => self::PANEL_ID,
				'title' => 'Header',
			)
		);

		// Featured text
		$this->_manager->add_section(
			self::SECTION_FEATURED_TEXT,
			array(
				'panel' => self::PANEL_ID,
				'title' => 'Featured Text',
			)
		);

		// Three Column text
		$this->_manager->add_section(
			self::SECTION_THREE,
			array(
				'panel' => self::PANEL_ID,
				'title' => 'How your Money is Used',
			)
		);

		// Three Column text
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

		// Become a Partner
		$this->_manager->add_section(
			self::SECTION_QUESTIONS,
			array(
				'panel' => self::PANEL_ID,
				'title' => 'Questions',
			)
		);

		// Other Ways to Help
		$this->_manager->add_section(
			self::SECTION_OTHER,
			array(
				'panel' => self::PANEL_ID,
				'title' => 'Circulation',
			)
		);

	}

	/** @inheritDoc */
	protected function _register_controls(): void {
		// Home

		// HEADER
		$this->_manager->add_control(
			new \WP_Customize_Media_Control(
				$this->_manager,
				self::SETTING_HOME_HERO_IMG,
				array(
					'setting'   => self::SETTING_HOME_HERO_IMG,
					'section'   => self::SECTION_HEADER,
					'label'     => 'Hero Image',
					'mime_type' => 'image',
				)
			)
		);

		$this->_manager->add_control(
			self::SETTING_HOME_HERO_TITLE,
			array(
				'setting' => self::SETTING_HOME_HERO_TITLE,
				'section' => self::SECTION_HEADER,
				'label'   => 'Hero Title',
				'type'    => 'text',
			)
		);

		$this->_manager->add_control(
			self::SETTING_HOME_HERO_DESC,
			array(
				'setting' => self::SETTING_HOME_HERO_DESC,
				'section' => self::SECTION_HEADER,
				'label'   => 'Hero Description',
				'type'    => 'text',
			)
		);

		// FEATURED TEXT

		$this->_manager->add_control(
			self::SETTING_FEATURED_TEXT_TITLE,
			array(
				'setting' => self::SETTING_FEATURED_TEXT_TITLE,
				'section' => self::SECTION_FEATURED_TEXT,
				'label'   => 'Featured Title',
				'type'    => 'text',
			)
		);

		$this->_manager->add_control(
			self::SETTING_FEATURED_TEXT_DESC,
			array(
				'setting' => self::SETTING_FEATURED_TEXT_DESC,
				'section' => self::SECTION_FEATURED_TEXT,
				'label'   => 'Featured Description',
				'type'    => 'text',
			)
		);

		// THREE COLUMNS
		$this->_manager->add_control(
			self::SETTING_THREE_TITLE,
			array(
				'setting' => self::SETTING_THREE_TITLE,
				'section' => self::SECTION_THREE,
				'label'   => '[1] Title',
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
					'label'   => '[1] Data',
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

		// ONE COLUMN
		$this->_manager->add_control(
			new Control\Custom_List(
				$this->_manager,
				self::SETTING_ONE_DATA,
				array(
					'setting' => self::SETTING_ONE_DATA,
					'section' => self::SECTION_ONE,
					'label'   => '[1] Data',
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

		// FEATURED LINKS
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
		$this->_manager->add_control(
			self::SETTING_TREVORSPACE_CIRCULATION_TITLE,
			array(
				'setting' => self::SETTING_TREVORSPACE_CIRCULATION_TITLE,
				'section' => self::SECTION_OTHER,
				'label'   => 'Title',
				'type'    => 'text',
			)
		);

		$this->_manager->add_control(
			self::SETTING_TREVORSPACE_CIRCULATION_DESC,
			array(
				'setting' => self::SETTING_TREVORSPACE_CIRCULATION_DESC,
				'section' => self::SECTION_OTHER,
				'label'   => 'Description',
				'type'    => 'text',
			)
		);

		
	}
}
