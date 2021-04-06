<?php namespace TrevorWP\Theme\ACF;

class ACF {
	const ALL_GROUPS = [
		// Common Fields
		Field_Group\DOM_Attr::class,
		Field_Group\Button::class,
		Field_Group\Button_Group::class,
		Field_Group\Carousel_Data::class,
		// Blocks
		Field_Group\Page_Section::class,
		// Page Specific
		Field_Group\Page_Header::class,
	];

	public static function construct() {
		add_action( 'acf/init', [ self::class, 'acf_init' ], 10, 0 );
	}

	/**
	 * @link https://www.advancedcustomfields.com/resources/acf-init/
	 */
	public static function acf_init(): void {
		# Groups
		/** @var Field_Group\A_Field_Group $group */
		foreach ( static::ALL_GROUPS as $group ) {
			$group::register();

			if ( $group::is_block() ) {
				$group::register_block();
			}
		}
	}
}
