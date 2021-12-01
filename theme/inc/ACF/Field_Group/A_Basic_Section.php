<?php namespace TrevorWP\Theme\ACF\Field_Group;

use TrevorWP\Util\Tools;

/**
 * Basic
 */
abstract class A_Basic_Section extends A_Field_Group {
	const FIELD_TITLE        = 'title';
	const FIELD_TITLE_ATTR   = 'title_attr';
	const FIELD_DESC         = 'desc';
	const FIELD_DESC_ATTR    = 'desc_attr';
	const FIELD_INNER_ATTR   = 'inner_attr';
	const FIELD_BUTTONS      = 'buttons';
	const FIELD_WRAPPER_ATTR = 'wrapper_attr';

	// const TAB_TITLE = static::_gen_tab_field( 'Title' );

	/**
	 * @return array
	 */
	protected static function _get_fields(): array {
		$title      = static::gen_field_key( static::FIELD_TITLE );
		$title_attr = static::gen_field_key( static::FIELD_TITLE_ATTR );
		$desc       = static::gen_field_key( static::FIELD_DESC );
		$desc_attr  = static::gen_field_key( static::FIELD_DESC_ATTR );
		$inner_attr = static::gen_field_key( static::FIELD_INNER_ATTR );
		$wrapper    = static::gen_field_key( static::FIELD_WRAPPER_ATTR );
		$buttons    = static::gen_field_key( static::FIELD_BUTTONS );

		return array_merge(
			static::_gen_tab_field( 'Title' ),
			array(
				static::FIELD_TITLE      => array(
					'key'   => $title,
					'name'  => static::FIELD_TITLE,
					'label' => 'Title',
					'type'  => 'text',
				),
				static::FIELD_TITLE_ATTR => DOM_Attr::clone(
					array(
						'key'   => $title_attr,
						'name'  => static::FIELD_TITLE_ATTR,
						'label' => 'Title',
					)
				),
			),
			static::_gen_tab_field( 'Description' ),
			array(
				static::FIELD_DESC      => array(
					'key'   => $desc,
					'name'  => static::FIELD_DESC,
					'label' => 'Description',
					'type'  => 'textarea',
				),
				static::FIELD_DESC_ATTR => DOM_Attr::clone(
					array(
						'key'               => $desc_attr,
						'name'              => static::FIELD_DESC_ATTR,
						'label'             => 'Desc.',
						'conditional_logic' => array(
							array(
								array(
									'field'    => $desc,
									'operator' => '!=empty',
								),
							),
						),
					)
				),
			),
			static::_gen_tab_field( 'Inner' ),
			array(
				static::FIELD_INNER_ATTR => DOM_Attr::clone(
					array(
						'key'   => $inner_attr,
						'name'  => static::FIELD_INNER_ATTR,
						'label' => 'Inner',
					)
				),
			),
			static::_gen_tab_field( 'Wrapper' ),
			array(
				static::FIELD_WRAPPER_ATTR => DOM_Attr::clone(
					array(
						'key'   => $wrapper,
						'name'  => static::FIELD_WRAPPER_ATTR,
						'label' => 'Wrapper',
					)
				),
			),
			static::_gen_tab_field( 'Buttons' ),
			array(
				static::FIELD_BUTTONS => Button_Group::clone(
					array(
						'key'     => $buttons,
						'name'    => static::FIELD_BUTTONS,
						'display' => 'seamless',
						'layout'  => 'block',
						'label'   => '',
					)
				),
			),
		);
	}

	/** @inheritdoc */
	public static function prepare_register_args(): array {
		return array(
			'title'  => '', // Required,
			'fields' => static::_get_fields(),
		);
	}

	/**
	 * @param $block
	 * @param array $cls
	 * @param $content
	 */
	public static function render_block_part_wrap( $block, array $cls = array(), $content, $anchor_id = '' ): void {
		# Add block's classnames
		if ( ! empty( $block['className'] ) ) {
			$cls[] = $block['className'];
		}
		?>
		<div id="<?php echo esc_attr( esc_html( $anchor_id ) ); ?>" tabindex="0" <?php echo DOM_Attr::render_attrs_of( static::get_val( static::FIELD_WRAPPER_ATTR ), $cls ); ?>>
			<?php echo $content; ?>
		</div>
		<?php
	}

	/**
	 * @param array $cls
	 */
	public static function render_block_part_title( array $cls = array() ): void {
		if ( empty( $title = static::get_val( static::FIELD_TITLE ) ) ) {
			return;
		}
		?>
		<h2 <?php echo DOM_Attr::render_attrs_of( static::get_val( static::FIELD_TITLE_ATTR ), $cls ); ?>>
			<?php echo $title; ?>
		</h2>
		<?php
	}

	/**
	 * @param array $cls
	 */
	public static function render_block_part_desc( array $cls = array() ): void {
		if ( empty( $desc = static::get_val( static::FIELD_DESC ) ) ) {
			return;
		}
		?>
		<p <?php echo DOM_Attr::render_attrs_of( static::get_val( static::FIELD_DESC_ATTR ), $cls ); ?>>
			<?php echo $desc; ?>
		</p>
		<?php
	}

	/**
	 * @param array $options
	 */
	public static function render_block_part_buttons( array $options = array() ): void {
		echo Button_Group::render( false, static::get_val( static::FIELD_BUTTONS ), $options );
	}

	/**
	 * @param $block
	 * @param $content
	 * @param array $options
	 */
	public static function render_block_wrapper( $block, $content, array $options = array() ): void {
		$wrap       = $options['wrap_cls'] ?? array();
		$inner      = $options['inner_cls'] ?? array();
		$title_wrap = $options['title_wrap_cls'] ?? array();
		$title      = $options['title_cls'] ?? array();
		$desc       = $options['desc_cls'] ?? array();
		$btn        = $options['btn_cls'] ?? array();
		$btn_inside = $options['btn_inside'] ?? false;
		$anchor_id  = $options['anchor_id'] ?? '';

		ob_start();
		echo '<div ' . DOM_Attr::render_attrs_of( static::get_val( static::FIELD_INNER_ATTR ), $inner ) . '>';
		echo '<div ' . Tools::flat_attr( array_filter( array( 'class' => implode( ' ', $title_wrap ) ) ) ) . '>';
		static::render_block_part_title( $title );
		static::render_block_part_desc( $desc );
		if ( $btn_inside ) {
			static::render_block_part_buttons( $btn );
		}
		echo '</div>';
		echo $content;
		if ( ! $btn_inside ) {
			static::render_block_part_buttons( $btn );
		}
		echo '</div>';
		static::render_block_part_wrap( $block, $wrap, ob_get_clean(), $anchor_id );
	}
}
