<?php namespace TrevorWP\Theme\ACF\Field_Group;

use TrevorWP\Theme\ACF\Util\Field_Val_Getter;

class Info_Boxes extends A_Field_Group implements I_Block, I_Renderable, I_Pattern {
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
										'required'   => true,
										'layout'     => 'row',
										'sub_fields' => array_merge(
												static::_gen_tab_field( 'General' ),
												[
														static::FIELD_BOX_IMG  => [
																'name'              => static::FIELD_BOX_IMG,
																'key'               => $box_img,
																'required'          => true,
																'label'             => 'Image',
																'type'              => 'image',
																'return_format'     => 'array',
																'preview_size'      => 'medium',
																'library'           => 'all',
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
																'required'          => true,
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
		$type = $val->get( static::FIELD_TYPE );

		$cls_wrapper     = [
				'info-boxes',
				"box-type-{$type}",
				"break-{$val->get(static::FIELD_BREAK)}",
		];
		$cls_inner       = [ 'info-boxes-container row-gap-px40' ];
		$cls_box_wrapper = [ 'info-box', "type-{$type}" ];
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

		switch ( $type ) {
			// Possible other variants...
			default:
				$cls_box_text[] = 'font-bold text-px64 leading-px74 tracking-em_001';
				$cls_box_text[] = 'md:text-px60 md:leading-px70';
				$cls_box_text[] = 'xl:text-px70 xl:leading-px80';
				$cls_box_desc[] = 'mt-px10 text-px18 leading-px24 tracking-em_001';
				$cls_box_desc[] = 'md:mt-px14';
				$cls_box_desc[] = 'xl:text-px20 xl:leading-px26';
		}
		
		ob_start(); ?>
		<div <?= DOM_Attr::render_attrs_of( static::get_val( static::FIELD_WRAPPER_ATTR ), $cls_wrapper ) ?>>
			<div <?= DOM_Attr::render_attrs_of( static::get_val( static::FIELD_BOX_INNER_ATTR ), $cls_inner ) ?>>
				<?php foreach ( $boxes as $box ) { ?>
					<div <?= DOM_Attr::render_attrs_of( @$box[ static::FIELD_BOX_WRAPPER_ATTR ], $cls_box_wrapper ) ?>>
						<div class="info-box-top">
							<?php if ( $type != static::BOX_TYPE_TEXT ): ?>
								<div <?= DOM_Attr::render_attrs_of( static::get_val( static::FIELD_BOX_IMG_ATTR ), $cls_box_img ) ?>>
									<?= wp_get_attachment_image( $box[ static::FIELD_BOX_IMG ], 'medium' ) ?>
								</div>
							<?php endif; ?>
							<?php if ( $type != static::BOX_TYPE_IMG ): ?>
								<div <?= DOM_Attr::render_attrs_of( static::get_val( static::FIELD_BOX_TEXT_ATTR ), $cls_box_text ) ?>>
									<?= $box[ static::FIELD_BOX_TEXT ] ?>
								</div>
							<?php endif; ?>
						</div>
						<p <?= DOM_Attr::render_attrs_of( static::get_val( static::FIELD_BOX_DESC_ATTR ), $cls_box_desc ) ?>>
							<?= $box[ static::FIELD_BOX_DESC ] ?>
						</p>
					</div>
				<?php } ?>
			</div>
			<?php if ( $is_carousel ) { ?>
				<div class="swiper-pagination info-box-pagination"></div>
			<?php } ?>
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

	/** @inheritdoc */
	public static function get_patterns(): array {
		$key = static::get_key();

		//todo: delete ?
		$args = json_decode( '{"id":"block_60732cf1c501e","name":"acf/trvr-info-boxes","data":{"box_type":"text","_box_type":"trvr-info-boxes:f:box_type","box_break":"carousel","_box_break":"trvr-info-boxes:f:box_break","boxes_0_box_text":"250","_boxes_0_box_text":"trvr-info-boxes:f:box_text","boxes_0_box_desc":"Volunteers have been  trained within the last year.","_boxes_0_box_desc":"trvr-info-boxes:f:box_desc","boxes_1_box_text":"14,000","_boxes_1_box_text":"trvr-info-boxes:f:box_text","boxes_1_box_desc":"Youth and youth-serving  reached through our Care, ALLY, and Lifeguard workshops.","_boxes_1_box_desc":"trvr-info-boxes:f:box_desc","boxes_2_box_text":"1st","_boxes_2_box_text":"trvr-info-boxes:f:box_text","boxes_2_box_desc":"In the nation to launch a partnership with the NY Department of Education.","_boxes_2_box_desc":"trvr-info-boxes:f:box_desc","boxes":3,"_boxes":"trvr-info-boxes:f:boxes","box_wrapper_attr_style_class":"","_box_wrapper_attr_style_class":"trvr-dom-attr:f:style_class","box_wrapper_attr_class":"","_box_wrapper_attr_class":"trvr-dom-attr:f:class","box_wrapper_attr_attributes":"","_box_wrapper_attr_attributes":"trvr-dom-attr:f:attributes","box_wrapper_attr":"","_box_wrapper_attr":"trvr-info-boxes:f:box_wrapper_attr","box_img_attr_style_class":"","_box_img_attr_style_class":"trvr-dom-attr:f:style_class","box_img_attr_class":"","_box_img_attr_class":"trvr-dom-attr:f:class","box_img_attr_attributes":"","_box_img_attr_attributes":"trvr-dom-attr:f:attributes","box_img_attr":"","_box_img_attr":"trvr-info-boxes:f:box_img_attr","box_text_attr_style_class_0_style_class_screen":"","_box_text_attr_style_class_0_style_class_screen":"trvr-dom-attr:f:style_class_screen","box_text_attr_style_class_0_style_class_data_0_value":"bold","_box_text_attr_style_class_0_style_class_data_0_value":"trvr-dom-attr:f:style_class-option-font-value","box_text_attr_style_class_0_style_class_data_1_value":"px80","_box_text_attr_style_class_0_style_class_data_1_value":"trvr-dom-attr:f:style_class-option-text-value","box_text_attr_style_class_0_style_class_data_2_value":"px92","_box_text_attr_style_class_0_style_class_data_2_value":"trvr-dom-attr:f:style_class-option-leading-value","box_text_attr_style_class_0_style_class_data_3_value":"em001","_box_text_attr_style_class_0_style_class_data_3_value":"trvr-dom-attr:f:style_class-option-tracking-value","box_text_attr_style_class_0_style_class_data":["font","text","leading","tracking"],"_box_text_attr_style_class_0_style_class_data":"trvr-dom-attr:f:style_class_data","box_text_attr_style_class":1,"_box_text_attr_style_class":"trvr-dom-attr:f:style_class","box_text_attr_class":"","_box_text_attr_class":"trvr-dom-attr:f:class","box_text_attr_attributes":"","_box_text_attr_attributes":"trvr-dom-attr:f:attributes","box_text_attr":"","_box_text_attr":"trvr-info-boxes:f:box_text_attr","box_desc_attr_style_class_0_style_class_screen":"","_box_desc_attr_style_class_0_style_class_screen":"trvr-dom-attr:f:style_class_screen","box_desc_attr_style_class_0_style_class_data_0_value":"normal","_box_desc_attr_style_class_0_style_class_data_0_value":"trvr-dom-attr:f:style_class-option-font-value","box_desc_attr_style_class_0_style_class_data_1_value":"px20","_box_desc_attr_style_class_0_style_class_data_1_value":"trvr-dom-attr:f:style_class-option-text-value","box_desc_attr_style_class_0_style_class_data_2_value":"px26","_box_desc_attr_style_class_0_style_class_data_2_value":"trvr-dom-attr:f:style_class-option-leading-value","box_desc_attr_style_class_0_style_class_data":["font","text","leading"],"_box_desc_attr_style_class_0_style_class_data":"trvr-dom-attr:f:style_class_data","box_desc_attr_style_class_1_style_class_screen":"xl","_box_desc_attr_style_class_1_style_class_screen":"trvr-dom-attr:f:style_class_screen","box_desc_attr_style_class_1_style_class_data_0_value":"px22","_box_desc_attr_style_class_1_style_class_data_0_value":"trvr-dom-attr:f:style_class-option-text-value","box_desc_attr_style_class_1_style_class_data_1_value":"px28","_box_desc_attr_style_class_1_style_class_data_1_value":"trvr-dom-attr:f:style_class-option-leading-value","box_desc_attr_style_class_1_style_class_data_2_value":"em_001","_box_desc_attr_style_class_1_style_class_data_2_value":"trvr-dom-attr:f:style_class-option-tracking-value","box_desc_attr_style_class_1_style_class_data":["text","leading","tracking"],"_box_desc_attr_style_class_1_style_class_data":"trvr-dom-attr:f:style_class_data","box_desc_attr_style_class":2,"_box_desc_attr_style_class":"trvr-dom-attr:f:style_class","box_desc_attr_class":"","_box_desc_attr_class":"trvr-dom-attr:f:class","box_desc_attr_attributes":"","_box_desc_attr_attributes":"trvr-dom-attr:f:attributes","box_desc_attr":"","_box_desc_attr":"trvr-info-boxes:f:box_desc_attr","box_inner_attr_style_class":"","_box_inner_attr_style_class":"trvr-dom-attr:f:style_class","box_inner_attr_class":"","_box_inner_attr_class":"trvr-dom-attr:f:class","box_inner_attr_attributes":"","_box_inner_attr_attributes":"trvr-dom-attr:f:attributes","box_inner_attr":"","_box_inner_attr":"trvr-info-boxes:f:box_inner_attr","wrapper_attr_style_class":"","_wrapper_attr_style_class":"trvr-dom-attr:f:style_class","wrapper_attr_class":"","_wrapper_attr_class":"trvr-dom-attr:f:class","wrapper_attr_attributes":"","_wrapper_attr_attributes":"trvr-dom-attr:f:attributes","wrapper_attr":"","_wrapper_attr":"trvr-info-boxes:f:wrapper_attr"},"align":"","mode":"edit"}', true );

		return [
				'public_education' => [
						'title'       => 'public education',
						'description' => '',
						'content'     => "<!-- wp:acf/{$key} " . json_encode( $args ) . " /-->"
				],
		];
	}
}
