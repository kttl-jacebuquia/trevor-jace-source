<?php namespace TrevorWP\Theme\ACF\Field_Group;

use TrevorWP\CPT;
use TrevorWP\Theme\ACF\Util\Field_Val_Getter;
use TrevorWP\Theme\Helper\Modal;

class Text_Only_Popup extends A_Field_Group {
	const FIELD_SUBHEADER       = 'subheader';
	const FIELD_BODY            = 'body';
	const MODAL_SELECTOR_PREFIX = 'js-text-only-popup';

	/** @inheritDoc */
	public static function prepare_register_args(): array {
		$subheader = static::gen_field_key( static::FIELD_SUBHEADER );
		$body      = static::gen_field_key( static::FIELD_BODY );

		return array(
			'title'    => 'Details',
			'fields'   => array(
				static::FIELD_SUBHEADER => array(
					'key'   => $subheader,
					'name'  => static::FIELD_SUBHEADER,
					'label' => 'Sub-header',
					'type'  => 'text',
				),
				static::FIELD_BODY      => array(
					'key'          => $body,
					'name'         => static::FIELD_BODY,
					'label'        => 'Body',
					'type'         => 'wysiwyg',
					'tabs'         => 'visual',
					'toolbar'      => 'basic',
					'media_upload' => 0,
					'delay'        => 0,
				),
			),
			'location' => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => CPT\Text_Only_Popup::POST_TYPE,
					),
				),
			),
		);
	}

	/**
	 * @params {WP_Post or ID} $post
	 */
	public static function render_modal_for( $post = null ) {
		if ( empty( $post ) ) {
			return '';
		}

		$post    = get_post( $post );
		$title   = get_the_title( $post );
		$id      = static::gen_modal_id( $post->ID );
		$options = array(
			'id'     => $id,
			'target' => '.' . $id,
			'class'  => array( 'text-only-popup' ),
			'title'  => $title,
		);
		$content = static::render_content_for( $post );
		Modal::create_and_render( $content, $options );
	}

	/**
	 * @params {WP_Post} $post
	 */
	public static function render_content_for( $post ) {
		$val       = new Field_Val_Getter( static::class, $post );
		$header    = get_the_title( $post );
		$subheader = $val->get( static::FIELD_SUBHEADER );
		$body      = $val->get( static::FIELD_BODY );

		ob_start();
		?>
		<div class="text-only-popup">
			<div class="text-only-popup__content">
				<h1 class="text-only-popup__title"><?php echo esc_html( $header ); ?></h1>
				<?php if ( ! empty( $subheader ) ) : ?>
					<h2 class="text-only-popup__subtitle"><?php echo esc_html( $subheader ); ?></h2>
				<?php endif; ?>
				<div class="text-only-popup__body"><?php echo $body; ?></div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	public static function gen_modal_id( $text_only_popup_id ) {
		if ( empty( $text_only_popup_id ) ) {
			return '';
		}
		return static::MODAL_SELECTOR_PREFIX . '-' . $text_only_popup_id;
	}
}
