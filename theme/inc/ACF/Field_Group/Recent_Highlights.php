<?php namespace TrevorWP\Theme\ACF\Field_Group;

use TrevorWP\CPT\Donate\Partner_Prod;
use TrevorWP\CPT\RC\RC_Object;
use TrevorWP\Theme\Helper;

use TrevorWP\Theme\ACF\Field\Color;

class Recent_Highlights extends A_Field_Group implements I_Block, I_Renderable {
	const FIELD_TITLE       = 'title';
	const FIELD_DESCRIPTION = 'description';
	const FIELD_CARDS       = 'cards';

	/**
	 * @inheritDoc
	 */
	protected static function prepare_register_args(): array {
		$title       = static::gen_field_key( static::FIELD_TITLE );
		$description = static::gen_field_key( static::FIELD_DESCRIPTION );
		$cards       = static::gen_field_key( static::FIELD_CARDS );

		return array(
			'title'  => 'Recent Highlights',
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
				static::FIELD_CARDS       => array(
					'key'       => $cards,
					'name'      => static::FIELD_CARDS,
					'label'     => 'Recent Highlights',
					'type'      => 'relationship',
					'required'  => 1,
					'post_type' => array( 'post' ),
					'min'       => 1,
					'max'       => 6,
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
				'title'      => 'Recent Highlights',
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
		$cards       = static::get_val( static::FIELD_CARDS );

		ob_start();
		// Next Step - FE
		?>
		<div>
			<?php if ( ! empty( $title ) ) : ?>
				<h3><?php echo esc_html( $title ); ?></h3>
			<?php endif; ?>

			<?php if ( ! empty( $description ) ) : ?>
				<p><?php echo esc_html( $description ); ?></p>
			<?php endif; ?>

			<?php if ( ! empty( $cards ) ) : ?>
				<div>
					<?php foreach ( $cards as $key => $card ) : ?>
						<div>
							<?php echo Helper\Card::post( $card, $key, $options ); ?>
						</div>
					<?php endforeach; ?>
				</div>
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
