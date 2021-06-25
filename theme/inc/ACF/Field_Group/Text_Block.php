<?php namespace TrevorWP\Theme\ACF\Field_Group;

class Text_Block extends A_Field_Group implements I_Block, I_Renderable {
	const FIELD_EYEBROW = 'eyebrow';
	const FIELD_TITLE   = 'title';

	/**
	 * @inheritDoc
	 */
	protected static function prepare_register_args(): array {
		$eyebrow = static::gen_field_key( static::FIELD_EYEBROW );
		$title   = static::gen_field_key( static::FIELD_TITLE );

		return array(
			'title'  => 'Text Block',
			'fields' => array(
				static::FIELD_EYEBROW => array(
					'key'   => $eyebrow,
					'name'  => static::FIELD_EYEBROW,
					'label' => 'Eyebrow',
					'type'  => 'text',
				),
				static::FIELD_TITLE   => array(
					'key'   => $title,
					'name'  => static::FIELD_TITLE,
					'label' => 'Title',
					'type'  => 'textarea',
				),
			),
		);
	}

	/**
	 * @inheritDoc
	 */
	public static function get_block_args(): array {
		return array_merge(
			parent::get_block_args(),
			array(
				'name'       => static::get_key(),
				'title'      => 'Text Block',
				'post_types' => array( 'page' ),
			)
		);
	}

	/**
	 * @inheritDoc
	 */
	public static function render( $post = false, array $data = null, array $options = array() ): ?string {
		$eyebrow = static::get_val( static::FIELD_EYEBROW );
		$title   = static::get_val( static::FIELD_TITLE );

		ob_start();
		?>
		<div class="container mx-auto">
			<?php if ( ! empty( $eyebrow ) ) : ?>
				<p><?php echo esc_html( $eyebrow ); ?></p>
			<?php endif; ?>

			<?php if ( ! empty( $title ) ) : ?>
				<h3><?php echo esc_html( $title ); ?></h3>
			<?php endif; ?>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * @inheritDoc
	 */
	public static function render_block( $block, $content = '', $is_preview = false, $post_id = 0 ): void {
		echo static::render( $post_id, null, compact( 'is_preview' ) );
	}
}
