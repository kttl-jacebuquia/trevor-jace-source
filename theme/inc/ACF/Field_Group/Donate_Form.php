<?php namespace TrevorWP\Theme\ACF\Field_Group;

class Donate_Form extends A_Field_Group implements I_Block, I_Renderable {
	const FIELD_FORM_IMAGE = 'form_image';
	const FIELD_HEADING    = 'heading';
	const FIELD_INTRO      = 'intro';
	const FIELD_DEDICATION = 'dedication';

	/**
	 * @inheritDoc
	 */
	protected static function prepare_register_args(): array {
		$form_image = static::gen_field_key( static::FIELD_FORM_IMAGE );
		$heading    = static::gen_field_key( static::FIELD_HEADING );
		$intro      = static::gen_field_key( static::FIELD_INTRO );
		$dedication = static::gen_field_key( static::FIELD_DEDICATION );

		return array(
			'title'  => 'Donation Form + IMG',
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
				static::FIELD_DEDICATION => array(
					'key'   => $dedication,
					'name'  => static::FIELD_DEDICATION,
					'label' => 'Show dedication field',
					'type'  => 'true_false',
					'ui'    => 1,
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
				'title'      => 'Donation Form + IMG',
				'post_types' => array( 'page' ),
			)
		);
	}

	/**
	 * @inheritDoc
	 */
	public static function render( $post = false, array $data = null, array $options = array() ): ?string {
		$uuid    = uniqid();
		$options = array_merge(
			array(
				'form_image' => static::get_val( static::FIELD_FORM_IMAGE ),
				'heading'    => static::get_val( static::FIELD_HEADING ),
				'intro'      => static::get_val( static::FIELD_INTRO ),
				'dedication' => static::get_val( static::FIELD_DEDICATION ),
			),
			$options,
		);

		$form_image = $options['form_image'];
		$heading    = $options['heading'];
		$intro      = $options['intro'];

		ob_start();
		?>
		<div class="donation-form">
			<div class="donation-form__content">
				<div class="donation-form__content-wrapper">
					<h2
						id="<?php echo 'form-heading-' . $uuid; ?>"
						class="donation-form__heading text-center xl:text-left"><?php echo $heading; ?></h2>
					<p class="donation-form__intro text-center xl:text-left"><?php echo $intro; ?></p>

					<form action="https://give.thetrevorproject.org/give/63307#!/donation/checkout/" method="get" target="_blank" id="donate-form-<?php echo $uuid; ?>" aria-labelledby="<?php echo 'form-heading-' . $uuid; ?>">
						<div class="frequency">
							<div class="visually-hidden">
								<input type="radio" name="recurring" value="0" id="<?php echo $uuid; ?>-once" checked class="donation-frequency">
								<input type="radio" name="recurring" value="1" id="<?php echo $uuid; ?>-monthly" class="donation-frequency">

								<input type="radio" name="amount" value="30" id="<?php echo $uuid; ?>-amount-30" class="fixed-amount">
								<input type="radio" name="amount" value="60" id="<?php echo $uuid; ?>-amount-60" class="fixed-amount">
								<input type="radio" name="amount" value="120" id="<?php echo $uuid; ?>-amount-120" class="fixed-amount">
								<input type="radio" name="amount" value="250" id="<?php echo $uuid; ?>-amount-250" class="fixed-amount">
							</div>

							<div class="frequency--choices">
								<button type="button" aria-label="click to select give once" tabindex="0" for="<?php echo $uuid; ?>-once" class="is-selected text-center">Give Once</button>
								<button type="button" aria-label="click to select give monthly" tabindex="0" for="<?php echo $uuid; ?>-monthly" class="text-center">Give Monthly</button>
							</div>

							<div class="amount">
								<div class="amount-choices">
									<button type="button" aria-label="click to select 30 dollars" tabindex="0" for="<?php echo $uuid; ?>-amount-30" class="selected">$30</button>
									<button type="button" aria-label="click to select 60 dollars" tabindex="0" for="<?php echo $uuid; ?>-amount-60">$60</button>
									<button type="button" aria-label="click to select 120 dollars" tabindex="0" for="<?php echo $uuid; ?>-amount-120">$120</button>
									<button type="button" aria-label="click to select 250 dollars" tabindex="0" for="<?php echo $uuid; ?>-amount-250">$250</button>
								</div>
								<div class="amount-custom">
									<input name="custom" class="custom-amount" placeholder="$Custom amount" aria-hidden="true">
									<input type="text" name="currency-field" class="display-amount" id="currency-field-<?php echo $uuid; ?>" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" value="" data-type="currency" placeholder="$ Custom amount">
								</div>
							</div>

							<div class="donation-form__error hidden">* Please select a donation amount before submitting.</div>

							<?php if ( $options['dedication'] ) : ?>
								<div class="dedication">
									<input type="checkbox" class="dedication-checkbox" id="<?php echo 'dedication-checkbox-' . $uuid; ?>" />
									<label for="<?php echo 'dedication-checkbox-' . $uuid; ?>">
										<strong>Dedicate my donation</strong> in honor or in memory of someone.
									</label>
								</div>
							<?php endif; ?>

							<div class="submit">
								<input type="submit" value="Donate Now" class="donation-form__submit" />
							</div>
						</div>
					</form>
				</div>
			</div>
			<?php if ( ! empty( $form_image ) ) : ?>
				<div class="donation-form__image">
					<div class="image-wrapper"><img src="<?php echo $form_image['url']; ?>" alt="<?php echo $form_image['alt']; ?>"/></div>
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
