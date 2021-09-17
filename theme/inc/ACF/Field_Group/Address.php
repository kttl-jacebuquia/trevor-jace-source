<?php namespace TrevorWP\Theme\ACF\Field_Group;

use TrevorWP\Theme\Helper\FundraiserQuizModal;

class Address extends A_Field_Group implements I_Block {
	const FIELD_TITLE           = 'title';
	const FIELD_ENTRIES         = 'entries';
	const FIELD_ENTRY_TITLE     = 'entry_title';
	const FIELD_ENTRY_LINES     = 'entry_lines';
	const FIELD_ENTRY_LINE_TYPE = 'entry_line_type';
	const FIELD_ENTRY_EMAIL     = 'entry_email';
	const FIELD_ENTRY_PHONE     = 'entry_phone';
	const FIELD_LINE            = 'line';

	protected static function prepare_register_args(): array {
		$title           = static::gen_field_key( static::FIELD_TITLE );
		$entries         = static::gen_field_key( static::FIELD_ENTRIES );
		$entry_title     = static::gen_field_key( static::FIELD_ENTRY_TITLE );
		$entry_lines     = static::gen_field_key( static::FIELD_ENTRY_LINES );
		$entry_line_type = static::gen_field_key( static::FIELD_ENTRY_LINE_TYPE );
		$entry_email     = static::gen_field_key( static::FIELD_ENTRY_EMAIL );
		$entry_phone     = static::gen_field_key( static::FIELD_ENTRY_PHONE );
		$line            = static::gen_field_key( static::FIELD_LINE );

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
								static::FIELD_ENTRY_LINE_TYPE => array(
									'key'           => $entry_line_type,
									'name'          => static::FIELD_ENTRY_LINE_TYPE,
									'label'         => 'Type',
									'type'          => 'select',
									'choices'       => array(
										'text'         => 'Text',
										'call'         => 'Call',
										'mailto'       => 'Mailto',
										'dev_inq_form' => 'Development Inquiry Form',
									),
									'default_value' => 'text',
									'return_format' => 'value',
								),
								static::FIELD_ENTRY_EMAIL => array(
									'key'               => $entry_email,
									'name'              => static::FIELD_ENTRY_EMAIL,
									'label'             => 'Mail to:',
									'type'              => 'email',
									'conditional_logic' => array(
										array(
											'field'    => $entry_line_type,
											'operator' => '==',
											'value'    => 'mailto',
										),
									),
								),
								static::FIELD_ENTRY_PHONE => array(
									'key'               => $entry_phone,
									'name'              => static::FIELD_ENTRY_PHONE,
									'label'             => 'Phone',
									'type'              => 'text',
									'conditional_logic' => array(
										array(
											'field'    => $entry_line_type,
											'operator' => '==',
											'value'    => 'call',
										),
									),
								),
								static::FIELD_LINE        => array(
									'key'       => $line,
									'name'      => static::FIELD_LINE,
									'label'     => 'Line',
									'type'      => 'textarea',
									'new_lines' => 'br',
								),
							),
						),
					),
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
		<div class="contact-information">
			<div class="contact-information__container">
				<h2 class="contact-information__heading">
					<?php echo esc_html( $title ); ?>
				</h2>
				<?php if ( ! empty( $entries ) ) { ?>
					<div class="contact-information__content">
					<?php foreach ( $entries as $entry ) : ?>
						<div class="contact-information__card">
							<?php if ( ! empty( $entry[ static::FIELD_ENTRY_TITLE ] ) ) : ?>
								<h3 class="contact-information__title"><?php echo esc_html( $entry[ static::FIELD_ENTRY_TITLE ] ); ?></h3>
							<?php endif; ?>

							<?php foreach ( $entry['entry_lines'] as $line ) : ?>
								<div class="contact-information__description">
									<?php if ( 'mailto' === $line[ static::FIELD_ENTRY_LINE_TYPE ] ) : ?>
										<a class="wave-underline" href="mailto:<?php echo esc_attr( $line[ static::FIELD_ENTRY_EMAIL ] ); ?>">
											<?php echo $line[ static::FIELD_LINE ]; ?>
										</a>
									<?php elseif ( 'call' === $line[ static::FIELD_ENTRY_LINE_TYPE ] ) : ?>
										<a class="wave-underline" href="tel://<?php echo esc_attr( $line[ static::FIELD_ENTRY_PHONE ] ); ?>">
											<?php echo $line[ static::FIELD_LINE ]; ?>
										</a>
									<?php elseif ( 'dev_inq_form' === $line[ static::FIELD_ENTRY_LINE_TYPE ] ) : ?>
										<?php FundraiserQuizModal::create(); ?>
										<?php $class = FundraiserQuizModal::ID; ?>
										<button class="<?php echo esc_attr( $class ); ?> wave-underline"
										aria-label="click to open fundraise quiz modal"
										data-fundraise-vertex="form"
										data-fundraise-single="1">
											<?php echo $line[ static::FIELD_LINE ]; ?>
										</button>
									<?php else : ?>
										<?php if ( ! empty( $line[ static::FIELD_LINE ] ) ) : ?>
											<?php echo $line[ static::FIELD_LINE ]; ?>
										<?php endif; ?>
									<?php endif; ?>
								</div>
							<?php endforeach; ?>
						</div>
					<?php endforeach; ?>
					</div>
				<?php } ?>
			</div>
		</div>
		<?php
		echo ob_get_clean();
	}
}
