<?php namespace TrevorWP\Theme\ACF\Field_Group;

use TrevorWP\Theme\Tailwind\Config;

class DOM_Attr extends A_Field_Group {
	const FIELD_CLASS              = 'class';
	const FIELD_STYLE_CLASS        = 'style_class';
	const FIELD_STYLE_CLASS_SCREEN = 'screen';
	const FIELD_STYLE_CLASS_DATA   = 'data';
	const FIELD_ACCORDION          = 'accordion';
	const FIELD_ATTRIBUTES         = 'attributes';
	const FIELD_ATTR_KEY           = 'attr_key';
	const FIELD_ATTR_VAL           = 'attr_val';

	/** @inheritDoc */
	protected static function prepare_register_args(): array {
		$class      = static::gen_field_key( static::FIELD_CLASS );
		$attributes = static::gen_field_key( static::FIELD_ATTRIBUTES );
		$attr_key   = static::gen_field_key( static::FIELD_ATTR_KEY );
		$attr_val   = static::gen_field_key( static::FIELD_ATTR_VAL );

		return array(
			'title'  => 'DOM Attr',
			'fields' => array(
				static::FIELD_ACCORDION          => array(
					'name'  => static::FIELD_ACCORDION,
					'key'   => static::gen_field_key( static::FIELD_ACCORDION ),
					'type'  => 'accordion',
					'label' => 'Attributes',
				),
				static::FIELD_STYLE_CLASS        => array(
					'name'         => static::FIELD_STYLE_CLASS,
					'key'          => static::gen_field_key( static::FIELD_STYLE_CLASS ),
					'label'        => 'Style Classes',
					'type'         => 'repeater',
					'display'      => 'group',
					'button_label' => 'New Screen',
					'collapsed'    => static::FIELD_STYLE_CLASS_SCREEN,
					'sub_fields'   => static::_prepare_style_subfields(),
				),
				static::FIELD_CLASS              => array(
					'key'   => $class,
					'name'  => static::FIELD_CLASS,
					'label' => 'Class',
					'type'  => 'text',
				),
				static::FIELD_ATTRIBUTES         => array(
					'key'        => $attributes,
					'name'       => static::FIELD_ATTRIBUTES,
					'type'       => 'repeater',
					'layout'     => 'table',
					'label'      => 'Custom',
					'collapsed'  => $attr_key,
					'sub_fields' => array(
						static::FIELD_ATTR_KEY => array(
							'key'      => $attr_key,
							'name'     => static::FIELD_ATTR_KEY,
							'label'    => 'Key',
							'type'     => 'text',
							'required' => true,
						),
						static::FIELD_ATTR_VAL => array(
							'key'   => $attr_val,
							'name'  => static::FIELD_ATTR_VAL,
							'label' => 'Value',
							'type'  => 'text',
						),
					),
				),
				static::FIELD_ACCORDION . '_end' => array(
					'name'     => static::FIELD_ACCORDION . '_end',
					'key'      => static::gen_field_key( static::FIELD_ACCORDION ) . '_end',
					'type'     => 'accordion',
					'endpoint' => true,
				),
			),
		);
	}

	/** @inheritdoc */
	public static function clone( array $args = array() ): array {
		return parent::clone(
			array_merge(
				array(
					'label'       => 'Attributes',
					'name'        => 'attr',
					'display'     => 'seamless',
					'prefix_name' => true,
				),
				$args
			)
		);
	}

	/**
	 * @param array $value
	 * @param array $default_cls
	 *
	 * @return string
	 */
	public static function render_attrs_of( $value = null, array $default_cls = array() ): string {
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
	public static function get_attrs_of( $value = null, array $default_cls = array() ): array {
		if ( ! is_array( $value ) ) {
			$value = array();
		}

		# Style classes
		$style_cls = empty( $value[ static::FIELD_STYLE_CLASS ] )
			? array()
			: array_map(
				function ( $data ) {
					$screen = (string) @$data[ static::FIELD_STYLE_CLASS_SCREEN ];

					return implode(
						' ',
						array_map(
							function ( $data ) use ( $screen ) {
								$has_val = isset( $data['value'] ) && '' != $data['value'];
								$val     = $has_val ? $data['value'] : null;
								$neg_val = $has_val && $data['value'][0] == '-';
								$rule    = @$data['acf_fc_layout'];

								if ( $neg_val ) {
									  $val  = substr( $val, 1 );
									  $rule = '-' . $rule;
								}

								return ( empty( $screen ) ? '' : "{$screen}:" ) .
								$rule .
								( $has_val ? "-{$val}" : '' );
							},
							(array) @$data[ static::FIELD_STYLE_CLASS_DATA ]
						)
					);
				},
				$value[ static::FIELD_STYLE_CLASS ]
			);

		# Extra classes
		$extra_cls = empty( $value[ static::FIELD_CLASS ] )
			? array()
			: explode( ' ', $value[ static::FIELD_CLASS ] );

		# Attributes
		$attributes      = array();
		$attributes_data = empty( $value[ static::FIELD_ATTRIBUTES ] )
			? array()
			: $value[ static::FIELD_ATTRIBUTES ];
		foreach ( $attributes_data as $attribute ) {
			$attributes[ $attribute[ self::FIELD_ATTR_KEY ] ] = $attribute[ self::FIELD_ATTR_VAL ];
		}

		$attributes['class'] = implode( ' ', array_merge( $default_cls, $extra_cls, $style_cls ) );

		return $attributes;
	}

	/**
	 * @return array
	 */
	protected static function _prepare_style_subfields(): array {
		$option_layouts = array();
		foreach ( Config::collect_options() as $key => $choices ) {
			$layout_key                    = static::gen_field_key( static::FIELD_STYLE_CLASS . '-option-' . $key );
			$option_layouts[ $layout_key ] = array(
				'key'        => $layout_key,
				'name'       => $key,
				'label'      => $key,
				'sub_fields' => array(
					'value' => array(
						'key'     => $layout_key . '-value',
						'name'    => 'value',
						'type'    => 'select',
						'choices' => $choices,
					),
				),
			);
		}

		return array(
			static::FIELD_STYLE_CLASS_SCREEN => array(
				'key'     => static::gen_field_key( static::FIELD_STYLE_CLASS_SCREEN ),
				'name'    => static::FIELD_STYLE_CLASS_SCREEN,
				'label'   => 'Screen',
				'type'    => 'select',
				'choices' => Config::collect_screens(),
			),
			static::FIELD_STYLE_CLASS_DATA   => array(
				'key'          => static::gen_field_key( static::FIELD_STYLE_CLASS_DATA ),
				'name'         => static::FIELD_STYLE_CLASS_DATA,
				'label'        => 'Values',
				'type'         => 'flexible_content',
				'display'      => 'group',
				'layouts'      => $option_layouts,
				'button_label' => 'Add New',
			),
		);
	}

	/**
	 * @params {Assoc} $attributes - e.g.: [ 'class' => [ 'someclass' ], 'aria-label' => 'some label' ]
	 */
	public static function attrs_from_array( $attributes = array() ): array {
		$dom_attr = array(
			static::FIELD_ATTRIBUTES => array(),
		);

		foreach ( $attributes as $key => $value ) {
			$dom_attr[ static::FIELD_ATTRIBUTES ][] = (
				array(
					DOM_Attr::FIELD_ATTR_KEY => $key,
					DOM_Attr::FIELD_ATTR_VAL => $value,
				)
			);
		}

		return $dom_attr;
	}
}
