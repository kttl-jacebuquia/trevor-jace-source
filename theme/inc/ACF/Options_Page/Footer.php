<?php namespace TrevorWP\Theme\ACF\Options_Page;

class Footer extends A_Options_Page {
	const FIELD_DESCRIPTION      = 'footer_description';
	const FIELD_NEWSLETTER_TITLE = 'footer_newsletter_title';
	const FIELD_FOOTER_LINKS     = 'footer_links';
	const FIELD_FACEBOOK_URL     = 'footer_facebook_url';
	const FIELD_TWITTER_URL      = 'footer_twitter_url';
	const FIELD_INSTAGRAM_URL    = 'footer_instagram_url';
	const FIELD_TIKTOK_URL       = 'footer_tiktok_url';
	const FIELD_YOUTUBE_URL      = 'footer_youtube_url';
	const FIELD_LINKEDIN_URL     = 'footer_linkedin_url';

	/** @inheritDoc */
	protected static function prepare_page_register_args(): array {
		return array_merge(
			parent::prepare_page_register_args(),
			array(
				'parent_slug' => 'general-settings',
			)
		);
	}

	/** @inheritDoc */
	protected static function prepare_fields(): array {
		$description      = static::gen_field_key( static::FIELD_DESCRIPTION );
		$newsletter_title = static::gen_field_key( static::FIELD_NEWSLETTER_TITLE );
		$footer_links     = static::gen_field_key( static::FIELD_FOOTER_LINKS );
		$facebook_url     = static::gen_field_key( static::FIELD_FACEBOOK_URL );
		$twitter_url      = static::gen_field_key( static::FIELD_TWITTER_URL );
		$instagram_url    = static::gen_field_key( static::FIELD_INSTAGRAM_URL );
		$tiktok_url       = static::gen_field_key( static::FIELD_TIKTOK_URL );
		$youtube_url      = static::gen_field_key( static::FIELD_YOUTUBE_URL );
		$linkedin_url     = static::gen_field_key( static::FIELD_LINKEDIN_URL );

		return array_merge(
			static::_gen_tab_field( 'General' ),
			array(
				static::FIELD_DESCRIPTION      => array(
					'key'           => $description,
					'name'          => static::FIELD_DESCRIPTION,
					'label'         => 'Description',
					'type'          => 'textarea',
					'required'      => 1,
					'default_value' => 'The Trevor Project is the leading national organization providing crisis intervention and suicide prevention services to lesbian, gay, bisexual, transgender, queer & questioning youth.',
				),
				static::FIELD_NEWSLETTER_TITLE => array(
					'key'           => $newsletter_title,
					'name'          => static::FIELD_NEWSLETTER_TITLE,
					'label'         => 'Newsletter Title',
					'type'          => 'text',
					'required'      => 1,
					'default_value' => 'Sign Up For Our Newsletter',
				),
			),
			static::_gen_tab_field( 'Footer Links' ),
			array(
				static::FIELD_FOOTER_LINKS => array(
					'key'       => $footer_links,
					'name'      => static::FIELD_FOOTER_LINKS,
					'label'     => 'Footer Links',
					'type'      => 'message',
					'required'  => 0,
					'message'   => 'Footer links can be configured on the <a href="/wp-admin/nav-menus.php?action=locations">menu page</a>.',
					'new_lines' => 'wpautop',
					'esc_html'  => 0,
				),
			),
			static::_gen_tab_field( 'Social Media Links' ),
			array(
				static::FIELD_FACEBOOK_URL  => array(
					'key'           => $facebook_url,
					'name'          => static::FIELD_FACEBOOK_URL,
					'label'         => 'Facebook URL',
					'type'          => 'url',
					'required'      => 1,
					'default_value' => 'https://www.facebook.com/TheTrevorProject',
				),
				static::FIELD_TWITTER_URL   => array(
					'key'           => $twitter_url,
					'name'          => static::FIELD_TWITTER_URL,
					'label'         => 'Twitter URL',
					'type'          => 'url',
					'required'      => 1,
					'default_value' => 'https://twitter.com/trevorproject',
				),
				static::FIELD_INSTAGRAM_URL => array(
					'key'           => $instagram_url,
					'name'          => static::FIELD_INSTAGRAM_URL,
					'label'         => 'Instagram URL',
					'type'          => 'url',
					'required'      => 1,
					'default_value' => 'https://www.instagram.com/trevorproject/',
				),
				static::FIELD_TIKTOK_URL    => array(
					'key'           => $tiktok_url,
					'name'          => static::FIELD_TIKTOK_URL,
					'label'         => 'Tiktok URL',
					'type'          => 'url',
					'required'      => 1,
					'default_value' => 'https://www.tiktok.com/@trevorproject',
				),
				static::FIELD_YOUTUBE_URL   => array(
					'key'           => $youtube_url,
					'name'          => static::FIELD_YOUTUBE_URL,
					'label'         => 'Youtube URL',
					'type'          => 'url',
					'required'      => 1,
					'default_value' => 'https://www.youtube.com/thetrevorproject',
				),
				static::FIELD_LINKEDIN_URL  => array(
					'key'           => $linkedin_url,
					'name'          => static::FIELD_LINKEDIN_URL,
					'label'         => 'LinkedIn URL',
					'type'          => 'url',
					'required'      => 1,
					'default_value' => 'https://www.linkedin.com/company/the-trevor-project',
				),
			),
		);
	}

	/**
	 * Gets all footer values
	 */
	public static function get_footer() {

		$data = array(
			'description'        => static::get_option( static::FIELD_DESCRIPTION ),
			'newsletter_title'   => static::get_option( static::FIELD_NEWSLETTER_TITLE ),
			'social_media_links' => array(
				array(
					'url'  => static::get_option( static::FIELD_FACEBOOK_URL ),
					'icon' => 'trevor-ti-facebook',
				),
				array(
					'url'  => static::get_option( static::FIELD_TWITTER_URL ),
					'icon' => 'trevor-ti-twitter',
				),
				array(
					'url'  => static::get_option( static::FIELD_INSTAGRAM_URL ),
					'icon' => 'trevor-ti-instagram',
				),
				array(
					'url'  => static::get_option( static::FIELD_TIKTOK_URL ),
					'icon' => 'trevor-ti-tiktok',
				),
				array(
					'url'  => static::get_option( static::FIELD_YOUTUBE_URL ),
					'icon' => 'trevor-ti-youtube',
				),
				array(
					'url'  => static::get_option( static::FIELD_LINKEDIN_URL ),
					'icon' => 'trevor-ti-linkedin',
				),
			),
		);

		return $data;
	}
}
