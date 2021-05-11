<?php namespace TrevorWP\Theme\ACF\Field_Group;

use TrevorWP\Exception;
use TrevorWP\Theme\ACF\Util\Field_Val_Getter;
use TrevorWP\Theme\Helper;
use TrevorWP\Theme\ACF\Field;

class Page_Header extends A_Basic_Section implements I_Renderable {
	const FIELD_TYPE           = 'header_type';
	const FIELD_TITLE_TOP      = 'title_top';
	const FIELD_TITLE_TOP_ATTR = 'title_top_attr';
	const FIELD_CAROUSEL       = 'carousel';
	const FIELD_IMAGE          = 'image';
	const FIELD_BG_CLR         = 'bg_clr';
	const FIELD_TEXT_CLR       = 'text_clr';

	/** @inheritdoc */
	public static function _get_fields(): array {
		$type           = static::gen_field_key( static::FIELD_TYPE );
		$title_top      = static::gen_field_key( static::FIELD_TITLE_TOP );
		$title_top_attr = static::gen_field_key( static::FIELD_TITLE_TOP_ATTR );
		$carousel       = static::gen_field_key( static::FIELD_CAROUSEL );
		$image          = static::gen_field_key( static::FIELD_IMAGE );
		$text_clr       = static::gen_field_key( static::FIELD_TEXT_CLR );
		$bg_clr         = static::gen_field_key( static::FIELD_BG_CLR );

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
						'text'           => 'Text',
						'split_img'      => 'Split Image',
						'img_bg'         => 'Full Image',
						'split_carousel' => 'Split Carousel',
						'horizontal'     => 'Horizontal',
					),
				),
			),
			static::_gen_tab_field( 'Title Top' ),
			array(
				static::FIELD_TITLE_TOP      => array(
					'key'   => $title_top,
					'name'  => static::FIELD_TITLE_TOP,
					'label' => 'Title Top',
					'type'  => 'text',
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
			parent::_get_fields(),
			static::_gen_tab_field(
				'Image(s)',
				array(
					'conditional_logic' => array(
						array(
							array(
								'field'    => $type,
								'operator' => '!=',
								'value'    => 'text',
							),
						),
					),
				)
			),
			array(
				static::FIELD_IMAGE    => array(
					'key'               => $image,
					'name'              => static::FIELD_IMAGE,
					'label'             => 'Image',
					'type'              => 'image',
					'conditional_logic' => array(
						array(
							array(
								'field'    => $type,
								'operator' => '!=',
								'value'    => 'text',
							),
						),
					),
				),
				static::FIELD_CAROUSEL => Carousel_Data::clone(
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
			static::_gen_tab_field( 'Styling' ),
			array(
				static::FIELD_TEXT_CLR => Field\Color::gen_args(
					$text_clr,
					static::FIELD_TEXT_CLR,
					array(
						'label'   => 'Text Color',
						'default' => 'teal-dark',
					)
				),
				static::FIELD_BG_CLR   => Field\Color::gen_args(
					$bg_clr,
					static::FIELD_BG_CLR,
					array(
						'label'   => 'BG Color',
						'default' => 'white',
					)
				),
			)
		);

		$return[ static::FIELD_TITLE ]['instructions'] = 'Leave empty to use Post`s title.';

		return $return;
	}

	/** @inheritdoc */
	public static function prepare_register_args(): array {
		$args = array_merge(
			parent::prepare_register_args(),
			array(
				'title'    => 'Page Header',
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
			'title'     => $val->get( static::FIELD_TITLE ) ?? get_the_title( $post ), // fallback to post's title
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

		return Helper\Page_Header::$type( $args );
	}
}
