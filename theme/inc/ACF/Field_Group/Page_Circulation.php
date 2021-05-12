<?php namespace TrevorWP\Theme\ACF\Field_Group;

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
					'key'      => static::gen_field_key( static::FIELD_CARDS ),
					'name'     => static::FIELD_CARDS,
					'label'    => 'Cards',
					'type'     => 'select',
					'multiple' => true,
					'choices'  => array_map(
						function ( $card ) {
							return $card['name'];
						},
						Circulation_Card::SETTINGS
					),
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
				'title'      => 'Page Circulation',
				'post_types' => array( 'page' ),
			)
		);
	}

	/** @inheritDoc */
	public static function render_block( $block, $content = '', $is_preview = false, $post_id = 0 ): void {
		$data = (array) @$block['data'];
		$val  = new Field_Val_Getter( static::class, $post_id, $data );

		static::render_block_wrapper(
			$block,
			static::render_grid( (array) $val->get( static::FIELD_CARDS ) ),
			array(
				'wrap_cls'  => array( 'page-section page-circulation bg-white text-teal-dark', 'pt-20 pb-24 lg:pt-24' ),
				'inner_cls' => array( 'container mx-auto' ),
				'title_cls' => array( 'page-sub-title centered' ),
				'desc_cls'  => array( 'page-sub-title-desc centered' ),
			)
		);
	}

	/**
	 * @param array $cards
	 *
	 * @return string
	 */
	public static function render_grid( array $cards ): string {
		$cards = array_slice( $cards, 0, 2 );
		foreach ( $cards as &$card ) {
			$card = call_user_func( array( Circulation_Card::class, "render_{$card}" ) );
		}
		$content = implode( "\n", $cards );

		return '<div class="grid grid-cols-1 gap-y-6 max-w-lg mx-auto mt-px60 md:mt-px50 lg:mt-px80 lg:grid-cols-2 lg:gap-x-7 lg:max-w-none xl:max-w-px1240">' .
			   $content .
			   '</div>';
	}
}
