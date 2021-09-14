<?php namespace TrevorWP\Theme\ACF\Field_Group;

use TrevorWP\Theme\ACF\Field\Color;

class Text_Overlapping_Image extends A_Field_Group implements I_Block, I_Renderable {
	const FIELD_TITLE          = 'title';
	const FIELD_DESCRIPTION    = 'description';
	const FIELD_BUTTON         = 'button';
	const FIELD_IMAGE          = 'image';
	const FIELD_IMAGE_TILTED   = 'image_titled';
	const FIELD_NUMBERS        = 'numbers';
	const FIELD_NUMBER_VALUE   = 'number_value';
	const FIELD_NUMBER_CAPTION = 'number_caption';
	const FIELD_STYLES         = 'styles';
	const FIELD_BUTTON_STYLES  = 'btn_styles';
	const FIELD_BTN_BGCOLOR    = 'btn_bgcolor';
	const FIELD_BTN_TEXTCOLOR  = 'btn_textcolor';
	const FIELD_ACC_STYLING    = 'styling';
	const FIELD_ACC_DETAILS    = 'details';

	/**
	 * @inheritDoc
	 */
	protected static function prepare_register_args(): array {
		$title          = static::gen_field_key( static::FIELD_TITLE );
		$description    = static::gen_field_key( static::FIELD_DESCRIPTION );
		$button         = static::gen_field_key( static::FIELD_BUTTON );
		$image          = static::gen_field_key( static::FIELD_IMAGE );
		$image_tilted   = static::gen_field_key( static::FIELD_IMAGE_TILTED );
		$numbers        = static::gen_field_key( static::FIELD_NUMBERS );
		$number_value   = static::gen_field_key( static::FIELD_NUMBER_VALUE );
		$number_caption = static::gen_field_key( static::FIELD_NUMBER_CAPTION );
		$acc_styling    = static::gen_field_key( static::FIELD_ACC_STYLING );
		$acc_details    = static::gen_field_key( static::FIELD_ACC_DETAILS );
		$styles         = static::gen_field_key( static::FIELD_STYLES );
		$btn_styles     = static::gen_field_key( static::FIELD_BUTTON_STYLES );
		$btn_bgcolor    = static::gen_field_key( static::FIELD_BTN_BGCOLOR );
		$btn_textcolor  = static::gen_field_key( static::FIELD_BTN_TEXTCOLOR );

		return array(
			'title'  => 'Text + Overlapping Image',
			'fields' => array_merge(
				static::_gen_accordion_field(
					'Styling',
					array(
						'key'  => $acc_styling,
						'name' => static::FIELD_ACC_STYLING,
					),
				),
				array(
					static::FIELD_STYLES => Block_Styles::clone(
						array(
							'key'  => $styles,
							'name' => static::FIELD_STYLES,
						),
					),
				),
				static::_gen_accordion_field(
					'Details',
					array(
						'key'  => $acc_details,
						'name' => static::FIELD_ACC_DETAILS,
						'open' => 1,
					),
				),
				array(
					static::FIELD_TITLE         => array(
						'key'   => $title,
						'name'  => static::FIELD_TITLE,
						'label' => 'Title',
						'type'  => 'text',
					),
					static::FIELD_DESCRIPTION   => array(
						'key'   => $description,
						'name'  => static::FIELD_DESCRIPTION,
						'label' => 'Description',
						'type'  => 'textarea',
					),
					static::FIELD_BUTTON        => array(
						'key'           => $button,
						'name'          => static::FIELD_BUTTON,
						'label'         => 'Button',
						'type'          => 'link',
						'return_format' => 'array',
					),
					static::FIELD_BUTTON_STYLES => array(
						'key'        => $btn_styles,
						'name'       => static::FIELD_BUTTON_STYLES,
						'label'      => 'Button Styles',
						'type'       => 'group',
						'layout'     => 'block',
						'sub_fields' => array(
							static::FIELD_BTN_BGCOLOR   => Color::gen_args(
								$btn_bgcolor,
								static::FIELD_BTN_BGCOLOR,
								array(
									'label'         => 'Button Background Color',
									'wrapper'       => array(
										'width' => '50',
									),
									'default_value' => 'white',
								),
							),
							static::FIELD_BTN_TEXTCOLOR => Color::gen_args(
								$btn_textcolor,
								static::FIELD_BTN_TEXTCOLOR,
								array(
									'label'         => 'Button Text Color',
									'wrapper'       => array(
										'width' => '50',
									),
									'default_value' => 'current',
								),
							),
						),
					),
					static::FIELD_IMAGE         => array(
						'key'           => $image,
						'name'          => static::FIELD_IMAGE,
						'label'         => 'Image',
						'type'          => 'image',
						'return_format' => 'array',
						'preview_size'  => 'thumbnail',
						'library'       => 'all',
					),
					static::FIELD_IMAGE_TILTED         => array(
						'key'           => $image_tilted,
						'name'          => static::FIELD_IMAGE_TILTED,
						'label'         => 'Tilted Image',
						'type'          => 'true_false',
						'default_value' => 0,
						'ui'            => 1,
					),
					static::FIELD_NUMBERS       => array(
						'key'          => $numbers,
						'name'         => static::FIELD_NUMBERS,
						'label'        => 'Numbers with caption',
						'type'         => 'repeater',
						'required'     => 0,
						'collapsed'    => $number_caption,
						'layout'       => 'block',
						'button_label' => 'Add Number',
						'sub_fields'   => array(
							array(
								'key'   => $number_value,
								'name'  => static::FIELD_NUMBER_VALUE,
								'label' => 'Number',
								'type'  => 'number',
							),
							array(
								'key'   => $number_caption,
								'name'  => static::FIELD_NUMBER_CAPTION,
								'label' => 'Caption',
								'type'  => 'text',
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
				'title'      => 'Text + Overlapping Image',
				'post_types' => array( 'page' ),
			)
		);
	}

	/**
	 * @inheritDoc
	 */
	public static function render( $post = false, array $data = null, array $options = array() ): ?string {
		$title        = static::get_val( static::FIELD_TITLE );
		$description  = static::get_val( static::FIELD_DESCRIPTION );
		$button       = static::get_val( static::FIELD_BUTTON );
		$image        = static::get_val( static::FIELD_IMAGE );
		$image_tilted = static::get_val( static::FIELD_IMAGE_TILTED );
		$numbers      = static::get_val( static::FIELD_NUMBERS );
		$styles       = static::get_val( static::FIELD_STYLES );
		$btn_styles   = static::get_val( static::FIELD_BUTTON_STYLES );

		list(
			$btn_bgcolor,
			$btn_textcolor,
		) = array_values( $btn_styles );

		list(
			$bg_color,
			$text_color,
		) = array_values( $styles );

		// Build class
		$class = array(
			'text-overlapping-image',
			'bg-' . $bg_color,
			'text-' . $text_color,
		);

		if ( empty( $numbers ) ) {
			$class[] = 'text-overlapping-image--no-numbers';
		}

		// CTA Attrs
		$btn_class = array(
			'bg-' . $btn_bgcolor,
			'text-' . $btn_textcolor,
			'text-overlapping-image__cta',
		);
		$btn_attrs = array(
			'href'   => esc_url( $button['url'] ),
			'target' => esc_attr( $button['target'] ),
		);

		$image_classnames = array(
			'text-overlapping-image__image',
		);

		if ( $image_tilted ) {
			$image_classnames[] = 'text-overlapping-image__image--tilted';
		}

		ob_start();
		?>
		<div <?php echo static::render_attrs( $class ); ?>>
			<div class="text-overlapping-image__container">
				<div class="text-overlapping-image__body">
					<?php if ( ! empty( $title ) ) : ?>
						<h2 class="text-overlapping-image__heading"><?php echo esc_html( $title ); ?></h2>
					<?php endif; ?>

					<?php if ( ! empty( $description ) ) : ?>
						<p class="text-overlapping-image__description"><?php echo esc_html( $description ); ?></p>
					<?php endif; ?>

					<?php if ( ! empty( $numbers ) ) : ?>
						<div class="text-overlapping-image__numbers">
							<?php foreach ( $numbers as $number ) : ?>
								<div class="text-overlapping-image__number">
									<div class="text-overlapping-image__number-value">
										<?php echo $number[ static::FIELD_NUMBER_VALUE ]; ?>
									</div>
									<div class="text-overlapping-image__number-caption">
										<?php echo $number[ static::FIELD_NUMBER_CAPTION ]; ?>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>

					<?php if ( ! empty( $button['url'] ) ) : ?>
						<div class="text-overlapping-image__cta-wrap">
							<a <?php echo static::render_attrs( $btn_class, $btn_attrs ); ?>><?php echo esc_html( $button['title'] ); ?></a>
						</div>
					<?php endif; ?>
				</div>
				<?php if ( ! empty( $image['url'] ) ) : ?>
					<div <?php echo static::render_attrs( $image_classnames ); ?>>
						<img src="<?php echo esc_url( $image['url'] ); ?>" alt="<?php echo ( ! empty( $image['alt'] ) ) ? esc_attr( $image['alt'] ) : esc_attr( $title ); ?>">
					</div>
				<?php endif; ?>
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
