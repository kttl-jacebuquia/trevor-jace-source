<?php namespace TrevorWP\Theme\Customizer\Component;

use TrevorWP\Theme\Customizer\Control;
use \TrevorWP\Theme\Helper;

/**
 * Info Boxes Customizer
 */
class Info_Boxes extends Section {
	const SETTING_DATA = 'data';

	/** @inheritDoc */
	public function register_section( array $args = array() ): void {
		parent::register_section( array_merge( array( 'title' => 'Info Boxes' ), $args ) );
	}

	/** @inheritDoc */
	public function register_controls(): void {
		parent::register_controls();

		$manager = $this->get_manager();
		$sec_id  = $this->get_section_id();

		# Data Fields
		$box_type    = $this->get_option( 'box_type' );
		$data_fields = array();
		if ( $box_type == Helper\Info_Boxes::BOX_TYPE_IMG || $box_type == Helper\Info_Boxes::BOX_TYPE_BOTH ) {
			$data_fields['img'] = array(
				'type'      => Control\Custom_List::FIELD_TYPE_MEDIA,
				'label'     => 'Image',
				'mime_type' => 'image',
			);
		}
		if ( $box_type == Helper\Info_Boxes::BOX_TYPE_TEXT || $box_type == Helper\Info_Boxes::BOX_TYPE_BOTH ) {
			$data_fields['txt'] = array(
				'type'       => Control\Custom_List::FIELD_TYPE_INPUT,
				'input_type' => 'text',
				'label'      => 'Hero Text',
			);
		}

		$data_fields['desc'] = array(
			'type'       => Control\Custom_List::FIELD_TYPE_INPUT,
			'input_type' => 'text',
			'label'      => 'Description',
		);

		$manager->add_control(
			new Control\Custom_List(
				$manager,
				$data_id = $this->get_setting_id( static::SETTING_DATA ),
				array(
					'setting' => $data_id,
					'section' => $sec_id,
					'label'   => 'Entries',
					'fields'  => $data_fields,
				)
			)
		);
	}

	/** @inheritDoc */
	function render( array $ext_options = array() ): string {
		$boxes = (array) static::get_val( static::SETTING_DATA );

		return Helper\Info_Boxes::render(
			$boxes,
			array_merge(
				array(
					'box_type'        => $this->get_option( 'box_type' ),
					'break_behaviour' => $this->get_option( 'break_behaviour' ),
					'title'           => static::get_val( static::SETTING_TITLE ),
					'desc'            => static::get_val( static::SETTING_DESC ),
				),
				$ext_options
			)
		);
	}
}
