<?php namespace TrevorWP\Theme\ACF\Field_Group;


class DOM_Attr extends A_Field_Group {
	const FIELD_CLASS = 'class';
	const FIELD_ATTRIBUTES = 'attributes';
	const FIELD_ATTR_KEY = 'attr_key';
	const FIELD_ATTR_VAL = 'attr_val';

	/** @inheritDoc */
	protected static function prepare_register_args(): array {
		$class      = static::gen_field_key( static::FIELD_CLASS );
		$attributes = static::gen_field_key( static::FIELD_ATTRIBUTES );
		$attr_key   = static::gen_field_key( static::FIELD_ATTR_KEY );
		$attr_val   = static::gen_field_key( static::FIELD_ATTR_VAL );

		return [
			'title'  => 'DOM Attr',
			'fields' => [
				static::FIELD_CLASS      => [
					'key'   => $class,
					'name'  => static::FIELD_CLASS,
					'label' => 'Class',
					'type'  => 'text',
				],
				static::FIELD_ATTRIBUTES => [
					'key'        => $attributes,
					'name'       => static::FIELD_ATTRIBUTES,
					'label'      => 'Attributes',
					'type'       => 'repeater',
					'layout'     => 'table',
					'collapsed'  => $attr_key,
					'sub_fields' => [
						static::FIELD_ATTR_KEY => [
							'key'      => $attr_key,
							'name'     => static::FIELD_ATTR_KEY,
							'label'    => 'Key',
							'type'     => 'text',
							'required' => 1,
						],
						static::FIELD_ATTR_VAL => [
							'key'   => $attr_val,
							'name'  => static::FIELD_ATTR_VAL,
							'label' => 'Value',
							'type'  => 'text',
						],
					],
				],
			],
		];
	}

	/**
	 * @param array $args
	 *
	 * @return array
	 */
	public static function clone( array $args = [] ): array {
		return parent::clone( array_merge( [
			'label'        => 'Attributes',
			'name'         => 'attr',
			'display'      => 'group',
			'layout'       => 'row',
			'prefix_label' => 1,
			'prefix_name'  => 1,
		], $args ) );
	}

	/**
	 * @param array $value
	 *
	 * @return string
	 */
	public static function render_attrs_of( $value = null, array $default_class = [] ): string {
		if ( ! is_array( $value ) ) {
			$value = [];
		}

		$class           = empty( $value[ static::FIELD_CLASS ] )
			? []
			: explode( ' ', $value[ static::FIELD_CLASS ] );
		$attributes_data = empty( $value[ static::FIELD_ATTRIBUTES ] )
			? []
			: $value[ static::FIELD_ATTRIBUTES ];

		$attributes = [];
		foreach ( $attributes_data as $attribute ) {
			$attributes[ $attribute[ DOM_Attr::FIELD_ATTR_KEY ] ] = $attribute[ DOM_Attr::FIELD_ATTR_VAL ];
		}

		return parent::render_attrs( array_merge( $default_class, $class ), $attributes );
	}
}
