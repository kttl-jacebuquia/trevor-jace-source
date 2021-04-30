<?php namespace TrevorWP\Theme\ACF\Field_Group;

use TrevorWP\Exception;
use TrevorWP\Theme\ACF\Util\Field_Val_Getter;
use TrevorWP\Theme\Helper;
use TrevorWP\Theme\ACF\Field;

class Page_Header extends A_Basic_Section implements I_Renderable {
	const FIELD_TYPE = 'header_type';
	const FIELD_TITLE_TOP = 'title_top';
	const FIELD_TITLE_TOP_ATTR = 'title_top_attr';
	const FIELD_CAROUSEL = 'carousel';
	const FIELD_IMAGE = 'image';
	const FIELD_BG_CLR = 'bg_clr';
	const FIELD_TEXT_CLR = 'text_clr';

	/** @inheritdoc */
	protected static function _get_fields(): array {
		$type           = static::gen_field_key( static::FIELD_TYPE );
		$title_top      = static::gen_field_key( static::FIELD_TITLE_TOP );
		$title_top_attr = static::gen_field_key( static::FIELD_TITLE_TOP_ATTR );
		$carousel       = static::gen_field_key( static::FIELD_CAROUSEL );
		$image          = static::gen_field_key( static::FIELD_IMAGE );
		$text_clr       = static::gen_field_key( static::FIELD_TEXT_CLR );
		$bg_clr         = static::gen_field_key( static::FIELD_BG_CLR );

		$return = array_merge(
			static::_gen_tab_field( 'General' ),
			[
				static::FIELD_TYPE => [
					'key'           => $type,
					'name'          => static::FIELD_TYPE,
					'label'         => 'Type',
					'type'          => 'select',
					'required'      => true,
					'default_value' => 'text',
					'choices'       => [
						'text'           => 'Text',
						'split_img'      => 'Split Image',
						'img_bg'         => 'Full Image',
						'split_carousel' => 'Split Carousel',
						'horizontal'     => 'Horizontal',
					],
				],
			],
			static::_gen_tab_field( 'Title Top' ),
			[
				static::FIELD_TITLE_TOP      => [
					'key'   => $title_top,
					'name'  => static::FIELD_TITLE_TOP,
					'label' => 'Title Top',
					'type'  => 'text',
				],
				static::FIELD_TITLE_TOP_ATTR => DOM_Attr::clone( [
					'key'               => $title_top_attr,
					'name'              => static::FIELD_TITLE_TOP_ATTR,
					'conditional_logic' => [
						[
							[
								'field'    => $title_top,
								'operator' => '!=empty',
							],
						],
					],
				] ),
			],
			parent::_get_fields(),
			static::_gen_tab_field( 'Image(s)', [
				'conditional_logic' => [
					[
						[
							'field'    => $type,
							'operator' => '!=',
							'value'    => 'text',
						],
					],
				],
			] ),
			[
				static::FIELD_IMAGE    => [
					'key'               => $image,
					'name'              => static::FIELD_IMAGE,
					'label'             => 'Image',
					'type'              => 'image',
					'conditional_logic' => [
						[
							[
								'field'    => $type,
								'operator' => '!=',
								'value'    => 'text',
							],
						],
					],
				],
				static::FIELD_CAROUSEL => Carousel_Data::clone( [
					'key'               => $carousel,
					'name'              => static::FIELD_CAROUSEL,
					'label'             => 'Carousel',
					'prefix_label'      => true,
					'display'           => 'group',
					'layout'            => 'row',
					'conditional_logic' => [
						[
							[
								'field'    => $type,
								'operator' => '==',
								'value'    => 'split_carousel',
							],
						],
					],
				] )
			],
			static::_gen_tab_field( 'Styling' ),
			[
				static::FIELD_TEXT_CLR => Field\Color::gen_args(
					$text_clr,
					static::FIELD_TEXT_CLR,
					[ 'label' => 'Text Color', 'default' => 'teal-dark', ]
				),
				static::FIELD_BG_CLR   => Field\Color::gen_args(
					$bg_clr,
					static::FIELD_BG_CLR,
					[ 'label' => 'BG Color', 'default' => 'white', ]
				),
			]
		);

		$return[ static::FIELD_TITLE ]['instructions'] = 'Leave empty to use Post`s title.';

		return $return;
	}

	/** @inheritdoc */
	public static function prepare_register_args(): array {
		$args = array_merge( parent::prepare_register_args(), [
			'title'    => 'Page Header',
			'location' => [
				[
					[
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'page',
					],
					[
						'param'    => 'post',
						'operator' => '!=',
						'value'    => get_option( 'page_for_posts' ),
					],
				],
			],
		] );

		// Title is not required here
		$args['fields'][ static::FIELD_TITLE ]['required'] = 0;

		return $args;
	}

	/** @inheritdoc */
	public static function render( $post = false, array $data = null, array $options = [] ): ?string {
		$val = new Field_Val_Getter( static::class, $post, $data );

		$type     = $val->get( static::FIELD_TYPE );
		$wrap_cls = [];

		# Check renderer
		if ( ! ( new \ReflectionClass( Helper\Page_Header::class ) )->hasMethod( $type ) ) {
			throw new Exception\Internal( 'Page header type is not supported.', compact( 'type' ) );
		}

		$args = [
			'title_top' => $val->get( static::FIELD_TITLE_TOP ),
			'title'     => $val->get( static::FIELD_TITLE ) ?: get_the_title( $post ), // fallback to post's title
			'desc'      => $val->get( static::FIELD_DESC ),
		];

		$args['styles'] = [];
		# Text color
		if ( ! empty( $txt_color = $val->get( static::FIELD_TEXT_CLR ) ) ) {
			$args['styles'][] = "text-{$txt_color}";
		}

		# BG Color
		if ( ! empty( $bg_color = $val->get( static::FIELD_BG_CLR ) ) ) {
			$args['styles'][] = "bg-{$bg_color}";
		}

		# Buttons
		$args['buttons'] = $val->get( static::FIELD_BUTTONS );

		# Update button attributes according to header attributes
		if ( ! empty( $args['buttons']['buttons'] ) ) {
			foreach ( $args['buttons']['buttons'] as &$button ) {
				switch ( $val->get( static::FIELD_BG_CLR ) ) {
					case "teal-dark":
						$button_class                             = $button['button']['button_attr']['class'];
						$button['button']['button_attr']['class'] = 'bg-white text-teal-dark ' . $button_class;
						break;
					default:
						break;
				}
			}
		}

		# Featured Image for Split Image, Horizontal, and Full
		if ( in_array( $val->get( static::FIELD_TYPE ), [ 'split_img', 'horizontal', 'img_bg' ] ) ) {
			$img            = $val->get( static::FIELD_IMAGE );
			$args['img_id'] = empty( $img ) ? get_post_thumbnail_id() : @$img['ID'];
		}

		# Split Carousel
		if ( $val->get( static::FIELD_TYPE ) === 'split_carousel' ) {
			$carousel_arr  = $val->get( static::FIELD_CAROUSEL );
			$carousel_data = [];

			if ( $carousel_arr['type'] === 'custom' ) {
				foreach ( $carousel_arr['data'] as $item ) {
					$carousel_data[] = [
						'img'      => $item['data_img'],
						'caption'  => $item['data_title'],
						'subtitle' => $item['data_subtitle'],
					];
				}
			} else {
				foreach ( $carousel_arr['posts'] as $post_item ) {
					$carousel_data[] = [
						'img'     => [ 'id' => get_post_thumbnail_id( $post_item ) ],
						'caption' => $post_item->post_title,
					];
				}
			}

			$args['carousel_data'] = $carousel_data;
			$args['swiper']        = [
				'centeredSlides' => true,
				'slidesPerView'  => 'auto',
			];
		}

		return Helper\Page_Header::$type( $args );
	}
}
