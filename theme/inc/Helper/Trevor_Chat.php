<?php namespace TrevorWP\Theme\Helper;

use TrevorWP\Theme\ACF\Field_Group\A_Field_Group;

/**
 * Leverages Trevor Chat Button Plugin
 */
class Trevor_Chat {

	public function __construct() {}

	/**
	 * Initializes Trevor Chat Button's additional logic,
	 * on top of the trevo-chat-button plugin
	 */
	public static function init() {
		add_shortcode( 'chat-button', array( static::class, 'render_chat_button' ) );
	}

	/**
	 * Renders a link with .tcb-link selector which will be picked up by the trevo-chat-button plugin
	 */
	public static function render_chat_button( array $attrs ): string {
		$label = $attrs['label'] ?? 'Connect With a TrevorChat Counselor';
		$class = array( 'trevor-chat-button tcb-link' );
		$attrs = array(
			'role'       => 'button',
			'aria-label' => 'click to open chat window',
		);

		ob_start();
		?>
			<a <?php echo A_Field_Group::render_attrs( $class, $attrs ); ?>>
				<?php echo esc_html( $label ); ?>
			</a>
		<?php
		return ob_get_clean();
	}
} ?>
