<?php namespace TrevorWP\Theme\ACF\Field_Group;

use TrevorWP\Theme\ACF\Util\Field_Val_Getter;

class Button_Group extends A_Field_Group implements I_Renderable, I_Block {
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
						static::FIELD_BUTTONS => [
								'key'          => $buttons,
								'name'         => static::FIELD_BUTTONS,
								'label'        => '',
								'type'         => 'repeater',
								'layout'       => 'table',
								'button_label' => 'Add Button',
								'sub_fields'   => [
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
						static::FIELD_ATTR    => DOM_Attr::clone( [
								'key'   => $attr,
								'name'  => static::FIELD_ATTR,
								'label' => '',
						] ),
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
	public static function render( $post = false, array $data = null, array $options = [] ): ?string {
		$val          = new Field_Val_Getter( static::class, $post, $data );
		$buttons_data = $val->get( static::FIELD_BUTTONS );
		$buttons      = empty( $buttons_data ) ? [] : $buttons_data[ static::FIELD_BUTTONS ];

		// Do not render if there is no button
		if ( empty( $buttons ) ) {
			return null;
		}

		ob_start(); ?>
		<div <?= DOM_Attr::render_attrs_of( $val->get( static::FIELD_ATTR ) ) ?>>
			<?php foreach ( $buttons as $button_data ): ?>
				<?= Button::render( null, (array) @$button_data[ static::FIELD_BUTTON ] ); ?>
			<?php endforeach; ?>
		</div>
		<?php return ob_get_clean();
	}

	/** @inheritdoc */
	public static function render_block( $block, $content = '', $is_preview = false, $post_id = 0 ): void {
		echo static::render( $post_id, null, compact( 'is_preview' ) );
	}

	/** @inheritdoc */
	public static function clone( array $args = [] ): array {
		return parent::clone( array_merge( [
				'display'      => 'seamless',
				'prefix_label' => 1,
		], $args ) );
	}
}
