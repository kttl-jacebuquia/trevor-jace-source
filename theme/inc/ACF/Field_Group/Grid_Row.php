<?php namespace TrevorWP\Theme\ACF\Field_Group;

class Grid_Row extends A_Field_Group implements I_Block {
	const FIELD_IMAGE = 'img';
	const FIELD_IMAGE_STYLE = 'img_style';
	const FIELD_IMAGE_ATTR = 'img_attr';
	const FIELD_ATTR = 'attr';
	const FIELD_CONTENT_ATTR = 'content_attr';

	const IMG_TYPE_NORMAL = 'normal';
	const IMG_TYPE_XL_EXPAND = 'xl_expand';

	/** @inheritDoc */
	protected static function prepare_register_args(): array {
		$img          = static::gen_field_key( static::FIELD_IMAGE );
		$img_style    = static::gen_field_key( static::FIELD_IMAGE_STYLE );
		$img_attr     = static::gen_field_key( static::FIELD_IMAGE_ATTR );
		$attr         = static::gen_field_key( static::FIELD_ATTR );
		$content_attr = static::gen_field_key( static::FIELD_CONTENT_ATTR );

		return [
				'title'  => 'Grid Row',
				'fields' => [
						static::FIELD_IMAGE        => [
								'key'           => $img,
								'name'          => static::FIELD_IMAGE,
								'label'         => 'Image',
								'type'          => 'image',
								'return_format' => 'array',
								'preview_size'  => 'medium',
								'library'       => 'all',
						],
						static::FIELD_IMAGE_STYLE  => [
								'key'           => $img_style,
								'name'          => static::FIELD_IMAGE_STYLE,
								'label'         => 'Image Style',
								'type'          => 'select',
								'default_value' => static::IMG_TYPE_NORMAL,
								'choices'       => [
										static::IMG_TYPE_NORMAL    => 'Normal',
										static::IMG_TYPE_XL_EXPAND => 'XL: Expand',
								],
						],
						static::FIELD_IMAGE_ATTR   => DOM_Attr::clone( [
								'key'     => $img_attr,
								'name'    => static::FIELD_IMAGE_ATTR,
								'label'   => 'Image',
								'display' => 'group',
						] ),
						static::FIELD_ATTR         => DOM_Attr::clone( [
								'key'     => $attr,
								'name'    => static::FIELD_ATTR,
								'label'   => 'Wrapper',
								'display' => 'group',
						] ),
						static::FIELD_CONTENT_ATTR => DOM_Attr::clone( [
								'key'     => $content_attr,
								'name'    => static::FIELD_CONTENT_ATTR,
								'label'   => 'Content',
								'display' => 'group',
						] ),
				],
		];
	}

	/** @inheritDoc */
	public static function render_block( $block, $content = '', $is_preview = false, $post_id = 0 ): void {
		$img_id = static::get_val( static::FIELD_IMAGE );
		if ( ! empty( $img_id ) ) {
			$img_id = $img_id['id'];
		}

		$cls_wrap         = [
				'grid-row',
				'grid',
				'gap-7',
		];
		$cls_img_wrap     = [
				'grid-row-img-wrap',
				'w-full',
		];
		$cls_img          = [
				'grid-row-img',
				'h-full w-full object-cover rounded-px10 xl:rounded-l-none',
		];
		$cls_content_wrap = [
				'grid-row-content',
				'flex flex-col justify-center items-center'
		];

		$img_style = static::get_val( static::FIELD_IMAGE_STYLE ) ?: static::IMG_TYPE_NORMAL;
		if ( $img_style == static::IMG_TYPE_XL_EXPAND ) {
			$cls_wrap[] = 'img-style-expand-xl';
			$cls_img[]  = 'rounded-l-none rounded-r-none md:rounded-px10';
		}

		?>
		<div <?= DOM_Attr::render_attrs_of( static::get_val( static::FIELD_ATTR ), $cls_wrap ) ?>>
			<div <?= DOM_Attr::render_attrs_of( static::get_val( static::FIELD_IMAGE_ATTR ), $cls_img_wrap ) ?>>
				<?= $img_id ? wp_get_attachment_image( $img_id, 'large', false, [ 'class' => implode( ' ', $cls_img ) ] ) : '' ?>
			</div>
			<div <?= DOM_Attr::render_attrs_of( static::get_val( static::FIELD_CONTENT_ATTR ), $cls_content_wrap ) ?>>
				<InnerBlocks/>
			</div>
		</div>
		<?php
	}

	/** @inheritDoc */
	public static function get_block_args(): array {
		return array_merge( parent::get_block_args(), [
				'name'       => static::get_key(),
				'title'      => 'Grid Row',
				'post_types' => [ 'page' ],
				'parent'     => [
						'acf/' . Grid_Rows_Container::get_key(),
				],
				'supports'   => [
						'jsx' => true,
				],
		] );
	}
}
