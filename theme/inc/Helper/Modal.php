<?php namespace TrevorWP\Theme\Helper;


class Modal {
	protected $_id;
	protected $_content;
	protected $_options = [];

	public function __construct( string $content, array $options = [] ) {
		# Id?
		$this->_id = empty( $this->_options['id'] ) ? uniqid( 'modal' ) : $this->_options['id'];
		unset( $this->_options['id'] );

		$this->_content = $content;
		$this->_options = array_merge( [

		], $options );
	}

	public function render(): string {
		add_action( 'wp_footer', [ $this, 'print_js' ] );
		ob_start();
		?>
		<div class="modal" id="<?= esc_attr( $this->_id ) ?>">
			<div class="modal-container">
				<div class="modal-close cursor-pointer z-50">
					<i class="trevor-ti-close"></i>
				</div>
				<div class="modal-content-wrap">
					<?= $this->_content ?>
				</div>
			</div>
		</div>
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
				trevorWP.features.modal($('#<?= esc_js( $this->_id ) ?>'), <?=json_encode( $options )?>, $('<?=$target?>'));
			});
		</script>
		<?php
	}
}
