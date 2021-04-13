<?php namespace TrevorWP\Theme\ACF\Field_Group;

class Info_Card extends A_Basic_Section implements I_Block {
	/** @inheritdoc */
	public static function get_block_args(): array {
		return array_merge( parent::get_block_args(), [
			'name'       => static::get_key(),
			'title'      => 'Info Card',
			'post_types' => [ 'page' ],
		] );
	}

	/** @inheritDoc */
	public static function render_block( $block, $content = '', $is_preview = false, $post_id = 0 ): void {
		$wrap_cls  = [ 'info-card' ];
		$inner_cls = [ 'info-card-inner' ];
		$title_cls = [ 'info-card-title' ];
		$desc_cls  = [ 'info-card-title-desc' ];
		$btn_cls   = [
			'wrap_cls' => [ 'info-card-btn-wrap' ]
		];

		static::render_block_wrapper( $block, null, [
			'wrap_cls'  => $wrap_cls,
			'inner_cls' => $inner_cls,
			'title_cls' => $title_cls,
			'desc_cls'  => $desc_cls,
			'btn_cls'   => $btn_cls,
		] );
	}
}
