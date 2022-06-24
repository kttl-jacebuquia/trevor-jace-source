<?php namespace TrevorWP\Theme\ACF\Field_Group;

use TrevorWP\Theme\ACF\Options_Page\Footer;
use TrevorWP\Theme\ACF\Util\Field_Val_Getter;

class Events_Grid extends A_Field_Group implements I_Block, I_Renderable {
	const FIELD_ID                    = 'id';
	const FIELD_HEADING               = 'heading';
	const FIELD_EVENT_MESSAGE_TOGGLE  = 'event_message_toggle';
	const FIELD_DEFAULT_MESSAGE       = 'default_message';
	const FIELD_CUSTOM_MESSAGE        = 'custom_message';
	const FIELD_SOCIAL_LINKS          = 'social_links';
	const FIELD_SOCIAL_LINK_TYPE      = 'social_link_type';
	const FIELD_SOCIAL_LINK_URL       = 'social_link_url';

	/** @inheritDoc */
	protected static function prepare_register_args(): array {
		$id                    = static::gen_field_key( static::FIELD_ID );
		$heading               = static::gen_field_key( static::FIELD_HEADING );
		$custom_message_toggle = static::gen_field_key( static::FIELD_EVENT_MESSAGE_TOGGLE );
		$empty_message         = static::gen_field_key( static::FIELD_DEFAULT_MESSAGE );
		$custom_message        = static::gen_field_key( static::FIELD_CUSTOM_MESSAGE );
		$social_links          = static::gen_field_key( static::FIELD_SOCIAL_LINKS );
		$social_link_type      = static::gen_field_key( static::FIELD_SOCIAL_LINK_TYPE );
		$social_link_url       = static::gen_field_key( static::FIELD_SOCIAL_LINK_URL );

		return array(
			'title'  => 'Events Grid',
			'fields' => array(
				static::FIELD_HEADING => array(
					'key'           => $heading,
					'name'          => static::FIELD_HEADING,
					'label'         => 'Heading',
					'type'          => 'text',
					'default_value' => 'Upcoming Events',
				),
				static::FIELD_EVENT_MESSAGE_TOGGLE => array(
					'key'           => $custom_message_toggle,
					'name'          => static::FIELD_EVENT_MESSAGE_TOGGLE,
					'label'         => 'Event Message',
					'type'          => 'button_group',
					'required'      => 1,
					'choices'       => array(
						'default' => 'Default',
						'custom'  => 'Custom',
					),
					'default_value' => 'default',
				),
				static::FIELD_DEFAULT_MESSAGE => array(
					'key'               => $empty_message,
					'name'              => static::FIELD_DEFAULT_MESSAGE,
					'label'             => 'Default Message',
					'type'              => 'textarea',
					'instructions'      => 'A message to display when there is no event available.',
					'default_value'     => 'We are working on how to safely host events during these difficult times. Please follow us on social to stay up to date.',
					'readonly'          => 1,
					'conditional_logic' => array(
						array(
							array(
								'field'    => $custom_message_toggle,
								'operator' => '==',
								'value'    => 'default',
							),
						),
					),
				),
				static::FIELD_CUSTOM_MESSAGE => array(
					'key'               => $custom_message,
					'name'              => static::FIELD_CUSTOM_MESSAGE,
					'label'             => 'Custom Message',
					'type'              => 'textarea',
					'instructions'      => 'A message to display when there is no event available.',
					'default_value'     => 'We are working on how to safely host events during these difficult times. Please follow us on social to stay up to date.',
					'conditional_logic' => array(
						array(
							array(
								'field'    => $custom_message_toggle,
								'operator' => '==',
								'value'    => 'custom',
							),
						),
					),
				),
				static::FIELD_SOCIAL_LINKS => array(
					'key'        => $social_links,
					'name'       => static::FIELD_SOCIAL_LINKS,
					'label'      => 'Social Links',
					'type'       => 'repeater',
					'layout'     => 'block',
					'sub_fields' => array(
						static::FIELD_SOCIAL_LINK_TYPE => array(
							'key'           => $social_link_type,
							'name'          => static::FIELD_SOCIAL_LINK_TYPE,
							'label'         => 'Type',
							'type'          => 'select',
							'required'      => 1,
							'choices'       => array(
								'facebook'  => 'Facebook',
								'twitter'   => 'Twitter',
								'instagram' => 'Instagram',
							),
							'default_value' => 'facebook',
							'return_format' => 'value',
						),
						static::FIELD_SOCIAL_LINK_URL  => array(
							'key'           => $social_link_url,
							'name'          => static::FIELD_SOCIAL_LINK_URL,
							'label'         => 'URL',
							'type'          => 'url',
							'required'      => 1,
						),
					),
				),
				static::FIELD_ID      => array(
					'key'          => $id,
					'name'         => static::FIELD_ID,
					'label'        => 'ID',
					'type'         => 'text',
					'instructions' => 'A unique identifier for this grid (Automatically filled).',
					'placeholder'  => 'events-grid-unique-id',
					'readonly'     => 1,
				),
			),
		);
	}

	/** @inheritDoc */
	public static function get_block_args(): array {
		return array(
			'name'       => static::get_key(),
			'title'      => 'Events Grid',
			'category'   => 'common',
			'icon'       => 'book-alt',
			'post_types' => array( 'page' ),
		);
	}

	/**
	 * @inheritDoc
	 */
	public static function render_block( $block, $content = '', $is_preview = false, $post_id = 0 ): void {
		echo static::render( $post_id, null, compact( 'is_preview' ) );
	}

	/** @inheritDoc */
	public static function render( $post = false, array $data = null, array $options = array() ): ?string {
		$id      = static::get_val( static::FIELD_ID );
		$heading = static::get_val( static::FIELD_HEADING );

		ob_start(); ?>
			<section class="events-grid events-grid--loading" id="<?php echo $id; ?>" aria-label="events grid" tabindex="0">
				<div class="events-grid__container">
					<h2 class="events-grid__heading"><?php echo $heading; ?></h2>
					<p class="events-grid__loading text-center my-px50">Loading...</p>
					<div class="events-grid__filters"></div>
					<div class="events-grid__grid"></div>
					<div class="events-grid__pagination"></div>
					<?php echo static::render_empty_content(); ?>
				</div>
			</section>
		<?php
		return ob_get_clean();
	}

	protected static function render_empty_content() {
		$message_toggle = static::get_val( static::FIELD_EVENT_MESSAGE_TOGGLE );
		$social_links   = static::get_val( static::FIELD_SOCIAL_LINKS );

		$empty_message  = 'We are working on how to safely host events during these difficult times. Please follow us on social to stay up to date.';
		
		if ( 'default' === $message_toggle ) {
			$empty_message = static::get_val( static::FIELD_DEFAULT_MESSAGE );
		} elseif ( 'custom' === $message_toggle ) {
			$empty_message = static::get_val( static::FIELD_CUSTOM_MESSAGE );
		}

		$social_media_accounts = array();

		foreach( $social_links as $link ) {
			$type = $link[ static::FIELD_SOCIAL_LINK_TYPE ] ?? '';

			$social_media_accounts[] = array(
				'url'   => $link[ static::FIELD_SOCIAL_LINK_URL ] ?? '',
				'icon'  => 'trevor-ti-' . $type,
				'label' => 'click to go to our ' . $type . ' page'
			);
		}

		ob_start();
		?>
			<div class="events-grid__empty hidden">
				<p class="events-grid__empty-text">
					<?php echo $empty_message; ?>
				</p>
				<div class="events-grid__social">
					<?php foreach ( $social_media_accounts as $social_link ): ?>
						<a
							class="events-grid__social-link"
							href="<?php echo $social_link['url']; ?>"
							aria-label="<?php echo $social_link['label']; ?>"
							target="_blank">
							<i class="<?php echo $social_link['icon']; ?>" aria-hidden="true"></i>
						</a>
					<?php endforeach; ?>
				</div>
			</div>
		<?php
			return ob_get_clean();
	}

	public static function apply_field_id( $value ) {
		if ( empty( $value ) ) {
			return wp_generate_uuid4();
		}

		return $value;
	}
}
