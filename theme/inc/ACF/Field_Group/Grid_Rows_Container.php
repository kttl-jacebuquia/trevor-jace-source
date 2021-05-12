<?php namespace TrevorWP\Theme\ACF\Field_Group;


class Grid_Rows_Container extends A_Field_Group implements I_Block {
	const FIELD_TYPE    = 'type';
	const FIELD_ATTR    = 'attr';
	const GRID_TYPE_5   = '5'; // 6/6 -> 4/8 -> 5/12
	const GRID_TYPE_5_6 = '5_6'; // 6/6 -> 4/8 -> 5/12 + 6/12

	/** @inheritDoc */
	protected static function prepare_register_args(): array {
		$type = static::gen_field_key( static::FIELD_TYPE );
		$attr = static::gen_field_key( static::FIELD_ATTR );

		return array(
			'title'  => 'Grid Rows Container',
			'fields' => array(
				static::FIELD_TYPE => array(
					'key'           => $type,
					'name'          => static::FIELD_TYPE,
					'label'         => 'Type',
					'type'          => 'select',
					'choices'       => array(
						static::GRID_TYPE_5   => '6/6 -> 4/8 -> 5/12',
						static::GRID_TYPE_5_6 => '6/6 -> 4/8 -> 5/12 + 6/12',
					),
					'default_value' => static::GRID_TYPE_5,
				),
				static::FIELD_ATTR => DOM_Attr::clone(
					array(
						'key'   => $attr,
						'name'  => static::FIELD_ATTR,
						'label' => 'Attributes',
					)
				),
			),
		);
	}

	/** @inheritDoc */
	public static function render_block( $block, $content = '', $is_preview = false, $post_id = 0 ): void {
		$type = static::get_val( static::FIELD_TYPE );

		$cls = array(
			'grid-rows',
			'mx-auto',
			"grid-rows-type-{$type}",
			'py-20 xl:py-24',
		);

		if ( $type == static::GRID_TYPE_5 ) {
			$cls[] = 'container';
		} elseif ( $type == static::GRID_TYPE_5_6 ) {
			$cls[] = 'md:container';
		}

		$allowed_blocks = array(
			'acf/' . Grid_Row::get_key(),
		);
		?>
		<div class="overflow-hidden">
			<div <?php echo DOM_Attr::render_attrs_of( static::get_val( static::FIELD_ATTR ), $cls ); ?>>
				<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>"/>
			</div>
		</div>
		<?php
	}

	/** @inheritDoc */
	public static function get_block_args(): array {
		return array_merge(
			parent::get_block_args(),
			array(
				'name'       => static::get_key(),
				'title'      => 'Grid Rows Container',
				'post_types' => array( 'page' ),
				'supports'   => array(
					'jsx' => true,
				),
			)
		);
	}
}
