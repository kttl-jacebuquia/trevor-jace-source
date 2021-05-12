<?php namespace TrevorWP\Theme\ACF\Field_Group;

use TrevorWP\CPT\Page_ReCirculation;

class Page_Circulation_Card extends A_Basic_Section {
	/** @inheritdoc */
	public static function prepare_register_args(): array {
		return array_merge(
			parent::prepare_register_args(),
			array(
				'title'    => 'Page Circulation Card',
				'location' => array(
					array(
						array(
							'param'    => 'post_type',
							'operator' => '==',
							'value'    => Page_ReCirculation::POST_TYPE,
						),
					),
				),
			)
		);
	}
}
