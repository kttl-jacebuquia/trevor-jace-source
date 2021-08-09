<?php namespace TrevorWP\Theme\ACF\Field_Group;

use TrevorWP\Theme\ACF\Field\Color;

class Block_Styles extends A_Field_Group {
	const FIELD_BG_COLOR   = 'bg_color';
	const FIELD_TEXT_COLOR = 'text_color';

	/** @inheritDoc */
	public static function prepare_register_args(): array {
		return array(
			'title'  => 'Block Styles',
			'fields' => static::get_fields(),
		);
	}

	public static function get_fields(): array {
		$bg_color   = static::gen_field_key( static::FIELD_BG_COLOR );
		$text_color = static::gen_field_key( static::FIELD_TEXT_COLOR );

		return array(
			static::FIELD_BG_COLOR   => Color::gen_args(
				$bg_color,
				static::FIELD_BG_COLOR,
				array(
					'label'         => 'Background Color',
					'default_value' => 'transparent',
					'wrapper'       => array(
						'width' => '50',
					),
				)
			),
			static::FIELD_TEXT_COLOR => Color::gen_args(
				$text_color,
				static::FIELD_TEXT_COLOR,
				array(
					'label'         => 'Text Color',
					'default_value' => 'current',
					'wrapper'       => array(
						'width' => '50',
					),
				),
			),
		);
	}
}
