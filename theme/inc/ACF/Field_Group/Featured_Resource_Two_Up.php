<?php namespace TrevorWP\Theme\ACF\Field_Group;

use TrevorWP\CPT\Donate\Partner_Prod;
use TrevorWP\CPT\RC\RC_Object;
use TrevorWP\Theme\Helper;

use TrevorWP\Theme\ACF\Field\Color;

class Featured_Resource_Two_Up extends A_Field_Group implements I_Block, I_Renderable {
	const FIELD_BG_COLOR   = 'bg_color';
	const FIELD_TEXT_COLOR = 'text_color';
	const FIELD_EYEBROW    = 'eyebrow';
	const FIELD_TITLE      = 'title';
	const FIELD_CARDS      = 'cards';

	/**
	 * @inheritDoc
	 */
	protected static function prepare_register_args(): array {
		$bg_color   = static::gen_field_key( static::FIELD_BG_COLOR );
		$text_color = static::gen_field_key( static::FIELD_TEXT_COLOR );
		$eyebrow    = static::gen_field_key( static::FIELD_EYEBROW );
		$title      = static::gen_field_key( static::FIELD_TITLE );
		$cards      = static::gen_field_key( static::FIELD_CARDS );

		return array(
			'title'  => 'Featured Resource 2-Up Block',
			'fields' => array(
				static::FIELD_BG_COLOR   => Color::gen_args(
					$bg_color,
					static::FIELD_BG_COLOR,
					array(
						'label'   => 'Background Color',
						'default' => 'white',
						'wrapper' => array(
							'width' => '50%',
						),
					)
				),
				static::FIELD_TEXT_COLOR => Color::gen_args(
					$text_color,
					static::FIELD_TEXT_COLOR,
					array(
						'label'   => 'Text Color',
						'default' => 'teal-dark',
						'wrapper' => array(
							'width' => '50%',
						),
					),
				),
				static::FIELD_EYEBROW    => array(
					'key'   => $eyebrow,
					'name'  => static::FIELD_EYEBROW,
					'label' => 'Eyebrow',
					'type'  => 'text',
				),
				static::FIELD_TITLE      => array(
					'key'   => $title,
					'name'  => static::FIELD_TITLE,
					'label' => 'Title',
					'type'  => 'text',
				),
				static::FIELD_CARDS      => array(
					'key'       => $cards,
					'name'      => static::FIELD_CARDS,
					'label'     => 'Cards',
					'type'      => 'relationship',
					'required'  => 1,
					'post_type' => array_merge( RC_Object::$PUBLIC_POST_TYPES, array( Partner_Prod::POST_TYPE, 'post' ) ),
					'min'       => 2,
					'max'       => 2,
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
				'title'      => 'Featured Resource 2-Up Block',
				'post_types' => array( 'page' ),
			)
		);
	}

	/**
	 * @inheritDoc
	 */
	public static function render( $post = false, array $data = null, array $options = array() ): ?string {
		$text_color = ! empty( static::get_val( static::FIELD_TEXT_COLOR ) ) ? static::get_val( static::FIELD_TEXT_COLOR ) : 'teal-dark';
		$bg_color   = ! empty( static::get_val( static::FIELD_BG_COLOR ) ) ? static::get_val( static::FIELD_BG_COLOR ) : 'white';
		$eyebrow    = static::get_val( static::FIELD_EYEBROW );
		$title      = static::get_val( static::FIELD_TITLE );
		$cards      = static::get_val( static::FIELD_CARDS );

		$styles = 'bg-' . $bg_color . ' ' . 'text-' . $text_color;

		$options = array();

		ob_start();
		// Next Step - FE
		?>
		<div class="container mx-auto  <?php echo esc_attr( $styles ); ?>">
			<?php if ( ! empty( $eyebrow ) ) : ?>
				<h3><?php echo esc_html( $eyebrow ); ?></h3>
			<?php endif; ?>

			<?php if ( ! empty( $title ) ) : ?>
				<h3><?php echo esc_html( $title ); ?></h3>
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
