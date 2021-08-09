<?php namespace TrevorWP\Theme\ACF\Field_Group;

use TrevorWP\CPT;
use TrevorWP\Theme\ACF\Util\Field_Val_Getter;
use TrevorWP\Theme\ACF\Field;

class Button extends Advanced_Link implements I_Renderable {
	const FIELD_TYPE         = 'type';
	const FIELD_BUTTON_ATTR  = 'button_attr';
	const FIELD_LABEL_ATTR   = 'label_attr';
	const FIELD_TEXT_COLOR   = 'text_color';
	const FIELD_BG_COLOR     = 'bg_color';
	const FIELD_BORDER_STYLE = 'border_style';


	/** @inheritDoc */
	public static function prepare_register_args(): array {
		$type         = static::gen_field_key( static::FIELD_TYPE );
		$button_attr  = static::gen_field_key( static::FIELD_BUTTON_ATTR );
		$label_attr   = static::gen_field_key( static::FIELD_LABEL_ATTR );
		$text_color   = static::gen_field_key( static::FIELD_TEXT_COLOR );
		$bg_color     = static::gen_field_key( static::FIELD_BG_COLOR );
		$border_style = static::gen_field_key( static::FIELD_BORDER_STYLE );

		return array(
			'title'  => 'Page Button',
			'fields' => array_merge(
				static::_gen_tab_field( 'General' ),
				array(
					static::FIELD_TYPE => array(
						'key'           => $type,
						'name'          => static::FIELD_TYPE,
						'label'         => 'Type',
						'type'          => 'select',
						'required'      => true,
						'choices'       => array(
							'primary'   => 'Primary',
							'secondary' => 'Secondary',
							'link'      => 'Link',
							'custom'    => 'Custom',
						),
						'default_value' => 'primary',
						'return_format' => 'value',
					),
				),
				parent::_get_fields(),
				static::_gen_tab_field(
					'Custom Styling',
					array(
						'conditional_logic' => array(
							array(
								array(
									'field'    => $type,
									'operator' => '==',
									'value'    => 'custom',
								),
							),
						),
					),
				),
				array(
					static::FIELD_TEXT_COLOR   => Field\Color::gen_args(
						$text_color,
						static::FIELD_TEXT_COLOR,
						array(
							'label'   => 'Text Color',
							'default' => 'teal-dark',
							'wrapper' => array(
								'width' => '50%',
							),
						),
					),
					static::FIELD_BG_COLOR     => Field\Color::gen_args(
						$bg_color,
						static::FIELD_BG_COLOR,
						array(
							'label'   => 'BG Color',
							'default' => 'white',
							'wrapper' => array(
								'width' => '50%',
							),
						),
					),
					static::FIELD_BORDER_STYLE => array(
						'key'           => $border_style,
						'name'          => static::FIELD_BORDER_STYLE,
						'label'         => 'Border Style',
						'type'          => 'button_group',
						'choices'       => array(
							'filled'   => 'Filled',
							'outlined' => 'Outlined',
						),
						'default_value' => 'filled',
					),
				),
			),
		);
	}

	/** @inheritdoc */
	public static function render( $post = false, array $data = null, array $options = array() ): ?string {
		$val       = new Field_Val_Getter( static::class, $post, $data );
		$btn_tag   = 'a';
		$btn_cls   = array_merge( array( 'page-btn' ), ( $options['btn_cls'] ?? array() ) );
		$label_cls = array_merge( array( 'page-btn-label' ), ( $options['label_cls'] ?? array() ) );

		$type       = $val->get( static::FIELD_TYPE );
		$btn_attr   = $val->get( static::FIELD_BUTTON_ATTR );
		$label_attr = $val->get( static::FIELD_LABEL_ATTR );

		if ( 'custom' === $type ) {
			$text_color   = $val->get( static::FIELD_TEXT_COLOR );
			$bg_color     = $val->get( static::FIELD_BG_COLOR );
			$border_style = $val->get( static::FIELD_BORDER_STYLE );
			$btn_cls[]    = 'text-' . $text_color;
			$btn_cls[]    = 'bg-' . $bg_color;
			if ( 'outlined' === $border_style ) {
				$btn_cls[] = "border-2 border-{$text_color}";
			}
		} else {
			$btn_cls[] = "page-btn-{$type}";
		}

		$id = uniqid( 'quiz-', true );

		if ( empty( $btn_attr[ DOM_Attr::FIELD_ATTRIBUTES ] ) ) {
			$btn_attr[ DOM_Attr::FIELD_ATTRIBUTES ] = array();
		}

		if ( 'link' === $type ) {
			$btn_cls[] = 'wave-underline';
		}

		$btn_attr[ DOM_Attr::FIELD_ATTRIBUTES ][] = array(
			DOM_Attr::FIELD_ATTR_KEY => 'id',
			DOM_Attr::FIELD_ATTR_VAL => $id,
		);

		$options = array(
			'tag'              => $btn_tag,
			'class'            => $btn_cls,
			'attributes'       => $btn_attr,
			'label_class'      => $label_cls,
			'label_attributes' => $label_attr,
		);

		return static::render_link( $options, $post, $data );
	}
}
