<?php namespace TrevorWP\Theme\Helper;

use TrevorWP\Theme\ACF\Field_Group\DOM_Attr;

class Modal {
	const MODAL_SELECTOR_PREFIX    = 'js-modal-';
	public static $rendered_modals = array();

	protected $_selector;
	protected $_content;
	protected $_options = array();

	public function __construct( string $content, array $options = array() ) {
		$this->_content = $content;
		$this->_options = array_merge(
			array_fill_keys(
				array(
					'id',
					'target',
					'class',
					'container_class',
					'title',
				),
				array()
			),
			$options
		);

		# Id?
		$this->_selector = empty( $this->_options['id'] ) ? uniqid( 'modal' ) : $this->_options['id'];
		unset( $this->_options['id'] );
	}

	public function render( $print_js = true ): string {
		// Don't render the same modal id twice
		if ( $this->_selector && in_array( $this->_selector, static::$rendered_modals, true ) ) {
			return '';
		}

		array_push( static::$rendered_modals, $this->_selector );

		// Setup classname
		$class = array_merge(
			$this->_options['class'],
			array( 'modal' ),
		);

		// Setup modal attributes
		$attributes = array(
			'id'   => esc_attr( $this->_selector ),
			'role' => 'dialog',
		);

		if ( ! empty( $this->_options['title'] ) ) {
			$attributes['title'] = $this->_options['title'];
		}

		ob_start();
		?>
		<div <?php echo DOM_Attr::render_attrs( $class, $attributes ); ?>>
			<div class="modal-wrap">
				<div class="modal-container <?php echo implode( ' ', $this->_options['container_class'] ); ?>" tabindex="0">
					<div class="modal-content-wrap">
						<?php echo $this->_content; ?>
					</div>
					<button class="modal-close js-modal-close cursor-pointer z-50" aria-label="click to close this modal">
						<i class="trevor-ti-close" aria-hidden="true"></i>
					</button>
				</div>
			</div>
		</div>
		<?php if ( $print_js ) : ?>
			<?php $this->print_js(); ?>
		<?php endif; ?>
		<?php
		return ob_get_clean();
	}

	/**
	 * @link https://developer.wordpress.org/reference/hooks/wp_footer/
	 */
	public function print_js() {
		$options = (object) array();
		$target  = @ $this->_options['target'] ?? null;
		?>
		<script>
			jQuery(function ($) {
				trevorWP.features.modal($('#<?php echo esc_js( $this->_selector ); ?>'), <?php echo json_encode( $options ); ?>, $('<?php echo $target; ?>'));
			});
		</script>
		<?php
	}

	static public function create_and_render( $content = '', $options = array() ) {
		// Ensure that modals are only rendered down the document
		add_action(
			'wp_footer',
			function() use ( $content, $options ) {
				echo ( new self( $content, $options ) )->render();
			},
			10,
			0
		);
	}

	public static function gen_modal_id( $id ) {
		if ( ! empty( $id ) ) {
			return static::MODAL_SELECTOR_PREFIX . '-' . $id;
		}

		return static::MODAL_SELECTOR_PREFIX;
	}
}
