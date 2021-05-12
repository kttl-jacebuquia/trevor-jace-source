<?php namespace TrevorWP\Theme\ACF\Field_Group;

class HTML_Elem extends A_Field_Group implements I_Block {
	const FIELD_NAME = 'name';
	const FIELD_ATTR = 'attr';

	/** @inheritDoc */
	public static function render_block( $block, $content = '', $is_preview = false, $post_id = 0 ): void {
		$elem = wp_filter_nohtml_kses( str_replace( ' ', '', (string) static::get_val( static::FIELD_NAME ) ) );
		if ( empty( $elem ) ) {
			$elem = 'div';
		}
		?>
		<<?php echo $elem; ?> <?php echo DOM_Attr::render_attrs_of( static::get_val( static::FIELD_ATTR ) ); ?>>
		<InnerBlocks/>
		</<?php echo $elem; ?>>
		<?php
	}

	/** @inheritdoc */
	public static function prepare_register_args(): array {
		$name = static::gen_field_key( static::FIELD_NAME );
		$attr = static::gen_field_key( static::FIELD_ATTR );

		return array(
			'title'  => 'HTML Element',
			'fields' => array(
				static::FIELD_NAME => array(
					'key'         => $name,
					'name'        => static::FIELD_NAME,
					'required'    => true,
					'type'        => 'text',
					'label'       => 'Element Name',
					'placeholder' => 'div',
				),
				static::FIELD_ATTR => DOM_Attr::clone(
					array(
						'key'         => $attr,
						'name'        => static::FIELD_ATTR,
						'prefix_name' => false,
					)
				),
			),
		);
	}

	/** @inheritDoc */
	public static function get_block_args(): array {
		return array_merge(
			parent::get_block_args(),
			array(
				'name'       => static::get_key(),
				'title'      => 'HTML Element',
				'post_types' => array( 'page' ),
				'supports'   => array(
					'jsx' => true,
				),
			)
		);
	}
}
