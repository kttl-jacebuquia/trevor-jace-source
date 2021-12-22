<?php namespace TrevorWP\Theme\Ajax;

use TrevorWP\Theme\ACF\Options_Page;
use TrevorWP\Theme\ACF\Field_Group;
use TrevorWP\Theme\ACF\Util\Field_Val_Getter;

/**
 *
 */
class Promo_Popup {

	public static function construct() {
		/**
		 * Ajax for Promo_Popup
		 */
		add_action( 'wp_ajax_nopriv_promo_popup', array( self::class, 'promo_popup' ) );
		add_action( 'wp_ajax_promo_popup', array( self::class, 'promo_popup' ) );
	}

	public static function promo_popup() {
		$type = $_GET['type'];
		$promo_popup_modal = static::get_promo_popup( $type );

		wp_die(
			json_encode(
				array(
					'status' => 'SUCCESS',
					'data' => $promo_popup_modal,
				),
			),
			200,
		);
	}

	public static function get_promo_popup( string $promo_type  ) {
		// Promo Modal
		$promo_popup = Options_Page\Promo::get_promo_popup($promo_type);
		$is_promo_active = Field_Group\Promo_Popup::get_promo_schedule( $promo_popup['promo'] );

		if ( $promo_popup['state'] && $is_promo_active ) {
			$val          = new Field_Val_Getter( Field_Group\Promo_Popup::class, $promo_popup['promo'] );
			$block_styles = $val->get( Field_Group\Promo_Popup::FIELD_BLOCK_STYLES );

			list( $bg_color, $text_color ) = array_values( $block_styles );

			$markup = ( new \TrevorWP\Theme\Helper\Modal(
				Options_Page\Promo::render( $promo_type ),
				array(
					'target'          => '.promo-popup-modal',
					'id'              => 'js-promo-popup-modal-' . $promo_popup['promo']->ID,
					'class'           => array( 'promo-popup-modal' ),
					'container_class' => array( "bg-{$bg_color}", "text-{$text_color}" ),
				)
			) )->render( false );

			return array(
				'markup' => $markup,
				'id' => $promo_popup['promo']->ID,
			);
		}

		return null;
	}

}
