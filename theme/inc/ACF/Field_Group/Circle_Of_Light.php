<?php namespace TrevorWP\Theme\ACF\Field_Group;

class Circle_Of_Light extends A_Field_Group implements I_Block, I_Renderable {
	const FIELD_IMAGE         = 'image';
	const FIELD_TITLE         = 'title';
	const FIELD_DESCRIPTION   = 'description';
	const FIELD_CARDS      		= 'cards';
	const FIELD_CARD_TITLE 		= 'card_title';
	const FIELD_CARD_SUBTITLE = 'card_subtitle';
	const FIELD_CARD_IMAGE    = 'card_image';
	const FIELD_CARD_CONTACT  = 'card_contact';
	const FIELD_CARD_INTRO    = 'card_intro';
	const FIELD_CARD_BODY   	= 'card_body';

	/**
	 * @inheritDoc
	 */
	protected static function prepare_register_args(): array {
		$image				 = static::gen_field_key( static::FIELD_IMAGE );
		$title         = static::gen_field_key( static::FIELD_TITLE );
		$description   = static::gen_field_key( static::FIELD_DESCRIPTION );
		$cards      	 = static::gen_field_key( static::FIELD_CARDS );
		$card_title 	 = static::gen_field_key( static::FIELD_CARD_TITLE );
		$card_subtitle = static::gen_field_key( static::FIELD_CARD_SUBTITLE );
		$card_image    = static::gen_field_key( static::FIELD_CARD_IMAGE );
		$card_contact  = static::gen_field_key( static::FIELD_CARD_CONTACT );
		$card_intro    = static::gen_field_key( static::FIELD_CARD_INTRO );
		$card_body     = static::gen_field_key( static::FIELD_CARD_BODY );

		// ACF field setting docs
		// https://www.advancedcustomfields.com/resources/register-fields-via-php/
		return array(
			'title'  => 'Circle of Light Tiers',
			'fields' => array(
				static::FIELD_IMAGE => array(
					'key'           => $image,
					'name'          => static::FIELD_IMAGE,
					'label'         => 'Image',
					'type'          => 'image',
					'return_format' => 'array',
					'preview_size'  => 'thumbnail',
					'library'       => 'all',
				),
				static::FIELD_TITLE => array(
					'key'      => $title,
					'name'     => static::FIELD_TITLE,
					'label'    => 'Title',
					'type'     => 'text',
					'required' => 1,
				),
				static::FIELD_DESCRIPTION => array(
					'key'   => $description,
					'name'  => static::FIELD_DESCRIPTION,
					'label' => 'Description',
					'type'  => 'textarea',
					'new_lines' => 'wpautop',// add <p> tags
				),
				static::FIELD_CARDS => array(
					'key'        => $cards,
					'name'       => static::FIELD_CARDS,
					'label'      => 'Cards',
					'type'       => 'repeater',
					'layout'     => 'block',
					'min'        => 1,
					'max'        => 4,
					'collapsed'  => $card_title,
					'sub_fields' => array(
						static::FIELD_CARD_TITLE => array(
							'key'      => $card_title,
							'name'     => static::FIELD_CARD_TITLE,
							'label'    => 'Title',
							'type'     => 'text',
							'required' => 1,
						),
						static::FIELD_CARD_SUBTITLE => array(
							'key'      => $card_subtitle,
							'name'     => static::FIELD_CARD_SUBTITLE,
							'label'    => 'Subtitle',
							'type'     => 'text',
							'required' => 1,
						),
						static::FIELD_CARD_IMAGE => array(
							'key'           => $card_image,
							'name'          => static::FIELD_CARD_IMAGE,
							'label'         => 'Image',
							'type'          => 'image',
							'return_format' => 'array',
							'preview_size'  => 'thumbnail',
							'library'       => 'all',
						),
						static::FIELD_CARD_CONTACT => array(
							'key'          => $card_contact,
							'name'         => static::FIELD_CARD_CONTACT,
							'label'        => 'Contact Info',
							'type'         => 'wysiwyg',
							'toolbar'      => 'basic',
							'media_upload' => 0,
						),
						static::FIELD_CARD_INTRO => array(
							'key'          => $card_intro,
							'name'         => static::FIELD_CARD_INTRO,
							'label'        => 'Intro Text',
							'type'         => 'text',
						),
						static::FIELD_CARD_BODY => array(
							'key'          => $card_body,
							'name'         => static::FIELD_CARD_BODY,
							'label'        => 'Description',
							'type'         => 'wysiwyg',
							'toolbar'      => 'basic',
							'media_upload' => 0,
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
				'title'      => 'Circle of Light Tiers',
				'post_types' => array( 'page' ),
			)
		);
	}

	/**
	 * @inheritDoc
	 */
	public static function render( $post = false, array $data = null, array $options = array() ): ?string {
		$image       = static::get_val( static::FIELD_IMAGE );
		$title       = static::get_val( static::FIELD_TITLE );
		$description = static::get_val( static::FIELD_DESCRIPTION );
		$cards       = static::get_val( static::FIELD_CARDS );

		ob_start();
		?>
		<div class="circle-of-light bg-indigo py-20 xl:py-24">
			<div class="circle-of-light__wrap">
				<?php if ( !empty( $image ) ) : ?>
					<img class="circle-of-light__image"
						src="<?php echo $image['url']; ?>"
						alt="<?php echo !empty($image['alt']) ? esc_attr($image['alt']) : ''; ?>">
				<?php endif; ?>

				<?php if ( !empty( $title ) ) : ?>
					<h2 class="circle-of-light__heading"><?php echo esc_html( $title ); ?></h2>
				<?php endif; ?>

				<?php if ( !empty( $description ) ) : ?>
					<div class="circle-of-light__description">
						<?php echo $description; ?>
					</div>
				<?php endif; ?>
			</div>

			<?php if ( !empty( $cards ) ) : ?>
				<ul class="circle-of-light__grid">
					<?php foreach ( $cards as $card ) : ?>
						<li class="circle-of-light__grid-item">
							<div class="circle-of-light__card">
								<div class="circle-of-light__card-top">
									<?php if ( ! empty( $card[ static::FIELD_CARD_IMAGE ] ) ) : ?>
										<img class="circle-of-light__card-image"
											src="<?php echo $card[ static::FIELD_CARD_IMAGE ]['url']; ?>"
											alt="<?php echo !empty($card[ static::FIELD_CARD_IMAGE ]['alt']) ? esc_attr($card[ static::FIELD_CARD_IMAGE ]['alt']) : ''; ?>">
									<?php endif; ?>
									<div class="circle-of-light__card-top-content">
										<?php if ( ! empty( $card[ static::FIELD_CARD_TITLE ] ) ) : ?>
											<h3 class="circle-of-light__card-title">
												<?php echo esc_html( $card[ static::FIELD_CARD_TITLE ] ); ?>
											</h2>
										<?php endif; ?>
										<?php if ( ! empty( $card[ static::FIELD_CARD_SUBTITLE ] ) ) : ?>
											<p class="circle-of-light__card-subtitle">
												<?php echo esc_html( $card[ static::FIELD_CARD_SUBTITLE ] ); ?>
											</p>
										<?php endif; ?>
									</div>
								</div>
								<div class="circle-of-light__card-bottom bg-gray-light">
									<?php if ( ! empty( $card[ static::FIELD_CARD_CONTACT ] ) ) : ?>
										<div class="circle-of-light__card-contact">
											<?php echo $card[ static::FIELD_CARD_CONTACT ]; ?>
										</div>
									<?php endif; ?>
									<?php if ( ! empty( $card[ static::FIELD_CARD_BODY ] ) ) : ?>
										<div class="circle-of-light__card-body">
											<?php if ( ! empty( $card[ static::FIELD_CARD_INTRO ] ) ) : ?>
												<p class="circle-of-light__card-intro">
													<?php echo esc_html( $card[ static::FIELD_CARD_INTRO ] ); ?>
												</p>
											<?php endif; ?>
											<?php echo $card[ static::FIELD_CARD_BODY ]; ?>
										</div>
									<?php endif; ?>
									<button class="circle-of-light__card-toggle" type="button">
										<span class="is-collapsed">Expand to see more</span>
										<span class="is-expanded">Collapse</span>
									</button>
								</div>
							</div>
						</li>
					<?php endforeach; ?>
				</ul>
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
