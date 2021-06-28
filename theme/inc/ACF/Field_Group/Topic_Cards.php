<?php namespace TrevorWP\Theme\ACF\Field_Group;

use TrevorWP\Theme\ACF\Field\Color;

class Topic_Cards extends A_Field_Group implements I_Block, I_Renderable {
	const FIELD_BG_COLOR                = 'bg_color';
	const FIELD_TEXT_COLOR              = 'text_color';
	const FIELD_TITLE                   = 'title';
	const FIELD_DESCRIPTION             = 'description';
	const FIELD_TOPIC_ENTRIES           = 'topic_entries';
	const FIELD_TOPIC_ENTRY_TITLE       = 'topic_entry_title';
	const FIELD_TOPIC_ENTRY_DESCRIPTION = 'topic_entry_description';
	const FIELD_TOPIC_ENTRY_LINK        = 'topic_entry_link';
	const FIELD_BUTTON                  = 'button';

	/**
	 * @inheritDoc
	 */
	protected static function prepare_register_args(): array {
		$bg_color                = static::gen_field_key( static::FIELD_BG_COLOR );
		$text_color              = static::gen_field_key( static::FIELD_TEXT_COLOR );
		$title                   = static::gen_field_key( static::FIELD_TITLE );
		$description             = static::gen_field_key( static::FIELD_DESCRIPTION );
		$topic_entries           = static::gen_field_key( static::FIELD_TOPIC_ENTRIES );
		$topic_entry_title       = static::gen_field_key( static::FIELD_TOPIC_ENTRY_TITLE );
		$topic_entry_description = static::gen_field_key( static::FIELD_TOPIC_ENTRY_DESCRIPTION );
		$topic_entry_link        = static::gen_field_key( static::FIELD_TOPIC_ENTRY_LINK );
		$button                  = static::gen_field_key( static::FIELD_BUTTON );

		return array(
			'title'  => 'Topic Cards',
			'fields' => array(
				static::FIELD_BG_COLOR      => Color::gen_args(
					$bg_color,
					static::FIELD_BG_COLOR,
					array(
						'label'   => 'Background Color',
						'default' => 'white',
						'wrapper' => array(
							'width' => '50%',
						),
					)
				),
				static::FIELD_TEXT_COLOR    => Color::gen_args(
					$text_color,
					static::FIELD_TEXT_COLOR,
					array(
						'label'   => 'Text Color',
						'default' => 'teal-dark',
						'wrapper' => array(
							'width' => '50%',
						),
					),
				),
				static::FIELD_TITLE         => array(
					'key'   => $title,
					'name'  => static::FIELD_TITLE,
					'label' => 'Title',
					'type'  => 'text',
				),
				static::FIELD_DESCRIPTION   => array(
					'key'   => $description,
					'name'  => static::FIELD_DESCRIPTION,
					'label' => 'Description',
					'type'  => 'textarea',
				),
				static::FIELD_TOPIC_ENTRIES => array(
					'key'        => $topic_entries,
					'name'       => static::FIELD_TOPIC_ENTRIES,
					'label'      => 'Topic Entries',
					'type'       => 'repeater',
					'layout'     => 'block',
					'min'        => 1,
					'collapsed'  => $topic_entry_title,
					'sub_fields' => array_merge(
						array(
							static::FIELD_TOPIC_ENTRY_TITLE => array(
								'key'   => $topic_entry_title,
								'name'  => static::FIELD_TOPIC_ENTRY_TITLE,
								'label' => 'Title',
								'type'  => 'text',
							),
							static::FIELD_TOPIC_ENTRY_DESCRIPTION => array(
								'key'   => $topic_entry_description,
								'name'  => static::FIELD_TOPIC_ENTRY_DESCRIPTION,
								'label' => 'Description',
								'type'  => 'textarea',
							),
							static::FIELD_TOPIC_ENTRY_LINK => array(
								'key'        => $topic_entry_link,
								'name'       => static::FIELD_TOPIC_ENTRY_LINK,
								'label'      => 'Link',
								'type'       => 'group',
								'layout'     => 'block',
								'sub_fields' => Advanced_Link::_get_fields(),
							),
						),
					),
				),
				static::FIELD_BUTTON        => array(
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
				'title'      => 'Topic Cards',
				'post_types' => array( 'page' ),
			)
		);
	}

	/**
	 * @inheritDoc
	 */
	public static function render( $post = false, array $data = null, array $options = array() ): ?string {
		$text_color    = ! empty( static::get_val( static::FIELD_TEXT_COLOR ) ) ? static::get_val( static::FIELD_TEXT_COLOR ) : 'teal-dark';
		$bg_color      = ! empty( static::get_val( static::FIELD_BG_COLOR ) ) ? static::get_val( static::FIELD_BG_COLOR ) : 'white';
		$title         = static::get_val( static::FIELD_TITLE );
		$description   = static::get_val( static::FIELD_DESCRIPTION );
		$topic_entries = static::get_val( static::FIELD_TOPIC_ENTRIES );
		$button        = static::get_val( static::FIELD_BUTTON );

		$styles = 'bg-' . $bg_color . ' ' . 'text-' . $text_color;

		ob_start();
		// Next Step - FE (Apply Color & Button)
		?>
		<div class="topic-cards">
			<div class="topic-cards__container">
				<h2 class="topic-cards__heading"><?php echo esc_html( $title ); ?></h2>
				<?php if ( ! empty( $description ) ) : ?>
					<p class="topic-cards__description"><?php echo esc_html( $description ); ?></p>
				<?php endif; ?>

				<?php if ( ! empty( $topic_entries ) ) : ?>
					<div class="topic-cards__accordion">
						<?php foreach ( $topic_entries as $topic ) : ?>
							<div class="topic-cards__accordion-item js-accordion">
								<div class="topic-cards__accordion-header">
									<?php if ( ! empty( $topic[ static::FIELD_TOPIC_ENTRY_TITLE ] ) ) : ?>
										<h3 class="topic-cards__item-title"><?php echo esc_html( $topic[ static::FIELD_TOPIC_ENTRY_TITLE ] ); ?></h3>
									<?php endif; ?>
									<button
										class="topic-cards__accordion-toggle accordion-button"
										aria-label="click to expand <?php echo esc_attr( $topic[ static::FIELD_TOPIC_ENTRY_TITLE ] ); ?>">
									</button>
								</div>
								<div class="topic-cards__accordion-content accordion-collapse">
									<?php if ( ! empty( $topic[ static::FIELD_TOPIC_ENTRY_DESCRIPTION ] ) ) : ?>
										<p class="topic-cards__item-description"><?php echo esc_html( $topic[ static::FIELD_TOPIC_ENTRY_DESCRIPTION ] ); ?></p>
									<?php endif; ?>
									<?php if ( ! empty( $topic[ static::FIELD_TOPIC_ENTRY_LINK ]['title'] ) ) : ?>
										<a class="topic-cards__cta" href="<?php echo esc_url( $topic[ static::FIELD_TOPIC_ENTRY_LINK ]['url'] ); ?>" target="<?php echo esc_attr( $topic[ static::FIELD_TOPIC_ENTRY_LINK ]['target'] ); ?>"><?php echo esc_html( $topic[ static::FIELD_TOPIC_ENTRY_LINK ]['title'] ); ?></a>
									<?php endif; ?>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

				<?php if ( ! empty( $topic_entries ) ) : ?>
					<div class="topic-cards__grid">
						<?php foreach ( $topic_entries as $topic ) : ?>
							<div class="topic-cards__item" role="listitem">
								<?php if ( ! empty( $topic[ static::FIELD_TOPIC_ENTRY_TITLE ] ) ) : ?>
									<h2 class="topic-cards__item-title"><?php echo esc_html( $topic[ static::FIELD_TOPIC_ENTRY_TITLE ] ); ?></h2>
								<?php endif; ?>

								<?php if ( ! empty( $topic[ static::FIELD_TOPIC_ENTRY_DESCRIPTION ] ) ) : ?>
									<p class="topic-cards__item-description"><?php echo esc_html( $topic[ static::FIELD_TOPIC_ENTRY_DESCRIPTION ] ); ?></p>
								<?php endif; ?>

								<?php
									echo Advanced_Link::render(
										null,
										$topic[ static::FIELD_TOPIC_ENTRY_LINK ],
										array(
											'class'      => array( 'topic-cards__cta wave-underline' ),
											'attributes' => array(
												'aria-label' => 'click to learn more about ' . $topic[ static::FIELD_TOPIC_ENTRY_TITLE ],
											),
										)
									);
								?>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

				<?php if ( ! empty( $button['url'] ) ) : ?>
					<a href="<?php echo esc_url( $button['url'] ); ?>" target="<?php echo esc_attr( $button['target'] ); ?>"><?php echo esc_html( $button['title'] ); ?></a>
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
