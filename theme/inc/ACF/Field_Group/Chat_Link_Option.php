<?php namespace TrevorWP\Theme\ACF\Field_Group;

use TrevorWP\CPT;

class Chat_Link_Option extends A_Field_Group {
	const FIELD_CHAT_OPTION = 'chat_option';

	/** @inheritDoc */
	public static function prepare_register_args(): array {
		$chat_option = static::gen_field_key( static::FIELD_CHAT_OPTION );

		return array(
			'title'    => 'Trevor Chat Link',
			'fields'   => array(
				static::FIELD_CHAT_OPTION => array(
					'key'               => $chat_option,
					'name'              => static::FIELD_CHAT_OPTION,
					'label'             => '',
					'type'              => 'true_false',
					'instructions'      => 'Check to activate the Trevor Chat using this link.',
					'required'          => 0,
					'conditional_logic' => 0,
					'wrapper'           => array(
						'width' => '',
						'class' => '',
						'id'    => '',
					),
					'message'           => '',
					'default_value'     => 0,
				),
			),
			'location' => array(
				array(
					array(
						'param'    => 'nav_menu_item',
						'operator' => '==',
						'value'    => 'location/header-support',
					),
				),
			),
		);
	}
}
