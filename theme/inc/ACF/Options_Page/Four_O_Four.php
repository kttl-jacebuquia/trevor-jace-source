<?php namespace TrevorWP\Theme\ACF\Options_Page;

class Four_O_Four extends A_Options_Page {
	const FIELD_HEADLINE    = 'four_o_four_headline';
	const FIELD_DESCRIPTION = 'four_o_four_description';
	const FIELD_LINK        = 'four_o_four_link';
	const FIELD_IMAGE       = 'four_o_four_image';

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
		$headline    = static::gen_field_key( static::FIELD_HEADLINE );
		$description = static::gen_field_key( static::FIELD_DESCRIPTION );
		$link        = static::gen_field_key( static::FIELD_LINK );
		$image       = static::gen_field_key( static::FIELD_IMAGE );

		return array_merge(
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
		);
	}

	/**
	 * Gets all 404 values
	 */
	public static function get_four_o_four() {
		$data = array(
			'headline'    => static::get_option( static::FIELD_HEADLINE ),
			'description' => static::get_option( static::FIELD_DESCRIPTION ),
			'link'        => static::get_option( static::FIELD_LINK ),
			'image'       => static::get_option( static::FIELD_IMAGE ),
		);

		return $data;
	}
}
