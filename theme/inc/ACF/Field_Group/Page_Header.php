<?php namespace TrevorWP\Theme\ACF\Field_Group;

use TrevorWP\Exception;
use TrevorWP\Theme\ACF\Util\Field_Val_Getter;
use TrevorWP\Theme\Helper;

class Page_Header extends A_Basic_Section implements I_Renderable {
	const FIELD_TYPE = 'header_type';
	const FIELD_TITLE_TOP = 'title_top';
	const FIELD_TITLE_TOP_ATTR = 'title_top_attr';
	const FIELD_CAROUSEL = 'carousel';

	/** @inheritdoc */
	protected static function _get_fields(): array {
		$type           = static::gen_key( static::FIELD_TYPE );
		$title_top      = static::gen_key( static::FIELD_TITLE_TOP );
		$title_top_attr = static::gen_key( static::FIELD_TITLE_TOP_ATTR );
		$carousel       = static::gen_field_key( static::FIELD_CAROUSEL );

		return array_merge(
			static::_gen_tab_field( 'General' ),
			[
				static::FIELD_TYPE => [
					'key'           => $type,
					'name'          => static::FIELD_TYPE,
					'label'         => 'Type',
					'type'          => 'select',
					'required'      => 1,
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
			parent::_get_fields(), [
			static::FIELD_CAROUSEL => Carousel_Data::clone( [
				'key'               => $carousel,
				'name'              => static::FIELD_CAROUSEL,
				'label'             => 'Carousel',
				'prefix_label'      => 1,
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
		] );
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

		$type = $val->get( static::FIELD_TYPE );

		# Check renderer
		if ( ! ( new \ReflectionClass( Helper\Page_Header::class ) )->hasMethod( $type ) ) {
			throw new Exception\Internal( 'Page header type is not supported.', compact( 'type' ) );
		}

		$args = [
			'title_top' => $val->get( static::FIELD_TITLE_TOP ),
			'title'     => $val->get( static::FIELD_TITLE ) ?: get_the_title( $post ), // fallback to post's title
			'desc'      => $val->get( static::FIELD_DESC ),
		];

		# Buttons
		$buttons = $val->get( static::FIELD_BUTTONS );
		if ( ! empty( $buttons ) ) {
			#todo
		}

		# Featured Image for Split Image, Horizontal, and Full
		if ( in_array( $val->get( static::FIELD_TYPE ), [ 'split_img', 'horizontal', 'img_bg' ] ) ) {
			$args['img_id'] = get_post_thumbnail_id();
		}


		# Split Carousel
		if ( $val->get( static::FIELD_TYPE ) === 'split_carousel' ) {
			$carousel_arr = $val->get( static::FIELD_CAROUSEL );
			$carousel_data = [];

			if ( $carousel_arr[ 'type' ] === 'custom' ) {
				foreach ( $carousel_arr[ 'data' ] as $item ) {
					$carousel_data[] = [
						'img'      => $item['data_img'],
						'caption'  => $item['data_title'],
						'subtitle' => $item['data_subtitle'],
					];
				}
			} else {
				foreach ( $carousel_arr[ 'posts' ] as $post_item ) {
					$carousel_data[] = [
						'img'      => [ 'id' => get_post_thumbnail_id($post_item) ],
						'caption'  => $post_item->post_title,
					];
				}
			}

			$args['carousel_data'] = $carousel_data;
			$args['swiper'] = [
				'centeredSlides' => true,
				'slidesPerView'  => 'auto',
			];
		}

		return Helper\Page_Header::$type( $args );
	}
}
