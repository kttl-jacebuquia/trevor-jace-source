<?php namespace TrevorWP\Theme\ACF\Field_Group;

class Address extends A_Field_Group implements I_Block {
	const FIELD_TITLE       = 'title';
	const FIELD_ENTRIES     = 'entries';
	const FIELD_ENTRY_TITLE = 'entry_title';
	const FIELD_ENTRY_LINES = 'entry_lines';
	const FIELD_LINE        = 'line';
	const FIELD_ATTR        = 'attr';

	protected static function prepare_register_args(): array {
		$title       = static::gen_field_key( static::FIELD_TITLE );
		$entries     = static::gen_field_key( static::FIELD_ENTRIES );
		$entry_title = static::gen_field_key( static::FIELD_ENTRY_TITLE );
		$entry_lines = static::gen_field_key( static::FIELD_ENTRY_LINES );
		$line        = static::gen_field_key( static::FIELD_LINE );
		$attr        = static::gen_field_key( static::FIELD_ATTR );

		return array(
			'title'  => 'Contact Information Block',
			'fields' => array(
				static::FIELD_TITLE   => array(
					'key'   => $title,
					'name'  => static::FIELD_TITLE,
					'label' => 'Title',
					'type'  => 'text',
				),
				static::FIELD_ENTRIES => array(
					'key'        => $entries,
					'name'       => static::FIELD_ENTRIES,
					'label'      => 'Entries',
					'type'       => 'repeater',
					'required'   => true,
					'layout'     => 'block',
					'sub_fields' => array(
						static::FIELD_ENTRY_TITLE => array(
							'key'   => $entry_title,
							'name'  => static::FIELD_ENTRY_TITLE,
							'label' => 'Title',
							'type'  => 'text',
						),
						static::FIELD_ENTRY_LINES => array(
							'key'        => $entry_lines,
							'name'       => static::FIELD_ENTRY_LINES,
							'label'      => 'Line entries',
							'type'       => 'repeater',
							'layout'     => 'block',
							'sub_fields' => array(
								static::FIELD_LINE => array(
									'key'   => $line,
									'name'  => static::FIELD_LINE,
									'label' => 'Line',
									'type'  => 'textarea',
								),
							),
						),
					),
				),
				static::FIELD_ATTR    => DOM_Attr::clone(
					array(
						'key'   => $attr,
						'name'  => static::FIELD_ATTR,
						'label' => '',
					)
				),
			),
		);
	}

	public static function get_block_args(): array {
		return array_merge(
			parent::get_block_args(),
			array(
				'name'       => static::get_key(),
				'title'      => 'Contact Information Block',
				'post_types' => array( 'page' ),
			)
		);
	}

	public static function render_block( $block, $content = '', $is_preview = false, $post_id = 0 ): void {
		$title   = static::get_val( static::FIELD_TITLE ) ?: 'Block Title';
		$entries = static::get_val( static::FIELD_ENTRIES ) ?: array();

		ob_start();
		?>
		<div <?php echo DOM_Attr::render_attrs_of( static::get_val( static::FIELD_ATTR ) ); ?>>
			<h2 class="font-bold mt-px60 md:mt-px80 lg:mt-px90 text-px28 leading-px34 md:text-px32 md:leading-px42 lg:text-px36 lg:leading-px40">
				<?php echo esc_html( @$title ); ?>
			</h2>
			<?php if ( ! empty( $entries ) ) { ?>
				<div class="flex flex-col md:flex-row address-container mt-px20 lg:mt-px30">
					<?php foreach ( $entries as $entry ) { ?>
						<div class="mb-px28 md:mb-0 md:mr-px40 lg:mr-px50 md:mt-px40 lg:mt-0">
							<h3 class="font-bold mb-px6 lg:mb-px12 text-px20 leading-px30 md:leading-px26 lg:text-px24 lg:leading-px30"><?php echo esc_html( @$entry[ static::FIELD_ENTRY_TITLE ] ); ?></h3>
							<?php foreach ( @$entry['entry_lines'] as $line ) { ?>
								<div class="font-normal text-px18 leading-px24 lg:text-px22 lg:leading-px28"><?php echo esc_html( $line[ static::FIELD_LINE ] ); ?></div>
							<?php } ?>
						</div>
					<?php } ?>
				</div>
			<?php } ?>
		</div>
		<?php
		echo ob_get_clean();
	}
}
