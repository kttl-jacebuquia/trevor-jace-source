<?php namespace TrevorWP\Theme\ACF\Field_Group;

use TrevorWP\Theme\ACF\Field;

class Info_Card extends A_Basic_Section implements I_Block {
	const FIELD_TYPE        = 'type';
	const FIELD_TITLE_ALIGN = 'title_align';
	const FIELD_BG_COLOR    = 'bg_color';
	const FIELD_TEXT_COLOR  = 'text_color';

	/** @inheritdoc */
	protected static function _get_fields(): array {
		$bg_color   = static::gen_field_key( static::FIELD_BG_COLOR );
		$text_color = static::gen_field_key( static::FIELD_TEXT_COLOR );

		return array_merge(
			parent::_get_fields(),
			static::_gen_tab_field( 'Styling' ),
			array(
				static::FIELD_BG_COLOR   => Field\Color::gen_args(
					$bg_color,
					static::FIELD_BG_COLOR,
					array(
						'label'   => 'Background Color',
						'default' => 'white',
						'wrapper' => array(
							'width' => '50',
						),
					)
				),
				static::FIELD_TEXT_COLOR => Field\Color::gen_args(
					$text_color,
					static::FIELD_TEXT_COLOR,
					array(
						'label'   => 'Text Color',
						'default' => 'teal-dark',
						'wrapper' => array(
							'width' => '50',
						),
					),
				),
			)
		);
	}

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
		$bg_color   = static::get_val( static::FIELD_BG_COLOR );
		$text_color = static::get_val( static::FIELD_TEXT_COLOR );

		$wrap_cls  = array( 'info-card' );
		$inner_cls = array( 'info-card-inner' );
		$title_cls = array( 'info-card-title' );
		$desc_cls  = array( 'info-card-title-desc' );
		$btn_cls   = array(
			'wrap_cls' => array( 'info-card-btn-wrap' ),
			'btn_cls'  => array( 'hover:bg-teal-dark', 'hover:text-white' ),
		);

		if ( ! empty( $bg_color ) ) {
			$wrap_cls[] = "bg-{$bg_color}";
		}
		if ( ! empty( $text_color ) ) {
			$wrap_cls[] = "text-{$text_color}";
		}

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
