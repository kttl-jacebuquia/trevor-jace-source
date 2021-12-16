<?php namespace TrevorWP\Theme\ACF\Field_Group;

use TrevorWP\CPT;
use TrevorWP\Theme\ACF\Util\Field_Val_Getter;

class Promo_Popup extends A_Field_Group {
	const FIELD_BLOCK_STYLES       = 'block_styles';
	const FIELD_PROMO_SCHEDULE     = 'promo_schedule';
	const FIELD_PROMO_START_DATE   = 'promo_start_date';
	const FIELD_PROMO_END_DATE     = 'promo_end_date';
	const FIELD_IMAGE              = 'promo_image';
	const FIELD_IMAGE_LINK         = 'image_link';
	const FIELD_HEADLINE           = 'promo_headline';
	const FIELD_WITH_FORMAT        = 'with_format';
	const FIELD_FORMAT_DESCRIPTION = 'format_description';
	const FIELD_DESCRIPTION        = 'promo_description';
	const FIELD_BUTTON             = 'promo_button';
	const MODAL_SELECTOR_PREFIX    = 'js-promo-popup-modal';

	/** @inheritDoc */
	public static function prepare_register_args(): array {
		$block_styles       = static::gen_field_key( static::FIELD_BLOCK_STYLES );
		$promo_schedule     = static::gen_field_key( static::FIELD_PROMO_SCHEDULE );
		$promo_start_date   = static::gen_field_key( static::FIELD_PROMO_START_DATE );
		$promo_end_date     = static::gen_field_key( static::FIELD_PROMO_END_DATE );
		$image              = static::gen_field_key( static::FIELD_IMAGE );
		$image_link         = static::gen_field_key( static::FIELD_IMAGE_LINK );
		$headline           = static::gen_field_key( static::FIELD_HEADLINE );
		$with_format        = static::gen_field_key( static::FIELD_WITH_FORMAT );
		$format_description = static::gen_field_key( static::FIELD_FORMAT_DESCRIPTION );
		$description        = static::gen_field_key( static::FIELD_DESCRIPTION );
		$button             = static::gen_field_key( static::FIELD_BUTTON );

		return array(
			'title'    => 'Promo',
			'fields'   => array(
				static::FIELD_BLOCK_STYLES       => Block_Styles::clone(
					array(
						'key'     => $block_styles,
						'name'    => static::FIELD_BLOCK_STYLES,
						'label'   => 'Block Styles',
						'display' => 'seamless',
						'layout'  => 'block',
					)
				),
				static::FIELD_PROMO_SCHEDULE     => array(
					'key'         => $promo_schedule,
					'name'        => static::FIELD_PROMO_SCHEDULE,
					'label'       => 'Add Schedule',
					'type'        => 'true_false',
					'ui'          => 1,
					'ui_on_text'  => '',
					'ui_off_text' => '',
				),
				static::FIELD_PROMO_START_DATE   => array(
					'key'               => $promo_start_date,
					'name'              => static::FIELD_PROMO_START_DATE,
					'label'             => 'Start Date',
					'type'              => 'date_time_picker',
					'wrapper'           => array(
						'width' => '50%',
					),
					'display_format'    => 'M j, Y g:i:s a',
					'return_format'     => 'M j, Y H:i:s',
					'first_day'         => 0,
					'conditional_logic' => array(
						array(
							array(
								'field'    => $promo_schedule,
								'operator' => '==',
								'value'    => '1',
							),
						),
					),
				),
				static::FIELD_PROMO_END_DATE     => array(
					'key'               => $promo_end_date,
					'name'              => static::FIELD_PROMO_END_DATE,
					'label'             => 'End Date',
					'type'              => 'date_time_picker',
					'wrapper'           => array(
						'width' => '50%',
					),
					'display_format'    => 'M j, Y g:i:s a',
					'return_format'     => 'M j, Y H:i:s',
					'first_day'         => 0,
					'conditional_logic' => array(
						array(
							array(
								'field'    => $promo_schedule,
								'operator' => '==',
								'value'    => '1',
							),
						),
					),
				),
				static::FIELD_IMAGE              => array(
					'key'          => $image,
					'name'         => static::FIELD_IMAGE,
					'label'        => 'Image',
					'type'         => 'image',
					'preview_size' => 'thumbnail',
					'wrapper'      => array(
						'width' => '50',
					),
				),
				static::FIELD_IMAGE_LINK         => array(
					'key'     => $image_link,
					'name'    => static::FIELD_IMAGE_LINK,
					'label'   => 'Image Link',
					'type'    => 'url',
					'wrapper' => array(
						'width' => '50',
					),
				),
				static::FIELD_HEADLINE           => array(
					'key'       => $headline,
					'name'      => static::FIELD_HEADLINE,
					'label'     => 'Headline',
					'type'      => 'text',
					'required'  => true,
					'maxlength' => 50,
				),
				static::FIELD_WITH_FORMAT        => array(
					'key'           => $with_format,
					'name'          => static::FIELD_WITH_FORMAT,
					'label'         => 'With Format?',
					'type'          => 'button_group',
					'choices'       => array(
						'yes' => 'Yes',
						'no'  => 'No',
					),
					'default_value' => 'no',
				),
				static::FIELD_FORMAT_DESCRIPTION => array(
					'key'               => $format_description,
					'name'              => static::FIELD_FORMAT_DESCRIPTION,
					'label'             => 'Description',
					'type'              => 'wysiwyg',
					'tabs'              => 'all',
					'toolbar'           => 'link',
					'media_upload'      => 0,
					'delay'             => 0,
					'conditional_logic' => array(
						array(
							array(
								'field'    => $with_format,
								'operator' => '==',
								'value'    => 'yes',
							),
						),
					),
				),
				static::FIELD_DESCRIPTION        => array(
					'key'               => $description,
					'name'              => static::FIELD_DESCRIPTION,
					'label'             => 'Description',
					'type'              => 'textarea',
					'maxlength'         => 200,
					'conditional_logic' => array(
						array(
							array(
								'field'    => $with_format,
								'operator' => '==',
								'value'    => 'no',
							),
						),
					),
				),
				static::FIELD_BUTTON             => Button::clone(
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
		$val                = new Field_Val_Getter( static::class, $post );
		$image              = $val->get( static::FIELD_IMAGE );
		$image_link         = $val->get( static::FIELD_IMAGE_LINK );
		$headline           = $val->get( static::FIELD_HEADLINE );
		$with_format        = $val->get( static::FIELD_WITH_FORMAT );
		$format_description = $val->get( static::FIELD_FORMAT_DESCRIPTION );
		$description        = $val->get( static::FIELD_DESCRIPTION );
		$button             = $val->get( static::FIELD_BUTTON );

		$description = ( 'yes' === $with_format ) ? $format_description : $description;
		ob_start();
		?>
			<div class="promo-popup-modal__content">
				<?php if ( ! empty( $image['url'] ) ) : ?>
					<figure class="promo-popup-modal__image">
						<?php if ( ! empty( $image_link ) ) : ?>
							<a href="<?php echo esc_url( $image_link ); ?>" target="_blank">
								<img src="<?php echo esc_url( $image['url'] ); ?>" alt="<?php echo esc_attr( $image['alt'] ); ?>">
							</a>
						<?php else : ?>
							<img src="<?php echo esc_url( $image['url'] ); ?>" alt="<?php echo esc_attr( $image['alt'] ); ?>">
						<?php endif; ?>
					</figure>
				<?php endif; ?>
				<div class="promo-popup-modal__body">
					<h2 class="promo-popup-modal__headline"><?php echo $headline; ?></h2>
					<div class="promo-popup-modal__description"><?php echo $description; ?></div>
					<?php if ( ! empty( $button ) ) : ?>
						<div class="promo-popup-modal__button-wrap">
							<?php echo Button::render( false, $button, array( 'btn_cls' => array( 'promo-popup-modal__button' ) ) ); ?>
						</div>
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

	public static function get_promo_schedule( $post ) {
		$val = new Field_Val_Getter( static::class, $post );

		$schedule   = $val->get( static::FIELD_PROMO_SCHEDULE );
		$start_date = $val->get( static::FIELD_PROMO_START_DATE );
		$end_date   = $val->get( static::FIELD_PROMO_END_DATE );

		// Consider active if no schedule is set
		if ( ! $schedule ) {
			return true;
		}

		$data = array(
			'schedule'   => $schedule,
			'start_date' => $start_date,
			'end_date'   => $end_date,
		);

		return self::is_promo_active( $data );
	}

	/**
	 * Check if promo popup is active
	 */
	public static function is_promo_active( array $data ): bool {
		$current_date = current_datetime();
		$current_date = wp_date( 'M j, Y H:i:s', $current_date->date, $current_date->timezone );
		$current_date = strtotime( $current_date );

		$start_date = ! empty( $data['start_date'] ) ? strtotime( $data['start_date'] ) : '';
		$end_date   = ! empty( $data['end_date'] ) ? strtotime( $data['end_date'] ) : '';

		if ( empty( $start_date ) && empty( $end_date ) ) {
			return true;
		}

		if ( ! empty( $start_date ) && ! empty( $end_date ) ) {
			if ( $current_date >= $start_date && $current_date <= $end_date ) {
				return true;
			}
		}

		if ( ! empty( $start_date ) && empty( $end_date ) ) {
			if ( $current_date >= $start_date ) {
				return true;
			}
		}

		if ( empty( $start_date ) && ! empty( $end_date ) ) {
			if ( $current_date <= $end_date ) {
				return true;
			}
		}

		return false;
	}
}
