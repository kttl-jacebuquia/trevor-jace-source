<?php namespace TrevorWP\Theme\ACF\Field_Group;

class Info_Card extends A_Basic_Section implements I_Block {
	/** @inheritdoc */
	public static function get_block_args(): array {
		return array_merge(
			parent::get_block_args(),
			array(
				'name'       => static::get_key(),
				'title'      => 'Info Card',
				'post_types' => array( 'page' ),
			)
		);
	}

	/** @inheritDoc */
	public static function render_block( $block, $content = '', $is_preview = false, $post_id = 0 ): void {
		$wrap_cls  = array( 'info-card' );
		$inner_cls = array( 'info-card-inner' );
		$title_cls = array( 'info-card-title' );
		$desc_cls  = array( 'info-card-title-desc' );
		$btn_cls   = array(
			'wrap_cls' => array( 'info-card-btn-wrap' ),
			'btn_cls'  => array( 'hover:bg-teal-dark', 'hover:text-white' ),
		);

		# Title additional classes.
		$title_attrs = DOM_Attr::get_attrs_of( static::get_val( static::FIELD_TITLE_ATTR ) );
		$title_cls[] = $title_attrs['class'];

		# Desc additional classes.
		$desc_attrs = DOM_Attr::get_attrs_of( static::get_val( static::FIELD_DESC_ATTR ) );
		$desc_cls[] = $desc_attrs['class'];

		static::render_block_wrapper(
			$block,
			null,
			array(
				'wrap_cls'  => $wrap_cls,
				'inner_cls' => $inner_cls,
				'title_cls' => $title_cls,
				'desc_cls'  => $desc_cls,
				'btn_cls'   => $btn_cls,
			)
		);
	}
}
