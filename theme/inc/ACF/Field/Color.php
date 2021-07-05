<?php namespace TrevorWP\Theme\ACF\Field;

class Color extends A_Field {
	/** @inheritdoc */
	public static function gen_args( string $key, string $name, array $ext_args = array() ): array {
		return array_merge(
			array(
				'label'    => 'Color',
				'type'     => 'select',
				'required' => 0,
				'choices'  => array(
					'white'          => 'White',
					'black'          => 'Black',
					'teal-dark'      => 'Teal - Dark',
					'teal-tint'      => 'Teal - Tint',
					'gray'           => 'Gray',
					'gray-light'     => 'Gray - Light',
					'orange'         => 'Orange',
					'blue_green'     => 'Blue Green',
					'canary'         => 'Canary',
					'gradient_peach' => 'Gradient Peach',
				),
			),
			parent::gen_args( $key, $name, $ext_args )
		);
	}
}
