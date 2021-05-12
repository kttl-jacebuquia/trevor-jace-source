<?php namespace TrevorWP\Theme\ACF\Field_Group;

use TrevorWP\CPT;

class Team_Member extends A_Field_Group {
	const FIELD_PRONOUN  = 'member_pronoun';
	const FIELD_LOCATION = 'member_location';

	/** @inheritDoc */
	public static function prepare_register_args(): array {
		$pronoun  = static::gen_field_key( static::FIELD_PRONOUN );
		$location = static::gen_field_key( static::FIELD_LOCATION );

		return array(
			'title'    => 'Details',
			'fields'   => array(
				static::FIELD_PRONOUN  => array(
					'key'   => $pronoun,
					'name'  => static::FIELD_PRONOUN,
					'label' => 'Pronoun',
					'type'  => 'text',
				),
				static::FIELD_LOCATION => array(
					'key'   => $location,
					'name'  => static::FIELD_LOCATION,
					'label' => 'Location',
					'type'  => 'text',
				),
			),
			'location' => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => CPT\Team::POST_TYPE,
					),
				),
			),
		);
	}
}
