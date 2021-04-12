<?php namespace TrevorWP\Theme\ACF\Field_Group;


use TrevorWP\Theme\ACF\Util\Field_Val_Getter;

class Info_Boxes extends A_Field_Group implements I_Block, I_Renderable {
	/* Box Type */
	const BOX_TYPE_IMG = 'img';
	const BOX_TYPE_TEXT = 'text';
	const BOX_TYPE_BOTH = 'both';

	/* Break Behaviour */
	const BREAK_BEHAVIOUR_GRID_1_2_2 = 'grid-1-2-2';
	const BREAK_BEHAVIOUR_GRID_1_2_3 = 'grid-1-2-3';
	const BREAK_BEHAVIOUR_GRID_1_2_4 = 'grid-1-2-4';
	const BREAK_BEHAVIOUR_GRID_2_2_4 = 'grid-2-2-4';
	const BREAK_BEHAVIOUR_CAROUSEL = 'carousel';

	/* Fields */
	const FIELD_TYPE = 'box_type';
	const FIELD_BREAK = 'box_break';
	const FIELD_BOXES = 'boxes';
	const FIELD_WRAPPER_ATTR = 'wrapper_attr';
	const FIELD_BOX_IMG = 'box_img';
	const FIELD_BOX_IMG_ATTR = 'box_img_attr';
	const FIELD_BOX_TEXT = 'box_text';
	const FIELD_BOX_TEXT_ATTR = 'box_text_attr';
	const FIELD_BOX_DESC = 'box_desc';
	const FIELD_BOX_DESC_ATTR = 'box_desc_attr';
	const FIELD_BOX_INNER_ATTR = 'box_inner_attr';
	const FIELD_BOX_WRAPPER_ATTR = 'box_wrapper_attr';

	/** @inheritDoc */
	protected static function prepare_register_args(): array {
		$type             = static::gen_field_key( static::FIELD_TYPE );
		$break            = static::gen_field_key( static::FIELD_BREAK );
		$boxes            = static::gen_field_key( static::FIELD_BOXES );
		$box_img          = static::gen_field_key( static::FIELD_BOX_IMG );
		$box_img_attr     = static::gen_field_key( static::FIELD_BOX_IMG_ATTR );
		$box_text         = static::gen_field_key( static::FIELD_BOX_TEXT );
		$box_text_attr    = static::gen_field_key( static::FIELD_BOX_TEXT_ATTR );
		$box_desc         = static::gen_field_key( static::FIELD_BOX_DESC );
		$box_desc_attr    = static::gen_field_key( static::FIELD_BOX_DESC_ATTR );
		$box_wrapper_attr = static::gen_field_key( static::FIELD_BOX_WRAPPER_ATTR );
		$inner_attr       = static::gen_field_key( static::FIELD_BOX_INNER_ATTR );
		$wrapper_attr     = static::gen_field_key( static::FIELD_WRAPPER_ATTR );

		return [
				'title'  => 'Info Boxes',
				'fields' => array_merge(
						static::_gen_tab_field( 'General' ),
						[
								static::FIELD_TYPE  => [
										'key'           => $type,
										'name'          => static::FIELD_TYPE,
										'default_value' => static::BOX_TYPE_TEXT,
										'type'          => 'select',
										'choices'       => [
												static::BOX_TYPE_IMG  => 'Image',
												static::BOX_TYPE_TEXT => 'Text',
												static::BOX_TYPE_BOTH => 'Both',
										]
								],
								static::FIELD_BREAK => [
										'key'           => $break,
										'name'          => static::FIELD_BREAK,
										'default_value' => static::BREAK_BEHAVIOUR_CAROUSEL,
										'type'          => 'select',
										'choices'       => [
												static::BREAK_BEHAVIOUR_GRID_1_2_2 => '1-2-2',
												static::BREAK_BEHAVIOUR_GRID_1_2_3 => '1-2-3',
												static::BREAK_BEHAVIOUR_GRID_1_2_4 => '1-2-4',
												static::BREAK_BEHAVIOUR_GRID_2_2_4 => '2-2-4',
												static::BREAK_BEHAVIOUR_CAROUSEL   => 'Carousel',
										],
								],
						],
						static::_gen_tab_field( 'Boxes' ),
						[
								static::FIELD_BOXES => [
										'key'        => $boxes,
										'name'       => static::FIELD_BOXES,
										'label'      => 'Boxes',
										'type'       => 'repeater',
										'required'   => 1,
										'layout'     => 'row',
										'sub_fields' => array_merge(
												static::_gen_tab_field( 'General' ),
												[
														static::FIELD_BOX_IMG  => [
																'name'              => static::FIELD_BOX_IMG,
																'key'               => $box_img,
																'required'          => 1,
																'label'             => 'Image',
																'conditional_logic' => [
																		[],
																		[
																				[
																						'field'    => $type,
																						'operator' => '==',
																						'value'    => static::BOX_TYPE_IMG,
																				],
																				[
																						'field'    => $type,
																						'operator' => '==',
																						'value'    => static::BOX_TYPE_BOTH,
																				],
																		]
																],
														],
														static::FIELD_BOX_TEXT => [
																'name'              => static::FIELD_BOX_TEXT,
																'key'               => $box_text,
																'required'          => 1,
																'type'              => 'text',
																'label'             => 'Text',
																'conditional_logic' => [
																		[],
																		[
																				[
																						'field'    => $type,
																						'operator' => '==',
																						'value'    => static::BOX_TYPE_TEXT,
																				],
																				[
																						'field'    => $type,
																						'operator' => '==',
																						'value'    => static::BOX_TYPE_BOTH,
																				],
																		]
																],
														],
														static::FIELD_BOX_DESC => [
																'name'  => static::FIELD_BOX_DESC,
																'key'   => $box_desc,
																'type'  => 'textarea',
																'label' => 'Description',
														],
												],
										)
								],
						],
						static::_gen_tab_field( 'Box Attributes' ),
						[
								static::FIELD_BOX_WRAPPER_ATTR => DOM_Attr::clone( [
										'name'    => static::FIELD_BOX_WRAPPER_ATTR,
										'key'     => $box_wrapper_attr,
										'display' => 'group',
										'label'   => 'Wrapper',
								] ),
								static::FIELD_BOX_IMG_ATTR     => DOM_Attr::clone( [
										'name'    => static::FIELD_BOX_IMG_ATTR,
										'key'     => $box_img_attr,
										'display' => 'group',
										'label'   => 'Image',
								] ),
								static::FIELD_BOX_TEXT_ATTR    => DOM_Attr::clone( [
										'name'    => static::FIELD_BOX_TEXT_ATTR,
										'key'     => $box_text_attr,
										'display' => 'group',
										'label'   => 'Text',
								] ),
								static::FIELD_BOX_DESC_ATTR    => DOM_Attr::clone( [
										'name'    => static::FIELD_BOX_DESC_ATTR,
										'key'     => $box_desc_attr,
										'display' => 'group',
										'label'   => 'Description',
								] ),
						],
						static::_gen_tab_field( 'Inner' ),
						[
								static::FIELD_BOX_INNER_ATTR => DOM_Attr::clone( [
										'name' => static::FIELD_BOX_INNER_ATTR,
										'key'  => $inner_attr
								] ),
						],
						static::_gen_tab_field( 'Wrapper' ),
						[
								static::FIELD_WRAPPER_ATTR => DOM_Attr::clone( [
										'name' => static::FIELD_WRAPPER_ATTR,
										'key'  => $wrapper_attr
								] ),
						],
				)
		];
	}

	/** @inheritdoc */
	public static function render( $post = false, array $data = null, array $options = [] ): ?string {
		$val = new Field_Val_Getter( static::class, $post, $data );

		$cls_wrapper     = [
				'info-boxes',
				"box-type-{$val->get(static::FIELD_TYPE)}",
				"break-{$val->get(static::FIELD_BREAK)}",
		];
		$cls_inner       = [ 'info-boxes-container' ];
		$cls_box_wrapper = [ 'info-box', "type-{$val->get(static::FIELD_TYPE)}" ];
		$cls_box_img     = [ 'info-box-img' ];
		$cls_box_text    = [ 'info-box-text' ];
		$cls_box_desc    = [ 'info-box-desc' ];

		# Carousel
		if ( $is_carousel = $val->get( static::FIELD_BREAK ) == 'carousel' ) { // if carousel
			$cls_box_wrapper[] = 'swiper-slide';
			$cls_inner[]       = 'swiper-wrapper';
			$cls_wrapper[]     = 'swiper-container';
		} else {
			$cls_wrapper[] = 'break-grid';
		}

		$box_count = $val->get( static::FIELD_BOXES );
		$boxes     = [];
		for ( $box_idx = 0; $box_idx < $box_count; $box_idx ++ ) {
			$box = [];
			foreach (
					[
							static::FIELD_BOX_IMG,
							static::FIELD_BOX_TEXT,
							static::FIELD_BOX_DESC,
					] as $key
			) {
				$box[ $key ] = $val->get_sub( static::FIELD_BOXES, $box_idx, $key );
			}

			$boxes[] = $box;
		}

		ob_start(); ?>
		<div <?= DOM_Attr::render_attrs_of( static::get_val( static::FIELD_WRAPPER_ATTR ), $cls_wrapper ) ?>>
			<div <?= DOM_Attr::render_attrs_of( static::get_val( static::FIELD_BOX_INNER_ATTR ), $cls_inner ) ?>>
				<?php foreach ( $boxes as $box ) { ?>
					<div <?= DOM_Attr::render_attrs_of( @$box[ static::FIELD_BOX_WRAPPER_ATTR ], $cls_box_wrapper ) ?>>
						<div class="info-box-top">
							<div <?= DOM_Attr::render_attrs_of( static::get_val( static::FIELD_BOX_IMG_ATTR ), $cls_box_img ) ?>>
								<?php #todo:  Add image ?>
							</div>
							<div <?= DOM_Attr::render_attrs_of( static::get_val( static::FIELD_BOX_TEXT_ATTR ), $cls_box_text ) ?>>
								<?= $box[ static::FIELD_BOX_TEXT ] ?>
							</div>
						</div>
						<p <?= DOM_Attr::render_attrs_of( static::get_val( static::FIELD_BOX_DESC_ATTR ), $cls_box_desc ) ?>>
							<?= $box[ static::FIELD_BOX_DESC ] ?>
						</p>
					</div>
				<?php } ?>
			</div>
		</div>
		<?php return ob_get_clean();
	}

	/** @inheritDoc */
	public static function render_block( $block, $content = '', $is_preview = false, $post_id = 0 ): void {
		echo static::render( $post_id, (array) @$block['data'], compact( 'is_preview' ) );
	}

	/** @inheritdoc */
	public static function get_block_args(): array {
		return array_merge( parent::get_block_args(), [
				'name'       => static::get_key(),
				'title'      => 'Info Boxes',
				'post_types' => [ 'page' ],
		] );
	}
}
