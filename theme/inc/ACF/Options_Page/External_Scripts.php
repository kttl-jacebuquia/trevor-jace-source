<?php namespace TrevorWP\Theme\ACF\Options_Page;

class External_Scripts extends A_Options_Page {
	const FIELD_HEAD_TOP    = 'head_top';
	const FIELD_HEAD_BOTTOM = 'head_bottom';
	const FIELD_BODY_TOP    = 'body_top';
	const FIELD_BODY_BOTTOM = 'body_bottom';

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
		$head_top    = static::gen_field_key( static::FIELD_HEAD_TOP );
		$head_bottom = static::gen_field_key( static::FIELD_HEAD_BOTTOM );
		$body_top    = static::gen_field_key( static::FIELD_BODY_TOP );
		$body_bottom = static::gen_field_key( static::FIELD_BODY_BOTTOM );

		return array_merge(
			static::_gen_tab_field( 'Head' ),
			array(
				static::FIELD_HEAD_TOP    => array(
					'key'          => $head_top,
					'name'         => static::FIELD_HEAD_TOP,
					'label'        => 'Head Top',
					'type'         => 'textarea',
					'required'     => 0,
					'instructions' => esc_html( 'Just after the <head> tag.' ),
				),
				static::FIELD_HEAD_BOTTOM => array(
					'key'          => $head_bottom,
					'name'         => static::FIELD_HEAD_BOTTOM,
					'label'        => 'Head Bottom',
					'type'         => 'textarea',
					'required'     => 0,
					'instructions' => esc_html( 'Just before the </head> tag.' ),
				),
			),
			static::_gen_tab_field( 'Body' ),
			array(
				static::FIELD_BODY_TOP    => array(
					'key'          => $body_top,
					'name'         => static::FIELD_BODY_TOP,
					'label'        => 'Body Top',
					'type'         => 'textarea',
					'required'     => 0,
					'instructions' => esc_html( 'Just after the <body> tag.' ),
				),
				static::FIELD_BODY_BOTTOM => array(
					'key'          => $body_bottom,
					'name'         => static::FIELD_BODY_BOTTOM,
					'label'        => 'Body Bottom',
					'type'         => 'textarea',
					'required'     => 0,
					'instructions' => esc_html( 'Just before the </body> tag.' ),
				),
			),
		);
	}

	/** @inheritDoc */
	public static function get_external_script( string $location ): string {
		switch ( $location ) {
			case 'HEAD_TOP':
				return static::get_option( static::FIELD_HEAD_TOP );
				break;
			case 'HEAD_BOTTOM':
				return static::get_option( static::FIELD_HEAD_BOTTOM );
				break;
			case 'BODY_TOP':
				return static::get_option( static::FIELD_BODY_TOP );
				break;
			case 'BODY_BOTTOM':
				return static::get_option( static::FIELD_BODY_BOTTOM );
				break;
		}
		return '';
	}
}
