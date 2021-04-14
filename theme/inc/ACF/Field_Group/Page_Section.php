<?php namespace TrevorWP\Theme\ACF\Field_Group;

use TrevorWP\Theme\ACF\Util\Field_Val_Getter;

class Page_Section extends A_Basic_Section implements I_Block {
	const FIELD_TITLE_ALIGN = 'title_align';

	const TITLE_ALIGN_CENTERED = 'centered';
	const TITLE_ALIGN_LEFT = 'left';
	const TITLE_ALIGN_CENTERED_XL_LEFT = 'centered_xl_left';

	/** @inheritdoc */
	protected static function _get_fields(): array {
		$title_align = static::gen_field_key( static::FIELD_TITLE_ALIGN );

		return array_merge(
			parent::_get_fields(),
			static::_gen_tab_field( 'Styling' ),
			[
				static::FIELD_TITLE_ALIGN => [
					'name'    => static::FIELD_TITLE_ALIGN,
					'key'     => $title_align,
					'label'   => 'Title Align',
					'type'    => 'select',
					'default' => static::TITLE_ALIGN_CENTERED,
					'choices' => [
						static::TITLE_ALIGN_CENTERED         => 'Centered',
						static::TITLE_ALIGN_LEFT             => 'Left',
						static::TITLE_ALIGN_CENTERED_XL_LEFT => 'Centered, XL:Left'
					]
				],
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
		$data      = (array) @$block['data'];
		$val       = new Field_Val_Getter( static::class, $post_id, $data );
		$wrap_cls  = [ 'page-section', 'py-20 xl:py-24' ];
		$title_cls = [ 'page-sub-title', 'page-section-title' ];
		$desc_cls  = [ 'page-sub-title-desc', 'page-section-title-desc' ];

		# Title align
		$title_align = $val->get( static::FIELD_TITLE_ALIGN );
		if ( static::TITLE_ALIGN_CENTERED == $title_align ) {
			$title_cls[] = $desc_cls[] = 'centered';
		} elseif ( static::TITLE_ALIGN_CENTERED_XL_LEFT == $title_align ) {
			$title_cls[] = $desc_cls[] = 'centered'; // todo: add XL:left
		}

		static::render_block_wrapper( $block, '<InnerBlocks/>', [
			'wrap_cls'  => $wrap_cls,
			'title_cls' => $title_cls,
			'desc_cls'  => $desc_cls,
			'inner_cls' => [ 'container mx-auto' ]
		] );
	}
}
