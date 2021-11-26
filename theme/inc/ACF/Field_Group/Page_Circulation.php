<?php namespace TrevorWP\Theme\ACF\Field_Group;

use TrevorWP\CPT\Page_ReCirculation;
use TrevorWP\Theme\ACF\Util\Field_Val_Getter;
use TrevorWP\Theme\Helper\Circulation_Card;

class Page_Circulation extends A_Basic_Section implements I_Block {
	const FIELD_CARDS = 'cards';

	/** @inheritdoc */
	protected static function _get_fields(): array {
		return array_merge(
			parent::_get_fields(),
			static::_gen_tab_field( 'Cards' ),
			array(
				static::FIELD_CARDS => array(
					'key'           => static::gen_field_key( static::FIELD_CARDS ),
					'name'          => static::FIELD_CARDS,
					'label'         => 'Cards',
					'type'          => 'post_object',
					'post_type'     => array(
						0 => Page_ReCirculation::POST_TYPE,
					),
					'multiple'      => true,
					'return_format' => 'object',
					'ui'            => 1,
				),
			),
		);
	}

	/** @inheritDoc */
	public static function get_block_args(): array {
		return array_merge(
			parent::get_block_args(),
			array(
				'name'       => static::get_key(),
				'title'      => 'Recirculation',
				'post_types' => array( 'page' ),
			)
		);
	}

	/** @inheritDoc */
	public static function render_block( $block, $content = '', $is_preview = false, $post_id = 0 ): void {
		$data = ( ! empty( $block['data'] ) ) ? (array) $block['data'] : null;
		$val  = new Field_Val_Getter( static::class, $post_id, $data );

		static::render_block_wrapper(
			$block,
			static::render_grid( (array) $val->get( static::FIELD_CARDS ) ),
			array(
				'wrap_cls'       => array( 'page-section page-circulation block-spacer' ),
				'inner_cls'      => array( 'container mx-auto page-circulation__container' ),
				'title_wrap_cls' => array( 'page-circulation__heading' ),
				'title_cls'      => array( 'page-sub-title centered' ),
				'desc_cls'       => array( 'page-sub-title-desc centered' ),
			)
		);
	}

	/**
	 * @param array $cards
	 *
	 * @return string
	 */
	public static function render_grid( array $cards ): string {
		$content = '';

		if ( ! empty( $cards ) ) {
			foreach ( $cards as &$card ) {
				$args = static::get_card_args( $card );

				$card = call_user_func( array( Circulation_Card::class, "render_{$args['type']}" ), $args );
			}

			$content = implode( "\n", $cards );
		}

		return '<div role="list" class="page-circulation__cards mx-auto mt-px60 md:mt-px50 lg:mt-px80 lg:max-w-none xl:max-w-px1240">' .
			$content .
			'</div>';
	}

	/**
	 * @param array $cards
	 *
	 * @return array
	 */
	public static function get_card_args( $card ) {
		$val = new Field_Val_Getter( Page_Circulation_Card::class, $card );

		# Additional wrapper and inner classnames.
		$wrapper_attrs = DOM_Attr::get_attrs_of( $val->get( Page_Circulation_Card::FIELD_WRAPPER_ATTR ) );
		$inner_attrs   = DOM_Attr::get_attrs_of( $val->get( Page_Circulation_Card::FIELD_INNER_ATTR ) );

		# Additional title and desc classnames.
		$title_attrs = DOM_Attr::get_attrs_of( $val->get( Page_Circulation_Card::FIELD_TITLE_ATTR ) );
		$desc_attrs  = DOM_Attr::get_attrs_of( $val->get( Page_Circulation_Card::FIELD_DESC_ATTR ) );

		$args = array(
			'type'      => $val->get( Page_Circulation_Card::FIELD_TYPE, $card ),
			'title'     => $val->get( Page_Circulation_Card::FIELD_TITLE, $card ),
			'desc'      => $val->get( Page_Circulation_Card::FIELD_DESC, $card ),
			'button'    => $val->get( Page_Circulation_Card::FIELD_BUTTON, $card ),
			'cls'       => array( $wrapper_attrs['class'] ),
			'inner_cls' => array( $inner_attrs['class'] ),
			'title_cls' => array( $title_attrs['class'] ),
			'desc_cls'  => array( $desc_attrs['class'] ),
		);

		return $args;
	}
}
