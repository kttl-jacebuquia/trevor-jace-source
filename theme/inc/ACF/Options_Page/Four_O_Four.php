<?php namespace TrevorWP\Theme\ACF\Options_Page;

use TrevorWP\Theme\ACF\Field\Color;

class Four_O_Four extends A_Options_Page {
	const FIELD_HEADLINE_TEXT_COLOR    = 'four_o_four_headline_text_color';
	const FIELD_DESCRIPTION_TEXT_COLOR = 'four_o_four_description_text_color';
	const FIELD_HEADLINE               = 'four_o_four_headline';
	const FIELD_DESCRIPTION            = 'four_o_four_description';
	const FIELD_LINK                   = 'four_o_four_link';
	const FIELD_IMAGE                  = 'four_o_four_image';

	/** @inheritDoc */
	protected static function prepare_page_register_args(): array {
		return array_merge(
			parent::prepare_page_register_args(),
			array(
				'parent_slug' => 'general-settings',
				'page_title'  => '404 Settings',
				'menu_title'  => '404',
			)
		);
	}

	/** @inheritDoc */
	protected static function prepare_register_args(): array {
		return array_merge(
			parent::prepare_register_args(),
			array(
				'title' => '404 Settings',
			),
		);
	}

	/** @inheritDoc */
	protected static function prepare_fields(): array {
		$headline_text_color    = static::gen_field_key( static::FIELD_HEADLINE_TEXT_COLOR );
		$description_text_color = static::gen_field_key( static::FIELD_DESCRIPTION_TEXT_COLOR );
		$headline               = static::gen_field_key( static::FIELD_HEADLINE );
		$description            = static::gen_field_key( static::FIELD_DESCRIPTION );
		$link                   = static::gen_field_key( static::FIELD_LINK );
		$image                  = static::gen_field_key( static::FIELD_IMAGE );

		return array_merge(
			static::_gen_tab_field( 'General' ),
			array(
				static::FIELD_HEADLINE    => array(
					'key'           => $headline,
					'name'          => static::FIELD_HEADLINE,
					'label'         => 'Headline',
					'type'          => 'text',
					'required'      => true,
					'default_value' => 'Oops!',
				),
				static::FIELD_DESCRIPTION => array(
					'key'           => $description,
					'name'          => static::FIELD_DESCRIPTION,
					'label'         => 'Description',
					'type'          => 'text',
					'default_value' => 'The page you\'re looking for doesn\'t exist',
				),
				static::FIELD_LINK        => array(
					'key'           => $link,
					'name'          => static::FIELD_LINK,
					'label'         => 'Link',
					'type'          => 'link',
					'return_format' => 'array',
				),
				static::FIELD_IMAGE       => array(
					'key'           => $image,
					'name'          => static::FIELD_IMAGE,
					'label'         => 'Image',
					'type'          => 'image',
					'required'      => 1,
					'return_format' => 'array',
					'preview_size'  => 'thumbnail',
					'library'       => 'all',
				),
			),
			static::_gen_tab_field( 'Styling' ),
			array(
				static::FIELD_HEADLINE_TEXT_COLOR    => Color::gen_args(
					$headline_text_color,
					static::FIELD_HEADLINE_TEXT_COLOR,
					array(
						'label'         => 'Headline Text Color',
						'default_value' => 'indigo',
						'wrapper'       => array(
							'width' => '50%',
						),
					),
				),
				static::FIELD_DESCRIPTION_TEXT_COLOR => Color::gen_args(
					$description_text_color,
					static::FIELD_DESCRIPTION_TEXT_COLOR,
					array(
						'label'         => 'Description Text Color',
						'default_value' => 'indigo',
						'wrapper'       => array(
							'width' => '50%',
						),
					),
				),
			)
		);
	}

	/**
	 * Gets all 404 values
	 */
	public static function get_four_o_four() {
		$data = array(
			'headline_text_color'    => static::get_option( static::FIELD_HEADLINE_TEXT_COLOR ),
			'description_text_color' => static::get_option( static::FIELD_DESCRIPTION_TEXT_COLOR ),
			'headline'               => static::get_option( static::FIELD_HEADLINE ),
			'description'            => static::get_option( static::FIELD_DESCRIPTION ),
			'link'                   => static::get_option( static::FIELD_LINK ),
			'image'                  => static::get_option( static::FIELD_IMAGE ),
		);

		return $data;
	}
}
