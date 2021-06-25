<?php namespace TrevorWP\Theme\ACF\Field_Group;

class Crisis_Support extends A_Field_Group implements I_Block, I_Renderable {
	const FIELD_TITLE                               = 'title';
	const FIELD_DESCRIPTION                         = 'description';
	const FIELD_CARD_ENTRIES                        = 'card_entries';
	const FIELD_CARD_ENTRY_HEADER                   = 'card_entry_header';
	const FIELD_CARD_ENTRY_DESCRIPTION              = 'card_entry_description';
	const FIELD_CARD_ENTRY_BULLET_TYPE              = 'card_entry_bullet_type';
	const FIELD_CARD_ENTRY_BULLET_ENTRIES           = 'card_entry_bullet_entries';
	const FIELD_CARD_ENTRY_BULLET_ENTRY_BULLET_TEXT = 'card_entry_bullet_entry_bullet_text';
	const FIELD_CARD_ENTRY_INFO_TYPE                = 'card_entry_info_type';
	const FIELD_CARD_ENTRY_INFO_LINK                = 'card_entry_info_link';
	const FIELD_CARD_ENTRY_INFO_TEXT                = 'card_entry_info_text';
	const FIELD_BUTTON                              = 'button';

	/**
	 * @inheritDoc
	 */
	protected static function prepare_register_args(): array {
		$title                               = static::gen_field_key( static::FIELD_TITLE );
		$description                         = static::gen_field_key( static::FIELD_DESCRIPTION );
		$card_entries                        = static::gen_field_key( static::FIELD_CARD_ENTRIES );
		$card_entry_header                   = static::gen_field_key( static::FIELD_CARD_ENTRY_HEADER );
		$card_entry_description              = static::gen_field_key( static::FIELD_CARD_ENTRY_DESCRIPTION );
		$card_entry_bullet_type              = static::gen_field_key( static::FIELD_CARD_ENTRY_BULLET_TYPE );
		$card_entry_bullet_entries           = static::gen_field_key( static::FIELD_CARD_ENTRY_BULLET_ENTRIES );
		$card_entry_bullet_entry_bullet_text = static::gen_field_key( static::FIELD_CARD_ENTRY_BULLET_ENTRY_BULLET_TEXT );
		$card_entry_info_type                = static::gen_field_key( static::FIELD_CARD_ENTRY_INFO_TYPE );
		$card_entry_info_link                = static::gen_field_key( static::FIELD_CARD_ENTRY_INFO_LINK );
		$card_entry_info_text                = static::gen_field_key( static::FIELD_CARD_ENTRY_INFO_TEXT );
		$button                              = static::gen_field_key( static::FIELD_BUTTON );

		return array(
			'title'  => 'Crisis Support',
			'fields' => array(
				static::FIELD_TITLE        => array(
					'key'      => $title,
					'name'     => static::FIELD_TITLE,
					'label'    => 'Title',
					'type'     => 'text',
					'required' => 1,
				),
				static::FIELD_DESCRIPTION  => array(
					'key'   => $description,
					'name'  => static::FIELD_DESCRIPTION,
					'label' => 'Description',
					'type'  => 'textarea',
				),
				static::FIELD_CARD_ENTRIES => array(
					'key'          => $card_entries,
					'name'         => static::FIELD_CARD_ENTRIES,
					'label'        => 'Card Entries',
					'type'         => 'repeater',
					'layout'       => 'block',
					'max'          => 2,
					'collapsed'    => $card_entry_header,
					'button_label' => 'Add Card',
					'sub_fields'   => array(
						static::FIELD_CARD_ENTRY_HEADER    => array(
							'key'      => $card_entry_header,
							'name'     => static::FIELD_CARD_ENTRY_HEADER,
							'label'    => 'Header',
							'type'     => 'text',
							'required' => 1,
						),
						static::FIELD_CARD_ENTRY_DESCRIPTION => array(
							'key'   => $card_entry_description,
							'name'  => static::FIELD_CARD_ENTRY_DESCRIPTION,
							'label' => 'Description',
							'type'  => 'textarea',
						),
						static::FIELD_CARD_ENTRY_BULLET_TYPE => array(
							'key'           => $card_entry_bullet_type,
							'name'          => static::FIELD_CARD_ENTRY_BULLET_TYPE,
							'label'         => 'Bullet Type',
							'type'          => 'button_group',
							'required'      => 1,
							'choices'       => array(
								'checkmark' => 'Checkmark',
								'number'    => 'Number',
							),
							'default_value' => 'checkmark',
						),
						static::FIELD_CARD_ENTRY_BULLET_ENTRIES => array(
							'key'          => $card_entry_bullet_entries,
							'name'         => static::FIELD_CARD_ENTRY_BULLET_ENTRIES,
							'label'        => 'Bullet Entries',
							'type'         => 'repeater',
							'layout'       => 'block',
							'max'          => 4,
							'collapsed'    => $card_entry_bullet_entry_bullet_text,
							'button_label' => 'Add Bullet',
							'sub_fields'   => array(
								static::FIELD_CARD_ENTRY_BULLET_ENTRY_BULLET_TEXT             => array(
									'key'      => $card_entry_bullet_entry_bullet_text,
									'name'     => static::FIELD_CARD_ENTRY_BULLET_ENTRY_BULLET_TEXT,
									'label'    => 'Text',
									'type'     => 'text',
									'required' => 1,
								),
							),
						),
						static::FIELD_CARD_ENTRY_INFO_TYPE => array(
							'key'           => $card_entry_info_type,
							'name'          => static::FIELD_CARD_ENTRY_INFO_TYPE,
							'label'         => 'Info Type',
							'type'          => 'button_group',
							'required'      => 1,
							'choices'       => array(
								'link' => 'Link',
								'text' => 'text',
							),
							'default_value' => 'link',
						),
						static::FIELD_CARD_ENTRY_INFO_LINK => array(
							'key'               => $card_entry_info_link,
							'name'              => static::FIELD_CARD_ENTRY_INFO_LINK,
							'label'             => 'Link',
							'type'              => 'link',
							'return_format'     => 'array',
							'conditional_logic' => array(
								array(
									array(
										'field'    => $card_entry_info_type,
										'operator' => '==',
										'value'    => 'link',
									),
								),
							),
						),
						static::FIELD_CARD_ENTRY_INFO_TEXT => array(
							'key'               => $card_entry_info_text,
							'name'              => static::FIELD_CARD_ENTRY_INFO_TEXT,
							'label'             => 'Text',
							'type'              => 'text',
							'required'          => 1,
							'conditional_logic' => array(
								array(
									array(
										'field'    => $card_entry_info_type,
										'operator' => '==',
										'value'    => 'text',
									),
								),
							),
						),
					),
				),
				static::FIELD_BUTTON       => array(
					'key'           => $button,
					'name'          => static::FIELD_BUTTON,
					'label'         => 'Button',
					'type'          => 'link',
					'return_format' => 'array',
				),
			),
		);
	}

	/**
	 * @inheritDoc
	 */
	public static function get_block_args(): array {
		return array_merge(
			parent::get_block_args(),
			array(
				'name'       => static::get_key(),
				'title'      => 'Crisis Support',
				'post_types' => array( 'page' ),
			)
		);
	}

	/**
	 * @inheritDoc
	 */
	public static function render( $post = false, array $data = null, array $options = array() ): ?string {
		$title        = static::get_val( static::FIELD_TITLE );
		$description  = static::get_val( static::FIELD_DESCRIPTION );
		$card_entries = static::get_val( static::FIELD_CARD_ENTRIES );
		$button       = static::get_val( static::FIELD_BUTTON );

		ob_start();
		// Next Step - FE
		?>
		<div class="container mx-auto">
			<?php if ( ! empty( $title ) ) : ?>
				<h3><?php echo esc_html( $title ); ?></h3>
			<?php endif; ?>

			<?php if ( ! empty( $description ) ) : ?>
				<p><?php echo esc_html( $description ); ?></p>
			<?php endif; ?>

			<div>
				<?php if ( ! empty( $card_entries ) ) : ?>
					<div>
						<?php foreach ( $card_entries as $entry ) : ?>
							<div>
								<?php if ( ! empty( $entry[ static::FIELD_CARD_ENTRY_HEADER ] ) ) : ?>
									<h2><?php echo esc_html( $entry[ static::FIELD_CARD_ENTRY_HEADER ] ); ?></h2>
								<?php endif; ?>

								<?php if ( ! empty( $entry[ static::FIELD_CARD_ENTRY_DESCRIPTION ] ) ) : ?>
									<p><?php echo esc_html( $entry[ static::FIELD_CARD_ENTRY_DESCRIPTION ] ); ?></p>
								<?php endif; ?>

								<?php if ( 'checkmark' === $entry[ static::FIELD_CARD_ENTRY_BULLET_TYPE ] ) : ?>
									<ul>
										<?php if ( ! empty( $entry[ static::FIELD_CARD_ENTRY_BULLET_ENTRIES ] ) ) : ?>
											<?php foreach ( $entry[ static::FIELD_CARD_ENTRY_BULLET_ENTRIES ] as $bullet ) : ?>
												<?php if ( ! empty( $bullet[ static::FIELD_CARD_ENTRY_BULLET_ENTRY_BULLET_TEXT ] ) ) : ?>
													<li><?php echo esc_html( $bullet[ static::FIELD_CARD_ENTRY_BULLET_ENTRY_BULLET_TEXT ] ); ?></li>
												<?php endif; ?>
											<?php endforeach; ?>
										<?php endif; ?>
									</ul>
								<?php endif ?>

								<?php if ( 'number' === $entry[ static::FIELD_CARD_ENTRY_BULLET_TYPE ] ) : ?>
									<ol>
										<?php if ( ! empty( $entry[ static::FIELD_CARD_ENTRY_BULLET_ENTRIES ] ) ) : ?>
											<?php foreach ( $entry[ static::FIELD_CARD_ENTRY_BULLET_ENTRIES ] as $bullet ) : ?>
												<?php if ( ! empty( $bullet[ static::FIELD_CARD_ENTRY_BULLET_ENTRY_BULLET_TEXT ] ) ) : ?>
													<li><?php echo esc_html( $bullet[ static::FIELD_CARD_ENTRY_BULLET_ENTRY_BULLET_TEXT ] ); ?></li>
												<?php endif; ?>
											<?php endforeach; ?>
										<?php endif; ?>
									</ol>
								<?php endif ?>

								<?php if ( 'link' === $entry[ static::FIELD_CARD_ENTRY_INFO_TYPE ] ) : ?>
									<?php if ( ! empty( $entry[ static::FIELD_CARD_ENTRY_INFO_LINK ]['url'] ) ) : ?>
										<a href="<?php echo esc_url( $entry[ static::FIELD_CARD_ENTRY_INFO_LINK ]['url'] ); ?>" target="<?php echo esc_attr( $entry[ static::FIELD_CARD_ENTRY_INFO_LINK ]['target'] ); ?>"><?php echo esc_html( $entry[ static::FIELD_CARD_ENTRY_INFO_LINK ]['title'] ); ?></a>
									<?php endif; ?>
								<?php endif; ?>

								<?php if ( 'text' === $entry[ static::FIELD_CARD_ENTRY_INFO_TYPE ] ) : ?>
									<?php if ( ! empty( $entry[ static::FIELD_CARD_ENTRY_INFO_TEXT ] ) ) : ?>
										<p><?php echo esc_html( $entry[ static::FIELD_CARD_ENTRY_INFO_TEXT ] ); ?></p>
									<?php endif; ?>
								<?php endif; ?>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>

			<?php if ( ! empty( $button['url'] ) ) : ?>
				<a href="<?php echo esc_url( $button['url'] ); ?>" target="<?php echo esc_attr( $button['target'] ); ?>"><?php echo esc_html( $button['title'] ); ?></a>
			<?php endif; ?>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * @inheritDoc
	 */
	public static function render_block( $block, $content = '', $is_preview = false, $post_id = 0 ): void {
		echo static::render( $post_id, null, compact( 'is_preview' ) );
	}
}
