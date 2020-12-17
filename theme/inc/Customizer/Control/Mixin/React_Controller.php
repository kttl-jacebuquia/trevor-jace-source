<?php namespace TrevorWP\Theme\Customizer\Control\Mixin;

/**
 * React Supported Controller
 */
trait React_Controller {
	/** @inheritDoc */
	public function render_content() {
		if ( ! empty( $this->label ) ) : ?>
			<label class="customize-control-title"><?php echo esc_html( $this->label ); ?></label>
		<?php endif; ?>
		<?php if ( ! empty( $this->description ) ) : ?>
			<span class="description customize-control-description"><?php echo $this->description; ?></span>
		<?php endif; ?>
		<div class="root-wrapper"></div>
		<?php
	}
}
