<?php

namespace TrevorWP\Theme\ACF\Field_Group;

use TrevorWP\CPT\Team;
use TrevorWP\Theme\Helper;

class Staff_Module extends A_Field_Group implements I_Block, I_Renderable {

	const FIELD_TITLE                  = 'title';
	const FIELD_DESCRIPTION            = 'description';
	const FIELD_DESKTOP_TEXT_ALIGNMENT = 'desktop_text_alignment';
	const FIELD_CARD_TYPE              = 'card_type';
	const FIELD_RESEARCHERS            = 'researchers';
	const FIELD_FOUNDERS               = 'founders';

	/**
	 * @inheritDoc
	 */
	protected static function prepare_register_args(): array {
		$title                  = static::gen_field_key( static::FIELD_TITLE );
		$description            = static::gen_field_key( static::FIELD_DESCRIPTION );
		$desktop_text_alignment = static::gen_field_key( static::FIELD_DESKTOP_TEXT_ALIGNMENT );
		$card_type              = static::gen_field_key( static::FIELD_CARD_TYPE );
		$researchers            = static::gen_field_key( static::FIELD_RESEARCHERS );
		$founders               = static::gen_field_key( static::FIELD_FOUNDERS );

		return array(
			'title'  => 'Staff Module',
			'fields' => array(
				static::FIELD_TITLE                  => array(
					'key'   => $title,
					'name'  => static::FIELD_TITLE,
					'label' => 'Title',
					'type'  => 'text',
				),
				static::FIELD_DESCRIPTION            => array(
					'key'   => $description,
					'name'  => static::FIELD_DESCRIPTION,
					'label' => 'Description',
					'type'  => 'textarea',
				),
				static::FIELD_DESKTOP_TEXT_ALIGNMENT => array(
					'key'           => $desktop_text_alignment,
					'name'          => static::FIELD_DESKTOP_TEXT_ALIGNMENT,
					'label'         => 'Desktop Text Alignment',
					'type'          => 'button_group',
					'choices'       => array(
						'left'   => 'Left',
						'center' => 'Center',
					),
					'default_value' => 'center',
				),
				static::FIELD_CARD_TYPE              => array(
					'key'           => $card_type,
					'name'          => static::FIELD_CARD_TYPE,
					'label'         => 'Card Type',
					'type'          => 'button_group',
					'choices'       => array(
						'researchers' => 'Researchers',
						'founders'    => 'Founders',
					),
					'default_value' => 'researchers',
				),
				static::FIELD_RESEARCHERS            => array(
					'key'               => $researchers,
					'name'              => static::FIELD_RESEARCHERS,
					'label'             => 'Researchers',
					'type'              => 'relationship',
					'required'          => 1,
					'post_type'         => array( Team::POST_TYPE ),
					'taxonomy'          => array( Team::TAXONOMY_GROUP . ':researcher' ),
					'min'               => 1,
					'conditional_logic' => array(
						array(
							array(
								'field'    => $card_type,
								'operator' => '==',
								'value'    => 'researchers',
							),
						),
					),
				),
				static::FIELD_FOUNDERS               => array(
					'key'               => $founders,
					'name'              => static::FIELD_FOUNDERS,
					'label'             => 'Founders',
					'type'              => 'relationship',
					'required'          => 1,
					'post_type'         => array( Team::POST_TYPE ),
					'taxonomy'          => array( Team::TAXONOMY_GROUP . ':founder' ),
					'min'               => 1,
					'conditional_logic' => array(
						array(
							array(
								'field'    => $card_type,
								'operator' => '==',
								'value'    => 'founders',
							),
						),
					),
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
				'title'      => 'Staff Module',
				'post_types' => array( 'page' ),
			)
		);
	}

	/**
	 * @inheritDoc
	 */
	public static function render( $post = false, array $data = null, array $options = array() ): ?string {
		$title                  = static::get_val( static::FIELD_TITLE );
		$description            = static::get_val( static::FIELD_DESCRIPTION );
		$desktop_text_alignment = static::get_val( static::FIELD_DESKTOP_TEXT_ALIGNMENT );
		$card_type              = static::get_val( static::FIELD_CARD_TYPE );

		$cards = array();

		if ( 'researchers' === $card_type ) {
			$cards = static::get_val( static::FIELD_RESEARCHERS );
		} elseif ( 'founders' === $card_type ) {
			$cards = static::get_val( static::FIELD_FOUNDERS );
		}

		$styles = $desktop_text_alignment;

		$tile_options = array();

		ob_start();
		// Next Step - FE
		?>
		<div class="container mx-auto  <?php echo esc_attr( $styles ); ?>">
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
							<?php echo Helper\Tile::staff( $card, $key, $tile_options ); ?>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<?php if ( ! empty( $button['url'] ) ) : ?>
				<a href="<?php echo esc_url( $button['url'] ); ?>" target="<?php echo esc_attr( $button['target'] ); ?>"><?php echo esc_html( $button['title'] ); ?></a>
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
