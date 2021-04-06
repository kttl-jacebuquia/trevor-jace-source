<?php namespace TrevorWP\Theme\ACF\Field_Group;

/**
 * Basic
 */
abstract class A_Basic_Section extends A_Field_Group {
	const FIELD_TITLE = 'title';
	const FIELD_TITLE_ATTR = 'title_attr';
	const FIELD_DESC = 'desc';
	const FIELD_DESC_ATTR = 'desc_attr';
	const FIELD_BUTTONS = 'buttons';

	/**
	 * @return array
	 */
	protected static function _get_fields(): array {
		$title      = static::gen_field_key( static::FIELD_TITLE );
		$title_attr = static::gen_field_key( static::FIELD_TITLE_ATTR );
		$desc       = static::gen_field_key( static::FIELD_DESC );
		$desc_attr  = static::gen_field_key( static::FIELD_DESC_ATTR );
		$buttons    = static::gen_field_key( static::FIELD_BUTTONS );

		return [
				static::FIELD_TITLE      => [
						'key'      => $title,
						'name'     => static::FIELD_TITLE,
						'label'    => 'Title',
						'type'     => 'text',
						'required' => 1,
				],
				static::FIELD_TITLE_ATTR => DOM_Attr::clone( [
						'key'   => $title_attr,
						'name'  => static::FIELD_TITLE_ATTR,
						'label' => 'Title Attributes',
				] ),
				static::FIELD_DESC       => [
						'key'   => $desc,
						'name'  => static::FIELD_DESC,
						'label' => 'Description',
						'type'  => 'textarea',
				],
				static::FIELD_DESC_ATTR  => DOM_Attr::clone( [
						'key'               => $desc_attr,
						'name'              => static::FIELD_DESC_ATTR,
						'label'             => 'Desc. Attributes',
						'conditional_logic' => [
								[
										[
												'field'    => $desc,
												'operator' => '!=empty',
										],
								],
						],
				] ),
				static::FIELD_BUTTONS    => Button_Group::clone( [
						'key'          => $buttons,
						'name'         => static::FIELD_BUTTONS,
						'label'        => 'Button Group',
						'display'      => 'group',
						'layout'       => 'row',
						'prefix_label' => 1,
				] ),
		];
	}

	/** @inheritdoc */
	public static function prepare_register_args(): array {
		return [
				'title'  => '', // Required,
				'fields' => static::_get_fields(),
		];
	}

	public static function render_block_part_wrap( $block, array $cls = [], $content ): void {
		# Add block's classnames
		if ( ! empty( $block['className'] ) ) {
			$cls[] = $block['className'];
		}

		# Text color
		if ( ! empty( $txt_color = static::get_val( static::FIELD_TEXT_CLR ) ) ) {
			$cls[] = "text-{$txt_color}";
		}

		# BG Color
		if ( ! empty( $bg_color = static::get_val( static::FIELD_BG_CLR ) ) ) {
			$cls[] = "bg-{$bg_color}";
		}
		?>
		<div <?= DOM_Attr::render_attrs_of( static::get_val( static::FIELD_WRAPPER_ATTR ), $cls ) ?>>
			<?= $content ?>
		</div>
		<?php
	}

	public static function render_block_part_title( array $cls = [] ): void {
		?>
		<h2 <?= DOM_Attr::render_attrs_of( static::get_val( static::FIELD_TITLE_ATTR ), $cls ) ?>>
			<?= static::get_val( static::FIELD_TITLE ) ?: '<em>Empty Title</em>' ?>
		</h2>
		<?php
	}

	public static function render_block_part_desc( array $cls = [] ): void {
		if ( ! empty( $desc = static::get_val( static::FIELD_DESC ) ) ) { ?>
			<p <?= DOM_Attr::render_attrs_of( static::get_val( static::FIELD_DESC_ATTR ), $cls ) ?>>
				<?= $desc ?>
			</p>
			<?php
		}
	}

	public static function render_block_part_buttons( array $cls = [] ): void {
		//todo: render buttons
	}

	public static function render_block_wrapper( $block, $content, array $classes = [] ): void {
		$wrap_cls  = $classes['wrap_cls'] ?? [];
		$title_cls = $classes['title_cls'] ?? [];
		$desc_cls  = $classes['desc_cls'] ?? [];

		ob_start();
		static::render_block_part_title( $title_cls );
		static::render_block_part_desc( $desc_cls );
		echo $content;
		static::render_block_part_buttons();
		static::render_block_part_wrap( $block, $wrap_cls, ob_get_clean() );
	}
}
