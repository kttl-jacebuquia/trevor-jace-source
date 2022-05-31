<?php namespace TrevorWP\Theme\ACF\Field_Group;

use TrevorWP\Theme\ACF\Field\Color;

/**
 * Field Group
 */
abstract class A_Custom_Styling {
	public static function gen_custom_styling_fields( $text_color_field_name, $background_color_field_name ) {
		$text_color_key = A_Field_Group::gen_field_key( $text_color_field_name );
		$background_color_key = A_Field_Group::gen_field_key( $background_color_field_name );

		return array(
			$text_color_field_name   => Color::gen_args(
				$text_color_key,
				$text_color_field_name,
				array(
					'label'   => 'Text Color',
					'default' => 'teal-dark',
					'wrapper' => array(
						'width' => '50%',
					),
				),
			),
			$background_color_field_name     => Color::gen_args(
				$background_color_key,
				$background_color_field_name,
				array(
					'label'   => 'BG Color',
					'default' => 'white',
					'wrapper' => array(
						'width' => '50%',
					),
				),
			),
		);
	}
}
