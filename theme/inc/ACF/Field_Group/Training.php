<?php namespace TrevorWP\Theme\ACF\Field_Group;

class Training extends A_Field_Group implements I_Block, I_Renderable {
	const FIELD_TITLE             = 'title';
	const FIELD_DESCRIPTION       = 'description';
	const FIELD_BUTTON            = 'button';
	const FIELD_CARD              = 'card_group';
	const FIELD_CARD_GROUP_HEADER = 'card_group_header';
	const FIELD_CARD_GROUP_BODY   = 'card_group_body';
	const FIELD_CARD_GROUP_LINK   = 'card_group_link';

	/**
	 * @inheritDoc
	 */
	protected static function prepare_register_args(): array {
		$title             = static::gen_field_key( static::FIELD_TITLE );
		$description       = static::gen_field_key( static::FIELD_DESCRIPTION );
		$button            = static::gen_field_key( static::FIELD_BUTTON );
		$card              = static::gen_field_key( static::FIELD_CARD );
		$card_group_header = static::gen_field_key( static::FIELD_CARD_GROUP_HEADER );
		$card_group_body   = static::gen_field_key( static::FIELD_CARD_GROUP_BODY );
		$card_group_link   = static::gen_field_key( static::FIELD_CARD_GROUP_LINK );

		return array(
			'title'  => 'Training Module',
			'fields' => array(
				static::FIELD_TITLE       => array(
					'key'   => $title,
					'name'  => static::FIELD_TITLE,
					'label' => 'Title',
					'type'  => 'text',
				),
				static::FIELD_DESCRIPTION => array(
					'key'   => $description,
					'name'  => static::FIELD_DESCRIPTION,
					'label' => 'Description',
					'type'  => 'textarea',
				),
				static::FIELD_BUTTON      => array(
					'key'   => $button,
					'name'  => static::FIELD_BUTTON,
					'label' => 'Button',
					'type'  => 'link',
				),
				static::FIELD_CARD        => array(
					'key'        => $card,
					'name'       => static::FIELD_CARD,
					'label'      => 'Card',
					'type'       => 'group',
					'layout'     => 'block',
					'sub_fields' => array(
						static::FIELD_CARD_GROUP_HEADER => array(
							'key'      => $card_group_header,
							'name'     => static::FIELD_CARD_GROUP_HEADER,
							'label'    => 'Header',
							'type'     => 'text',
							'required' => 1,
						),
						static::FIELD_CARD_GROUP_BODY   => array(
							'key'   => $card_group_body,
							'name'  => static::FIELD_CARD_GROUP_BODY,
							'label' => 'Body',
							'type'  => 'textarea',
						),
						static::FIELD_CARD_GROUP_LINK   => array(
							'key'   => $card_group_link,
							'name'  => static::FIELD_CARD_GROUP_LINK,
							'label' => 'Link',
							'type'  => 'link',
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
				'title'      => 'Training Module',
				'post_types' => array( 'page' ),
			)
		);
	}

	/**
	 * @inheritDoc
	 */
	public static function render( $post = false, array $data = null, array $options = array() ): ?string {
		$title       = static::get_val( static::FIELD_TITLE );
		$description = static::get_val( static::FIELD_DESCRIPTION );
		$button      = static::get_val( static::FIELD_BUTTON );
		$card        = static::get_val( static::FIELD_CARD );

		ob_start();
		// Next Step: FE
		?>
		<div class="training">
			<div class="training__container">
				<div class="training__main">
					<?php if ( ! empty( $title ) ) : ?>
						<h2 class="training__heading"><?php echo esc_html( $title ); ?></h2>
					<?php endif; ?>

					<?php if ( ! empty( $description ) ) : ?>
						<div class="training__description"><?php echo esc_html( $description ); ?></div>
					<?php endif; ?>

					<?php if ( ! empty( $button['url'] ) ) : ?>
						<a class="training__cta" href="<?php echo esc_url( $button['url'] ); ?>" target="<?php echo esc_attr( $button['target'] ); ?>"><?php echo esc_html( $button['title'] ); ?></a>
					<?php endif; ?>
				</div>
				<?php if ( ! empty( $card ) ) : ?>
					<div class="training__card">
						<?php if ( ! empty( $card[ static::FIELD_CARD_GROUP_HEADER ] ) ) : ?>
							<h3 class="training__card-heading"><?php echo esc_html( $card[ static::FIELD_CARD_GROUP_HEADER ] ); ?></h3>
						<?php endif; ?>

						<?php if ( ! empty( $card[ static::FIELD_CARD_GROUP_BODY ] ) ) : ?>
							<p class="training__card-body"><?php echo esc_html( $card[ static::FIELD_CARD_GROUP_BODY ] ); ?></p>
						<?php endif; ?>

						<?php if ( ! empty( $card[ static::FIELD_CARD_GROUP_LINK ]['url'] ) ) : ?>
							<a class="training__card-cta" href="<?php echo esc_url( $card[ static::FIELD_CARD_GROUP_LINK ]['url'] ); ?>" target="<?php echo esc_attr( $card[ static::FIELD_CARD_GROUP_LINK ]['target'] ); ?>"><?php echo esc_html( $card[ static::FIELD_CARD_GROUP_LINK ]['title'] ); ?></a>
						<?php endif; ?>
					</div>
				<?php endif; ?>
				<?php if ( ! empty( $button['url'] ) ) : ?>
					<div class="training__bottom">
						<a class="training__cta" href="<?php echo esc_url( $button['url'] ); ?>" target="<?php echo esc_attr( $button['target'] ); ?>"><?php echo esc_html( $button['title'] ); ?></a>
					</div>
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
