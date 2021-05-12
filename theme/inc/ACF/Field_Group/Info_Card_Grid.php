<?php namespace TrevorWP\Theme\ACF\Field_Group;


class Info_Card_Grid extends A_Field_Group implements I_Block {
	const FIELD_ATTR = 'attr';

	/** @inheritdoc */
	public static function prepare_register_args(): array {
		return array(
			'title'  => 'Info Card Grid', // Required,
			'fields' => static::_get_fields(),
		);
	}

	/** @inheritdoc */
	protected static function _get_fields(): array {
		return array(
			static::FIELD_ATTR => DOM_Attr::clone(
				array(
					'name'    => static::FIELD_ATTR,
					'key'     => static::gen_field_key( static::FIELD_ATTR ),
					'label'   => 'Attributes',
					'display' => 'seamless',
				)
			),
		);
	}

	/** @inheritDoc */
	public static function get_block_args(): array {
		return array_merge(
			parent::get_block_args(),
			array(
				'name'       => static::get_key(),
				'title'      => 'Info Card Grid',
				'post_types' => array( 'page' ),
				'supports'   => array(
					'jsx' => true,
				),
			)
		);
	}

	/** @inheritDoc */
	public static function render_block( $block, $content = '', $is_preview = false, $post_id = 0 ): void {
		$cls            = array( 'info-card-grid' );
		$allowed_blocks = array(
			'acf/' . Info_Card::get_key(),
		);
		?>
		<div <?php echo DOM_Attr::render_attrs_of( static::get_val( static::FIELD_ATTR ), $cls ); ?>>
			<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>"/>
		</div>
		<?php
	}
}
