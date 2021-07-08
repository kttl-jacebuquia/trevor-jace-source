<?php namespace TrevorWP\Theme\ACF\Options_Page;

class Header extends A_Options_Page {
	const FIELD_FIND_SUPPORT_LINK   = 'header_find_support_link';
	const FIELD_EXPLORE_TREVOR_LINK = 'header_explore_trevor_link';
	const FIELD_COUNSELOR_LINK      = 'header_counselor_link';
	const FIELD_DONATE_LINK         = 'header_donate_link';
	const FIELD_TOP_NAV_LINKS       = 'header_top_nav_links';

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
		$find_support_link   = static::gen_field_key( static::FIELD_FIND_SUPPORT_LINK );
		$explore_trevor_link = static::gen_field_key( static::FIELD_EXPLORE_TREVOR_LINK );
		$counselor_link      = static::gen_field_key( static::FIELD_COUNSELOR_LINK );
		$donate_link         = static::gen_field_key( static::FIELD_DONATE_LINK );
		$top_nav_links       = static::gen_field_key( static::FIELD_TOP_NAV_LINKS );

		return array_merge(
			static::_gen_tab_field( 'Top Bar' ),
			array(
				static::FIELD_FIND_SUPPORT_LINK   => array(
					'key'      => $find_support_link,
					'name'     => static::FIELD_FIND_SUPPORT_LINK,
					'label'    => 'Find Support Link',
					'type'     => 'link',
					'required' => 1,
				),
				static::FIELD_EXPLORE_TREVOR_LINK => array(
					'key'      => $explore_trevor_link,
					'name'     => static::FIELD_EXPLORE_TREVOR_LINK,
					'label'    => 'Explore Trevor Link',
					'type'     => 'link',
					'required' => 1,
				),
				static::FIELD_COUNSELOR_LINK      => array(
					'key'      => $counselor_link,
					'name'     => static::FIELD_COUNSELOR_LINK,
					'label'    => 'Counselor Link',
					'type'     => 'link',
					'required' => 1,
				),
				static::FIELD_DONATE_LINK         => array(
					'key'      => $donate_link,
					'name'     => static::FIELD_DONATE_LINK,
					'label'    => 'Donate Link',
					'type'     => 'link',
					'required' => 1,
				),
			),
			static::_gen_tab_field( 'Top Navigation' ),
			array(
				static::FIELD_TOP_NAV_LINKS => array(
					'key'       => $top_nav_links,
					'name'      => static::FIELD_TOP_NAV_LINKS,
					'label'     => 'Top Navigation Links',
					'type'      => 'message',
					'required'  => 0,
					'message'   => 'Top Navigation links can be configured on the <a href="/wp-admin/nav-menus.php?action=locations">menu page</a>.',
					'new_lines' => 'wpautop',
					'esc_html'  => 0,
				),
			),
		);
	}

	/**
	 * Gets all header values
	 */
	public static function get_header() {
		$data['find_support_link']   = static::get_option( static::FIELD_FIND_SUPPORT_LINK );
		$data['explore_trevor_link'] = static::get_option( static::FIELD_EXPLORE_TREVOR_LINK );
		$data['counselor_link']      = static::get_option( static::FIELD_COUNSELOR_LINK );
		$data['donate_link']         = static::get_option( static::FIELD_DONATE_LINK );

		$data['find_support_link']['url']   = ! empty( $data['find_support_link']['url'] ) ? $data['find_support_link']['url'] : home_url( \TrevorWP\CPT\RC\RC_Object::PERMALINK_BASE );
		$data['find_support_link']['title'] = ! empty( $data['find_support_link']['title'] ) ? $data['find_support_link']['title'] : 'Find Support';

		$data['explore_trevor_link']['url']   = ! empty( $data['explore_trevor_link']['url'] ) ? $data['explore_trevor_link']['url'] : home_url( \TrevorWP\CPT\Org\Org_Object::PERMALINK_ORG_LP );
		$data['explore_trevor_link']['title'] = ! empty( $data['explore_trevor_link']['title'] ) ? $data['explore_trevor_link']['title'] : 'Explore Trevor';

		$data['counselor_link']['url']   = ! empty( $data['counselor_link']['url'] ) ? $data['counselor_link']['url'] : home_url( \TrevorWP\CPT\RC\RC_Object::PERMALINK_GET_HELP );
		$data['counselor_link']['title'] = ! empty( $data['counselor_link']['title'] ) ? $data['counselor_link']['title'] : 'Reach a Counselor';

		$data['donate_link']['url']   = ! empty( $data['donate_link']['url'] ) ? $data['donate_link']['url'] : home_url( \TrevorWP\CPT\Donate\Donate_Object::PERMALINK_DONATE );
		$data['donate_link']['title'] = ! empty( $data['donate_link']['title'] ) ? $data['donate_link']['title'] : 'Donate';

		return $data;
	}
}
