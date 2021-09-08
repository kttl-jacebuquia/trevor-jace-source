<?php namespace TrevorWP\Theme\ACF\Field_Group;

class Campaign_Form extends A_Field_Group implements I_Block, I_Renderable {
	const FIELD_TITLE       = 'title';
	const FIELD_DESCRIPTION = 'description';

	static $rendered = false;

	/**
	 * @inheritDoc
	 */
	protected static function prepare_register_args(): array {
		$title       = static::gen_field_key( static::FIELD_TITLE );
		$description = static::gen_field_key( static::FIELD_DESCRIPTION );

		return array(
			'title'  => 'Join The Campaign Form',
			'fields' => array(
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
				'title'      => 'Join The Campaign Form',
				'post_types' => array( 'page' ),
			)
		);
	}

	/**
	 * @inheritDoc
	 */
	public static function render( $post = false, array $data = null, array $options = array() ): ?string {
		$title       = static::get_val( static::FIELD_TITLE );
		$description = static::get_val( static::FIELD_DESCRIPTION );

		ob_start();
		?>
		<div class="join-the-campaign-form sticky-cta-anchor pt-14 md:pt-px110 pb-12 text-white bg-blue_green md:pb-20 lg:pt-28 lg:pb-28">
			<div class="container mx-auto site-content-inner text-center">
				<?php if ( ! empty( $title ) ) : ?>
					<h2 class="font-semibold md:font-bold text-px32 leading-px42 mb-3.5 lg:text-px46 lg:leading-px56">
						<?php echo esc_html( $title ); ?>
					</h2>
				<?php endif; ?>

				<?php if ( ! empty( $description ) ) : ?>
					<p class="text-px18 leading-px26 mb-px50 md:text-px20 md:leading-px24 lg:text-px22 lg:leading-px32 lg:mb-px72">
						<?php echo esc_html( $description ); ?>
					</p>
				<?php endif; ?>

				<p class="text-px16 leading-px24 mb-px24 md:mb-px30 lg:text-px20 lg:leading-px26 hidden join-the-campaign-form__success">Thank you for your submission!</p>

				<form class="join-the-campaign-form__form mx-auto w-full lg:max-w-px818" novalidate>
					<div class="join-the-campaign-form__field-group md:flex md:grid md:grid-cols-2 md:gap-x-7 md:mb-10 lg:gap-x-7 lg:mb-px60">
						<div class="join-the-campaign-form__field flex full-w relative mb-7 md:mb-0">
							<div class="floating-label-input">
								<label for="fullname">Full Name*</label>
								<input
									name="fullname"
									required="required"
									class="bg-white text-px18 leading-px26 md:leading-px22 rounded-px10 text-teal-dark py-5 md:py-px23 lg:py-6 px-7 flex-1 placeholder-teal-dark lg:text-px20 lg:leading-px24 lg:py-6"
									placeholder="Full Name*"/>
							</div>
							<div class="join-the-campaign-form__error"></div>
						</div>
						<div class="join-the-campaign-form__field flex full-w relative mb-7 md:mb-0">
							<div class="floating-label-input">
								<label for="email">Email*</label>
								<input
									name="email"
									type="email"
									required="required"
									class="bg-white text-px18 leading-px26 md:leading-px22 rounded-px10 text-teal-dark py-5 md:py-px23 lg:py-6 px-7 flex-1 placeholder-teal-dark lg:text-px20 lg:leading-px24 lg:py-6"
									placeholder="Email*"/>
							</div>
							<div class="join-the-campaign-form__error"></div>
						</div>
						<div class="join-the-campaign-form__field flex full-w relative mb-7 md:mb-0">
							<div class="floating-label-input">
								<label for="mobilephone">Mobile Phone</label>
								<input
									name="phone"
									maxlength="16"
									class="phone-number-format bg-white text-px18 leading-px26 md:leading-px22 rounded-px10 text-teal-dark py-5 md:py-px23 lg:py-6 px-7 flex-1 placeholder-teal-dark lg:text-px20 lg:leading-px24 lg:py-6"
									placeholder="Mobile Phone"/>
							</div>
							<div class="join-the-campaign-form__error"></div>
						</div>
						<div class="join-the-campaign-form__field flex full-w relative mb-12 md:mb-0">
							<div class="floating-label-input">
								<label for="zipcode">Zip Code*</label>
								<input
									name="zipcode"
									maxlength="5"
									required="required"
									class="bg-white text-px18 leading-px26 md:leading-px22 rounded-px10 text-teal-dark py-5 md:py-px23 lg:py-6 px-7 flex-1 placeholder-teal-dark lg:text-px20 lg:leading-px24 lg:py-6"
									placeholder="Zip Code*"/>
							</div>
							<div class="join-the-campaign-form__error"></div>
						</div>
					</div>
					<div class="flex flex-row full-w relative mb-7 md:mb-6 lg:mb-7">
						<input name="email_notif" id="checkbox-1" type="checkbox" checked class="mr-5 w-7 h-7 border-0 rounded"/>
						<label for="checkbox-1"
							class="text-px16 leading-px24 text-white text-left cursor-pointer mt-0.5 lg:text-px18 lg:leading-px26">
							Send me emails about this campaign.
						</label>
					</div>
					<div class="flex flex-row full-w relative mb-px50">
						<input name="sms_notif" type="checkbox" checked class="mr-5 w-7 h-7 border-0 rounded"/>
						<label for="checkbox-2"
							class="text-px16 leading-px24 text-white text-left cursor-pointer mt-0.5 lg:text-px18 lg:leading-px26">
							Send me text messages about this campaign.
						</label>
					</div>

					<button
						type="submit"
						class="block w-full font-bold text-teal-dark bg-white py-5 px-8 rounded-px10 md:py-4 md:px-20 lg:text-px18 lg:leading-px26 lg:py-5 md:w-auto">
						Submit
					</button>
				</form>
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
