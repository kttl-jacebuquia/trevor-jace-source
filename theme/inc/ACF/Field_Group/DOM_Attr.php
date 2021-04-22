<?php namespace TrevorWP\Theme\ACF\Field_Group;

use TrevorWP\Theme\Tailwind\Config;

class DOM_Attr extends A_Field_Group {
	const FIELD_CLASS = 'class';
	const FIELD_STYLE_CLASS = 'style_class';
	const FIELD_STYLE_CLASS_SCREEN = 'screen';
	const FIELD_STYLE_CLASS_DATA = 'data';
	const FIELD_ACCORDION = 'accordion';
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
				static::FIELD_ACCORDION          => [
					'name'  => static::FIELD_ACCORDION,
					'key'   => static::gen_field_key( static::FIELD_ACCORDION ),
					'type'  => 'accordion',
					'label' => 'Attributes'
				],
				static::FIELD_STYLE_CLASS        => [
					'name'         => static::FIELD_STYLE_CLASS,
					'key'          => static::gen_field_key( static::FIELD_STYLE_CLASS ),
					'label'        => 'Style Classes',
					'type'         => 'repeater',
					'display'      => 'group',
					'button_label' => 'New Screen',
					'collapsed'    => static::FIELD_STYLE_CLASS_SCREEN,
					'sub_fields'   => static::_prepare_style_subfields(),
				],
				static::FIELD_CLASS              => [
					'key'   => $class,
					'name'  => static::FIELD_CLASS,
					'label' => 'Class',
					'type'  => 'text',
				],
				static::FIELD_ATTRIBUTES         => [
					'key'        => $attributes,
					'name'       => static::FIELD_ATTRIBUTES,
					'type'       => 'repeater',
					'layout'     => 'table',
					'label'      => 'Custom',
					'collapsed'  => $attr_key,
					'sub_fields' => [
						static::FIELD_ATTR_KEY => [
							'key'      => $attr_key,
							'name'     => static::FIELD_ATTR_KEY,
							'label'    => 'Key',
							'type'     => 'text',
							'required' => true,
						],
						static::FIELD_ATTR_VAL => [
							'key'   => $attr_val,
							'name'  => static::FIELD_ATTR_VAL,
							'label' => 'Value',
							'type'  => 'text',
						],
					],
				],
				static::FIELD_ACCORDION . '_end' => [
					'name'     => static::FIELD_ACCORDION . '_end',
					'key'      => static::gen_field_key( static::FIELD_ACCORDION ) . '_end',
					'type'     => 'accordion',
					'endpoint' => true,
				],
			],
		];
	}

	/** @inheritdoc */
	public static function clone( array $args = [] ): array {
		return parent::clone( array_merge( [
			'label'       => 'Attributes',
			'name'        => 'attr',
			'display'     => 'seamless',
			'prefix_name' => true,
		], $args ) );
	}

	/**
	 * @param array $value
	 * @param array $default_cls
	 *
	 * @return string
	 */
	public static function render_attrs_of( $value = null, array $default_cls = [] ): string {
		$attributes = static::get_attrs_of( $value, $default_cls );
		$class      = (array) @$attributes['class'];
		unset( $attributes['class'] );

		return parent::render_attrs( $class, $attributes );
	}

	/**
	 * @param null $value
	 * @param array $default_cls
	 *
	 * @return array
	 */
	public static function get_attrs_of( $value = null, array $default_cls = [] ): array {
		if ( ! is_array( $value ) ) {
			$value = [];
		}

		# Style classes
		$style_cls = empty( $value[ static::FIELD_STYLE_CLASS ] )
			? []
			: array_map( function ( $data ) {
				$screen = (string) @$data[ static::FIELD_STYLE_CLASS_SCREEN ];

				return implode( ' ', array_map( function ( $data ) use ( $screen ) {
					return ( empty( $screen ) ? '' : "{$screen}:" ) .
					       $data['acf_fc_layout'] .
					       ( ( ! isset( $data['value'] ) || '' == $data['value'] ) ? '' : "-{$data['value']}" );
				}, (array) @$data[ static::FIELD_STYLE_CLASS_DATA ] ) );
			}, $value[ static::FIELD_STYLE_CLASS ] );

		# Extra classes
		$extra_cls = empty( $value[ static::FIELD_CLASS ] )
			? []
			: explode( ' ', $value[ static::FIELD_CLASS ] );

		# Attributes
		$attributes      = [];
		$attributes_data = empty( $value[ static::FIELD_ATTRIBUTES ] )
			? []
			: $value[ static::FIELD_ATTRIBUTES ];
		foreach ( $attributes_data as $attribute ) {
			$attributes[ $attribute[ DOM_Attr::FIELD_ATTR_KEY ] ] = $attribute[ DOM_Attr::FIELD_ATTR_VAL ];
		}

		$attributes['class'] = implode( ' ', array_merge( $default_cls, $style_cls, $extra_cls ) );

		return $attributes;
	}

	/**
	 * @return array
	 */
	protected static function _prepare_style_subfields(): array {
		$option_layouts = [];
		foreach ( Config::collect_options() as $key => $choices ) {
			$layout_key                    = static::gen_field_key( static::FIELD_STYLE_CLASS . '-option-' . $key );
			$option_layouts[ $layout_key ] = [
				'key'        => $layout_key,
				'name'       => $key,
				'label'      => $key,
				'sub_fields' => [
					'value' => [
						'key'     => $layout_key . '-value',
						'name'    => 'value',
						'type'    => 'select',
						'choices' => $choices
					],
				]
			];
		}

		return [
			static::FIELD_STYLE_CLASS_SCREEN => [
				'key'     => static::gen_field_key( static::FIELD_STYLE_CLASS_SCREEN ),
				'name'    => static::FIELD_STYLE_CLASS_SCREEN,
				'label'   => 'Screen',
				'type'    => 'select',
				'choices' => Config::collect_screens(),
			],
			static::FIELD_STYLE_CLASS_DATA   => [
				'key'          => static::gen_field_key( static::FIELD_STYLE_CLASS_DATA ),
				'name'         => static::FIELD_STYLE_CLASS_DATA,
				'label'        => 'Values',
				'type'         => 'flexible_content',
				'display'      => 'group',
				'layouts'      => $option_layouts,
				'button_label' => 'Add New',
			],
		];
	}
}
