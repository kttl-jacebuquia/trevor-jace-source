<?php namespace TrevorWP\Theme\ACF\Field_Group;

use \TrevorWP\Theme\Helper;

class Breathing_Exercise extends A_Field_Group implements I_Block, I_Renderable {
	const FIELD_TITLE       = 'title';
	const FIELD_DESCRIPTION = 'description';
	const FIELD_CTA_TEXT    = 'cta_link';

	/**
	 * @inheritDoc
	 */
	protected static function prepare_register_args(): array {
		$title       = static::gen_field_key( static::FIELD_TITLE );
		$description = static::gen_field_key( static::FIELD_DESCRIPTION );
		$cta_text    = static::gen_field_key( static::FIELD_CTA_TEXT );

		return array(
			'title'  => 'Breathing Exercise',
			'fields' => array(
				static::FIELD_TITLE       => array(
					'key'   => $title,
					'name'  => static::FIELD_TITLE,
					'label' => 'Title',
					'type'  => 'text',
				),
				static::FIELD_DESCRIPTION => array(
					'key'   => $description,
					'name'  => static::FIELD_DESCRIPTION,
					'label' => 'Description',
					'type'  => 'textarea',
				),
				static::FIELD_CTA_TEXT    => array(
					'key'   => $cta_text,
					'name'  => static::FIELD_CTA_TEXT,
					'label' => 'CTA Text',
					'type'  => 'text',
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
				'title'      => 'Breathing Exercise',
				'post_types' => array( 'page' ),
			)
		);
	}

	/**
	 * @inheritDoc
	 */
	public static function render( $post = false, array $data = null, array $options = array() ): ?string {
		$title       = static::get_val( static::FIELD_TITLE );
		$description = static::get_val( static::FIELD_DESCRIPTION );
		$cta_text    = static::get_val( static::FIELD_CTA_TEXT );

		$cta_class = array(
			'breathing-exercise__cta',
			Helper\Breathing_Exercise::TRIGGER_ELEMENT_CLASS,
		);

		// Overlay will render in footer
		Helper\Breathing_Exercise::render_overlay();

		ob_start();
		?>
		<div class="breathing-exercise">
			<div class="breathing-exercise__container">
				<div class="breathing-exercise__content">
					<?php if ( ! empty( $title ) ) : ?>
						<h2 class="breathing-exercise__title"><?php echo esc_html( $title ); ?></h2>
					<?php endif; ?>

					<?php if ( ! empty( $description ) ) : ?>
						<p class="breathing-exercise__description"><?php echo esc_html( $description ); ?></p>
					<?php endif; ?>
					<?php if ( ! empty( $cta_text ) ) : ?>
						<button class="<?php echo esc_attr( implode( ' ', $cta_class ) ); ?>" type="button">
							<?php echo $cta_text; ?>
						</button>
					<?php endif; ?>
				</div>
			</div>
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
