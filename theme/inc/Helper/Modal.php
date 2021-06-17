<?php namespace TrevorWP\Theme\Helper;


class Modal {
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
				),
				array()
			),
			$options
		);

		# Id?
		$this->_selector = empty( $this->_options['id'] ) ? uniqid( 'modal' ) : $this->_options['id'];
		unset( $this->_options['id'] );
	}

	public function render(): string {
		ob_start();
		?>
		<div class="modal <?php echo implode( ' ', $this->_options['class'] ); ?>" id="<?php echo esc_attr( $this->_selector ); ?>">
			<div class="modal-container">
				<button class="modal-close js-modal-close cursor-pointer z-50" aria-label="click to close this modal">
					<i class="trevor-ti-close" aria-hidden="true"></i>
				</button>
				<div class="modal-content-wrap">
					<?php echo $this->_content; ?>
				</div>
			</div>
		</div>
		<?php $this->print_js(); ?>
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
}
