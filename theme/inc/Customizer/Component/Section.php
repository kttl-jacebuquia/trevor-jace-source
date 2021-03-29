<?php namespace TrevorWP\Theme\Customizer\Component;

use TrevorWP\Theme\Customizer\Control;

/**
 * Section Component
 */
class Section extends Abstract_Component {
	const TYPE_VERTICAL = 'vertical';
	const TYPE_HORIZONTAL = 'horizontal';

	const SETTING_TITLE = 'title';
	const SETTING_DESC = 'desc';
	const SETTING_BUTTONS = 'buttons';

	/** @inheritDoc */
	public function register_section( array $args = [] ): void {
		$this->get_manager()->add_section(
				$this->get_section_id(),
				array_merge( [
						'panel' => $this->get_panel_id(),
						'title' => '', // required
				], $args )
		);
	}

	/** @inheritDoc */
	function render( array $ext_options = [] ): string {
		$options = array_merge( [
				'cls'          => [],
				'title_cls'    => [],
				'desc_cls'     => [],
				'content_cls'  => [],
				'btn_cls'      => [],
				'btn_wrap_cls' => [],
				'type'         => static::TYPE_VERTICAL,
				'content'      => '', // required
		], $this->_options, $ext_options );

		$cls = [
				'grid grid-cols-1'
		];

		if ( $options['type'] == self::TYPE_HORIZONTAL ) {
			$cls[] = 'xl:grid-cols-2';
		}

		$cls         = array_merge( $cls, $options['cls'] );
		$content_cls = array_merge( [ 'content-wrap' ], $options['content_cls'] );

		ob_start(); ?>
		<div class="<?= esc_attr( implode( ' ', $cls ) ) ?>">
			<?php if ( $options['type'] == self::TYPE_VERTICAL ): ?>
				<?= $this->_render_top( $options ) ?>
				<div class="<?= esc_attr( implode( ' ', $content_cls ) ) ?>">
					<?= $options['content'] ?>
				</div>
				<?= $this->_render_buttons( $options ) ?>
			<?php elseif ( $options['type'] == self::TYPE_HORIZONTAL ): ?>
				<div>
					<?= $this->_render_top( $options ) ?>
					<?= $this->_render_buttons( $options ) ?>
				</div>
				<div class="<?= esc_attr( implode( ' ', $content_cls ) ) ?>">
					<?= $options['content'] ?>
				</div>
			<?php endif; ?>
		</div>
		<?php return ob_get_clean();
	}

	/**
	 * @param $options
	 *
	 * @return string
	 */
	protected function _render_top( array $options ): string {
		$title_cls = array_merge( [ 'page-sub-title' ], $options['title_cls'] );
		$desc_cls  = array_merge( [ 'page-sub-title-desc' ], $options['desc_cls'] );

		ob_start(); ?>
		<h2 class="<?= esc_attr( implode( ' ', $title_cls ) ) ?>"><?= $this->get_val( $this::SETTING_TITLE ) ?></h2>
		<?php if ( ! empty( $desc = $this->get_val( $this::SETTING_DESC ) ) ) { ?>
			<p class="<?= esc_attr( implode( ' ', $desc_cls ) ) ?>"><?= esc_html( $desc ) ?></p>
		<?php } ?>
		<?php return ob_get_clean();
	}

	/**
	 * @param array $options
	 *
	 * @return string|null
	 */
	protected function _render_buttons( array $options ): ?string {
		if ( empty( $buttons = $this->get_val( $this::SETTING_BUTTONS ) ) ) {
			return null;
		}

		$wrap_cls = array_merge( [ 'page-btn-wrap' ], $options['btn_wrap_cls'] );
		$btn_cls  = array_merge( [ 'page-btn' ], $options['btn_cls'] );

		ob_start(); ?>
		<div class="<?= esc_attr( implode( ' ', $wrap_cls ) ) ?>">
			<?php foreach ( $this->get_val( $this::SETTING_BUTTONS ) as $btn ) { ?>
				<a class="<?= esc_attr( implode( ' ', $btn_cls ) ) ?>"
				   href="<?= esc_url( empty( $btn['href'] ) ? @$options['btn_href'] : $btn['href'] ) ?>"><?= esc_html( @$btn['label'] ) ?></a>
			<?php } ?>
		</div>
		<?php return ob_get_clean();
	}

	/** @inheritDoc */
	public function register_controls(): void {
		$manager = $this->get_manager();
		$sec_id  = $this->get_section_id();

		# Title
		$manager->add_control(
				$setting_title = $this->get_setting_id( self::SETTING_TITLE ),
				[
						'setting' => $setting_title,
						'section' => $sec_id,
						'label'   => 'Title',
						'type'    => 'text',
				]
		);

		# Desc
		$manager->add_control(
				$setting_desc = $this->get_setting_id( self::SETTING_DESC ),
				[
						'setting' => $setting_desc,
						'section' => $sec_id,
						'label'   => 'Description',
						'type'    => 'text',
				]
		);

		# Buttons
		$manager->add_control( new Control\Custom_List( $manager, $buttons_data = $this->get_setting_id( static::SETTING_BUTTONS ), [
				'setting' => $buttons_data,
				'section' => $sec_id,
				'label'   => 'Buttons',
				'fields'  => [
						'label' => [
								'type'       => Control\Custom_List::FIELD_TYPE_INPUT,
								'input_type' => 'text',
								'label'      => 'Label',
						],
						'href'  => [
								'type'       => Control\Custom_List::FIELD_TYPE_INPUT,
								'input_type' => 'text',
								'label'      => 'URL',
						]
				],
		] ) );
	}
}
