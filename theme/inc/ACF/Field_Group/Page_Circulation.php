<?php namespace TrevorWP\Theme\ACF\Field_Group;

use TrevorWP\Theme\ACF\Util\Field_Val_Getter;
use TrevorWP\Theme\Helper\Circulation_Card;

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
								'key'      => $cards,
								'name'     => static::FIELD_CARDS,
								'label'    => 'Cards',
								'type'     => 'select',
								'multiple' => true,
								'choices'  => array_map( function ( $card ) {
									return $card['name'];
								}, Circulation_Card::SETTINGS ),
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
		$data = (array) @$block['data'];
		$val  = new Field_Val_Getter( static::class, $post_id, $data );

		# Prepare cards
		$cards = array_slice( (array) $val->get( static::FIELD_CARDS ), 0, 2 );

		// TODO: Implement render_block() method.

		ob_start(); ?>
		<div class="grid grid-cols-1 gap-y-6 max-w-lg mx-auto mt-px60 md:mt-px50 lg:mt-px80 lg:grid-cols-2 lg:gap-x-7 lg:max-w-none xl:max-w-px1240">
			<?php foreach ( $cards as $card ): ?>
				<?= call_user_func( [ Circulation_Card::class, "render_{$card}" ] ) ?>
			<?php endforeach; ?>
		</div>
		<?php static::render_block_wrapper( $block, ob_get_clean(), [
				'wrap_cls'  => [ 'page-section page-circulation bg-white text-teal-dark', 'pt-20 pb-24 lg:pt-24' ],
				'inner_cls' => [ 'container mx-auto' ],
				'title_cls' => [ 'page-sub-title centered' ],
				'desc_cls'  => [ 'page-sub-title-desc centered' ],
		] );
	}
}
