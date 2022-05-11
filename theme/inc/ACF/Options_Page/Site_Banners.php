<?php namespace TrevorWP\Theme\ACF\Options_Page;

use TrevorWP\Main;

class Site_Banners extends A_Options_Page {
	const LONG_WAIT_TRANSIENT = 'trevor__long_wait_banner_transient';

	const FIELD_LONG_WAIT_URL           = 'long_wait_url';
	const FIELD_LONG_WAIT_FORCE_SHOW    = 'force_show';
	const FIELD_LONG_WAIT_TITLE         = 'long_wait_title';
	const FIELD_LONG_WAIT_DESCRIPTION   = 'long_wait_description';
	const FIELD_LONG_WAIT_CURRENT       = 'long_wait_current';
	const FIELD_CUSTOM_ENTRIES          = 'custom_entries';
	const FIELD_CUSTOM_ENTRY_FORCE_SHOW = 'custom_entry_force_show';
	const FIELD_CUSTOM_ENTRY_TITLE      = 'custom_entry_title';
	const FIELD_CUSTOM_ENTRY_MESSAGE    = 'custom_entry_message';
	const FIELD_CUSTOM_ENTRY_START_DATE = 'custom_entry_start_date';
	const FIELD_CUSTOM_ENTRY_END_DATE   = 'custom_entry_end_date';
	const FIELD_PRIDE_PROMO_START_DATE  = 'pride_promo_start_date';
	const FIELD_PRIDE_PROMO_END_DATE    = 'pride_promo_end_date';
	const FIELD_PRIDE_PROMO_TITLE       = 'pride_promo_title';
	const FIELD_PRIDE_PROMO_LINK        = 'pride_promo_link';
	const FIELD_PRIDE_PROMO_LINK_LABEL  = 'pride_promo_link_title';
	const FIELD_PRIDE_PROMO_LINK_URL    = 'pride_promo_link_url';

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
		$long_wait_url           = static::gen_field_key( static::FIELD_LONG_WAIT_URL );
		$long_wait_force_show    = static::gen_field_key( static::FIELD_LONG_WAIT_FORCE_SHOW );
		$long_wait_title         = static::gen_field_key( static::FIELD_LONG_WAIT_TITLE );
		$long_wait_description   = static::gen_field_key( static::FIELD_LONG_WAIT_DESCRIPTION );
		$long_wait_current       = static::gen_field_key( static::FIELD_LONG_WAIT_CURRENT );
		$custom_entries          = static::gen_field_key( static::FIELD_CUSTOM_ENTRIES );
		$custom_entry_force_show = static::gen_field_key( static::FIELD_CUSTOM_ENTRY_FORCE_SHOW );
		$custom_entry_start_date = static::gen_field_key( static::FIELD_CUSTOM_ENTRY_START_DATE );
		$custom_entry_end_date   = static::gen_field_key( static::FIELD_CUSTOM_ENTRY_END_DATE );
		$custom_entry_title      = static::gen_field_key( static::FIELD_CUSTOM_ENTRY_TITLE );
		$custom_entry_message    = static::gen_field_key( static::FIELD_CUSTOM_ENTRY_MESSAGE );
		$pride_promo_start_date  = static::gen_field_key( static::FIELD_PRIDE_PROMO_START_DATE );
		$pride_promo_end_date    = static::gen_field_key( static::FIELD_PRIDE_PROMO_END_DATE );
		$pride_promo_title       = static::gen_field_key( static::FIELD_PRIDE_PROMO_TITLE );
		$pride_promo_link        = static::gen_field_key( static::FIELD_PRIDE_PROMO_LINK );
		$pride_promo_link_label  = static::gen_field_key( static::FIELD_PRIDE_PROMO_LINK_LABEL );
		$pride_promo_link_url    = static::gen_field_key( static::FIELD_PRIDE_PROMO_LINK_URL );

		$current_long_wait_value = get_option( Main::OPTION_KEY_COUNSELOR_LONG_WAIT ) ? 'TRUE' : 'FALSE';

		return array_merge(
			static::_gen_tab_field( 'Long Wait' ),
			array(
				static::FIELD_LONG_WAIT_URL         => array(
					'key'           => $long_wait_url,
					'name'          => static::FIELD_LONG_WAIT_URL,
					'label'         => 'Wait URL',
					'type'          => 'url',
					'default_value' => 'https://trevorproject.secure.force.com/services/apexrest/wait',
					'required'      => true,
					'wrapper'       => array(
						'width' => '50%',
					),
				),
				static::FIELD_LONG_WAIT_FORCE_SHOW  => array(
					'key'         => $long_wait_force_show,
					'name'        => static::FIELD_LONG_WAIT_FORCE_SHOW,
					'label'       => 'Force Show',
					'type'        => 'true_false',
					'ui'          => 1,
					'ui_on_text'  => '',
					'ui_off_text' => '',
					'wrapper'     => array(
						'width' => '50%',
					),
				),
				static::FIELD_LONG_WAIT_TITLE       => array(
					'key'      => $long_wait_title,
					'name'     => static::FIELD_LONG_WAIT_TITLE,
					'label'    => 'Title',
					'type'     => 'text',
					'required' => true,
					'wrapper'  => array(
						'width' => '50%',
					),
				),
				static::FIELD_LONG_WAIT_DESCRIPTION => array(
					'key'     => $long_wait_description,
					'name'    => static::FIELD_LONG_WAIT_DESCRIPTION,
					'label'   => 'Description',
					'type'    => 'text',
					'wrapper' => array(
						'width' => '50%',
					),
				),
				static::FIELD_LONG_WAIT_DESCRIPTION => array(
					'key'     => $long_wait_description,
					'name'    => static::FIELD_LONG_WAIT_DESCRIPTION,
					'label'   => 'Description',
					'type'    => 'text',
					'wrapper' => array(
						'width' => '50%',
					),
				),
				static::FIELD_LONG_WAIT_CURRENT     => array(
					'key'         => $long_wait_current,
					'name'        => static::FIELD_LONG_WAIT_CURRENT,
					'label'       => 'Current Value',
					'type'        => 'true_false',
					'ui'          => 1,
					'ui_on_text'  => 'TRUE',
					'ui_off_text' => 'FALSE',
				),
			),
			static::_gen_tab_field( 'Custom' ),
			array(
				static::FIELD_CUSTOM_ENTRIES => array(
					'key'        => $custom_entries,
					'name'       => static::FIELD_CUSTOM_ENTRIES,
					'label'      => 'Custom Entries',
					'type'       => 'repeater',
					'layout'     => 'block',
					'sub_fields' => array(
						static::FIELD_CUSTOM_ENTRY_FORCE_SHOW => array(
							'key'         => $custom_entry_force_show,
							'name'        => static::FIELD_CUSTOM_ENTRY_FORCE_SHOW,
							'label'       => 'Force Show',
							'type'        => 'true_false',
							'ui'          => 1,
							'ui_on_text'  => '',
							'ui_off_text' => '',
						),
						static::FIELD_CUSTOM_ENTRY_START_DATE => array(
							'key'               => $custom_entry_start_date,
							'name'              => static::FIELD_CUSTOM_ENTRY_START_DATE,
							'label'             => 'Start Date',
							'type'              => 'date_time_picker',
							'display_format'    => 'M j, Y g:i:s a',
							'return_format'     => 'M j, Y H:i:s',
							'first_day'         => 0,
							'wrapper'           => array(
								'width' => '50%',
							),
							'conditional_logic' => array(
								array(
									'field'    => $custom_entry_force_show,
									'operator' => '==',
									'value'    => 0,
								),
							),
						),
						static::FIELD_CUSTOM_ENTRY_END_DATE => array(
							'key'               => $custom_entry_end_date,
							'name'              => static::FIELD_CUSTOM_ENTRY_END_DATE,
							'label'             => 'End Date',
							'type'              => 'date_time_picker',
							'display_format'    => 'M j, Y g:i:s a',
							'return_format'     => 'M j, Y H:i:s',
							'first_day'         => 0,
							'wrapper'           => array(
								'width' => '50%',
							),
							'conditional_logic' => array(
								array(
									'field'    => $custom_entry_force_show,
									'operator' => '==',
									'value'    => 0,
								),
							),
						),
						static::FIELD_CUSTOM_ENTRY_TITLE   => array(
							'key'      => $custom_entry_title,
							'name'     => static::FIELD_CUSTOM_ENTRY_TITLE,
							'label'    => 'Title',
							'type'     => 'text',
							'required' => true,
							'wrapper'  => array(
								'width' => '50%',
							),
						),
						static::FIELD_CUSTOM_ENTRY_MESSAGE => array(
							'key'     => $custom_entry_message,
							'name'    => static::FIELD_CUSTOM_ENTRY_MESSAGE,
							'label'   => 'Message',
							'type'    => 'text',
							'wrapper' => array(
								'width' => '50%',
							),
						),
					),
				),
			),
			static::_gen_tab_field( 'Pride Promo' ),
			array(
				static::FIELD_PRIDE_PROMO_START_DATE => array(
					'key'            => $pride_promo_start_date,
					'name'           => static::FIELD_PRIDE_PROMO_START_DATE,
					'label'          => 'Start Date',
					'type'           => 'date_time_picker',
					'wrapper'        => array(
						'width' => '50%',
					),
					'display_format' => 'M j, Y g:i:s a',
					'return_format'  => 'M j, Y H:i:s',
					'first_day'      => 0,
				),
				static::FIELD_PRIDE_PROMO_END_DATE   => array(
					'key'            => $pride_promo_end_date,
					'name'           => static::FIELD_PRIDE_PROMO_END_DATE,
					'label'          => 'End Date',
					'type'           => 'date_time_picker',
					'wrapper'        => array(
						'width' => '50%',
					),
					'display_format' => 'M j, Y g:i:s a',
					'return_format'  => 'M j, Y H:i:s',
					'first_day'      => 0,
				),
				static::FIELD_PRIDE_PROMO_TITLE      => array(
					'key'          => $pride_promo_title,
					'name'         => static::FIELD_PRIDE_PROMO_TITLE,
					'label'        => 'Title',
					'type'         => 'text',
					'instructions' => 'HTML tags are accepted. Use ' . esc_html( '<tilt></tilt>' ) . ' for cursive text style',
				),
				static::FIELD_PRIDE_PROMO_LINK       => array(
					'key'        => $pride_promo_link,
					'name'       => static::FIELD_PRIDE_PROMO_LINK,
					'label'      => 'Link',
					'type'       => 'group',
					'layout'     => 'block',
					'sub_fields' => array(
						static::FIELD_PRIDE_PROMO_LINK_LABEL => array(
							'key'     => $pride_promo_link_label,
							'name'    => static::FIELD_PRIDE_PROMO_LINK_LABEL,
							'label'   => 'Link Text',
							'type'    => 'text',
							'wrapper' => array(
								'width' => '50%',
							),
						),
						static::FIELD_PRIDE_PROMO_LINK_URL => array(
							'key'            => $pride_promo_link_url,
							'name'           => static::FIELD_PRIDE_PROMO_LINK_URL,
							'label'          => 'Link URL',
							'type'           => 'page_link',
							'allow_archives' => 0,
							'wrapper'        => array(
								'width' => '50%',
							),
						),
					),
				),
			)
		);
	}

	public static function is_long_wait() {
		$is_long_wait = get_transient( static::LONG_WAIT_TRANSIENT );

		if ( $is_long_wait ) {
			return $is_long_wait;
		}

		$url = Site_Banners::get_option( Site_Banners::FIELD_LONG_WAIT_URL );

		if ( $url ) {
			$response = wp_remote_get( $url, array( 'timeout' => 2 ) );

			if ( is_array( $response ) && ! is_wp_error( $response ) ) {
				$body = json_decode( $response['body'] );

				$long_wait = empty( $body->isLongWait ) ? 0 : 1;

				set_transient( static::LONG_WAIT_TRANSIENT, $long_wait, 30 );
				update_option( Main::OPTION_KEY_COUNSELOR_LONG_WAIT, $long_wait );

				return $long_wait;
			} else {
				set_transient( static::LONG_WAIT_TRANSIENT, 0, 30 );
				update_option( Main::OPTION_KEY_COUNSELOR_LONG_WAIT, 0 );

				return false;
			}
		}

		return false;
	}
}
