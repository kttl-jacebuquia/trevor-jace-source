<?php namespace TrevorWP\Theme\ACF\Field_Group;

use TrevorWP\Theme\ACF\Util\Field_Val_Getter;
use TrevorWP\Util\Tools;

class Page_Section extends A_Basic_Section implements I_Block {
	const FIELD_TYPE = 'type';
	const FIELD_TITLE_ALIGN = 'title_align';

	const TYPE_VERTICAL = 'vertical';
	const TYPE_HORIZONTAL = 'horizontal';

	const TITLE_ALIGN_CENTERED = 'centered';
	const TITLE_ALIGN_LEFT = 'left';
	const TITLE_ALIGN_CENTERED_XL_LEFT = 'centered_xl_left';

	/** @inheritdoc */
	protected static function _get_fields(): array {
		$type        = static::gen_field_key( static::FIELD_TYPE );
		$title_align = static::gen_field_key( static::FIELD_TITLE_ALIGN );

		return array_merge(
				parent::_get_fields(),
				static::_gen_tab_field( 'Styling' ),
				[
						static::FIELD_TYPE        => [
								'name'    => static::FIELD_TYPE,
								'key'     => $type,
								'label'   => 'Type',
								'default' => static::TYPE_VERTICAL,
								'type'    => 'select',
								'choices' => [
										static::TYPE_VERTICAL   => 'Vertical',
										static::TYPE_HORIZONTAL => 'Horizontal',
								],
						],
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
		$data             = (array) @$block['data'];
		$val              = new Field_Val_Getter( static::class, $post_id, $data );
		$type             = $val->get( static::FIELD_TYPE );
		$wrap_cls         = [ 'page-section', "page-section-type-${type}", 'py-20 xl:py-24' ];
		$inner_cls        = [ 'page-section-inner', 'container mx-auto' ];
		$content_wrap_cls = [ 'page-section-content-wrap' ];
		$title_wrap_cls   = [ 'page-sub-title-wrap' ];
		$title_cls        = [ 'page-sub-title', 'page-section-title' ];
		$desc_cls         = [ 'page-sub-title-desc', 'page-section-title-desc' ];
		$btn_cls          = [
				'wrap_cls' => [],
		];

		# Title align
		$title_align = $val->get( static::FIELD_TITLE_ALIGN );
		if ( static::TITLE_ALIGN_CENTERED == $title_align ) {
			$title_cls[] = $desc_cls[] = 'centered';
		} elseif ( static::TITLE_ALIGN_CENTERED_XL_LEFT == $title_align ) {
			$title_cls[] = $desc_cls[] = 'centered xl:no-centered';
		}

		# Type
		if ( $type == static::TYPE_HORIZONTAL ) {
			$inner_cls[]           = 'xl:flex xl:flex-row xl:flex-wrap';
			$title_wrap_cls[]      = $content_wrap_cls[] = 'xl:w-1/2 xl:flex xl:flex-col xl:justify-center';
			$title_wrap_cls[]      = 'xl:flex-col xl:items-start xl:justify-start';
			$content_wrap_cls[]    = 'xl:items-end';
			$btn_cls['wrap_cls'][] = 'xl:w-full xl:justify-start';
			$title_cls[]           = $desc_cls[] = 'xl:w-3/4'; //fix left align
		}

		ob_start(); ?>
		<div <?= Tools::flat_attr( array_filter( [ 'class' => implode( ' ', $content_wrap_cls ) ] ) ) ?>>
			<InnerBlocks/>
		</div>
		<?php static::render_block_wrapper( $block, ob_get_clean(), [
				'wrap_cls'       => $wrap_cls,
				'title_cls'      => $title_cls,
				'desc_cls'       => $desc_cls,
				'title_wrap_cls' => $title_wrap_cls,
				'inner_cls'      => $inner_cls,
				'btn_cls'        => $btn_cls,
				'is_btn_inside'  => $type == static::TYPE_HORIZONTAL,
		] );
	}
}
