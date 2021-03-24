<?php namespace TrevorWP\Theme\Customizer\Component;

use TrevorWP\Exception\Internal;
use TrevorWP\Theme\Customizer\Control\Custom_List;
use TrevorWP\Theme\Helper\Page_Header;

/**
 * Header Component Customizer
 */
class Header extends Abstract_Component {
	const SETTING_TITLE_TOP = 'title_top';
	const SETTING_TITLE = 'title';
	const SETTING_DESC = 'desc';
	const SETTING_CTA_TXT = 'cta_txt';
	const SETTING_CTA_URL = 'cta_url';
	const SETTING_CTA2_TXT = 'cta2_txt';
	const SETTING_CTA2_URL = 'cta2_url';
	const SETTING_IMG = 'img';
	const SETTING_CAROUSEL = 'carousel';

	const TYPE_SPLIT_IMG = 'split_img';
	const TYPE_IMG_BG = 'img_bg';
	const TYPE_CAROUSEL = 'split_carousel';
	const TYPE_TEXT = 'text';
	const TYPE_HORIZONTAL = 'horizontal';

	const FIELDSET_CUSTOM_LIST_CAROUSEL = [
		'img'     => [
			'type'      => Custom_List::FIELD_TYPE_MEDIA,
			'label'     => 'Media',
			'mime_type' => 'image',
		],
		'caption' => [
			'type'  => Custom_List::FIELD_TYPE_TEXTAREA,
			'label' => 'Description'
		],
		'cta_txt' => [
			'type'  => Custom_List::FIELD_TYPE_INPUT,
			'label' => 'CTA Text'
		],
		'cta_url' => [
			'type'  => Custom_List::FIELD_TYPE_INPUT,
			'label' => 'CTA Url'
		],
	];

	/** @inheritDoc */
	public function register_section(): void {
		$this->get_manager()->add_section(
			$this->get_section_id(),
			[
				'panel' => $this->get_panel_id(),
				'title' => 'Header',
			]
		);
	}

	/** @inheritDoc */
	public function register_controls(): void {
		$manager = $this->get_manager();
		$sec_id  = $this->get_section_id();
		$type    = @$this->_options['type'];

		# Title top
		$manager->add_control(
			$setting_title = $this->get_setting_id( self::SETTING_TITLE_TOP ),
			[
				'setting' => $setting_title,
				'section' => $sec_id,
				'label'   => 'Title Top',
				'type'    => 'text',
			]
		);

		# Title
		$manager->add_control(
			$setting_title = $this->get_setting_id( self::SETTING_TITLE ),
			[
				'setting' => $setting_title,
				'section' => $sec_id,
				'label'   => 'Title',
				'type'    => 'text',
			]
		);

		# Description
		$manager->add_control(
			$setting_title = $this->get_setting_id( self::SETTING_DESC ),
			[
				'setting' => $setting_title,
				'section' => $sec_id,
				'label'   => 'Description',
				'type'    => 'text',
			]
		);

		# CTA1 Text
		$manager->add_control(
			$setting_cta_txt = $this->get_setting_id( self::SETTING_CTA_TXT ),
			[
				'setting' => $setting_cta_txt,
				'section' => $sec_id,
				'label'   => 'CTA1 Text',
				'type'    => 'text',
			]
		);

		# CTA1 URL
		$manager->add_control(
			$setting_cta_url = $this->get_setting_id( self::SETTING_CTA_URL ),
			[
				'setting' => $setting_cta_url,
				'section' => $sec_id,
				'label'   => 'CTA1 URL',
				'type'    => 'url',
			]
		);

		# CTA2 Text
		$manager->add_control(
			$setting_cta2_txt = $this->get_setting_id( self::SETTING_CTA2_TXT ),
			[
				'setting' => $setting_cta2_txt,
				'section' => $sec_id,
				'label'   => 'CTA2 Text',
				'type'    => 'text',
			]
		);

		# CTA2 URL
		$manager->add_control(
			$setting_cta2_url = $this->get_setting_id( self::SETTING_CTA2_URL ),
			[
				'setting' => $setting_cta2_url,
				'section' => $sec_id,
				'label'   => 'CTA2 URL',
				'type'    => 'url',
			]
		);

		if ( $type == self::TYPE_IMG_BG || $type == self::TYPE_SPLIT_IMG || $type == self::TYPE_HORIZONTAL) {
			# Image
			$manager->add_control(
				new \WP_Customize_Media_Control(
					$manager,
					$img_id = $this->get_setting_id( self::SETTING_IMG ),
					array(
						'setting'   => $img_id,
						'section'   => $sec_id,
						'label'     => 'Image',
						'mime_type' => 'image',
					)
				)
			);
		} else if ( $type == self::TYPE_CAROUSEL ) {
			# Carousel
			$manager->add_control( new Custom_List( $manager, $carousel_id = $this->get_setting_id( self::SETTING_CAROUSEL ), [
				'setting' => $carousel_id,
				'section' => $sec_id,
				'label'   => 'Carousel Data',
				'fields'  => static::FIELDSET_CUSTOM_LIST_CAROUSEL,
			] ) );
		}
	}

	/** @inheritdoc */
	public function render( array $ext_options = [] ): ?string {
		$options = array_merge( [
			'title_top' => $this->get_val( static::SETTING_TITLE_TOP ),
			'title'     => $this->get_val( static::SETTING_TITLE ),
			'desc'      => $this->get_val( static::SETTING_DESC ),
			'img_id'    => $this->get_val( static::SETTING_IMG ),
			'cta_txt'   => $this->get_val( static::SETTING_CTA_TXT ),
			'cta_url'   => $this->get_val( static::SETTING_CTA_URL ),
			'cta2_txt'  => $this->get_val( static::SETTING_CTA2_TXT ),
			'cta2_url'  => $this->get_val( static::SETTING_CTA2_URL ),
		], $ext_options );

		switch ( $type = $this->get_option( 'type' ) ) {
			case static::TYPE_SPLIT_IMG:
				return Page_Header::split_img( $options );
			case static::TYPE_TEXT:
				return Page_Header::text( $options );
			case static::TYPE_IMG_BG:
				return Page_Header::img_bg( $options );
			case static::TYPE_CAROUSEL:
				return Page_Header::split_carousel( $options );
			case static::TYPE_HORIZONTAL:
				return Page_Header::horizontal( $options );
			default:
				throw new Internal( 'Unknown type provided', compact( 'type' ) );
		}
	}
}
