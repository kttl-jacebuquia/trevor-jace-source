<?php namespace TrevorWP\Theme\ACF\Field_Group;

use TrevorWP\Theme\ACF\Field;

class Page_Section extends A_Basic_Section implements I_Block {
	const FIELD_WRAPPER_ATTR = 'wrapper_attr';
	const FIELD_TEXT_CLR = 'text_clr';
	const FIELD_BG_CLR = 'bg_clr';

	/** @inheritdoc */
	protected static function _get_fields(): array {
		$wrapper  = static::gen_field_key( static::FIELD_WRAPPER_ATTR );
		$text_clr = static::gen_field_key( static::FIELD_TEXT_CLR );
		$bg_clr   = static::gen_field_key( static::FIELD_BG_CLR );

		return array_merge(
			parent::_get_fields(),
			static::_gen_tab_field( 'Wrapper' ),
			[
				static::FIELD_WRAPPER_ATTR => DOM_Attr::clone( [
					'key'   => $wrapper,
					'name'  => static::FIELD_WRAPPER_ATTR,
					'label' => 'Wrapper'
				] ),
			],
			static::_gen_tab_field( 'Styling' ),
			[
				static::FIELD_TEXT_CLR => Field\Color::gen_args(
					$text_clr,
					static::FIELD_TEXT_CLR,
					[ 'label' => 'Text Color' ]
				),
				static::FIELD_BG_CLR   => Field\Color::gen_args(
					$bg_clr,
					static::FIELD_BG_CLR,
					[ 'label' => 'BG Color' ]
				),
			]
		);
	}

	/** @inheritDoc */
	public static function get_block_args(): array {
		return array_merge( parent::get_block_args(), [
			'name'       => static::get_key(),
			'title'      => 'Page Section',
			'post_types' => [ 'page' ],
			'supports'   => [
				'jsx' => true,
			],
		] );
	}

	/** @inheritDoc */
	public static function render_block( $block, $content = '', $is_preview = false, $post_id = 0 ): void {
		static::render_block_wrapper( $block, '<InnerBlocks/>', [
			'wrap_cls'  => [ 'page-section' ],
			'title_cls' => [ 'page-sub-title' ],
			'desc_cls'  => [ 'page-sub-title-desc' ],
			'inner_cls' => [ 'container mx-auto' ]
		] );
	}
}
