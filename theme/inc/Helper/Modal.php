<?php namespace TrevorWP\Theme\Helper;


class Modal {
	protected $_selector;
	protected $_content;
	protected $_options = [];

	public function __construct( string $content, array $options = [] ) {
		$this->_content = $content;
		$this->_options = array_merge( array_fill_keys( [
			'id',
			'target',
			'class',
		], [] ), $options );

		# Id?
		$this->_selector = empty( $this->_options['id'] ) ? uniqid( 'modal' ) : $this->_options['id'];
		unset( $this->_options['id'] );
	}

	public function render(): string {
		ob_start();
		?>
		<div class="modal <?php echo implode( ' ', $this->_options[ 'class' ] );  ?>" id="<?= esc_attr( $this->_selector ) ?>">
			<div class="modal-container">
				<div class="modal-close cursor-pointer z-50">
					<i class="trevor-ti-close"></i>
				</div>
				<div class="modal-content-wrap">
					<?= $this->_content ?>
				</div>
			</div>
		</div>
		<?php $this->print_js() ?>
		<?php return ob_get_clean();
	}

	/**
	 * @link https://developer.wordpress.org/reference/hooks/wp_footer/
	 */
	public function print_js() {
		$options = (object) [];
		$target  = @ $this->_options['target'] ?? null;
		?>
		<script>
			jQuery(function ($) {
				trevorWP.features.modal($('#<?= esc_js( $this->_selector ) ?>'), <?=json_encode( $options )?>, $('<?=$target?>'));
			});
		</script>
		<?php
	}
}
