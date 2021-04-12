<?php namespace TrevorWP\Theme\ACF\Field_Group;

use TrevorWP\Theme\ACF\Options_Page\Page_Circulation_Options;

class Page_Circulation extends A_Basic_Section implements I_Block {
	const FIELD_CARDS = 'cards';

	/** @inheritdoc */
	protected static function _get_fields(): array {
		$cards = static::gen_field_key( static::FIELD_CARDS );

		return array_merge(
			parent::_get_fields(),
			static::_gen_tab_field( 'Cards' ),
			[
				static::FIELD_CARDS => [
					'key'     => $cards,
					'name'    => static::FIELD_CARDS,
					'label'   => 'Cards',
					'type'    => 'select',
					'choices' => array_keys( Page_Circulation_Options::get_cards() )
				],
			],
		);
	}

	/** @inheritDoc */
	public static function get_block_args(): array {
		return array_merge( parent::get_block_args(), [
			'name'       => static::get_key(),
			'title'      => 'Page Circulation',
			'post_types' => [ 'page' ],
		] );
	}

	/** @inheritDoc */
	public static function render_block( $block, $content = '', $is_preview = false, $post_id = 0 ): void {
		// TODO: Implement render_block() method.
	}
}
