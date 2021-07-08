<?php namespace TrevorWP\Theme\ACF\Options_Page;

use TrevorWP\Theme\ACF\Field_Group;
use TrevorWP\Theme\ACF\Util\Field_Val_Getter;

class Fundraiser_Quiz extends A_Options_Page {
	const STEP_1_TITLE                           = 'step_1_title';
	const STEP_1_DESCRIPTION                     = 'step_1_description';
	const STEP_1_CHOICES                         = 'step_1_choices';
	const ITEM                                   = 'item';
	const ITEM_TYPE                              = 'item_type';
	const DEV_INQ_FORM_TITLE                     = 'dev_inq_form_title';
	const DEV_INQ_FORM_DESCRIPTION               = 'dev_inq_form_description';
	const DEV_INQ_FORM_REASONS                   = 'dev_inq_form_reasons';
	const DEV_INQ_FORM_REASONS_ITEM              = 'dev_inq_form_reasons_item';
	const CREATE_FUNDRAISER_TITLE                = 'create_fundraiser_title';
	const CREATE_FUNDRAISER_BUTTON               = 'create_fundraiser_button';
	const CREATE_FUNDRAISER_CARD_TITLE           = 'create_fundraiser_card_title';
	const CREATE_FUNDRAISER_CARD_DESCRIPTION     = 'create_fundraiser_card_description';
	const CREATE_FUNDRAISER_CARD_LINKS           = 'create_fundraiser_card_links';
	const CREATE_FUNDRAISER_CARD_LINK_TYPE       = 'create_fundraiser_card_link_type';
	const CREATE_FUNDRAISER_CARD_LINK            = 'create_fundraiser_card_link';
	const CREATE_FUNDRAISER_CARD_FILE_LINK_GROUP = 'create_fundraiser_card_file_link_group';
	const CREATE_FUNDRAISER_CARD_FILE_LINK_TITLE = 'create_fundraiser_card_file_link_title';
	const CREATE_FUNDRAISER_CARD_FILE_LINK       = 'create_fundraiser_card_file_link';
	const COLLECT_DONATIONS_TITLE                = 'collect_donations_title';
	const WHO_FUNDRAISING_TITLE                  = 'who_fundraising_title';
	const WHO_FUNDRAISING_CHOICES                = 'who_fundraising_choices';
	const WHO_FUNDRAISING_ITEM                   = 'who_fundraising_item';
	const PLANNING_GATHERING_TITLE               = 'planning_gathering_title';

	/** @inheritDoc */
	protected static function prepare_page_register_args(): array {
		return array_merge(
			parent::prepare_page_register_args(),
			array(
				'parent_slug' => 'general-settings',
			)
		);
	}

	/** @inheritDoc */
	protected static function prepare_fields(): array {
		$step_1_title                     = static::gen_field_key( static::STEP_1_TITLE );
		$step_1_description               = static::gen_field_key( static::STEP_1_DESCRIPTION );
		$step_1_choices                   = static::gen_field_key( static::STEP_1_CHOICES );
		$item                             = static::gen_field_key( static::ITEM );
		$item_type                        = static::gen_field_key( static::ITEM_TYPE );
		$dev_inq_form_title               = static::gen_field_key( static::DEV_INQ_FORM_TITLE );
		$dev_inq_form_description         = static::gen_field_key( static::DEV_INQ_FORM_DESCRIPTION );
		$dev_inq_form_reasons             = static::gen_field_key( static::DEV_INQ_FORM_REASONS );
		$dev_inq_form_reasons_item        = static::gen_field_key( static::DEV_INQ_FORM_REASONS_ITEM );
		$create_fund_title                = static::gen_field_key( static::CREATE_FUNDRAISER_TITLE );
		$create_fund_button               = static::gen_field_key( static::CREATE_FUNDRAISER_BUTTON );
		$create_fund_card_title           = static::gen_field_key( static::CREATE_FUNDRAISER_CARD_TITLE );
		$create_fund_card_description     = static::gen_field_key( static::CREATE_FUNDRAISER_CARD_DESCRIPTION );
		$create_fund_card_links           = static::gen_field_key( static::CREATE_FUNDRAISER_CARD_LINKS );
		$create_fund_card_link_type       = static::gen_field_key( static::CREATE_FUNDRAISER_CARD_LINK_TYPE );
		$create_fund_card_link            = static::gen_field_key( static::CREATE_FUNDRAISER_CARD_LINK );
		$create_fund_card_file_link_group = static::gen_field_key( static::CREATE_FUNDRAISER_CARD_FILE_LINK_GROUP );
		$create_fund_card_file_link_title = static::gen_field_key( static::CREATE_FUNDRAISER_CARD_FILE_LINK_TITLE );
		$create_fund_card_file_link       = static::gen_field_key( static::CREATE_FUNDRAISER_CARD_FILE_LINK );
		$collect_donations_title          = static::gen_field_key( static::COLLECT_DONATIONS_TITLE );
		$who_fundraising_title            = static::gen_field_key( static::WHO_FUNDRAISING_TITLE );
		$who_fundraising_choices          = static::gen_field_key( static::WHO_FUNDRAISING_CHOICES );
		$who_fundraising_item             = static::gen_field_key( static::WHO_FUNDRAISING_ITEM );
		$planning_gathering_title         = static::gen_field_key( static::PLANNING_GATHERING_TITLE );

		return array_merge(
			static::_gen_tab_field( 'Main' ),
			array(
				static::STEP_1_TITLE       => array(
					'key'   => $step_1_title,
					'name'  => static::STEP_1_TITLE,
					'label' => 'Title',
					'type'  => 'text',
				),
				static::STEP_1_DESCRIPTION => array(
					'key'   => $step_1_description,
					'name'  => static::STEP_1_DESCRIPTION,
					'label' => 'Description',
					'type'  => 'textarea',
				),
				static::STEP_1_CHOICES     => array(
					'key'          => $step_1_choices,
					'name'         => static::STEP_1_CHOICES,
					'label'        => 'Choices',
					'type'         => 'repeater',
					'layout'       => 'table',
					'button_label' => 'New Item',
					'sub_fields'   => array(
						static::ITEM      => array(
							'key'      => $item,
							'name'     => static::ITEM,
							'type'     => 'text',
							'label'    => 'Item',
							'required' => 1,
							'wrapper'  => array(
								'width' => '50',
								'class' => '',
								'id'    => '',
							),
						),
						static::ITEM_TYPE => array(
							'key'     => $item_type,
							'name'    => static::ITEM_TYPE,
							'label'   => 'Type',
							'type'    => 'select',
							'choices' => array(
								'donate'    => 'Donate',
								'streaming' => 'Streaming',
								'personal'  => 'In Person',
								'social'    => 'Social',
							),
							'wrapper' => array(
								'width' => '50',
								'class' => '',
								'id'    => '',
							),
						),
					),
				),
			),
			static::_gen_tab_field( 'Dev Inquiry Form' ),
			array(
				static::DEV_INQ_FORM_TITLE       => array(
					'key'   => $dev_inq_form_title,
					'name'  => static::DEV_INQ_FORM_TITLE,
					'label' => 'Title',
					'type'  => 'text',
				),
				static::DEV_INQ_FORM_DESCRIPTION => array(
					'key'           => $dev_inq_form_description,
					'name'          => static::DEV_INQ_FORM_DESCRIPTION,
					'label'         => 'Description',
					'type'          => 'textarea',
					'placeholder'   => 'Please fill out this form, and someone from our team will reach out soon.',
					'default_value' => 'Please fill out this form, and someone from our team will reach out soon.',
				),
				static::DEV_INQ_FORM_REASONS     => array(
					'key'          => $dev_inq_form_reasons,
					'name'         => static::DEV_INQ_FORM_REASONS,
					'label'        => 'Inquiry Reasons',
					'type'         => 'repeater',
					'layout'       => 'row',
					'button_label' => 'Add Item',
					'sub_fields'   => array(
						static::DEV_INQ_FORM_REASONS_ITEM => array(
							'key'   => $dev_inq_form_reasons_item,
							'name'  => static::DEV_INQ_FORM_REASONS_ITEM,
							'label' => 'Item',
							'type'  => 'text',
						),
					),
				),
			),
			static::_gen_tab_field( 'Create Fundraiser Now' ),
			array(
				static::CREATE_FUNDRAISER_TITLE            => array(
					'key'           => $create_fund_title,
					'name'          => static::CREATE_FUNDRAISER_TITLE,
					'label'         => 'Title',
					'type'          => 'text',
					'placeholder'   => 'Are you ready to start fundraising?',
					'default_value' => 'Are you ready to start fundraising?',
				),
				static::CREATE_FUNDRAISER_BUTTON           => array(
					'key'           => $create_fund_button,
					'name'          => static::CREATE_FUNDRAISER_BUTTON,
					'label'         => 'Button',
					'type'          => 'link',
					'return_format' => 'array',
				),
				static::CREATE_FUNDRAISER_CARD_TITLE       => array(
					'key'           => $create_fund_card_title,
					'name'          => static::CREATE_FUNDRAISER_CARD_TITLE,
					'label'         => 'Card Title',
					'type'          => 'text',
					'placeholder'   => 'Get your fundraiser started now.',
					'default_value' => 'Get your fundraiser started now.',
				),
				static::CREATE_FUNDRAISER_CARD_DESCRIPTION => array(
					'key'           => $create_fund_card_description,
					'name'          => static::CREATE_FUNDRAISER_CARD_DESCRIPTION,
					'label'         => 'Card Description',
					'type'          => 'textarea',
					'placeholder'   => 'Here are some helpful tips and tools to help  you get started on your fundraiser.',
					'default_value' => 'Here are some helpful tips and tools to help  you get started on your fundraiser.',
				),
				static::CREATE_FUNDRAISER_CARD_LINKS       => array(
					'key'          => $create_fund_card_links,
					'name'         => static::CREATE_FUNDRAISER_CARD_LINKS,
					'label'        => 'Card Links',
					'type'         => 'repeater',
					'layout'       => 'table',
					'button_label' => 'New Link',
					'sub_fields'   => array(
						static::CREATE_FUNDRAISER_CARD_LINK_TYPE => array(
							'key'     => $create_fund_card_link_type,
							'name'    => static::CREATE_FUNDRAISER_CARD_LINK_TYPE,
							'label'   => 'Link Type',
							'type'    => 'select',
							'choices' => array(
								'link' => 'Link',
								'file' => 'File',
							),
							'wrapper' => array(
								'width' => '50',
								'class' => '',
								'id'    => '',
							),
						),
						static::CREATE_FUNDRAISER_CARD_LINK => array(
							'key'               => $create_fund_card_link,
							'name'              => static::CREATE_FUNDRAISER_CARD_LINK,
							'label'             => 'Link',
							'type'              => 'link',
							'return_format'     => 'array',
							'conditional_logic' => array(
								array(
									array(
										'field'    => $create_fund_card_link_type,
										'operator' => '==',
										'value'    => 'link',
									),
								),
							),
						),
						static::CREATE_FUNDRAISER_CARD_FILE_LINK_GROUP => array(
							'key'               => $create_fund_card_file_link_group,
							'name'              => static::CREATE_FUNDRAISER_CARD_FILE_LINK_GROUP,
							'label'             => 'Link',
							'type'              => 'group',
							'conditional_logic' => array(
								array(
									array(
										'field'    => $create_fund_card_link_type,
										'operator' => '==',
										'value'    => 'file',
									),
								),
							),
							'layout'            => 'block',
							'sub_fields'        => array(
								static::CREATE_FUNDRAISER_CARD_FILE_LINK_TITLE => array(
									'key'   => $create_fund_card_file_link_title,
									'name'  => static::CREATE_FUNDRAISER_CARD_FILE_LINK_TITLE,
									'label' => 'File Label',
									'type'  => 'text',
								),
								static::CREATE_FUNDRAISER_CARD_FILE_LINK => array(
									'key'           => $create_fund_card_file_link,
									'name'          => static::CREATE_FUNDRAISER_CARD_FILE_LINK,
									'label'         => 'File',
									'type'          => 'file',
									'return_format' => 'array',
									'library'       => 'all',
								),
							),
						),
					),
				),
			),
			static::_gen_tab_field( 'Collect Donations?' ),
			array(
				static::COLLECT_DONATIONS_TITLE => array(
					'key'           => $collect_donations_title,
					'name'          => static::COLLECT_DONATIONS_TITLE,
					'label'         => 'Title',
					'type'          => 'text',
					'placeholder'   => 'Do you need to collect online donations?',
					'default_value' => 'Do you need to collect online donations?',
					'instructions'  => 'Answerable by yes or no',
				),
			),
			static::_gen_tab_field( 'Who is fundraising?' ),
			array(
				static::WHO_FUNDRAISING_TITLE   => array(
					'key'           => $who_fundraising_title,
					'name'          => static::WHO_FUNDRAISING_TITLE,
					'label'         => 'Title',
					'type'          => 'text',
					'placeholder'   => 'Who is fundraising?',
					'default_value' => 'Who is fundraising?',
					'wrapper'       => array(
						'width' => '50',
						'class' => '',
						'id'    => '',
					),
				),
				static::WHO_FUNDRAISING_CHOICES => array(
					'key'        => $who_fundraising_choices,
					'name'       => static::WHO_FUNDRAISING_CHOICES,
					'label'      => 'Choices',
					'type'       => 'repeater',
					'layout'     => 'row',
					'sub_fields' => array(
						static::WHO_FUNDRAISING_ITEM => array(
							'key'   => $who_fundraising_item,
							'name'  => static::WHO_FUNDRAISING_ITEM,
							'label' => 'Item',
							'type'  => 'text',
						),
					),
					'min'        => 0,
					'max'        => 0,
					'wrapper'    => array(
						'width' => '50',
						'class' => '',
						'id'    => '',
					),
				),
			),
			static::_gen_tab_field( 'Gathering?' ),
			array(
				static::PLANNING_GATHERING_TITLE => array(
					'key'           => $planning_gathering_title,
					'name'          => static::PLANNING_GATHERING_TITLE,
					'label'         => 'Title',
					'type'          => 'text',
					'placeholder'   => 'Are you planning to have a gathering?',
					'default_value' => 'Are you planning to have a gathering?',
					'instructions'  => 'Answerable by yes or no',
				),
			),
		);
	}

	/**
	 * Renders the fundraiser quiz modal
	 */
	public static function render(): string {
		$data           = array();
		$step_1_choices = array();

		if ( static::have_rows( static::STEP_1_CHOICES ) ) {
			while ( static::have_rows( static::STEP_1_CHOICES ) ) {
				the_row();

				$item = get_sub_field( static::gen_field_key( static::ITEM ) );
				$type = get_sub_field( static::gen_field_key( static::ITEM_TYPE ) );

				$step_1_choices[] = array(
					'item' => $item,
					'type' => $type,
				);
			}
		}

		$step_1_data = array(
			'title'       => static::get_val( static::STEP_1_TITLE, 'option' ),
			'description' => static::get_val( static::STEP_1_DESCRIPTION, 'option' ),
			'choices'     => $step_1_choices,
		);

		$dev_form_data = array(
			'title'           => static::get_val( static::DEV_INQ_FORM_TITLE, 'option' ),
			'description'     => static::get_val( static::DEV_INQ_FORM_DESCRIPTION, 'option' ),
			'inquiry_reasons' => static::get_val( static::DEV_INQ_FORM_REASONS, 'option' ),
		);

		$card_links     = array();
		$card_links_raw = static::get_val( static::CREATE_FUNDRAISER_CARD_LINKS, 'option' );

		if ( ! empty( $card_links_raw ) ) {
			foreach ( $card_links_raw as $link ) {
				if ( $link['create_fundraiser_card_link_type'] === 'link' ) {
					$card_links[] = array_merge(
						array(
							'type' => 'link',
						),
						$link['create_fundraiser_card_link']
					);
				} else {
					$link_group   = $link['create_fundraiser_card_file_link_group'];
					$card_links[] = array(
						'type'  => 'file',
						'title' => @$link_group['create_fundraiser_card_file_link_title'] ?: @$link_group['create_fundraiser_card_file_link']['title'],
						'url'   => @$link_group['create_fundraiser_card_file_link']['url'],
					);
				}
			}
		}

		$create_fundraiser_data = array(
			'title'            => static::get_val( static::CREATE_FUNDRAISER_TITLE, 'option' ),
			'button'           => static::get_val( static::CREATE_FUNDRAISER_BUTTON, 'option' ),
			'card_title'       => static::get_val( static::CREATE_FUNDRAISER_CARD_TITLE, 'option' ),
			'card_description' => static::get_val( static::CREATE_FUNDRAISER_CARD_DESCRIPTION, 'option' ),
			'card_links'       => $card_links,
		);

		$collect_donations_data = array(
			'title' => static::get_val( static::COLLECT_DONATIONS_TITLE, 'option' ),
		);

		$who_is_fundraising_data = array(
			'title'   => static::get_val( static::WHO_FUNDRAISING_TITLE, 'option' ),
			'choices' => static::get_val( static::WHO_FUNDRAISING_CHOICES, 'option' ),
		);

		$planning_to_gather_data = array(
			'title' => static::get_val( static::PLANNING_GATHERING_TITLE, 'option' ),
		);

		ob_start();
		?>
			<button class="fundraiser-quiz__back-btn">BACK</button>
			<div class="fundraiser-quiz__pagination"><span class="fundraiser-quiz__current-page"></span>/<span class="fundraiser-quiz__total-page"></span></div>
			<form class="fundraiser-quiz container mx-auto text-white text-center">
				<?php
					echo self::step_one( $step_1_data );
					echo self::form( $dev_form_data );
					echo self::create_fundraiser( $create_fundraiser_data );
					echo self::collect_donations( $collect_donations_data );
					echo self::who_is_fundraising( $who_is_fundraising_data );
					echo self::gathering( $planning_to_gather_data );
				?>
			</form>
		<?php
		return ob_get_clean();
	}


	protected static function step_one( array $data = array() ): string {
		ob_start();
		?>
			<div class="fundraiser-quiz--step-one hidden bg-teal-dark">
				<?php if ( ! empty( $data['title'] ) ) { ?>
					<h2 class="fundraiser-quiz__title"><?php echo esc_html( $data['title'] ); ?></h2>
				<?php } ?>

				<?php if ( ! empty( $data['description'] ) ) { ?>
					<p class="fundraiser-quiz__description"><?php echo esc_html( $data['description'] ); ?></p>
				<?php } ?>
				<p class="fundraiser-quiz__instruction">* Choose one</p>
				<fieldset>
					<div class="fundraiser-quiz__choices">
						<?php
						if ( ! empty( $data['choices'] ) ) {
							foreach ( $data['choices'] as $item ) {
								?>
							<div class="fundraiser-quiz__btn-container">
								<input class="fundraiser-quiz__radio-btn" type="radio" name="step-1" id="<?php echo esc_attr( acf_slugify( $item['item'] ) ); ?>" value="<?php echo esc_attr( acf_slugify( $item['item'] ) ); ?>" data-vertex="<?php echo $item['type']; ?>">
								<label for="<?php echo esc_attr( acf_slugify( $item['item'] ) ); ?>"><?php echo esc_html( $item['item'] ); ?></label>
							</div>
								<?php
							}
						}
						?>
					</div>
				</fieldset>
			</div>
		<?php
		return ob_get_clean();
	}

	protected static function form( array $data = array() ): string {
		ob_start();
		?>
			<div class="fundraiser-quiz--form fundraiser-quiz--steps" data-vertex="form">
					<?php if ( ! empty( $data['title'] ) ) { ?>
						<h2 class="fundraiser-quiz__title"><?php echo esc_html( $data['title'] ); ?></h2>
					<?php } ?>

					<?php if ( ! empty( $data['description'] ) ) { ?>
						<p class="fundraiser-quiz__description"><?php echo esc_html( $data['description'] ); ?></p>
					<?php } ?>
					<div class="fundraiser-quiz__fields">
						<div class="fundraiser-quiz__small-fields">
							<input class="fundraiser-quiz__first-name" placeholder="First Name*" type="text" name="first-name">
							<input class="fundraiser-quiz__last-name" placeholder="Last Name*" type="text" name="last-name">
						</div>
						<div class="fundraiser-quiz__small-fields">
							<input class="fundraiser-quiz__email" placeholder="Email*" type="text" name="email">
							<input class="fundraiser-quiz__phone" placeholder="Phone*" type="text" name="phone">
						</div>
						<textarea class="fundraiser-quiz__message" name="message" id="message" placeholder="Message"></textarea>
						<select class="fundraiser-quiz__inquiry" name="inquiry-reason" id="inquiry-reason">
							<option value="">Inquiry Reason</option>
							<?php
							if ( ! empty( $data['inquiry_reasons'] ) ) {
								foreach ( $data['inquiry_reasons'] as $reason ) {
									?>
								<option value="<?php echo esc_attr( acf_slugify( $reason['dev_inq_form_reasons_item'] ) ); ?>"><?php echo esc_html( $reason['dev_inq_form_reasons_item'] ); ?></option>
									<?php
								}
							}
							?>
						</select>
						<button class="fundraiser-quiz__submit btn btn-primary">Submit</button>
					</div>
			</div>
		<?php
		return ob_get_clean();
	}

	protected static function create_fundraiser( array $data = array() ): string {
		ob_start();
		?>
			<div class="fundraiser-quiz--create-fundraiser fundraiser-quiz--steps" data-vertex="create">
				<?php if ( ! empty( $data['title'] ) ) { ?>
					<h2 class="fundraiser-quiz__title">Are you ready to start fundraising?</h2>
				<?php } ?>

				<?php if ( ! empty( $data['button'] ) ) { ?>
					<a class="fundraiser-quiz__create-fundraiser-btn btn btn-primary" target="<?php echo esc_attr( $data['button']['target'] ); ?>" href="<?php echo esc_attr( $data['button']['url'] ); ?>"><?php echo esc_attr( $data['button']['title'] ); ?></a>
				<?php } ?>
				<div class="fundraiser-quiz__get-your-fundraiser">
					<?php if ( ! empty( $data['card_title'] ) ) { ?>
						<h3 class="fundraiser-quiz__get-your-fundraiser-title"><?php echo esc_html( $data['card_title'] ); ?></h3>
					<?php } ?>

					<?php if ( ! empty( $data['card_description'] ) ) { ?>
						<p class="fundraiser-quiz__get-your-fundraiser-description"><?php echo esc_html( $data['card_description'] ); ?></p>
					<?php } ?>

					<?php if ( ! empty( $data['card_links'] ) ) { ?>
						<ul class="fundraiser-quiz__get-your-fundraiser-links">
							<?php foreach ( $data['card_links'] as $link ) { ?>
								<li class="fundraiser-quiz__get-your-fundraiser-link">
									<a class="wave-underline" target="<?php echo $link['target'] ?: ''; ?>" href="<?php echo esc_attr( $link['url'] ); ?>"
																				 <?php
																					if ( $link['type'] === 'file' ) {
																						echo 'download';}
																					?>
										><?php echo esc_html( $link['title'] ); ?></a>
								</li>
							<?php } ?>
						</ul>
					<?php } ?>
				</div>
			</div>
		<?php
		return ob_get_clean();
	}

	protected static function collect_donations( array $data = array() ): string {
		ob_start();
		?>
			<div class="fundraiser-quiz--collect-donations fundraiser-quiz--steps" data-vertex="collect">
				<?php if ( ! empty( $data['title'] ) ) { ?>
					<h2 class="fundraiser-quiz__title"><?php echo esc_html( $data['title'] ); ?></h2>
				<?php } ?>
				<p class="fundraiser-quiz__instruction">* Choose one</p>
				<fieldset>
					<div class="fundraiser-quiz__choices">
						<div class="fundraiser-quiz__btn-container">
							<input class="fundraiser-quiz__radio-btn" type="radio" id="yes-collect" name="collect_donations" value="yes" data-vertex="collect">
							<label for="yes-collect">Yes</label>
						</div>
						<div class="fundraiser-quiz__btn-container">
							<input class="fundraiser-quiz__radio-btn" type="radio" id="no-collect" name="collect_donations" value="no" data-vertex="collect">
							<label for="no-collect">No</label>
						</div>
					</div>
				</fieldset>
			</div>
		<?php
		return ob_get_clean();
	}

	protected static function who_is_fundraising( array $data = array() ): string {
		ob_start();
		?>
			<div class="fundraiser-quiz--who-is-fundraising fundraiser-quiz--steps" data-vertex="who">
				<?php if ( ! empty( $data['title'] ) ) { ?>
					<h2 class="fundraiser-quiz__title"><?php echo esc_html( $data['title'] ); ?></h2>
				<?php } ?>

				<p class="fundraiser-quiz__instruction">* Choose one</p>
				<?php if ( ! empty( $data['choices'] ) ) { ?>
					<fieldset>
						<div class="fundraiser-quiz__choices">
							<?php foreach ( $data['choices'] as $item ) { ?>
								<div class="fundraiser-quiz__btn-container">
									<input class="fundraiser-quiz__radio-btn" type="radio" id="<?php echo esc_attr( acf_slugify( $item['who_fundraising_item'] ) ); ?>" name="who_is_fundraising" value="<?php echo esc_attr( acf_slugify( $item['who_fundraising_item'] ) ); ?>" data-vertex="who">
									<label for="<?php echo esc_attr( acf_slugify( $item['who_fundraising_item'] ) ); ?>"><?php echo esc_html( $item['who_fundraising_item'] ); ?></label>
								</div>
							<?php } ?>
						</div>
					</fieldset>
				<?php } ?>

			</div>
		<?php
		return ob_get_clean();
	}

	protected static function gathering( array $data = array() ): string {
		ob_start();
		?>
			<div class="fundraiser-quiz--gathering fundraiser-quiz--steps" data-vertex="gathering">
				<?php if ( ! empty( $data['title'] ) ) { ?>
					<h2 class="fundraiser-quiz__title">Are you planning to have a gathering?</h2>
				<?php } ?>
				<p class="fundraiser-quiz__instruction">* Choose one</p>
				<fieldset>
					<div class="fundraiser-quiz__choices">
						<div class="fundraiser-quiz__btn-container">
							<input class="fundraiser-quiz__radio-btn" type="radio" id="yes-gathering" name="gathering" value="yes" data-vertex="gathering">
							<label for="yes-gathering">Yes</label>
						</div>
						<div class="fundraiser-quiz__btn-container">
							<input class="fundraiser-quiz__radio-btn" type="radio" id="no-gathering" name="gathering" value="no" data-vertex="gathering">
							<label for="no-gathering">No</label>
						</div>
					</div>
				</fieldset>
			</div>
		<?php
		return ob_get_clean();
	}

}
