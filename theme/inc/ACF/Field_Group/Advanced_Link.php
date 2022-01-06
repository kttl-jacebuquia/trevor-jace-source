<?php namespace TrevorWP\Theme\ACF\Field_Group;

/**
 * TODO:
 * Add more fields for Fundraise Quiz when tackled later on.
 */

use TrevorWP\CPT;
use TrevorWP\CPT\Research;
use TrevorWP\Theme\ACF\Util\Field_Val_Getter;
use TrevorWP\Theme\Helper\Breathing_Exercise;
use TrevorWP\Theme\Helper\DonationModal;
use TrevorWP\Theme\Helper\FundraiserQuizModal;
use TrevorWP\Theme\Helper\WhatToExpectModal;

class Advanced_Link extends A_Field_Group implements I_Renderable {
	const FIELD_LABEL                = 'label';
	const FIELD_ACTION               = 'action';
	const FIELD_LINK                 = 'link';
	const FIELD_PAGE_LINK            = 'page_link';
	const FIELD_FILE                 = 'file';
	const FIELD_MODAL                = 'modal';
	const FIELD_DONATE_DEDICATION    = 'donate_dedication';
	const FIELD_TEXTONLY_POPUP       = 'textonly_popup';
	const FIELD_WHAT_TO_EXPECT_POPUP = 'what_to_expect_popup';
	const FIELD_PHONE                = 'phone';
	const FIELD_EMAIL                = 'email';
	const FIELD_FORM                 = 'form';
	const FIELD_LATEST_RESEARCH      = 'latest_research';
	const FIELD_SHOW_DOWNLOAD_ICON   = 'show_download_icon';
	const FIELD_SHOW_DEV_FORM_ONLY   = 'show_dev_form_only';

	/** @inheritDoc */
	public static function register(): bool {
		add_filter( 'acf/load_field/name=' . static::FIELD_LATEST_RESEARCH, array( static::class, 'load_field_latest_research' ) );

		return parent::register();
	}

	/** @inheritDoc */
	public static function prepare_register_args(): array {
		return static::_get_fields();
	}

	/** @inheritDoc */
	public static function _get_fields(): array {
		$label                = static::gen_field_key( static::FIELD_LABEL );
		$action               = static::gen_field_key( static::FIELD_ACTION );
		$link                 = static::gen_field_key( static::FIELD_LINK );
		$page_link            = static::gen_field_key( static::FIELD_PAGE_LINK );
		$file                 = static::gen_field_key( static::FIELD_FILE );
		$modal                = static::gen_field_key( static::FIELD_MODAL );
		$donate_dedication    = static::gen_field_key( static::FIELD_DONATE_DEDICATION );
		$textonly_popup       = static::gen_field_key( static::FIELD_TEXTONLY_POPUP );
		$what_to_expect_popup = static::gen_field_key( static::FIELD_WHAT_TO_EXPECT_POPUP );
		$phone                = static::gen_field_key( static::FIELD_PHONE );
		$email                = static::gen_field_key( static::FIELD_EMAIL );
		$form                 = static::gen_field_key( static::FIELD_FORM );
		$latest_research      = static::gen_field_key( static::FIELD_LATEST_RESEARCH );
		$show_download_icon   = static::gen_field_key( static::FIELD_SHOW_DOWNLOAD_ICON );
		$show_dev_form_only   = static::gen_field_key( static::FIELD_SHOW_DEV_FORM_ONLY );

		return array_merge(
			static::_gen_tab_field( 'Action' ),
			array(
				static::FIELD_LABEL  => array(
					'key'      => $label,
					'name'     => static::FIELD_LABEL,
					'label'    => 'Label',
					'type'     => 'text',
					'required' => true,
				),
				static::FIELD_ACTION => array(
					'key'           => $action,
					'name'          => static::FIELD_ACTION,
					'label'         => 'Action',
					'type'          => 'select',
					'required'      => true,
					'choices'       => array(
						'link'            => 'Link',
						'page_link'       => 'Page Link',
						'file_download'   => 'File Download',
						'modal'           => 'Pop-up Modal',
						'call'            => 'Call',
						'sms'             => 'SMS',
						'trevor_chat'     => 'Trevor Chat',
						'email'           => 'Email',
						'form'            => 'Forms',
						'latest_research' => 'Latest Research Report',
					),
					'default_value' => 'page_link',
					'allow_null'    => true,
					'multiple'      => false,
				),
			),
			static::_gen_tab_field(
				'Data',
				array(
					'conditional_logic' => array(
						array(
							'field'    => $action,
							'operator' => '!=',
							'value'    => 'trevor_chat',
						),
					),
				),
			),
			array(
				static::FIELD_PHONE                => array(
					'key'               => $phone,
					'name'              => static::FIELD_PHONE,
					'label'             => 'Phone Number',
					'type'              => 'text',
					'conditional_logic' => array(
						array(
							array(
								'field'    => $action,
								'operator' => '==',
								'value'    => 'call',
							),
						),
						array(
							array(
								'field'    => $action,
								'operator' => '==',
								'value'    => 'sms',
							),
						),
					),
				),
				static::FIELD_EMAIL                => array(
					'key'               => $email,
					'name'              => static::FIELD_EMAIL,
					'label'             => 'Email',
					'type'              => 'email',
					'required'          => 1,
					'conditional_logic' => array(
						array(
							array(
								'field'    => $action,
								'operator' => '==',
								'value'    => 'email',
							),
						),
					),
				),
				static::FIELD_LINK                 => array(
					'key'               => $link,
					'label'             => 'Link',
					'name'              => static::FIELD_LINK,
					'type'              => 'link',
					'conditional_logic' => array(
						array(
							array(
								'field'    => $action,
								'operator' => '==',
								'value'    => 'link',
							),
						),
					),
					'return_format'     => 'array',
				),
				static::FIELD_SHOW_DOWNLOAD_ICON   => array(
					'key'               => $show_download_icon,
					'name'              => static::FIELD_SHOW_DOWNLOAD_ICON,
					'label'             => 'Show download icon',
					'type'              => 'true_false',
					'required'          => 0,
					'default_value'     => 0,
					'ui'                => 1,
					'ui_on_text'        => '',
					'ui_off_text'       => '',
					'conditional_logic' => array(
						array(
							array(
								'field'    => $action,
								'operator' => '==',
								'value'    => 'file_download',
							),
						),
					),
				),
				static::FIELD_PAGE_LINK            => array(
					'key'               => $page_link,
					'name'              => static::FIELD_PAGE_LINK,
					'label'             => 'Page Link',
					'type'              => 'page_link',
					'required'          => true,
					'conditional_logic' => array(
						array(
							array(
								'field'    => $action,
								'operator' => '==',
								'value'    => 'page_link',
							),
						),
					),
					'allow_null'        => false,
					'multiple'          => false,
					'return_format'     => 'object',
					'ui'                => true,
				),
				static::FIELD_FILE                 => array(
					'key'               => $file,
					'name'              => static::FIELD_FILE,
					'label'             => 'File',
					'type'              => 'file',
					'required'          => true,
					'conditional_logic' => array(
						array(
							array(
								'field'    => $action,
								'operator' => '==',
								'value'    => 'file_download',
							),
						),
					),
					'allow_null'        => false,
					'multiple'          => false,
					'return_format'     => 'object',
					'ui'                => true,
				),
				static::FIELD_MODAL                => array(
					'key'               => $modal,
					'name'              => static::FIELD_MODAL,
					'label'             => 'Modal',
					'type'              => 'select',
					'required'          => 1,
					'conditional_logic' => array(
						array(
							array(
								'field'    => $action,
								'operator' => '==',
								'value'    => 'modal',
							),
						),
					),
					'choices'           => array(
						'donate'             => 'Donate Modal',
						'fundraise_quiz'     => 'Fundraise Quiz Modal',
						'text_only_popup'    => 'Text Only Pop-up',
						'what_to_expect'     => 'What to Expect Pop-up',
						'breathing_exercise' => 'Breathing Exercise',
					),
				),
				static::FIELD_TEXTONLY_POPUP       => array(
					'key'               => $textonly_popup,
					'name'              => static::FIELD_TEXTONLY_POPUP,
					'label'             => 'Text Only Popup',
					'type'              => 'post_object',
					'required'          => 1,
					'conditional_logic' => array(
						array(
							array(
								'field'    => $modal,
								'operator' => '==',
								'value'    => 'text_only_popup',
							),
						),
					),
					'post_type'         => array(
						0 => CPT\Text_Only_Popup::POST_TYPE,
					),
					'filters'           => array(
						0 => 'search',
					),
					'taxonomy'          => '',
					'allow_null'        => 0,
					'multiple'          => 0,
					'return_format'     => 'object',
					'ui'                => 1,
				),
				static::FIELD_WHAT_TO_EXPECT_POPUP => array(
					'key'               => $what_to_expect_popup,
					'name'              => static::FIELD_WHAT_TO_EXPECT_POPUP,
					'label'             => 'What To Expect',
					'type'              => 'post_object',
					'required'          => 1,
					'conditional_logic' => array(
						array(
							array(
								'field'    => $modal,
								'operator' => '==',
								'value'    => 'what_to_expect',
							),
						),
					),
					'post_type'         => array(
						0 => CPT\What_To_Expect_Popup::POST_TYPE,
					),
					'filters'           => array(
						0 => 'search',
					),
					'taxonomy'          => '',
					'allow_null'        => 0,
					'multiple'          => 0,
					'return_format'     => 'object',
					'ui'                => 1,
				),
				static::FIELD_FORM                 => array(
					'key'               => $form,
					'name'              => static::FIELD_FORM,
					'label'             => 'Form',
					'type'              => 'post_object',
					'required'          => 1,
					'conditional_logic' => array(
						array(
							array(
								'field'    => $action,
								'operator' => '==',
								'value'    => 'form',
							),
						),
					),
					'post_type'         => array( CPT\Form::POST_TYPE ),
					'filters'           => array(
						0 => 'search',
					),
					'taxonomy'          => '',
					'allow_null'        => 0,
					'multiple'          => 0,
					'return_format'     => 'object',
					'ui'                => 1,
				),
				static::FIELD_LATEST_RESEARCH      => array(
					'key'               => $latest_research,
					'name'              => static::FIELD_LATEST_RESEARCH,
					'label'             => 'Latest Research Report',
					'type'              => 'select',
					'choices'           => array( /* @see load_field_latest_research() */ ),
					'ui'                => true,
					'conditional_logic' => array(
						array(
							array(
								'field'    => $action,
								'operator' => '==',
								'value'    => 'latest_research',
							),
						),
					),
				),
				static::FIELD_DONATE_DEDICATION    => array(
					'key'               => $donate_dedication,
					'name'              => static::FIELD_DONATE_DEDICATION,
					'label'             => 'Dedication Donation',
					'type'              => 'true_false',
					'required'          => 0,
					'default_value'     => 0,
					'ui'                => 1,
					'ui_on_text'        => '',
					'ui_off_text'       => '',
					'conditional_logic' => array(
						array(
							array(
								'field'    => $modal,
								'operator' => '==',
								'value'    => 'donate',
							),
						),
					),
				),
				static::FIELD_SHOW_DEV_FORM_ONLY   => array(
					'key'               => $show_dev_form_only,
					'name'              => static::FIELD_SHOW_DEV_FORM_ONLY,
					'label'             => 'Show Dev Form Only',
					'type'              => 'true_false',
					'required'          => 0,
					'default_value'     => false,
					'ui'                => 1,
					'ui_on_text'        => '',
					'ui_off_text'       => '',
					'conditional_logic' => array(
						array(
							array(
								'field'    => $modal,
								'operator' => '==',
								'value'    => 'fundraise_quiz',
							),
						),
					),
				),
			)
		);
	}

	/** @inheritdoc */
	public static function render_link( $options = array(), $post, $data ) {
		$val     = new Field_Val_Getter( static::class, $post, $data );
		$options = array_merge(
			array(
				'type'             => 'link',
				'tag'              => 'a',
				'class'            => array(),
				'attributes'       => array(
					DOM_Attr::FIELD_ATTRIBUTES => array(),
				),
				'label_class'      => array(),
				'label_attributes' => array(
					DOM_Attr::FIELD_ATTRIBUTES => array(),
				),
			),
			$options,
		);

		$has_url = true;

		$label = $val->get( static::FIELD_LABEL );

		# Links
		switch ( $val->get( static::FIELD_ACTION ) ) {
			case 'link':
				$link = $val->get( static::FIELD_LINK );
				if ( $link && is_array( $link ) ) {
					foreach (
							array_filter(
								array(
									'title'  => $link['title'] ?? null,
									'href'   => $link['url'] ?? null,
									'target' => $link['target'] ?? null,
								)
							) as $k => $v
					) {
						$options['attributes'][ DOM_Attr::FIELD_ATTRIBUTES ][] = array(
							DOM_Attr::FIELD_ATTR_KEY => $k,
							DOM_Attr::FIELD_ATTR_VAL => $v,
						);
					}
				}

				if ( empty( $link['url'] ) ) {
					$has_url = false;
				}
				break;
			case 'page_link':
				$page_link = $val->get( static::FIELD_PAGE_LINK );
				if ( $page_link ) {
					$options['attributes'][ DOM_Attr::FIELD_ATTRIBUTES ][] = array(
						DOM_Attr::FIELD_ATTR_KEY => 'href',
						DOM_Attr::FIELD_ATTR_VAL => $page_link,
					);
				} else {
					$has_url = false;
				}
				break;
			case 'file_download':
				$file = $val->get( static::FIELD_FILE );
				if ( ! empty( $file['url'] ) ) {
					$options['attributes'][ DOM_Attr::FIELD_ATTRIBUTES ] = array_merge(
						$options['attributes'][ DOM_Attr::FIELD_ATTRIBUTES ],
						array(
							array(
								DOM_Attr::FIELD_ATTR_KEY => 'href',
								DOM_Attr::FIELD_ATTR_VAL => $file['url'],
							),
							array(
								DOM_Attr::FIELD_ATTR_KEY => 'download',
								DOM_Attr::FIELD_ATTR_VAL => $file['filename'],
							),
							array(
								DOM_Attr::FIELD_ATTR_KEY => 'download',
								DOM_Attr::FIELD_ATTR_VAL => $file['filename'],
							),
							array(
								DOM_Attr::FIELD_ATTR_KEY => 'target',
								DOM_Attr::FIELD_ATTR_VAL => '_blank',
							),
						)
					);
				} else {
					$has_url = false;
				}
				$options['show_download_icon'] = $val->get( static::FIELD_SHOW_DOWNLOAD_ICON );
				break;
			case 'call':
				$phone = $val->get( static::FIELD_PHONE );
				$options['attributes'][ DOM_Attr::FIELD_ATTRIBUTES ] = array_merge(
					$options['attributes'][ DOM_Attr::FIELD_ATTRIBUTES ],
					array(
						array(
							DOM_Attr::FIELD_ATTR_KEY => 'href',
							DOM_Attr::FIELD_ATTR_VAL => 'tel://' . $phone,
						),
					)
				);
				break;
			case 'sms':
				$phone = $val->get( static::FIELD_PHONE );
				$options['attributes'][ DOM_Attr::FIELD_ATTRIBUTES ] = array_merge(
					$options['attributes'][ DOM_Attr::FIELD_ATTRIBUTES ],
					array(
						array(
							DOM_Attr::FIELD_ATTR_KEY => 'href',
							DOM_Attr::FIELD_ATTR_VAL => 'sms://' . $phone,
						),
					)
				);
				break;
			case 'email':
				$email = $val->get( static::FIELD_EMAIL );
				$options['attributes'][ DOM_Attr::FIELD_ATTRIBUTES ] = array_merge(
					$options['attributes'][ DOM_Attr::FIELD_ATTRIBUTES ],
					array(
						array(
							DOM_Attr::FIELD_ATTR_KEY => 'href',
							DOM_Attr::FIELD_ATTR_VAL => 'mailto:' . $email,
						),
					)
				);
				break;
			case 'modal':
				$modal_type = $val->get( static::FIELD_MODAL );

				// DONATE MODAL
				if ( 'donate' === $modal_type ) {
					$dedication_donation                                   = $val->get( static::FIELD_DONATE_DEDICATION );
					$options['tag']                                        = 'button';
					$options['class'][]                                    = DonationModal::ID;
					$options['attributes'][ DOM_Attr::FIELD_ATTRIBUTES ][] = array(
						DOM_Attr::FIELD_ATTR_KEY => 'aria-label',
						DOM_Attr::FIELD_ATTR_VAL => 'click to open donate form modal',
					);

					// Determines whether to show or hide dedication field
					if ( ! $dedication_donation ) {
						$options['attributes'][ DOM_Attr::FIELD_ATTRIBUTES ][] = array(
							DOM_Attr::FIELD_ATTR_KEY => 'data-hide-dedication',
							DOM_Attr::FIELD_ATTR_VAL => true,
						);
					}

					DonationModal::create(
						array(
							'dedication' => true,
						),
					);

					// FUNDRAISE MODAL
				} elseif ( 'fundraise_quiz' === $modal_type ) {
					$show_dev_form_only                                    = $val->get( static::FIELD_SHOW_DEV_FORM_ONLY );
					$options['tag']                                        = 'button';
					$options['class'][]                                    = FundraiserQuizModal::ID;
					$options['attributes'][ DOM_Attr::FIELD_ATTRIBUTES ][] = array(
						DOM_Attr::FIELD_ATTR_KEY => 'aria-label',
						DOM_Attr::FIELD_ATTR_VAL => 'click to open fundraise quiz modal',
					);

					// Add attributes to trigger Fundraise Quiz Modal to show Dev Form Only
					if ( $show_dev_form_only ) {
						$options['attributes'][ DOM_Attr::FIELD_ATTRIBUTES ][] = array(
							DOM_Attr::FIELD_ATTR_KEY => 'data-fundraise-vertex',
							DOM_Attr::FIELD_ATTR_VAL => 'form',
						);
						$options['attributes'][ DOM_Attr::FIELD_ATTRIBUTES ][] = array(
							DOM_Attr::FIELD_ATTR_KEY => 'data-fundraise-single',
							DOM_Attr::FIELD_ATTR_VAL => true,
						);
					}

					FundraiserQuizModal::create();

					// TEXT ONLY POPUP
				} elseif ( 'text_only_popup' === $modal_type ) {
					$text_only_popup    = $val->get( static::FIELD_TEXTONLY_POPUP );
					$modal_id           = Text_Only_Popup::gen_modal_id( $text_only_popup->ID );
					$options['class'][] = $modal_id;
					$options['tag']     = 'button';
					Text_Only_Popup::render_modal_for( $text_only_popup );

					// WHAT TO EXPECT POPUP
				} elseif ( 'what_to_expect' === $modal_type ) {
					$what_to_expect_popup                                  = $val->get( static::FIELD_WHAT_TO_EXPECT_POPUP );
					$options['tag']                                        = 'button';
					$options['class'][]                                    = ! empty( $what_to_expect_popup->ID ) ? What_To_Expect_Popup::gen_modal_id( $what_to_expect_popup->ID ) : WhatToExpectModal::ID;
					$options['attributes'][ DOM_Attr::FIELD_ATTRIBUTES ][] = array(
						DOM_Attr::FIELD_ATTR_KEY => 'aria-label',
						DOM_Attr::FIELD_ATTR_VAL => 'click to open what to expect modal',
					);
					WhatToExpectModal::create( $what_to_expect_popup );

					// Breathing Exercise
				} elseif ( 'breathing_exercise' === $modal_type ) {
					$options['tag']                                        = 'button';
					$options['class'][]                                    = Breathing_Exercise::TRIGGER_ELEMENT_CLASS;
					$options['attributes'][ DOM_Attr::FIELD_ATTRIBUTES ][] = array(
						DOM_Attr::FIELD_ATTR_KEY => 'aria-label',
						DOM_Attr::FIELD_ATTR_VAL => 'click to open breathing exercise modal',
					);
					Breathing_Exercise::render_overlay();
				}
				break;
			case 'trevor_chat':
				$options['class'][]                                    = 'tcb-link';
				$options['attributes'][ DOM_Attr::FIELD_ATTRIBUTES ][] = array(
					DOM_Attr::FIELD_ATTR_KEY => 'aria-label',
					DOM_Attr::FIELD_ATTR_VAL => 'click to open chat window',
				);
				break;
			case 'form':
				$form               = $val->get( static::FIELD_FORM );
				$options['tag']     = 'button';
				$options['class'][] = Form::gen_modal_id( $form->ID );
				$options['attributes'][ DOM_Attr::FIELD_ATTRIBUTES ][] = array(
					DOM_Attr::FIELD_ATTR_KEY => 'aria-label',
					DOM_Attr::FIELD_ATTR_VAL => 'click to open Form',
				);

				Form::create_modal( $form );
				break;
			case 'latest_research':
				$research = $val->get( static::FIELD_LATEST_RESEARCH );
				if ( ! empty( $research ) ) {
					$options['attributes'][ DOM_Attr::FIELD_ATTRIBUTES ][] = array(
						DOM_Attr::FIELD_ATTR_KEY => 'href',
						DOM_Attr::FIELD_ATTR_VAL => get_permalink( $research ),
					);
				} else {
					$has_url = false;
				}
				break;
		}

		ob_start();
		?>
		<?php if ( ! empty( $label ) && $has_url ) : ?>
			<<?php echo $options['tag']; ?>
				<?php echo DOM_Attr::render_attrs_of( $options['attributes'], $options['class'] ); ?>
			>
				<span <?php echo DOM_Attr::render_attrs_of( $options['label_attributes'], $options['label_class'] ); ?>>
					<?php echo esc_html( $label ); ?>
					<?php if ( $options['show_download_icon'] ) : ?>
						<i class="trevor-ti-download" aria-hidden="true"></i>
					<?php endif; ?>
				</span>
			</<?php echo $options['tag']; ?>>
		<?php endif; ?>
		<?php
		return ob_get_clean();
	}

	/** @inheritdoc */
	public static function render( $post = false, array $data = null, array $options = array() ): ?string {
		// Ensure attributes matches the DOM_Attr attributes format
		if ( array_key_exists( 'attributes', $options ) ) {
			$options['attributes'] = DOM_Attr::attrs_from_array( $options['attributes'] );
		}
		if ( array_key_exists( 'label_attributes', $options ) ) {
			$options['label_attributes'] = DOM_Attr::attrs_from_array( $options['label_attributes'] );
		}

		return static::render_link( $options, $post, $data );
	}

	/**
	 * @param $field
	 *
	 * @return mixed
	 */
	public static function load_field_latest_research( $field ) {
		if ( $field && ! empty( $field['key'] ) && static::gen_field_key( static::FIELD_LATEST_RESEARCH ) === $field['key'] ) {
			$choices = array();

			$posts = get_posts(
				array(
					'post_type'     => Research::POST_TYPE,
					'post_per_page' => -1,
					'post_status'   => 'publish',
					'orderby'       => 'post_date',
					'order'         => 'DESC',
				)
			);

			foreach ( $posts as $post ) {
				$choices[ $post->ID ] = $post->post_title . ' — ' . date( 'F j, Y', strtotime( $post->post_date ) );
			}

			$field['choices'] = $choices;
		}

		return $field;
	}
}
