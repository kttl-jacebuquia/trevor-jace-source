<?php namespace TrevorWP\Theme\ACF\Field_Group;

use TrevorWP\Exception;
use TrevorWP\Theme\ACF\Util\Field_Val_Getter;
use TrevorWP\Theme\Helper;

class Page_Header extends A_Basic_Section implements I_Renderable {
	const FIELD_TYPE = 'type';
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
			[
				static::FIELD_TYPE           => [
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
				static::FIELD_TITLE_TOP      => [
					'key'   => $title_top,
					'name'  => static::FIELD_TITLE_TOP,
					'label' => 'Title Top',
					'type'  => 'text',
				],
				static::FIELD_TITLE_TOP_ATTR => DOM_Attr::clone( [
					'key'               => $title_top_attr,
					'name'              => static::FIELD_TITLE_TOP_ATTR,
					'label'             => 'Title Top Attributes',
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
				'key'          => $carousel,
				'name'         => static::FIELD_CAROUSEL,
				'prefix_label' => 1,
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
			// todo: add buttons
		}

		# todo: Add image for Split,Full,Horizontal types. (Post's featured image)

		# todo: Carousel
		$carousel = $val->get( static::FIELD_CAROUSEL );

		return Helper\Page_Header::$type( $args );
	}
}
