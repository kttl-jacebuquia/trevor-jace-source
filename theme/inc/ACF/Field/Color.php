<?php namespace TrevorWP\Theme\ACF\Field;

class Color extends A_Field {
	/** @inheritdoc */
	public static function gen_args( string $key, string $name, array $ext_args = array() ): array {
		return array_merge(
			array(
				'label'    => 'Color',
				'type'     => 'select',
				'required' => 'transparent',
				'choices'  => array(
					'not_set'             => 'Not Set',
					'transparent'         => 'None (Transparent)',
					'current'             => 'Current',
					'white'               => 'White',
					'black'               => 'Black',
					'teal-dark'           => 'Teal - Dark',
					'teal-tint'           => 'Teal - Tint',
					'indigo'              => 'Indigo',
					'gray'                => 'Gray',
					'gray-light'          => 'Gray - Light',
					'orange'              => 'Orange',
					'blue_green'          => 'Blue Green',
					'violet'              => 'Violet',
					'canary'              => 'Canary',
					'gold'                => 'Gold',
					'canary'              => 'Canary',
					'gradient_peach'      => 'Gradient Peach',
					'gradient-orange'     => 'Gradient Orange',
					'gradient-light-blue' => 'Gradient Light Blue',
				),
			),
			parent::gen_args( $key, $name, $ext_args )
		);
	}
}
