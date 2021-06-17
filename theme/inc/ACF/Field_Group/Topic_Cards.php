<?php namespace TrevorWP\Theme\ACF\Field_Group;

class Topic_Cards extends A_Field_Group implements I_Block, I_Renderable {
	const FIELD_TITLE                   = 'title';
	const FIELD_DESCRIPTION             = 'description';
	const FIELD_TOPIC_ENTRIES           = 'topic_entries';
	const FIELD_TOPIC_ENTRY_TITLE       = 'topic_entry_title';
	const FIELD_TOPIC_ENTRY_DESCRIPTION = 'topic_entry_description';
	const FIELD_TOPIC_ENTRY_LINK        = 'topic_entry_link';

	/**
	 * @inheritDoc
	 */
	protected static function prepare_register_args(): array {
		$title                   = static::gen_field_key( static::FIELD_TITLE );
		$description             = static::gen_field_key( static::FIELD_DESCRIPTION );
		$topic_entries           = static::gen_field_key( static::FIELD_TOPIC_ENTRIES );
		$topic_entry_title       = static::gen_field_key( static::FIELD_TOPIC_ENTRY_TITLE );
		$topic_entry_description = static::gen_field_key( static::FIELD_TOPIC_ENTRY_DESCRIPTION );
		$topic_entry_link        = static::gen_field_key( static::FIELD_TOPIC_ENTRY_LINK );

		return array(
			'title'  => 'Topic Cards',
			'fields' => array(
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
					'sub_fields' => array(
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
						static::FIELD_TOPIC_ENTRY_LINK  => array(
							'key'           => $topic_entry_link,
							'name'          => static::FIELD_TOPIC_ENTRY_LINK,
							'label'         => 'Link',
							'type'          => 'link',
							'return_format' => 'array',
						),
					),
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
		$title         = static::get_val( static::FIELD_TITLE );
		$description   = static::get_val( static::FIELD_DESCRIPTION );
		$topic_entries = static::get_val( static::FIELD_TOPIC_ENTRIES );

		ob_start();
		?>
		<div class="topic-cards">
			<div class="topic-cards__container">
				<h2 class="topic-cards__heading"><?php echo esc_html( $title ); ?></h2>
				<?php if ( ! empty( $description ) ) : ?>
					<p class="topic-cards__description"><?php echo esc_html( $description ); ?></p>
				<?php endif; ?>

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

				<div class="topic-cards__grid">
					<?php if ( ! empty( $topic_entries ) ) : ?>
						<?php foreach ( $topic_entries as $topic ) : ?>
							<div class="topic-cards__item">
								<?php if ( ! empty( $topic[ static::FIELD_TOPIC_ENTRY_TITLE ] ) ) : ?>
									<h2 class="topic-cards__item-title"><?php echo esc_html( $topic[ static::FIELD_TOPIC_ENTRY_TITLE ] ); ?></h2>
								<?php endif; ?>

								<?php if ( ! empty( $topic[ static::FIELD_TOPIC_ENTRY_DESCRIPTION ] ) ) : ?>
									<p class="topic-cards__item-description"><?php echo esc_html( $topic[ static::FIELD_TOPIC_ENTRY_DESCRIPTION ] ); ?></p>
								<?php endif; ?>

								<?php if ( ! empty( $topic[ static::FIELD_TOPIC_ENTRY_LINK ]['title'] ) ) : ?>
									<a class="topic-cards__cta" href="<?php echo esc_url( $topic[ static::FIELD_TOPIC_ENTRY_LINK ]['url'] ); ?>" target="<?php echo esc_attr( $topic[ static::FIELD_TOPIC_ENTRY_LINK ]['target'] ); ?>"><?php echo esc_html( $topic[ static::FIELD_TOPIC_ENTRY_LINK ]['title'] ); ?></a>
								<?php endif; ?>
							</div>
						<?php endforeach; ?>
					<?php endif; ?>
				</div>
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
