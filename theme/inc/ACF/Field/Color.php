<?php namespace TrevorWP\Theme\ACF\Field;

class Color extends A_Field {
	/** @inheritdoc */
	public static function gen_args( string $key, string $name, array $ext_args = [] ): array {
		return array_merge( [
			'label'    => 'Color',
			'type'     => 'select',
			'required' => 0,
			'choices'  => [
				'white'      => 'White',
				'black'      => 'Black',
				'teal-dark'  => 'Teal - Dark',
				'gray'       => 'Gray',
				'gray-light' => 'Gray - Light',
				'orange'     => 'Orange',
			]
		], parent::gen_args( $key, $name, $ext_args ) );
	}
}
