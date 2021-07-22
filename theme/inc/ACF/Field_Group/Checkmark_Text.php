<?php

namespace TrevorWP\Theme\ACF\Field_Group;

class Checkmark_Text extends A_Field_Group implements I_Block, I_Renderable {

	const FIELD_HEADLINE                            = 'headline';
	const FIELD_DESCRIPTION                         = 'description';
	const FIELD_CARD_BACKGROUND                     = 'card_background';
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
		$headline                            = static::gen_field_key( static::FIELD_HEADLINE );
		$description                         = static::gen_field_key( static::FIELD_DESCRIPTION );
		$card_background                     = static::gen_field_key( static::FIELD_CARD_BACKGROUND );
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
			'title'  => 'Checkmark + Text',
			'fields' => array(
				static::FIELD_HEADLINE        => array(
					'key'   => $headline,
					'name'  => static::FIELD_HEADLINE,
					'label' => 'Headline',
					'type'  => 'text',
				),
				static::FIELD_DESCRIPTION     => array(
					'key'          => $description,
					'name'         => static::FIELD_DESCRIPTION,
					'label'        => 'Description',
					'type'         => 'wysiwyg',
					'toolbar'      => 'basic',
					'media_upload' => 0,
				),
				static::FIELD_CARD_BACKGROUND => array(
					'key'           => $card_background,
					'name'          => static::FIELD_CARD_BACKGROUND,
					'label'         => 'Card Background',
					'type'          => 'button_group',
					'choices'       => array(
						'none'  => 'None',
						'white' => 'White',
					),
					'default_value' => 'none',
				),
				static::FIELD_CARD_ENTRIES    => array(
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
							'key'   => $card_entry_header,
							'name'  => static::FIELD_CARD_ENTRY_HEADER,
							'label' => 'Header',
							'type'  => 'text',
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
							'collapsed'    => $card_entry_bullet_entry_bullet_text,
							'button_label' => 'Add Bullet',
							'sub_fields'   => array(
								static::FIELD_CARD_ENTRY_BULLET_ENTRY_BULLET_TEXT             => array(
									'key'   => $card_entry_bullet_entry_bullet_text,
									'name'  => static::FIELD_CARD_ENTRY_BULLET_ENTRY_BULLET_TEXT,
									'label' => 'Text',
									'type'  => 'text',
								),
							),
						),
						static::FIELD_CARD_ENTRY_INFO_TYPE => array(
							'key'           => $card_entry_info_type,
							'name'          => static::FIELD_CARD_ENTRY_INFO_TYPE,
							'label'         => 'Info Type',
							'type'          => 'button_group',
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
				static::FIELD_BUTTON          => array(
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
				'title'      => 'Checkmark + Text',
				'post_types' => array( 'page' ),
			)
		);
	}

	/**
	 * @inheritDoc
	 */
	public static function render( $post = false, array $data = null, array $options = array() ): ?string {
		$headline        = static::get_val( static::FIELD_HEADLINE );
		$description     = static::get_val( static::FIELD_DESCRIPTION );
		$card_background = static::get_val( static::FIELD_CARD_BACKGROUND );
		$card_entries    = static::get_val( static::FIELD_CARD_ENTRIES );
		$button          = static::get_val( static::FIELD_BUTTON );

		$cards_container_class_name = 'checkmark-text__cards';
		$cards_container_classes[]  = $cards_container_class_name;
		if ( 'none' !== $card_background ) {
			$cards_container_classes[] = "${cards_container_class_name}--${card_background}";
		}

		ob_start();
		?>
		<div class="checkmark-text">
			<div class="checkmark-text__container">
				<h3 class="checkmark-text__headline"><?php echo esc_html( $headline ); ?></h3>

				<?php if ( ! empty( $description ) ) : ?>
				<div class="checkmark-text__description"><?php echo $description; ?></div>
				<?php endif; ?>

				<?php if ( ! empty( $card_entries ) ) : ?>
					<div class="<?php echo esc_attr( implode( ' ', $cards_container_classes ) ); ?>">
						<?php foreach ( $card_entries as $entry ) : ?>
							<div class="checkmark-text__card">
								<?php if ( ! empty( $entry[ static::FIELD_CARD_ENTRY_HEADER ] ) ) : ?>
									<h2 class="checkmark-text__card-header"><?php echo esc_html( $entry[ static::FIELD_CARD_ENTRY_HEADER ] ); ?></h2>
								<?php endif; ?>

								<?php if ( ! empty( $entry[ static::FIELD_CARD_ENTRY_DESCRIPTION ] ) ) : ?>
									<p class="checkmark-text__card-description"><?php echo esc_html( $entry[ static::FIELD_CARD_ENTRY_DESCRIPTION ] ); ?></p>
								<?php endif; ?>

								<?php $list_element = ( 'number' === $entry[ static::FIELD_CARD_ENTRY_BULLET_TYPE ] ) ? 'ol' : 'ul'; ?>
								<<?php echo $list_element; ?> class="checkmark-text__list" role="list">
								<?php if ( ! empty( $entry[ static::FIELD_CARD_ENTRY_BULLET_ENTRIES ] ) ) : ?>
									<?php foreach ( $entry[ static::FIELD_CARD_ENTRY_BULLET_ENTRIES ] as $index => $bullet ) : ?>
										<?php if ( ! empty( $bullet[ static::FIELD_CARD_ENTRY_BULLET_ENTRY_BULLET_TEXT ] ) ) : ?>
											<li class="checkmark-text__item" role="listitem">
												<?php if ( 'number' === $entry[ static::FIELD_CARD_ENTRY_BULLET_TYPE ] ) : ?>
													<span aria-hidden="true" class="checkmark-text__number"><?php echo esc_html( $index + 1 ); ?></span>
												<?php else : ?>
													<span aria-hidden="true" class="trevor-ti-checkmark checkmark-text__icon"></span>
												<?php endif ?>

												<?php if ( ! empty( $bullet[ static::FIELD_CARD_ENTRY_BULLET_ENTRY_BULLET_TEXT ] ) ) : ?>
													<div class="checkmark-text__item-text">
														<?php echo esc_html( $bullet[ static::FIELD_CARD_ENTRY_BULLET_ENTRY_BULLET_TEXT ] ); ?>
													</div>
												<?php endif; ?>
											</li>
										<?php endif; ?>
									<?php endforeach; ?>
								<?php endif; ?>
								</<?php echo $list_element; ?>>

								<div class="checkmark-text__info">
								<?php if ( 'link' === $entry[ static::FIELD_CARD_ENTRY_INFO_TYPE ] ) : ?>
									<?php if ( ! empty( $entry[ static::FIELD_CARD_ENTRY_INFO_LINK ]['url'] ) ) : ?>
										<a class="checkmark-text__info-link" href="<?php echo esc_url( $entry[ static::FIELD_CARD_ENTRY_INFO_LINK ]['url'] ); ?>" target="<?php echo esc_attr( $entry[ static::FIELD_CARD_ENTRY_INFO_LINK ]['target'] ); ?>"><?php echo esc_html( $entry[ static::FIELD_CARD_ENTRY_INFO_LINK ]['title'] ); ?></a>
									<?php endif; ?>
								<?php endif; ?>

								<?php if ( 'text' === $entry[ static::FIELD_CARD_ENTRY_INFO_TYPE ] ) : ?>
									<?php if ( ! empty( $entry[ static::FIELD_CARD_ENTRY_INFO_TEXT ] ) ) : ?>
										<p class="checkmark-text__info-text"><?php echo esc_html( $entry[ static::FIELD_CARD_ENTRY_INFO_TEXT ] ); ?></p>
									<?php endif; ?>
								<?php endif; ?>
								</div>

							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

				<?php if ( ! empty( $checkmark_entries ) ) : ?>
					<ul role="list">
						<?php foreach ( $checkmark_entries as $entry ) : ?>
							<li class="checkmark-text__item" role="listitem">
								<span aria-hidden="true" class="trevor-ti-checkmark checkmark-text__icon"></span>
								<?php if ( ! empty( $entry[ static::FIELD_CHECKMARK_ENTRY_TEXT ] ) ) : ?>
									<div class="checkmark-text__item-text">
										<?php echo esc_html( $entry[ static::FIELD_CHECKMARK_ENTRY_TEXT ] ); ?>
									</div>
								<?php endif; ?>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
				<?php if ( ! empty( $button['url'] ) && ! empty( $button['title'] ) ) : ?>
					<div class="checkmark-text__cta-wrap">
						<a class="checkmark-text__cta" href="<?php echo esc_url( $button['url'] ); ?>" target="<?php echo esc_attr( $button['target'] ); ?>"><?php echo esc_html( $button['title'] ); ?></a>
					</div>
				<?php endif; ?>
			</div>
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
