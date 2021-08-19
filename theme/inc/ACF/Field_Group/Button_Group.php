<?php namespace TrevorWP\Theme\ACF\Field_Group;

use TrevorWP\Theme\ACF\Util\Field_Val_Getter;

class Button_Group extends A_Field_Group implements I_Renderable, I_Block {
	const FIELD_BUTTONS = 'buttons';
	const FIELD_BUTTON  = 'button';

	/** @inheritDoc */
	protected static function prepare_register_args(): array {
		$buttons = static::gen_field_key( static::FIELD_BUTTONS );
		$button  = static::gen_field_key( static::FIELD_BUTTON );

		return array(
			'title'  => 'Button Group',
			'fields' => array(
				static::FIELD_BUTTONS => array(
					'key'          => $buttons,
					'name'         => static::FIELD_BUTTONS,
					'label'        => '',
					'type'         => 'repeater',
					'layout'       => 'table',
					'button_label' => 'Add Button',
					'sub_fields'   => array(
						static::FIELD_BUTTON => Button::clone(
							array(
								'key'     => $button,
								'name'    => static::FIELD_BUTTON,
								'label'   => 'Button',
								'display' => 'group',
								'layout'  => 'block',
							)
						),
					),
				),
			),
		);
	}

	/** @inheritdoc */
	public static function get_block_args(): array {
		return array(
			'name'       => static::get_key(),
			'title'      => 'Button Group',
			'category'   => 'common',
			'icon'       => 'book-alt',
			'post_types' => array( 'page' ),
		);
	}

	/** @inheritdoc */
	public static function render( $post = false, array $data = null, array $options = array() ): ?string {
		$val          = new Field_Val_Getter( static::class, $post, $data );
		$buttons_data = $val->get( static::FIELD_BUTTONS );

		// Do not render if there is no button
		if ( empty( $buttons_data ) ) {
			return null;
		}

		$wrap_cls  = array_merge( array( 'button-group flex justify-center mt-12' ), $options['wrap_cls'] ?? array() );
		$btn_cls   = $options['btn_cls'] ?? array();
		$label_cls = $options['label_cls'] ?? array();

		$multiple = count( $buttons_data );
		if ( $multiple > 1 ) {
			$btn_cls[]  = 'my-2.5 md:my-0 md:mx-2.5';
			$wrap_cls[] = 'flex flex-col md:flex-row';
		}

		ob_start(); ?>
		<div <?php echo DOM_Attr::render_attrs_of( $val->get( static::FIELD_ATTR ), $wrap_cls ); ?>>
			<?php foreach ( $buttons_data as $button_data ) : ?>
				<?php echo Button::render( null, (array) @$button_data[ static::FIELD_BUTTON ], compact( 'btn_cls', 'label_cls' ) ); ?>
			<?php endforeach; ?>
		</div>
		<?php
		return ob_get_clean();
	}

	/** @inheritdoc */
	public static function render_block( $block, $content = '', $is_preview = false, $post_id = 0 ): void {
		$data = array(
			static::FIELD_BUTTONS => array(),
		);

		if ( have_rows( static::FIELD_BUTTONS ) ) :
			while ( have_rows( static::FIELD_BUTTONS ) ) :
				the_row();
				$data[ static::FIELD_BUTTONS ][] = array(
					static::FIELD_BUTTON => get_sub_field( static::FIELD_BUTTON ),
				);
			endwhile;
		endif;

		echo static::render( $post_id, $data, compact( 'is_preview' ) );
	}

	/** @inheritdoc */
	public static function clone( array $args = array() ): array {
		return parent::clone(
			array_merge(
				array(
					'display'      => 'seamless',
					'prefix_label' => true,
					'prefix_name'  => true,
				),
				$args
			)
		);
	}
}
