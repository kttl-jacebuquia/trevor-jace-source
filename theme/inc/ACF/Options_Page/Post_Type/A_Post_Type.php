<?php namespace TrevorWP\Theme\ACF\Options_Page\Post_Type;

use TrevorWP\Theme\ACF\Field_Group\Button_Group;
use TrevorWP\Theme\ACF\Field_Group\Page_Header;
use TrevorWP\Theme\ACF\Options_Page\A_Options_Page;

/**
 * Abstract Post Type Options Page
 */
abstract class A_Post_Type extends A_Options_Page {
	const PAGE_SLUG_PREFIX = 'trvr-pt-';
	const POST_TYPE = ''; // required

	# Pagination Types
	const PAGINATION_TYPE_AJAX = 'ajax';
	const PAGINATION_TYPE_NORMAL = 'normal';

	# Fields
	const FIELD_HEADER = 'header';
	const FIELD_SORTER_ACTIVE = 'sorter_active';
	const FIELD_ARCHIVE_PP = 'arc_pp';
	const FIELD_ARCHIVE_PAGINATION_TYPE = 'arc_pg_type';
	const FIELD_ARCHIVE_CONTENT_TOP = 'arc_content_top';
	const FIELD_ARCHIVE_CONTENT_BTM = 'arc_content_btm';
	const FIELD_ARCHIVE_CTA = 'arc_cta';
	// todo: add slug

	/** @inheritDoc */
	protected static function prepare_page_register_args(): array {
		$pto = get_post_type_object( static::POST_TYPE );

		return array_merge( parent::prepare_page_register_args(), [
			'parent_slug' => 'edit.php?post_type=' . static::POST_TYPE,
			'menu_title'  => 'Options',
			'page_title'  => "{$pto->label} Options"
		] );
	}

	/** @inheritDoc */
	protected static function prepare_fields(): array {
		$header              = static::gen_field_key( static::FIELD_HEADER );
		$sorter_active       = static::gen_field_key( static::FIELD_SORTER_ACTIVE );
		$archive_pp          = static::gen_field_key( static::FIELD_ARCHIVE_PP );
		$archive_pt          = static::gen_field_key( static::FIELD_ARCHIVE_PAGINATION_TYPE );
		$archive_content_top = static::gen_field_key( static::FIELD_ARCHIVE_CONTENT_TOP );
		$archive_content_btm = static::gen_field_key( static::FIELD_ARCHIVE_CONTENT_BTM );
		$archive_cta         = static::gen_field_key( static::FIELD_ARCHIVE_CTA );

		return [
			static::FIELD_HEADER                  => Page_Header::clone( [
				'key'         => $header,
				'name'        => $header, // name = key
				'prefix_name' => true,
				'display'     => 'group',
				'label'       => 'Header',
			] ),
			static::FIELD_SORTER_ACTIVE           => [
				'key'           => $sorter_active,
				'name'          => $sorter_active,
				'type'          => 'true_false',
				'label'         => 'Sorter Active',
				'default_value' => true,
				'ui'            => true,
			],
			static::FIELD_ARCHIVE_PP              => [
				'key'           => $archive_pp,
				'name'          => $archive_pp,
				'type'          => 'number',
				'label'         => 'Archive Per Page',
				'default_value' => 6,
			],
			static::FIELD_ARCHIVE_PAGINATION_TYPE => [
				'key'           => $archive_pt,
				'name'          => $archive_pt,
				'type'          => 'select',
				'label'         => 'Pagination Type',
				'default_value' => static::PAGINATION_TYPE_AJAX,
				'choices'       => [
					static::PAGINATION_TYPE_AJAX   => 'Ajax',
					static::PAGINATION_TYPE_NORMAL => 'Normal',
				],
			],
			static::FIELD_ARCHIVE_CONTENT_TOP     => [
				'key'   => $archive_content_top,
				'name'  => $archive_content_top,
				'label' => 'Content / Top',
				'type'  => 'wysiwyg',
			],
			static::FIELD_ARCHIVE_CONTENT_BTM     => [
				'key'   => $archive_content_btm,
				'name'  => $archive_content_btm,
				'label' => 'Content / Bottom',
				'type'  => 'wysiwyg',
			],
			static::FIELD_ARCHIVE_CTA             => Button_Group::clone( [
				'key'         => $archive_cta,
				'name'        => $archive_cta,
				'prefix_name' => true,
				'label'       => 'CTA',
				'display'     => 'group',
			] ),
		];
	}

	/** @inheritDoc */
	public static function gen_key( string $suffix = '' ): string {
		return static::POST_TYPE;
	}

	/**
	 * @param string $post_type
	 * @param string $field_name
	 *
	 * @return mixed
	 */
	public static function get_option_for( string $post_type, string $field_name ) {
		return get_field( static::gen_field_key( $field_name, $post_type ), 'option' );
	}
}
