<?php namespace TrevorWP\Theme\Customizer\Component;

/**
 * Info Card Component
 */
class Info_Card extends Section {
	/** @inheritDoc */
	public function register_section( array $args = [] ): void {
		parent::register_section( array_merge( [ 'title' => 'Info Card' ], $args ) );
	}

	/** @inheritDoc */
	public function render( array $ext_options = [], $main_class = 'info-card' ): string {
		$cls          = array_merge( [ $main_class ], (array) @$ext_options['cls'] );
		$title_cls    = array_merge( [ "{$main_class}-title" ], (array) @$ext_options['title_cls'] );
		$desc_cls     = array_merge( [ "{$main_class}-desc" ], (array) @$ext_options['desc_cls'] );
		$btn_cls      = array_merge( [ 'btn', "{$main_class}-btn" ], (array) @$ext_options['btn_cls'] );
		$btn_wrap_cls = array_merge( [ "{$main_class}-btn-wrap" ], (array) @$ext_options['btn_wrap_cls'] );

		ob_start(); ?>
		<div class="<?= esc_attr( implode( ' ', $cls ) ) ?>">
			<h2 class="<?= esc_attr( implode( ' ', $title_cls ) ) ?>"><?= $this->get_val( $this::SETTING_TITLE ) ?></h2>
			<?php if ( ! empty( $desc = $this->get_val( $this::SETTING_DESC ) ) ) { ?>
				<p class="<?= esc_attr( implode( ' ', $desc_cls ) ) ?>"><?= esc_html( $desc ) ?></p>
			<?php } ?>

			<?php if ( ! empty( $buttons = $this->get_val( $this::SETTING_BUTTONS ) ) ) { ?>
				<div class="<?= esc_attr( implode( ' ', $btn_wrap_cls ) ) ?>">
					<?php foreach ( $this->get_val( $this::SETTING_BUTTONS ) as $btn ) { ?>
						<a class="<?= esc_attr( implode( ' ', $btn_cls ) ) ?>"
						   href="<?= esc_url( empty( $btn['href'] ) ? @$ext_options['btn_href'] : $btn['href'] ) ?>"><?= esc_html( @$btn['label'] ) ?></a>
					<?php } ?>
				</div>
			<?php } ?>
		</div>
		<?php return ob_get_clean();
	}

	/**
	 * @param array $ext_options
	 *
	 * @return string
	 */
	public function render_as_cta_box( array $ext_options = [] ): string {
		return self::render( $ext_options, 'cta-box' );
	}
}
