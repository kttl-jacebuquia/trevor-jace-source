<?php namespace TrevorWP\Theme\ACF\Field_Group;

use TrevorWP\Theme\ACF\Options_Page\Footer;
use TrevorWP\Theme\ACF\Util\Field_Val_Getter;

class Events_Grid extends A_Field_Group implements I_Block, I_Renderable {
	const FIELD_ID            = 'id';
	const FIELD_HEADING       = 'heading';
	const FIELD_EMPTY_MESSAGE = 'empty_message';

	/** @inheritDoc */
	protected static function prepare_register_args(): array {
		$id            = static::gen_field_key( static::FIELD_ID );
		$heading       = static::gen_field_key( static::FIELD_HEADING );
		$empty_message = static::gen_field_key( static::FIELD_EMPTY_MESSAGE );

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
				static::FIELD_EMPTY_MESSAGE => array(
					'key'           => $empty_message,
					'name'          => static::FIELD_EMPTY_MESSAGE,
					'label'         => 'No Events Message',
					'type'          => 'textarea',
					'instructions'  => 'A message to display when there is no event available.',
					'default_value' => 'No Events Available.',
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
		$empty_message = static::get_val( static::FIELD_EMPTY_MESSAGE );

		$social_media_accounts = array(
			array(
				'url'   => Footer::get_option( Footer::FIELD_FACEBOOK_URL ),
				'icon'  => 'trevor-ti-facebook',
				'label' => 'click to go to our facebook page',
			),
			array(
				'url'   => Footer::get_option( Footer::FIELD_TWITTER_URL ),
				'icon'  => 'trevor-ti-twitter',
				'label' => 'click to go to our twitter page',
			),
			array(
				'url'   => Footer::get_option( Footer::FIELD_INSTAGRAM_URL ),
				'icon'  => 'trevor-ti-instagram',
				'label' => 'click to go to our instagram page',
			),
		);

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
