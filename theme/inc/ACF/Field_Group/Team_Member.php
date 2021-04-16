<?php namespace TrevorWP\Theme\ACF\Field_Group;

use TrevorWP\CPT;

class Team_Member extends A_Field_Group {

	const FIELD_PRONOUN = 'member_pronoun';
	const FIELD_LOCATION = 'member_location';

	/** @inheritDoc */
	public static function prepare_register_args(): array {
		$pronoun = static::gen_field_key( static::FIELD_PRONOUN );
		$location = static::gen_field_key( static::FIELD_LOCATION );

		return [
			'title'    => 'Details',
			'fields'   => [
				static::FIELD_PRONOUN => [
					'key'               => $pronoun,
					'name'              => static::FIELD_PRONOUN,
					'label'             => 'Pronoun',
					'type'              => 'text',
				],
				static::FIELD_LOCATION => [
					'key'                => $location,
					'name'               => static::FIELD_LOCATION,
					'label'              => 'Location',
					'type'               => 'text',
				]
			],
			'location' => [
				[
					[
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'trevor_team',
					],
				],
			],
		];
	}
}
