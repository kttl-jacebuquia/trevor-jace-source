<?php namespace TrevorWP\Theme\ACF\Field_Group;

use TrevorWP\CPT;
use TrevorWP\Theme\ACF\Util\Field_Val_Getter;

class Promo_Popup extends A_Field_Group {
	const FIELD_IMAGE           = 'promo_image';
	const FIELD_HEADLINE        = 'promo_headline';
	const FIELD_DESCRIPTION     = 'promo_description';
	const FIELD_BUTTON          = 'promo_button';
	const MODAL_SELECTOR_PREFIX = 'js-promo-popup-modal';

	/** @inheritDoc */
	public static function prepare_register_args(): array {
		$image       = static::gen_field_key( static::FIELD_IMAGE );
		$headline    = static::gen_field_key( static::FIELD_HEADLINE );
		$description = static::gen_field_key( static::FIELD_DESCRIPTION );
		$button      = static::gen_field_key( static::FIELD_BUTTON );

		return array(
			'title'    => 'Promo',
			'fields'   => array(
				static::FIELD_IMAGE       => array(
					'key'          => $image,
					'name'         => static::FIELD_IMAGE,
					'label'        => 'Image',
					'type'         => 'image',
					'preview_size' => 'thumbnail',
				),
				static::FIELD_HEADLINE    => array(
					'key'       => $headline,
					'name'      => static::FIELD_HEADLINE,
					'label'     => 'Headline',
					'type'      => 'text',
					'required'  => true,
					'maxlength' => 50,
				),
				static::FIELD_DESCRIPTION => array(
					'key'       => $description,
					'name'      => static::FIELD_DESCRIPTION,
					'label'     => 'Description',
					'type'      => 'textarea',
					'maxlength' => 200,
				),
				static::FIELD_BUTTON      => Button::clone(
					array(
						'key'           => $button,
						'name'          => static::FIELD_BUTTON,
						'label'         => 'Button',
						'type'          => 'link',
						'return_format' => 'array',
						'display'       => 'group',
						'layout'        => 'block',
					),
				),
			),
			'location' => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => CPT\Promo_Popup::POST_TYPE,
					),
				),
			),
		);
	}

	/**
	 * Renders the promo popup modal contents.
	 */
	public static function render( $post = null ): string {
		$val         = new Field_Val_Getter( static::class, $post );
		$image       = $val->get( static::FIELD_IMAGE );
		$headline    = $val->get( static::FIELD_HEADLINE );
		$description = $val->get( static::FIELD_DESCRIPTION );
		$button      = $val->get( static::FIELD_BUTTON );

		ob_start();
		?>
			<div>
				<div>
					<?php if ( ! empty( $image['url'] ) ) : ?>
						<img src="<?php echo esc_url( $image['url'] ); ?>" alt="<?php echo esc_attr( $image['alt'] ); ?>">
					<?php endif; ?>
					<h2><?php echo $headline; ?></h2>
					<p><?php echo $description; ?></p>
					<?php if ( ! empty( $button ) ) : ?>
						<?php echo Button::render( false, $button, array() ); ?>
					<?php endif; ?>
				</div>
			</div>
		<?php
		return ob_get_clean();
	}

	public static function gen_modal_id( $id ) {
		if ( ! empty( $id ) ) {
			return static::MODAL_SELECTOR_PREFIX . '-' . $id;
		}

		return static::MODAL_SELECTOR_PREFIX;
	}
}
