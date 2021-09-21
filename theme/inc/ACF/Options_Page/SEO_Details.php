<?php namespace TrevorWP\Theme\ACF\Options_Page;

class SEO_Details extends A_Options_Page {
	const FIELD_SEARCH_APPEARANCE_GROUP       = '_seo_search_appearance_group';
	const FIELD_SEARCH_APPEARANCE_KEYWORDS    = '_seo_search_appearance_keywords';
	const FIELD_SEARCH_APPEARANCE_TITLE       = '_seo_search_appearance_title';
	const FIELD_SEARCH_APPEARANCE_DESCRIPTION = '_seo_search_appearance_description';
	const FIELD_SEARCH_APPEARANCE_IMAGE       = '_seo_search_appearance_image';

	const FIELD_FACEBOOK_GROUP       = '_seo_facebook_group';
	const FIELD_FACEBOOK_TITLE       = '_seo_facebook_title';
	const FIELD_FACEBOOK_DESCRIPTION = '_seo_facebook_description';
	const FIELD_FACEBOOK_IMAGE       = '_seo_facebook_image';

	const FIELD_TWITTER_GROUP       = '_seo_twitter_group';
	const FIELD_TWITTER_TITLE       = '_seo_twitter_title';
	const FIELD_TWITTER_DESCRIPTION = '_seo_twitter_description';
	const FIELD_TWITTER_IMAGE       = '_seo_twitter_image';

	/** @inheritDoc */
	public static function prepare_fields(): array {
		return static::_get_fields();
	}

	/** @inheritDoc */
	public static function _get_fields( $prefix = '' ): array {
		$search_appearance_group       = static::gen_field_key( $prefix . static::FIELD_SEARCH_APPEARANCE_GROUP );
		$search_appearance_keywords    = static::gen_field_key( $prefix . static::FIELD_SEARCH_APPEARANCE_KEYWORDS );
		$search_appearance_title       = static::gen_field_key( $prefix . static::FIELD_SEARCH_APPEARANCE_TITLE );
		$search_appearance_description = static::gen_field_key( $prefix . static::FIELD_SEARCH_APPEARANCE_DESCRIPTION );
		$search_appearance_image       = static::gen_field_key( $prefix . static::FIELD_SEARCH_APPEARANCE_IMAGE );

		$facebook_group       = static::gen_field_key( $prefix . static::FIELD_FACEBOOK_GROUP );
		$facebook_title       = static::gen_field_key( $prefix . static::FIELD_FACEBOOK_TITLE );
		$facebook_description = static::gen_field_key( $prefix . static::FIELD_FACEBOOK_DESCRIPTION );
		$facebook_image       = static::gen_field_key( $prefix . static::FIELD_FACEBOOK_IMAGE );

		$twitter_group       = static::gen_field_key( $prefix . static::FIELD_TWITTER_GROUP );
		$twitter_title       = static::gen_field_key( $prefix . static::FIELD_TWITTER_TITLE );
		$twitter_description = static::gen_field_key( $prefix . static::FIELD_TWITTER_DESCRIPTION );
		$twitter_image       = static::gen_field_key( $prefix . static::FIELD_TWITTER_IMAGE );

		return array_merge(
			static::_gen_tab_field( 'SEO' ),
			array(
				static::FIELD_SEARCH_APPEARANCE_GROUP => array(
					'key'        => $search_appearance_group,
					'name'       => $prefix . static::FIELD_SEARCH_APPEARANCE_GROUP,
					'label'      => 'Search Appearance',
					'type'       => 'group',
					'sub_fields' => array(
						static::FIELD_SEARCH_APPEARANCE_KEYWORDS  => array(
							'key'   => $search_appearance_keywords,
							'name'  => $prefix . static::FIELD_SEARCH_APPEARANCE_KEYWORDS,
							'label' => 'Keywords',
							'type'  => 'text',
						),
						static::FIELD_SEARCH_APPEARANCE_TITLE  => array(
							'key'   => $search_appearance_title,
							'name'  => $prefix . static::FIELD_SEARCH_APPEARANCE_TITLE,
							'label' => 'SEO Title',
							'type'  => 'text',
						),
						static::FIELD_SEARCH_APPEARANCE_DESCRIPTION  => array(
							'key'   => $search_appearance_description,
							'name'  => $prefix . static::FIELD_SEARCH_APPEARANCE_DESCRIPTION,
							'label' => 'Meta Description',
							'type'  => 'textarea',
						),
						static::FIELD_SEARCH_APPEARANCE_IMAGE  => array(
							'key'          => $search_appearance_image,
							'name'         => $prefix . static::FIELD_SEARCH_APPEARANCE_IMAGE,
							'label'        => 'Image',
							'type'         => 'image',
							'preview_size' => 'thumbnail',
						),
					),
				),
				static::FIELD_FACEBOOK_GROUP          => array(
					'key'        => $facebook_group,
					'name'       => $prefix . static::FIELD_FACEBOOK_GROUP,
					'label'      => 'Facebook',
					'type'       => 'group',
					'sub_fields' => array(
						static::FIELD_FACEBOOK_TITLE       => array(
							'key'   => $facebook_title,
							'name'  => $prefix . static::FIELD_FACEBOOK_TITLE,
							'label' => 'Title',
							'type'  => 'text',
						),
						static::FIELD_FACEBOOK_DESCRIPTION => array(
							'key'   => $facebook_description,
							'name'  => $prefix . static::FIELD_FACEBOOK_DESCRIPTION,
							'label' => 'Description',
							'type'  => 'textarea',
						),
						static::FIELD_FACEBOOK_IMAGE       => array(
							'key'          => $facebook_image,
							'name'         => $prefix . static::FIELD_FACEBOOK_IMAGE,
							'label'        => 'Image',
							'type'         => 'image',
							'preview_size' => 'thumbnail',
						),
					),
				),
				static::FIELD_TWITTER_GROUP           => array(
					'key'        => $twitter_group,
					'name'       => $prefix . static::FIELD_TWITTER_GROUP,
					'label'      => 'Twitter',
					'type'       => 'group',
					'sub_fields' => array(
						static::FIELD_TWITTER_TITLE       => array(
							'key'   => $twitter_title,
							'name'  => $prefix . static::FIELD_TWITTER_TITLE,
							'label' => 'Title',
							'type'  => 'text',
						),
						static::FIELD_TWITTER_DESCRIPTION => array(
							'key'   => $twitter_description,
							'name'  => $prefix . static::FIELD_TWITTER_DESCRIPTION,
							'label' => 'Description',
							'type'  => 'textarea',
						),
						static::FIELD_TWITTER_IMAGE       => array(
							'key'          => $twitter_image,
							'name'         => $prefix . static::FIELD_TWITTER_IMAGE,
							'label'        => 'Image',
							'type'         => 'image',
							'preview_size' => 'thumbnail',
						),
					),
				),
			),
		);
	}

	/** @inheritdoc */
	public static function render_seo_option( $post, $data, $options = array() ) {
		global $wp;

		$prefix                  = $options['prefix'];
		$search_appearance_group = static::get_option( $prefix . static::FIELD_SEARCH_APPEARANCE_GROUP );
		$facebook_group          = static::get_option( $prefix . static::FIELD_FACEBOOK_GROUP );
		$twitter_group           = static::get_option( $prefix . static::FIELD_TWITTER_GROUP );

		if ( empty( $search_appearance_group ) & empty( $facebook_group ) & empty( $twitter_group ) ) {
			return '';
		}

		$site_name   = get_bloginfo( 'name' );
		$keywords    = $search_appearance_group[ $prefix . static::FIELD_SEARCH_APPEARANCE_KEYWORDS ];
		$title       = $search_appearance_group[ $prefix . static::FIELD_SEARCH_APPEARANCE_TITLE ] . ' - ' . $site_name;
		$description = $search_appearance_group[ $prefix . static::FIELD_SEARCH_APPEARANCE_DESCRIPTION ];
		$image       = $search_appearance_group[ $prefix . static::FIELD_SEARCH_APPEARANCE_IMAGE ];

		$fb_title       = ! empty( $facebook_group[ $prefix . static::FIELD_FACEBOOK_TITLE ] ) ? $facebook_group[ $prefix . static::FIELD_FACEBOOK_TITLE ] : $title;
		$fb_description = ! empty( $facebook_group[ $prefix . static::FIELD_FACEBOOK_DESCRIPTION ] ) ? $facebook_group[ $prefix . static::FIELD_FACEBOOK_DESCRIPTION ] : $description;
		$fb_image       = ! empty( $facebook_group[ $prefix . static::FIELD_FACEBOOK_IMAGE ]['url'] ) ? $facebook_group[ $prefix . static::FIELD_FACEBOOK_IMAGE ] : $image;

		$twitter_title       = $twitter_group[ $prefix . static::FIELD_TWITTER_TITLE ];
		$twitter_description = $twitter_group[ $prefix . static::FIELD_TWITTER_DESCRIPTION ];
		$twitter_image       = $twitter_group[ $prefix . static::FIELD_TWITTER_IMAGE ];

		ob_start();
		?>
		<!-- SEO data -->
		<title><?php echo esc_html( $title ); ?></title>
		<meta name="description" content="<?php echo esc_attr( esc_html( $description ) ); ?>">
		<meta name="keywords" content="<?php echo esc_attr( esc_html( $keywords ) ); ?>" />

		<!-- Open Graph data -->
		<meta property="og:locale" content="en_US">
		<meta property="og:type" content="article">
		<meta property="og:title" content="<?php echo esc_attr( esc_html( $fb_title ) ); ?>" />
		<meta property="og:description" content="<?php echo esc_attr( esc_html( $fb_description ) ); ?>" />
		<meta property="og:url" content="<?php echo esc_url( home_url( $wp->request ) ); ?>" />
		<meta property="og:site_name" content="<?php esc_attr( esc_html( $site_name ) ); ?>">
		<?php if ( ! empty( $fb_image['url'] ) ) : ?>
			<meta property="og:image" content="<?php echo esc_url( $fb_image['url'] ); ?>" />
			<meta property="og:image:width" content="<?php echo esc_attr( $fb_image['width'] ); ?>">
			<meta property="og:image:height" content="<?php echo esc_attr( $fb_image['height'] ); ?>">
		<?php endif; ?>

		<!-- Twitter Card data -->
		<meta name="twitter:card" content="summary_large_image">
		<?php if ( ! empty( $twitter_title ) ) : ?>
			<meta name="twitter:title" content="<?php echo esc_attr( esc_html( $twitter_title ) ); ?>">
		<?php endif; ?>
		<?php if ( ! empty( $twitter_description ) ) : ?>
			<meta name="twitter:description" content="<?php echo esc_attr( esc_html( $twitter_description ) ); ?>">
		<?php endif; ?>
		<?php if ( ! empty( $twitter_image['url'] ) ) : ?>
			<meta name="twitter:image" content="<?php echo esc_url( $twitter_image['url'] ); ?>">
		<?php endif; ?>

		<?php
		return ob_get_clean();
	}

	/** @inheritdoc */
	public static function render( $post = false, array $data = null, array $options = array() ): ?string {
		$options = array_merge(
			array_fill_keys(
				array(
					'prefix',
				),
				null
			),
			$options
		);

		return static::render_seo_option( $post, $data, $options );
	}
}
