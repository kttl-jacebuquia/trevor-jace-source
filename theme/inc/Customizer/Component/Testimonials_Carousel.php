<?php namespace TrevorWP\Theme\Customizer\Component;

use TrevorWP\Theme\Customizer\Control;
use TrevorWP\Theme\Helper\Carousel;

/**
 * Testimonials Carousel Customizer
 */
class Testimonials_Carousel extends Abstract_Component {
	const SETTING_DATA = 'data';

	/** @inheritDoc */
	public function register_section(): void {
		$this->get_manager()->add_section(
			$this->get_section_id(),
			[
				'panel' => $this->get_panel_id(),
				'title' => 'Testimonials Carousel',
			]
		);
	}

	/** @inheritDoc */
	public function register_controls(): void {
		$manager = $this->get_manager();
		$sec_id  = $this->get_section_id();

		# Quote
		$manager->add_control( new Control\Custom_List( $manager, $data_id = $this->get_setting_id( self::SETTING_DATA ), [
			'setting' => $data_id,
			'section' => $sec_id,
			'label'   => 'Testimonials',
			'fields'  => Control\Custom_List::FIELDSET_QUOTE,
		] ) );
	}

	/** @inheritDoc */
	public function render( array $ext_options = [] ): ?string {
		return Carousel::testimonials( (array) $this->get_val( self::SETTING_DATA ), $ext_options );
	}
}
