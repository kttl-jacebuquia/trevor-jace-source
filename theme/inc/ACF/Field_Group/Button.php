<?php namespace TrevorWP\Theme\ACF\Field_Group;

use TrevorWP\CPT;
use TrevorWP\Theme\ACF\Util\Field_Val_Getter;

class Button extends Advanced_Link implements I_Renderable {
	const FIELD_TYPE        = 'type';
	const FIELD_BUTTON_ATTR = 'button_attr';
	const FIELD_LABEL_ATTR  = 'label_attr';

	/** @inheritDoc */
	public static function prepare_register_args(): array {
		$type        = static::gen_field_key( static::FIELD_TYPE );
		$button_attr = static::gen_field_key( static::FIELD_BUTTON_ATTR );
		$label_attr  = static::gen_field_key( static::FIELD_LABEL_ATTR );

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
						),
						'default_value' => 'primary',
						'return_format' => 'value',
					),
				),
				parent::_get_fields(),
				static::_gen_tab_field( 'Attributes' ),
				array(
					static::FIELD_BUTTON_ATTR => DOM_Attr::clone(
						array(
							'key'     => $button_attr,
							'name'    => static::FIELD_BUTTON_ATTR,
							'label'   => 'Button',
							'display' => 'group',
						)
					),
					static::FIELD_LABEL_ATTR  => DOM_Attr::clone(
						array(
							'key'     => $label_attr,
							'name'    => static::FIELD_LABEL_ATTR,
							'label'   => 'Label',
							'display' => 'group',
						)
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
		$btn_cls[]  = "page-btn-{$type}";
		$btn_attr   = $val->get( static::FIELD_BUTTON_ATTR );
		$label_attr = $val->get( static::FIELD_LABEL_ATTR );

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
