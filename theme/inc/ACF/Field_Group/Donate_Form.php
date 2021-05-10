<?php namespace TrevorWP\Theme\ACF\Field_Group;

class Donate_Form extends A_Field_Group implements I_Block, I_Renderable {
	const FIELD_FORM_IMAGE = 'form_image';
	const FIELD_HEADING    = 'heading';
	const FIELD_INTRO      = 'intro';

	/**
	 * @inheritDoc
	 */
	protected static function prepare_register_args(): array {
		$form_image = static::gen_field_key( static::FIELD_FORM_IMAGE );
		$heading    = static::gen_field_key( static::FIELD_HEADING );
		$intro      = static::gen_field_key( static::FIELD_INTRO );

		return array(
			'title'  => 'Donate Form',
			'fields' => array(
				static::FIELD_FORM_IMAGE => array(
					'key'           => $form_image,
					'name'          => static::FIELD_FORM_IMAGE,
					'label'         => 'Form Image',
					'type'          => 'image',
					'return_format' => 'array',
					'preview_size'  => 'thumbnail',
					'library'       => 'all',
				),
				static::FIELD_HEADING    => array(
					'key'     => $heading,
					'name'    => static::FIELD_HEADING,
					'label'   => 'Heading',
					'type'    => 'text',
					'wrapper' => array(
						'width' => '50%',
					),
				),
				static::FIELD_INTRO      => array(
					'key'     => $intro,
					'name'    => static::FIELD_INTRO,
					'label'   => 'Intro',
					'type'    => 'text',
					'wrapper' => array(
						'width' => '50%',
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
				'title'      => 'Donate Form',
				'post_types' => array( 'page' ),
			)
		);
	}

	/**
	 * @inheritDoc
	 */
	public static function render( $post = false, array $data = null, array $options = array() ): ?string {
		$form_image = static::get_val( static::FIELD_FORM_IMAGE );
		$heading    = static::get_val( static::FIELD_HEADING );
		$intro      = static::get_val( static::FIELD_INTRO );

		ob_start();
		?>
		<div class="donation-form">
			<div class="donation-form__content">
				<div class="donation-form__content-wrapper">
					<h2><?php echo $heading; ?></h2>
					<p><?php echo $intro; ?></p>

					<form action="https://give.thetrevorproject.org/give/63307" method="get" id="donate-form">
						<div class="frequency">
							<div class="visually-hidden">
								<input type="radio" name="recurring" value="0" id="once" checked class="donation-frequency">
								<input type="radio" name="recurring" value="1" id="monthly" class="donation-frequency">

								<input type="radio" name="amount" value="30" id="amount-30" class="fixed-amount">
								<input type="radio" name="amount" value="60" id="amount-60" class="fixed-amount">
								<input type="radio" name="amount" value="120" id="amount-120" class="fixed-amount">
								<input type="radio" name="amount" value="250" id="amount-250" class="fixed-amount">
							</div>

							<div class="frequency--choices">
								<label for="once" class="is-selected text-center">Give Once</label>
								<label for="monthly" class="text-center">Give Monthly</label>
							</div>

							<div class="amount">
								<div class="amount-choices">
									<label for="amount-30" class="selected">$30</label>
									<label for="amount-60">$60</label>
									<label for="amount-120">$120</label>
									<label for="amount-250">$250</label>
								</div>
								<div class="amount-custom">
									<input type="number" name="custom" class="custom-amount" placeholder="$Custom amount">
									<input type="text" name="currency-field" class="display-amount" id="currency-field" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" value="" data-type="currency" placeholder="$ Custom amount">
								</div>
							</div>

							<div class="submit">
								<input type="submit" value="Donate Now"/>
							</div>
						</div>
					</form>
				</div>
			</div>
			<?php if ( ! empty( $form_image ) ) : ?>
				<div class="donation-form__image">
					<div class="image-wrapper"><img src="<?php echo $form_image['url']; ?>"/></div>
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
