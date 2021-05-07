<?php

namespace TrevorWP\Theme\ACF\Options_Page\Post_Type;

use TrevorWP\Theme\ACF\Field_Group\Button_Group;
use TrevorWP\Theme\ACF\Field_Group\Page_Header;
use TrevorWP\Theme\ACF\Field_Group\DOM_Attr;
use TrevorWP\Theme\ACF\Options_Page\A_Options_Page;

/**
 * Abstract Post Type Options Page
 */
abstract class A_Post_Type extends A_Options_Page {
	const PAGE_SLUG_PREFIX = 'trvr-pt-';
	const POST_TYPE        = ''; // required

	# Pagination Types
	const PAGINATION_TYPE_AJAX   = 'ajax';
	const PAGINATION_TYPE_NORMAL = 'normal';

	# Fields
	const FIELD_HEADER                  = 'header';
	const FIELD_SORTER_ACTIVE           = 'sorter_active';
	const FIELD_ARCHIVE_PP              = 'arc_pp';
	const FIELD_ARCHIVE_PAGINATION_TYPE = 'arc_pg_type';
	const FIELD_ARCHIVE_CONTENT_TOP     = 'arc_content_top';
	const FIELD_ARCHIVE_CONTENT_BTM     = 'arc_content_btm';
	const FIELD_ARCHIVE_CTA             = 'arc_cta';
	const FIELD_ARCHIVE_CTA_LOCATION    = 'arc_cta_location';
	const FIELD_NUM_WORDS               = 'num_words';
	const FIELD_ARCHIVE_CONTAINER_ATTR  = 'arc_container_attrs';
	// todo: add slug

	# CTA Location Options
	const CTA_LOCATION_OPTION_BEFORE = 'arc_cta_location_before_btm';
	const CTA_LOCATION_OPTION_INSIDE = 'arc_cta_location_inside_btm';
	const CTA_LOCATION_OPTION_AFTER  = 'arc_cta_location_after_btm';

	/** @inheritDoc */
	protected static function prepare_page_register_args(): array {
		$pto = get_post_type_object( static::POST_TYPE );

		return array_merge(
			parent::prepare_page_register_args(),
			array(
				'parent_slug' => 'edit.php?post_type=' . static::POST_TYPE,
				'menu_title'  => 'Options',
				'page_title'  => "{$pto->label} Options",
			)
		);
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
		$arc_cta_location    = static::gen_field_key( static::FIELD_ARCHIVE_CTA_LOCATION );
		$arc_container_attrs = static::gen_field_key( static::FIELD_ARCHIVE_CONTAINER_ATTR );

		return array(
			static::FIELD_HEADER                  => Page_Header::clone(
				array(
					'key'         => $header,
					'name'        => $header, // name = key
					'prefix_name' => true,
					'display'     => 'group',
					'label'       => 'Header',
				)
			),
			static::FIELD_SORTER_ACTIVE           => array(
				'key'           => $sorter_active,
				'name'          => $sorter_active,
				'type'          => 'true_false',
				'label'         => 'Sorter Active',
				'default_value' => true,
				'ui'            => true,
			),
			static::FIELD_ARCHIVE_CONTAINER_ATTR  => DOM_Attr::clone(
				array(
					'key'     => $arc_container_attrs,
					'name'    => static::FIELD_ARCHIVE_CONTAINER_ATTR,
					'label'   => 'Archive Container',
					'display' => 'group',
				)
			),
			static::FIELD_ARCHIVE_PP              => array(
				'key'           => $archive_pp,
				'name'          => $archive_pp,
				'type'          => 'number',
				'label'         => 'Archive Per Page',
				'default_value' => 6,
			),
			static::FIELD_ARCHIVE_PAGINATION_TYPE => array(
				'key'           => $archive_pt,
				'name'          => $archive_pt,
				'type'          => 'select',
				'label'         => 'Pagination Type',
				'default_value' => static::PAGINATION_TYPE_AJAX,
				'choices'       => array(
					static::PAGINATION_TYPE_AJAX   => 'Ajax',
					static::PAGINATION_TYPE_NORMAL => 'Normal',
				),
			),
			static::FIELD_ARCHIVE_CONTENT_TOP     => array(
				'key'   => $archive_content_top,
				'name'  => $archive_content_top,
				'label' => 'Content / Top',
				'type'  => 'wysiwyg',
			),
			static::FIELD_ARCHIVE_CONTENT_BTM     => array(
				'key'   => $archive_content_btm,
				'name'  => $archive_content_btm,
				'label' => 'Content / Bottom',
				'type'  => 'wysiwyg',
			),
			static::FIELD_ARCHIVE_CTA             => Button_Group::clone(
				array(
					'key'         => $archive_cta,
					'name'        => $archive_cta,
					'prefix_name' => true,
					'label'       => 'CTA',
					'display'     => 'group',
				)
			),
			static::FIELD_ARCHIVE_CTA_LOCATION    => array(
				'key'           => $arc_cta_location,
				'name'          => $arc_cta_location,
				'prefix_name'   => true,
				'label'         => 'CTA Location',
				'display'       => 'group',
				'type'          => 'radio',
				'choices'       => array(
					static::CTA_LOCATION_OPTION_BEFORE => 'Before Bottom Content',
					static::CTA_LOCATION_OPTION_INSIDE => 'Inside Bottom Content',
					static::CTA_LOCATION_OPTION_AFTER  => 'After Bottom Content',
				),
				'default_value' => static::CTA_LOCATION_OPTION_BEFORE,
				'layout'        => 'vertical',
				'wrapper'       => array(
					'width' => '50',
				),
			),
		);
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
