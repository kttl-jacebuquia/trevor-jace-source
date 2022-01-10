<?php namespace TrevorWP\Theme\ACF\Field_Group;

use TrevorWP\CPT;
use TrevorWP\Theme\ACF\Util\Field_Val_Getter;
use TrevorWP\Theme\Helper\Modal;

class Form extends A_Field_Group {
	const MODAL_SELECTOR_PREFIX = 'js-form-modal-';
	const FIELD_FORM_ID         = 'form_id';
	const FIELD_SERVER_URL      = 'form_server_url';
	const FIELD_TITLE           = 'form_title';
	const FIELD_DESCRIPTION     = 'form_description';

	/** @inheritDoc */
	public static function prepare_register_args(): array {
		$form_id     = static::gen_field_key( static::FIELD_FORM_ID );
		$server_url  = static::gen_field_key( static::FIELD_SERVER_URL );
		$title       = static::gen_field_key( static::FIELD_TITLE );
		$description = static::gen_field_key( static::FIELD_DESCRIPTION );

		return array(
			'title'    => 'Promo',
			'fields'   => array(
				static::FIELD_FORM_ID     => array(
					'key'           => $form_id,
					'name'          => static::FIELD_FORM_ID,
					'label'         => 'Form ID',
					'type'          => 'text',
					'default_value' => '4840144',
					'required'      => 1,
					'wrapper'       => array(
						'width' => '50',
					),
				),
				static::FIELD_SERVER_URL  => array(
					'key'           => $server_url,
					'name'          => static::FIELD_SERVER_URL,
					'label'         => 'Server URL',
					'type'          => 'url',
					'required'      => 1,
					'default_value' => 'https://trevor.tfaforms.net/',
					'wrapper'       => array(
						'width' => '50',
					),
				),
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
			),
			'location' => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => CPT\Form::POST_TYPE,
					),
				),
			),
		);
	}

	public static function create_modal( $post = null ) {
		if ( ! $post ) {
			return;
		}

		$val     = new Field_Val_Getter( static::class, $post );
		$title   = $val->get( static::FIELD_TITLE );
		$content = static::render( $post );
		$id      = static::gen_modal_id( $post->ID );

		$options = array(
			'id'     => $id,
			'target' => '.' . $id,
			'class'  => array( 'form-modal' ),
			'title'  => $title,
		);

		// Ensure that modals are only rendered down the document
		add_action(
			'wp_footer',
			function() use ( $content, $options ) {
				echo ( new Modal( $content, $options ) )->render();
			},
			10,
			0
		);
	}

	public static function render( $post = null ) {
		$val         = new Field_Val_Getter( static::class, $post );
		$form_id     = $val->get( static::FIELD_FORM_ID );
		$server_url  = $val->get( static::FIELD_SERVER_URL );
		$title       = $val->get( static::FIELD_TITLE );
		$description = $val->get( static::FIELD_DESCRIPTION );

		// Get FormAssembly form using shortcode
		$form_assembly_form = do_shortcode( "[formassembly formid={$form_id} server=\"{$server_url}\"]" );

		// Remove embedded styles and extra gtm scripts
		$pattern            = implode(
			'|',
			array(
				'<style[^>]*>[^<]*<\/style>',
				'<link[^>]*theme-51\.css[^>]*>',
				'style="[^"]*"',
				'[^>]+GTM-[a-z\d]+[^<]+',
			),
		);
		$form_assembly_form = preg_replace( "/{$pattern}/i", '', $form_assembly_form );

		ob_start();
		?>
			<div class="form-modal__content">
				<?php if ( ! empty( $title ) ) : ?>
					<h2 class="form-modal__heading"><?php echo esc_html( $title ); ?></h2>
				<?php endif; ?>

				<?php if ( ! empty( $description ) ) : ?>
					<p class="form-modal__description"><?php echo esc_html( $description ); ?></p>
				<?php endif; ?>

				<div class="form-modal__fields">
					<?php echo $form_assembly_form; ?>
				</div>
			</div>
		<?php
		return ob_get_clean();
	}

	public static function gen_modal_id( $id = null ) {
		$new_id = $id ?? wp_generate_uuid4();
		return static::MODAL_SELECTOR_PREFIX . '-' . $new_id;
	}
}
