<?php namespace TrevorWP\Theme\ACF\Field_Group;

use TrevorWP\CPT;
use TrevorWP\Theme\ACF\Util\Field_Val_Getter;

class Form extends A_Field_Group {
	const FIELD_FORM_ID     = 'form_id';
	const FIELD_SERVER_URL  = 'form_server_url';
	const FIELD_TITLE       = 'form_title';
	const FIELD_DESCRIPTION = 'form_description';

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

	public static function render( $post = null ) {
		$val         = new Field_Val_Getter( static::class, $post );
		$form_id     = $val->get( static::FIELD_FORM_ID );
		$server_url  = $val->get( static::FIELD_SERVER_URL );
		$title       = $val->get( static::FIELD_TITLE );
		$description = $val->get( static::FIELD_DESCRIPTION );

		// Get FormAssembly form using shortcode
		$form_assembly_form = do_shortcode( "[formassembly formid={$form_id} server=\"{$server_url}\"]" );

		// Remove embedded styles
		$pattern            = implode( '|', array( '<style[^>]*>[^<]*<\/style>', '<link[^>]*theme-51\.css[^>]*>', 'style="[^"]*"' ) );
		$form_assembly_form = preg_replace( "/{$pattern}/", '', $form_assembly_form );

		ob_start();
		?>
			<div class="fundraiser-quiz--form fundraiser-quiz--steps" data-vertex="form">
				<?php if ( ! empty( $title ) ) : ?>
					<h2 class="fundraiser-quiz__title"><?php echo esc_html( $title ); ?></h2>
				<?php endif; ?>

				<?php if ( ! empty( $description ) ) : ?>
					<p class="fundraiser-quiz__description"><?php echo esc_html( $title ); ?></p>
				<?php endif; ?>

				<div class="fundraiser-quiz__fields">
					<?php echo $form_assembly_form; ?>
				</div>
			</div>
		<?php
		return ob_get_clean();
	}
}
