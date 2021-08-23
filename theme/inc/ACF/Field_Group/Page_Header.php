<?php namespace TrevorWP\Theme\ACF\Field_Group;

use TrevorWP\Exception;
use TrevorWP\Theme\ACF\Util\Field_Val_Getter;
use TrevorWP\Theme\Helper;
use TrevorWP\Theme\ACF\Field;

class Page_Header extends A_Basic_Section implements I_Renderable {
	const FIELD_TYPE              = 'header_type';
	const FIELD_TITLE_TOP         = 'title_top';
	const FIELD_TITLE_TOP_ATTR    = 'title_top_attr';
	const FIELD_CAROUSEL          = 'carousel';
	const FIELD_IMAGE             = 'image';
	const FIELD_BG_CLR            = 'bg_clr';
	const FIELD_TEXT_CLR          = 'text_clr';
	const FIELD_IMAGE_ENTRIES     = 'image_entries';
	const FIELD_IMAGE_ENTRY_IMAGE = 'image_entry_image';
	const FIELD_VIDEO             = 'video';
	const FIELD_MEDIA_TYPE        = 'media_type';
	const FIELD_MEMBERS_COUNT     = 'members_count';
	const FIELD_BOTTOM_TEXT       = 'bottom_text';
	const FIELD_CONTENT_ALIGNMENT = 'content_alignment';
	const FIELD_CALL_NUMBER       = 'call_number';
	const FIELD_SMS_NUMBER        = 'sms_number';

	/** @inheritdoc */
	public static function _get_fields(): array {
		$type              = static::gen_field_key( static::FIELD_TYPE );
		$title_top         = static::gen_field_key( static::FIELD_TITLE_TOP );
		$title_top_attr    = static::gen_field_key( static::FIELD_TITLE_TOP_ATTR );
		$carousel          = static::gen_field_key( static::FIELD_CAROUSEL );
		$image             = static::gen_field_key( static::FIELD_IMAGE );
		$text_clr          = static::gen_field_key( static::FIELD_TEXT_CLR );
		$bg_clr            = static::gen_field_key( static::FIELD_BG_CLR );
		$image_entries     = static::gen_field_key( static::FIELD_IMAGE_ENTRIES );
		$image_entry_image = static::gen_field_key( static::FIELD_IMAGE_ENTRY_IMAGE );
		$video             = static::gen_field_key( static::FIELD_VIDEO );
		$media_type        = static::gen_field_key( static::FIELD_MEDIA_TYPE );
		$members_count     = static::gen_field_key( static::FIELD_MEMBERS_COUNT );
		$bottom_text       = static::gen_field_key( static::FIELD_BOTTOM_TEXT );
		$content_alignment = static::gen_field_key( static::FIELD_CONTENT_ALIGNMENT );
		$call_number       = static::gen_field_key( static::FIELD_CALL_NUMBER );
		$sms_number        = static::gen_field_key( static::FIELD_SMS_NUMBER );

		$return = array_merge(
			static::_gen_tab_field( 'General' ),
			array(
				static::FIELD_TYPE => array(
					'key'           => $type,
					'name'          => static::FIELD_TYPE,
					'label'         => 'Type',
					'type'          => 'select',
					'required'      => true,
					'default_value' => 'text',
					'choices'       => array(
						'text'                    => 'Colorblock + Text',
						'horizontal'              => 'Colorblock + Image + Text',
						'multi_image_text'        => 'Multi-image + Text',
						'img_bg'                  => 'Full Bleed Image/Video + Text',
						'split_img'               => 'Text + Image',
						'split_carousel'          => 'Text + Carousel',
						'support_trevorspace'     => 'Support Trevorspace',
						'support_crisis_services' => 'Support Crisis Services',
					),
				),
			),
			static::_gen_tab_field(
				'Content Alignment',
				array(
					'conditional_logic' => array(
						array(
							array(
								'field'    => $type,
								'operator' => '==',
								'value'    => 'img_bg',
							),
						),
					),
				)
			),
			array(
				static::FIELD_CONTENT_ALIGNMENT => array(
					'key'               => $content_alignment,
					'name'              => static::FIELD_CONTENT_ALIGNMENT,
					'label'             => 'Alignment',
					'instructions'      => '(Desktop only)',
					'type'              => 'radio',
					'choices'           => array(
						'left'   => 'Left',
						'center' => 'Center',
						'right'  => 'Right',
					),
					'allow_null'        => 0,
					'other_choice'      => 0,
					'default_value'     => 'center',
					'layout'            => 'horizontal',
					'return_format'     => 'value',
					'save_other_choice' => 0,
				),
			),
			static::_gen_tab_field(
				'Support TrevoSpace',
				array(
					'conditional_logic' => array(
						array(
							array(
								'field'    => $type,
								'operator' => '==',
								'value'    => 'support_trevorspace',
							),
						),
					),
				)
			),
			array(
				static::FIELD_MEMBERS_COUNT => array(
					'key'               => $members_count,
					'name'              => static::FIELD_MEMBERS_COUNT,
					'label'             => 'Members Count',
					'type'              => 'number',
					'required'          => 1,
					'min'               => 0,
					'step'              => 1,
					'conditional_logic' => array(
						array(
							array(
								'field'    => $type,
								'operator' => '==',
								'value'    => 'support_trevorspace',
							),
						),
					),
				),
				static::FIELD_BOTTOM_TEXT   => array(
					'key'               => $bottom_text,
					'name'              => static::FIELD_BOTTOM_TEXT,
					'label'             => 'Members Login Text',
					'type'              => 'wysiwyg',
					'tabs'              => 'visual',
					'toolbar'           => 'basic',
					'media_upload'      => 0,
					'required'          => 1,
					'conditional_logic' => array(
						array(
							array(
								'field'    => $type,
								'operator' => '==',
								'value'    => 'support_trevorspace',
							),
						),
					),
				),
			),
			static::_gen_tab_field(
				'Title Top',
				array(
					'conditional_logic' => array(
						array(
							array(
								'field'    => $type,
								'operator' => '!=',
								'value'    => 'multi_image_text',
							),
							array(
								'field'    => $type,
								'operator' => '!=',
								'value'    => 'support_trevorspace',
							),
							array(
								'field'    => $type,
								'operator' => '!=',
								'value'    => 'support_crisis_services',
							),
						),
					),
				)
			),
			array(
				static::FIELD_TITLE_TOP      => array(
					'key'               => $title_top,
					'name'              => static::FIELD_TITLE_TOP,
					'label'             => 'Title Top',
					'type'              => 'text',
					'conditional_logic' => array(
						array(
							array(
								'field'    => $type,
								'operator' => '!=',
								'value'    => 'multi_image_text',
							),
						),
					),
				),
				static::FIELD_TITLE_TOP_ATTR => DOM_Attr::clone(
					array(
						'key'               => $title_top_attr,
						'name'              => static::FIELD_TITLE_TOP_ATTR,
						'conditional_logic' => array(
							array(
								array(
									'field'    => $title_top,
									'operator' => '!=empty',
								),
							),
						),
					)
				),
			),
			static::_get_parent_fields(),
			static::_gen_tab_field(
				'Image/Video',
				array(
					'conditional_logic' => array(
						array(
							array(
								'field'    => $type,
								'operator' => '!=',
								'value'    => 'text',
							),
							array(
								'field'    => $type,
								'operator' => '!=',
								'value'    => 'support_trevorspace',
							),
							array(
								'field'    => $type,
								'operator' => '!=',
								'value'    => 'support_crisis_services',
							),
						),
					),
				)
			),
			array(
				static::FIELD_MEDIA_TYPE    => array(
					'key'               => $media_type,
					'name'              => static::FIELD_MEDIA_TYPE,
					'label'             => 'Media Type',
					'type'              => 'button_group',
					'choices'           => array(
						'image' => 'Image',
						'video' => 'Video',
					),
					'default_value'     => 'image',
					'conditional_logic' => array(
						array(
							array(
								'field'    => $type,
								'operator' => '==',
								'value'    => 'img_bg',
							),
						),
					),
				),
				static::FIELD_IMAGE         => array(
					'key'               => $image,
					'name'              => static::FIELD_IMAGE,
					'label'             => 'Image',
					'type'              => 'image',
					'conditional_logic' => array(
						array(
							array(
								'field'    => $type,
								'operator' => '==',
								'value'    => 'horizontal',
							),
						),
						array(
							array(
								'field'    => $type,
								'operator' => '==',
								'value'    => 'split_img',
							),
						),
						array(
							array(
								'field'    => $type,
								'operator' => '==',
								'value'    => 'img_bg',
							),
							array(
								'field'    => $media_type,
								'operator' => '==',
								'value'    => 'image',
							),
						),
					),
				),
				static::FIELD_VIDEO         => array(
					'key'               => $video,
					'name'              => static::FIELD_VIDEO,
					'label'             => 'Video',
					'type'              => 'file',
					'return_format'     => 'array',
					'library'           => 'all',
					'mime_types'        => 'MP4',
					'conditional_logic' => array(
						array(
							array(
								'field'    => $type,
								'operator' => '==',
								'value'    => 'img_bg',
							),
							array(
								'field'    => $media_type,
								'operator' => '==',
								'value'    => 'video',
							),
						),
					),
				),
				static::FIELD_IMAGE_ENTRIES => array(
					'key'               => $image_entries,
					'name'              => static::FIELD_IMAGE_ENTRIES,
					'label'             => 'Image Entries',
					'type'              => 'gallery',
					'layout'            => 'block',
					'return_format'     => 'array',
					'preview_size'      => 'medium',
					'insert'            => 'append',
					'library'           => 'all',
					'min'               => 6,
					'max'               => 6,
					'conditional_logic' => array(
						array(
							array(
								'field'    => $type,
								'operator' => '==',
								'value'    => 'multi_image_text',
							),
						),
					),
				),
				static::FIELD_CAROUSEL      => Carousel_Data::clone(
					array(
						'key'               => $carousel,
						'name'              => static::FIELD_CAROUSEL,
						'label'             => 'Carousel',
						'prefix_label'      => true,
						'display'           => 'group',
						'layout'            => 'row',
						'conditional_logic' => array(
							array(
								array(
									'field'    => $type,
									'operator' => '==',
									'value'    => 'split_carousel',
								),
							),
						),
					)
				),
			),
			static::_gen_tab_field(
				'Styling',
				array(
					'conditional_logic' => array(
						array(
							array(
								'field'    => $type,
								'operator' => '!=',
								'value'    => 'support_crisis_services',
							),
						),
					),
				)
			),
			array(
				static::FIELD_TEXT_CLR => Field\Color::gen_args(
					$text_clr,
					static::FIELD_TEXT_CLR,
					array(
						'label'   => 'Text Color',
						'default' => 'teal-dark',
						'wrapper' => array(
							'width' => '50%',
						),
					),
				),
				static::FIELD_BG_CLR   => Field\Color::gen_args(
					$bg_clr,
					static::FIELD_BG_CLR,
					array(
						'label'   => 'BG Color',
						'default' => 'white',
						'wrapper' => array(
							'width' => '50%',
						),
					),
				),
			),
			static::_gen_tab_field(
				'Contacts',
				array(
					'conditional_logic' => array(
						array(
							array(
								'field'    => $type,
								'operator' => '==',
								'value'    => 'support_crisis_services',
							),
						),
					),
				),
			),
			static::_gen_conditional_fields(
				array(
					'field'    => $type,
					'operator' => '==',
					'value'    => 'support_crisis_services',
				),
				array(
					static::FIELD_CALL_NUMBER => array(
						'key'     => $call_number,
						'name'    => static::FIELD_CALL_NUMBER,
						'label'   => 'Call Number',
						'type'    => 'text',
						'wrapper' => array(
							'width' => '50',
						),
					),
					static::FIELD_SMS_NUMBER => array(
						'key'     => $sms_number,
						'name'    => static::FIELD_SMS_NUMBER,
						'label'   => 'SMS Number',
						'type'    => 'text',
						'wrapper' => array(
							'width' => '50',
						),
					),
				)
			)
		);

		$return[ static::FIELD_TITLE ]['instructions'] = 'Leave empty to use Post`s title.';

		return $return;
	}

	/** @inheritdoc */
	public static function _get_parent_fields(): array {
		$type_field_key = static::gen_field_key( static::FIELD_TYPE );
		$parent_fields  = parent::_get_fields();

		// Parent fields override
		foreach ( $parent_fields as $key => &$field ) {
			switch ( $key ) {
				case 'tab_inner':
				case 'tab_wrapper':
					$field['conditional_logic'] = array(
						array(
							'field'    => $type_field_key,
							'operator' => '!=',
							'value'    => 'support_crisis_services',
						),
						array(
							'field'    => $type_field_key,
							'operator' => '!=',
							'value'    => 'support_trevorspace',
						),
						array(
							'field'    => $type_field_key,
							'operator' => '!=',
							'value'    => 'split_carousel',
						),
					);
					break;
				case 'tab_title-top':
					$field['conditional_logic'] = array(
						array(
							'field'    => $type_field_key,
							'operator' => '!=',
							'value'    => 'split_carousel',
						),
					);
					break;
				case 'tab_buttons':
					$field['conditional_logic'] = array(
						array(
							'field'    => $type_field_key,
							'operator' => '!=',
							'value'    => 'support_crisis_services',
						),
					);
					break;
				case 'title_attr':
				case 'desc_attr':
					$field['display']           = 'group';
					$field['conditional_logic'] = array(
						array(
							'field'    => $type_field_key,
							'operator' => '!=',
							'value'    => 'support_trevorspace',
						),
						array(
							'field'    => $type_field_key,
							'operator' => '!=',
							'value'    => 'support_crisis_services',
						),
					);
					break;
				default:
					;
			}
		}

		return $parent_fields;
	}

	/** @inheritdoc */
	public static function prepare_register_args(): array {
		$args = array_merge(
			parent::prepare_register_args(),
			array(
				'title'    => 'Landing Page Hero',
				'location' => array(
					array(
						array(
							'param'    => 'post_type',
							'operator' => '==',
							'value'    => 'page',
						),
						array(
							'param'    => 'post',
							'operator' => '!=',
							'value'    => get_option( 'page_for_posts' ),
						),
						array(
							'param'    => 'page_template',
							'operator' => '!=',
							'value'    => 'template-info-page.php',
						),
					),
				),
			)
		);

		// Title is not required here
		$args['fields'][ static::FIELD_TITLE ]['required'] = 0;

		return $args;
	}

	/** @inheritdoc */
	public static function render( $post = false, array $data = null, array $options = array() ): ?string {
		$val = new Field_Val_Getter( static::class, $post, $data );

		$type     = $val->get( static::FIELD_TYPE );
		$wrap_cls = array();

		# Check renderer
		if ( ! ( new \ReflectionClass( Helper\Page_Header::class ) )->hasMethod( $type ) ) {
			throw new Exception\Internal( 'Page header type is not supported.', compact( 'type' ) );
		}

		$args = array(
			'title_top' => $val->get( static::FIELD_TITLE_TOP ),
			'title'     => ( ! empty( $val->get( static::FIELD_TITLE ) ) ) ? $val->get( static::FIELD_TITLE ) : get_the_title( $post ), // fallback to post's title
			'desc'      => $val->get( static::FIELD_DESC ),
			'title_cls' => array( 'page-header-title' ),
			'desc_cls'  => array( 'page-header-desc' ),
		);

		# Additional title and desc classnames.
		$title_attrs         = DOM_Attr::get_attrs_of( $val->get( static::FIELD_TITLE_ATTR ) );
		$args['title_cls'][] = $title_attrs['class'];
		$desc_attrs          = DOM_Attr::get_attrs_of( $val->get( static::FIELD_DESC_ATTR ) );
		$args['desc_cls'][]  = $desc_attrs['class'];

		$args['styles'] = array();
		# Text color
		$txt_color = $val->get( static::FIELD_TEXT_CLR );
		if ( ! empty( $txt_color ) ) {
			$args['styles'][] = "text-{$txt_color}";
		}

		# BG Color
		$bg_color = $val->get( static::FIELD_BG_CLR );
		if ( ! empty( $bg_color ) ) {
			$args['styles'][] = "bg-{$bg_color}";
		}

		# Buttons
		$args['buttons'] = $val->get( static::FIELD_BUTTONS );

		# Update button attributes according to header attributes
		if ( ! empty( $args['buttons']['buttons'] ) ) {
			foreach ( $args['buttons']['buttons'] as &$button ) {
				if ( 'custom' === $button['button']['type'] ) {
					continue;
				}
				switch ( $val->get( static::FIELD_BG_CLR ) ) {
					case 'teal-dark':
						$button_class                             = $button['button']['button_attr']['class'];
						$button['button']['button_attr']['class'] = 'bg-white text-teal-dark ' . $button_class;
						break;
					default:
						break;
				}
			}
		}

		# Featured Image for Split Image, Horizontal, and Full
		if ( in_array( $val->get( static::FIELD_TYPE ), array( 'split_img', 'horizontal', 'img_bg' ), true ) ) {
			$img            = $val->get( static::FIELD_IMAGE );
			$args['img_id'] = empty( $img ) ? get_post_thumbnail_id() : $img['ID'];
		}

		# Split Carousel
		if ( $val->get( static::FIELD_TYPE ) === 'split_carousel' ) {
			$carousel_arr  = $val->get( static::FIELD_CAROUSEL );
			$carousel_data = array();

			if ( 'custom' === $carousel_arr['type'] ) {
				foreach ( $carousel_arr['data'] as $item ) {
					$carousel_data[] = array(
						'img'      => $item['data_img'],
						'caption'  => $item['data_title'],
						'subtitle' => $item['data_subtitle'],
						'cta_txt'  => $item['data_cta'] ? $item['data_cta']['title'] : '',
						'cta_url'  => $item['data_cta'] ? $item['data_cta']['url'] : '',
					);
				}
			} else {
				foreach ( $carousel_arr['posts'] as $post_item ) {
					$carousel_data[] = array(
						'img'     => array( 'id' => get_post_thumbnail_id( $post_item ) ),
						'caption' => $post_item->post_title,
					);
				}
			}

			$args['carousel_data'] = $carousel_data;
			$args['swiper']        = array(
				'centeredSlides' => true,
				'slidesPerView'  => 'auto',
			);
		}

		# Multi Image + Text
		if ( 'multi_image_text' === $type ) {
			$images = $val->get( static::FIELD_IMAGE_ENTRIES );

			$args['images'] = $images;
		}

		# Full Bleed Image/Video + Text
		if ( 'img_bg' === $type ) {
			$media_type = $val->get( static::FIELD_MEDIA_TYPE );

			$args['media_type']        = $media_type;
			$args['content_alignment'] = $val->get( static::FIELD_CONTENT_ALIGNMENT );

			if ( 'video' === $media_type ) {
				$args['video'] = $val->get( static::FIELD_VIDEO );
			}
		}

		# Support TrevorSpace
		if ( 'support_trevorspace' === $type ) {
			$members_count = $val->get( static::FIELD_MEMBERS_COUNT );
			$bottom_text   = $val->get( static::FIELD_BOTTOM_TEXT );

			if ( ! empty( $members_count ) ) {
				$args['title_top'] = $members_count . ' members currently online';
			}

			if ( ! empty( $bottom_text ) ) {
				$args['bottom'] = $bottom_text;
			}
		}

		# Support Crisis Services
		if ( 'support_crisis_services' === $type ) {
			$call_number = $val->get( static::FIELD_CALL_NUMBER );
			$sms_number  = $val->get( static::FIELD_SMS_NUMBER );

			$args['call_number'] = $call_number;
			$args['sms_number']  = $sms_number;
		}

		return Helper\Page_Header::$type( $args );
	}
}
