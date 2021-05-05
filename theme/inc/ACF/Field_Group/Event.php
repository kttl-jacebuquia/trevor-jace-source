<?php namespace TrevorWP\Theme\ACF\Field_Group;

use TrevorWP\CPT;

class Event extends A_Field_Group {
	const FIELD_DATE = 'event_date';
	const FIELD_TIME = 'event_time';
	const FIELD_LABEL = 'event_label';
	const LABEL_OPTION = [
		'' => '',
		'free' => 'Free',
		'limited_spots' => 'Limited Spots',
		'sold_out' => 'Sold Out',
	];

	/** @inheritDoc */
	public static function prepare_register_args(): array {
		$event_date = static::gen_field_key( static::FIELD_DATE );
		$event_time = static::gen_field_key( static::FIELD_TIME );
		$event_label = static::gen_field_key( static::FIELD_LABEL );
		

		return [
			'title'    => 'Event Details',
			'fields'   => [
				static::FIELD_DATE => [
					'key'               => $event_date,
					'name'              => static::FIELD_DATE,
					'label'             => 'Event Date',
					'type'              => 'date_picker',
					'required' 			=> 1,
					'wrapper' 			=> [
						'width' 		=> '30',
					],
					'display_format' 	=> 'M. j, Y',
					'return_format' 	=> 'M. j, Y',
					'first_day' 		=> 0,
				],
				static::FIELD_TIME => [
					'key'               => $event_time,
					'name'              => static::FIELD_TIME,
					'label'             => 'Event Time',
					'type' 				=> 'time_picker',
					'required'			=> 1,
					'wrapper' 			=> [
						'width' 		=> '30',
					],
					'display_format'	=> 'g:ia',
					'return_format' 	=> 'ga',
				],
				static::FIELD_LABEL => [
					'key'               => $event_label,
					'name'              => static::FIELD_LABEL,
					'label'             => 'Event Label',
					'type' 				=> 'select',
					'choices' 			=> static::LABEL_OPTION,
					'wrapper' 			=> [
						'width' 		=> '30',
					],
					'return_format' 	=> 'label',
					'default_value' 	=> false,
				],
			],
			'location' => [
				[
					[
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => CPT\Event::POST_TYPE,
					],
				],
			],
		];
	}
}
