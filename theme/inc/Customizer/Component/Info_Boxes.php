<?php namespace TrevorWP\Theme\Customizer\Component;

use TrevorWP\Exception\Internal;
use TrevorWP\Theme\Customizer\Control;
use \TrevorWP\Theme\Helper;

/**
 * Info Boxes Customizer
 */
class Info_Boxes extends Abstract_Component {
	const SETTING_DATA = 'data';
	const SETTING_TITLE = 'title';
	const SETTING_DESC = 'desc';

	/** @inheritDoc */
	public function register_section(): void {
		$this->get_manager()->add_section(
			$this->get_section_id(),
			[
				'panel' => $this->get_panel_id(),
				'title' => 'Info Boxes',
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

		# Data Fields
		switch ( $this->get_option( 'box_type' ) ) {
			case Helper\Info_Boxes::BOX_TYPE_TEXT:
				$data_fields = [
					'txt' => [
						'type'       => Control\Custom_List::FIELD_TYPE_INPUT,
						'input_type' => 'text',
						'label'      => 'Hero Text'
					]
				];
				break;
			case Helper\Info_Boxes::BOX_TYPE_IMG:
				$data_fields = [
					'img' => [
						'type'      => Control\Custom_List::FIELD_TYPE_MEDIA,
						'label'     => 'Image',
						'mime_type' => 'image',
					]
				];
				break;
			default:
				throw new Internal( 'Unknown box type.' );
		}

		$data_fields['desc'] = [
			'type'       => Control\Custom_List::FIELD_TYPE_INPUT,
			'input_type' => 'text',
			'label'      => 'Description'
		];

		$manager->add_control( new Control\Custom_List( $manager, $data_id = $this->get_setting_id( static::SETTING_DATA ), [
			'setting' => $data_id,
			'section' => $sec_id,
			'label'   => 'Entries',
			'fields'  => $data_fields,
		] ) );
	}

	/** @inheritDoc */
	function render( array $ext_options = [] ): ?string {
		$boxes = (array) static::get_val( static::SETTING_DATA );

		return Helper\Info_Boxes::render( $boxes, array_merge(
			[
				'box_type'        => $this->get_option( 'box_type' ),
				'break_behaviour' => $this->get_option( 'break_behaviour' ),
				'title'           => static::get_val( static::SETTING_TITLE ),
				'desc'            => static::get_val( static::SETTING_DESC ),
			],
			$ext_options
		) );
	}
}
