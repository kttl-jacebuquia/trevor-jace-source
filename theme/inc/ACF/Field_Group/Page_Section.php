<?php namespace TrevorWP\Theme\ACF\Field_Group;

use TrevorWP\Theme\ACF\Util\Field_Val_Getter;
use TrevorWP\Theme\ACF\Field;
use TrevorWP\Util\Tools;

class Page_Section extends A_Basic_Section implements I_Block {
	const FIELD_TYPE        = 'type';
	const FIELD_TITLE_ALIGN = 'title_align';
	const FIELD_BG_COLOR    = 'bg_color';
	const FIELD_TEXT_COLOR  = 'text_color';

	const TYPE_VERTICAL   = 'vertical';
	const TYPE_HORIZONTAL = 'horizontal';

	const TITLE_ALIGN_CENTERED         = 'centered';
	const TITLE_ALIGN_LEFT             = 'left';
	const TITLE_ALIGN_CENTERED_XL_LEFT = 'centered_xl_left';

	/** @inheritdoc */
	protected static function _get_fields(): array {
		$type        = static::gen_field_key( static::FIELD_TYPE );
		$title_align = static::gen_field_key( static::FIELD_TITLE_ALIGN );
		$bg_color    = static::gen_field_key( static::FIELD_BG_COLOR );
		$text_color  = static::gen_field_key( static::FIELD_TEXT_COLOR );

		return array_merge(
			parent::_get_fields(),
			static::_gen_tab_field( 'Styling' ),
			array(
				static::FIELD_TYPE        => array(
					'name'    => static::FIELD_TYPE,
					'key'     => $type,
					'label'   => 'Type',
					'default' => static::TYPE_VERTICAL,
					'type'    => 'select',
					'choices' => array(
						static::TYPE_VERTICAL   => 'Vertical',
						static::TYPE_HORIZONTAL => 'Horizontal',
					),
					'wrapper' => array(
						'width' => '50',
					),
				),
				static::FIELD_TITLE_ALIGN => array(
					'name'    => static::FIELD_TITLE_ALIGN,
					'key'     => $title_align,
					'label'   => 'Title Align',
					'type'    => 'select',
					'default' => static::TITLE_ALIGN_CENTERED,
					'choices' => array(
						static::TITLE_ALIGN_CENTERED => 'Centered',
						static::TITLE_ALIGN_LEFT     => 'Left',
						static::TITLE_ALIGN_CENTERED_XL_LEFT => 'Centered, XL:Left',
					),
					'wrapper' => array(
						'width' => '50',
					),
				),
				static::FIELD_BG_COLOR    => Field\Color::gen_args(
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
				static::FIELD_TEXT_COLOR  => Field\Color::gen_args(
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

	/** @inheritDoc */
	public static function get_block_args(): array {
		return array_merge(
			parent::get_block_args(),
			array(
				'name'       => static::get_key(),
				'title'      => 'Page Section',
				'post_types' => array( 'page' ),
				'supports'   => array(
					'jsx' => true,
				),
			)
		);
	}

	/** @inheritDoc */
	public static function render_block( $block, $content = '', $is_preview = false, $post_id = 0 ): void {
		$data             = (array) @$block['data'];
		$val              = new Field_Val_Getter( static::class, $post_id, $data );
		$type             = $val->get( static::FIELD_TYPE );
		$wrap_cls         = array( 'page-section', "page-section-type-${type}", 'py-20 xl:py-24' );
		$inner_cls        = array( 'page-section-inner', 'container mx-auto' );
		$content_wrap_cls = array( 'page-section-content-wrap' );
		$title_wrap_cls   = array( 'page-sub-title-wrap' );
		$title_cls        = array( 'page-sub-title', 'page-section-title' );
		$desc_cls         = array( 'page-sub-title-desc', 'page-section-title-desc' );
		$btn_cls          = array(
			'wrap_cls' => array(),
		);

		# Additional wrapper classes.
		$bg_color  = $val->get( static::FIELD_BG_COLOR );
		$txt_color = $val->get( static::FIELD_TEXT_COLOR );

		if ( ! empty( $bg_color ) ) {
			$wrap_cls[] = "bg-{$bg_color}";
		}
		if ( ! empty( $txt_color ) ) {
			$wrap_cls[] = "text-{$txt_color}";
		}

		# Title align
		$title_align = $val->get( static::FIELD_TITLE_ALIGN );
		if ( static::TITLE_ALIGN_CENTERED === $title_align ) {
			$desc_cls[]  = 'centered';
			$title_cls[] = 'centered';
		} elseif ( static::TITLE_ALIGN_CENTERED_XL_LEFT === $title_align ) {
			$desc_cls[]  = 'centered xl:no-centered';
			$title_cls[] = 'centered xl:no-centered';
		}

		# Type
		switch ( $type ) {
			case static::TYPE_HORIZONTAL:
				$inner_cls[] = 'xl:flex xl:flex-row xl:flex-wrap';

				$xl_wrap_cls        = 'xl:w-1/2 xl:flex xl:flex-col xl:justify-center';
				$content_wrap_cls[] = $xl_wrap_cls;
				$title_wrap_cls[]   = $xl_wrap_cls;

				$title_wrap_cls[]      = 'xl:flex-col xl:items-start';
				$content_wrap_cls[]    = 'xl:items-end mt-12 xl:mt-0';
				$btn_cls['wrap_cls'][] = 'xl:w-full xl:justify-start';

				$xl_wrap_cls_width = 'xl:w-3/4'; //fix left align
				$title_cls[]       = $xl_wrap_cls_width;
				$desc_cls[]        = $xl_wrap_cls_width;
				break;

			default:
				$title_wrap_cls[] = 'flex flex-col items-center';
				$title_cls[]      = 'mb-px14';
				$desc_cls[]       = 'tracking-px05 mb-0';
				$desc_cls[]       = 'md:mt-0';
				break;
		}

		$blocks = acf_get_block_types();

		if ( isset( $blocks['acf/trvr-page-section'] ) ) {
			unset( $blocks['acf/trvr-page-section'] );
		}

		$blocks = array_keys( $blocks );
		$blocks = array_merge(
			$blocks,
			array(
				'core/image',
			)
		);

		ob_start(); ?>
		<div <?php echo Tools::flat_attr( array_filter( array( 'class' => implode( ' ', $content_wrap_cls ) ) ) ); ?>>
			<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $blocks ) ); ?>"/>
		</div>
		<?php
		static::render_block_wrapper(
			$block,
			ob_get_clean(),
			array(
				'wrap_cls'       => $wrap_cls,
				'title_cls'      => $title_cls,
				'desc_cls'       => $desc_cls,
				'title_wrap_cls' => $title_wrap_cls,
				'inner_cls'      => $inner_cls,
				'btn_cls'        => $btn_cls,
				'btn_inside'     => static::TYPE_HORIZONTAL === $type,
			)
		);
	}
}
