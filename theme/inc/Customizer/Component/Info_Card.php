<?php namespace TrevorWP\Theme\Customizer\Component;

use TrevorWP\Theme\Customizer\Control;

class Info_Card extends Abstract_Component {
	const SETTING_TITLE = 'title';
	const SETTING_DESC = 'desc';
	const SETTING_BUTTONS = 'buttons';

	/** @inheritDoc */
	public function register_section(): void {
		$this->get_manager()->add_section(
				$this->get_section_id(),
				[
						'panel' => $this->get_panel_id(),
						'title' => 'Info Card',
				]
		);
	}

	/** @inheritDoc */
	public function register_controls(): void {
		$manager = $this->get_manager();
		$sec_id  = $this->get_section_id();

		# Title
		$manager->add_control(
				$setting_title = $this->get_setting_id( self::SETTING_TITLE ),
				array(
						'setting' => $setting_title,
						'section' => $sec_id,
						'label'   => 'Title',
						'type'    => 'text',
				)
		);

		# Desc
		$manager->add_control(
				$setting_desc = $this->get_setting_id( self::SETTING_DESC ),
				array(
						'setting' => $setting_desc,
						'section' => $sec_id,
						'label'   => 'Description',
						'type'    => 'text',
				)
		);

		# Buttons
		$manager->add_control( new Control\Custom_List( $manager, $buttons_data = $this->get_setting_id( static::SETTING_BUTTONS ), [
				'setting' => $buttons_data,
				'section' => $sec_id,
				'label'   => 'Buttons',
				'fields'  => [
						'label' => [
								'type'       => Control\Custom_List::FIELD_TYPE_INPUT,
								'input_type' => 'text',
								'label'      => 'Label',
						],
						'href'  => [
								'type'       => Control\Custom_List::FIELD_TYPE_INPUT,
								'input_type' => 'text',
								'label'      => 'URL',
						]
				],
		] ) );
	}

	/** @inheritDoc */
	public function render( array $ext_options = [] ): ?string {
		$cls       = array_merge( [ 'info-card' ], (array) @$ext_options['cls'] );
		$title_cls = array_merge( [ 'info-card-title' ], (array) @$ext_options['title_cls'] );
		$desc_cls  = array_merge( [ 'info-card-desc' ], (array) @$ext_options['desc_cls'] );
		$btn_cls   = array_merge( [ 'btn', 'info-card-btn' ], (array) @$ext_options['btn_cls'] );

		ob_start(); ?>
		<div class="<?= esc_attr( implode( ' ', $cls ) ) ?>">
			<h2 class="<?= esc_attr( implode( ' ', $title_cls ) ) ?>"><?= $this->get_val( $this::SETTING_TITLE ) ?></h2>
			<?php if ( ! empty( $desc = $this->get_val( $this::SETTING_DESC ) ) ) { ?>
				<p class="<?= esc_attr( implode( ' ', $desc_cls ) ) ?>"><?= esc_html( $desc ) ?></p>
			<?php } ?>

			<?php if ( ! empty( $buttons = $this->get_val( $this::SETTING_BUTTONS ) ) ) { ?>
				<div class="info-card-btn-wrap">
					<?php foreach ( $this->get_val( $this::SETTING_BUTTONS ) as $btn ) { ?>
						<a class="<?= esc_attr( implode( ' ', $btn_cls ) ) ?>"
						   href="<?= esc_url( empty( $btn['href'] ) ? @$ext_options['btn_href'] : $btn['href'] ) ?>"><?= esc_html( @$btn['label'] ) ?></a>
					<?php } ?>
				</div>
			<?php } ?>
		</div>
		<?php return ob_get_clean();
	}
}
