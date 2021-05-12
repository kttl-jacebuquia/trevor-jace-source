<?php namespace TrevorWP\Theme\ACF\Options_Page;

use TrevorWP\Theme\ACF\Field_Group;
use TrevorWP\Theme\ACF\Util\Field_Val_Getter;

class Donation_Modal extends A_Options_Page {
	const FIELD_TITLE = 'title';
	const FIELD_INTRO = 'intro';
	const FIELD_PAGES = 'pages';

	/** @inheritDoc */
	protected static function prepare_fields(): array {
		$title = static::gen_field_key( static::FIELD_TITLE );
		$intro = static::gen_field_key( static::FIELD_INTRO );
		$pages = static::gen_field_key( static::FIELD_PAGES );

		return array(
			static::FIELD_TITLE => array(
				'key'   => $title,
				'name'  => static::FIELD_TITLE,
				'label' => 'Title',
				'type'  => 'text',
			),
			static::FIELD_INTRO => array(
				'key'   => $intro,
				'name'  => static::FIELD_INTRO,
				'label' => 'Intro',
				'type'  => 'textarea',
			),
			static::FIELD_PAGES => array(
				'key'           => $pages,
				'name'          => static::FIELD_PAGES,
				'label'         => 'Visible Pages',
				'type'          => 'relationship',
				'post_type'     => array( 'page' ),
				'return_format' => 'id',
			),
		);
	}

	/**
	 * Checks whether donate modal will render in the given post ID
	 */
	public static function will_render_in( $post_id ) {
		$pages = (array) static::get_option( static::FIELD_PAGES );
		return in_array( $post_id, $pages );
	}

	/**
	 * Renders the fundraiser quiz modal
	 */
	public static function render(): string {
		$heading = static::get_option( static::FIELD_TITLE );
		$intro   = static::get_option( static::FIELD_INTRO );

		return Field_Group\Donate_Form::render( null, null, compact( 'heading', 'intro' ) );
	}

}
