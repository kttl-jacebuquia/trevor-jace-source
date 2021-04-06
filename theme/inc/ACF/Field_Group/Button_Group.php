<?php namespace TrevorWP\Theme\ACF\Field_Group;

use TrevorWP\Theme\ACF\Util\Field_Val_Getter;

class Button_Group extends A_Field_Group implements I_Block {
	const FIELD_BUTTONS = 'buttons';
	const FIELD_BUTTON = 'button';
	const FIELD_ATTR = 'attr';

	/** @inheritDoc */
	protected static function prepare_register_args(): array {
		$buttons = static::gen_field_key( static::FIELD_BUTTONS );
		$button  = static::gen_field_key( static::FIELD_BUTTON );
		$attr    = static::gen_field_key( static::FIELD_ATTR );

		return [
				'title'  => 'Button Group',
				'fields' => [
						static::FIELD_ATTR    => DOM_Attr::clone( [
								'key'  => $attr,
								'name' => static::FIELD_ATTR,
						] ),
						static::FIELD_BUTTONS => [
								'key'        => $buttons,
								'name'       => static::FIELD_BUTTONS,
								'label'      => 'Buttons',
								'type'       => 'repeater',
								'layout'     => 'table',
								'sub_fields' => [
										static::FIELD_BUTTON => Button::clone(
												[
														'key'     => $button,
														'name'    => static::FIELD_BUTTON,
														'label'   => 'Button',
														'display' => 'group',
														'layout'  => 'block',
												]
										),
								],
						],
				],
		];
	}

	/** @inheritdoc */
	public static function get_block_args(): array {
		return [
				'name'       => static::get_key(),
				'title'      => 'Button Group',
				'category'   => 'common',
				'icon'       => 'book-alt',
				'post_types' => [ 'page' ],
		];
	}

	/** @inheritdoc */
	public static function render_block( $block, $content = '', $is_preview = false, $post_id = 0 ): void {
		$buttons = static::get_val( static::FIELD_BUTTONS );
		?>
		<div <?= DOM_Attr::render_attrs_of( static::get_val( static::FIELD_ATTR ) ) ?>>
			<?php foreach ( $buttons as $button_data ): ?>
				<?= Button::render( null, @$button_data[ static::FIELD_BUTTON ] ); ?>
			<?php endforeach; ?>
		</div>
		<?php
	}
}
