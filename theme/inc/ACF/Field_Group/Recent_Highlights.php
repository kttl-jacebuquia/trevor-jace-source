<?php namespace TrevorWP\Theme\ACF\Field_Group;

use TrevorWP\CPT\Donate\Partner_Prod;
use TrevorWP\CPT\RC\RC_Object;
use TrevorWP\Theme\Helper;
use TrevorWP\Theme\Helper\Thumbnail;

use TrevorWP\Theme\ACF\Field\Color;

class Recent_Highlights extends A_Field_Group implements I_Block, I_Renderable {
	const FIELD_TITLE             = 'title';
	const FIELD_DESCRIPTION       = 'description';
	const FIELD_PLACEHOLDER_IMAGE = 'placeholder_image';
	const FIELD_CARDS             = 'cards';
	const FIELD_STYLES            = 'styles';

	/**
	 * @inheritDoc
	 */
	protected static function prepare_register_args(): array {
		$title             = static::gen_field_key( static::FIELD_TITLE );
		$description       = static::gen_field_key( static::FIELD_DESCRIPTION );
		$placeholder_image = static::gen_field_key( static::FIELD_PLACEHOLDER_IMAGE );
		$cards             = static::gen_field_key( static::FIELD_CARDS );
		$styles            = static::gen_field_key( static::FIELD_STYLES );

		return array(
			'title'  => 'Recent Highlights',
			'fields' => array(
				static::FIELD_STYLES            => Block_Styles::clone(
					array(
						'key'    => $styles,
						'name'   => static::FIELD_STYLES,
						'layout' => 'block',
					),
				),
				static::FIELD_TITLE             => array(
					'key'   => $title,
					'name'  => static::FIELD_TITLE,
					'label' => 'Title',
					'type'  => 'text',
				),
				static::FIELD_DESCRIPTION       => array(
					'key'   => $description,
					'name'  => static::FIELD_DESCRIPTION,
					'label' => 'Description',
					'type'  => 'textarea',
				),
				static::FIELD_PLACEHOLDER_IMAGE => array(
					'key'          => $placeholder_image,
					'name'         => static::FIELD_PLACEHOLDER_IMAGE,
					'label'        => 'Placeholder Image',
					'type'         => 'image',
					'required'     => 1,
					'preview_size' => 'thumbnail',
				),
				static::FIELD_CARDS             => array(
					'key'       => $cards,
					'name'      => static::FIELD_CARDS,
					'label'     => 'Recent Highlights',
					'type'      => 'relationship',
					'required'  => 1,
					'post_type' => array( 'post' ),
					'min'       => 1,
					'max'       => 6,
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
				'title'      => 'Recent Highlights',
				'post_types' => array( 'page' ),
			)
		);
	}

	/**
	 * @inheritDoc
	 */
	public static function render( $post = false, array $data = null, array $options = array() ): ?string {
		$title             = static::get_val( static::FIELD_TITLE );
		$description       = static::get_val( static::FIELD_DESCRIPTION );
		$placeholder_image = static::get_val( static::FIELD_PLACEHOLDER_IMAGE );
		$cards             = static::get_val( static::FIELD_CARDS );
		$styles            = static::get_val( static::FIELD_STYLES );

		list(
			$bg_color,
			$text_color,
		) = array_values( $styles );

		$attr = array(
			'class' => implode(
				' ',
				array(
					'recent-highlights',
					'bg-' . $bg_color,
					'text-' . $text_color,
				)
			),
		);

		if ( ! empty( $placeholder_image['url'] ) ) {
			$placeholder_image = $placeholder_image['url'];
		} else {
			$placeholder_image = '/wp-content/themes/trevor/static/media/generic-placeholder.png';
		}

		ob_start();
		?>
		<div <?php echo static::render_attrs( $attr ); ?>>
			<div class="recent-highlights__container">
				<div class="recent-highlights__headings">
					<?php if ( ! empty( $title ) ) : ?>
						<h3 class="recent-highlights__heading"><?php echo esc_html( $title ); ?></h3>
					<?php endif; ?>
					<?php if ( ! empty( $description ) ) : ?>
						<p class="recent-highlights__description"><?php echo esc_html( $description ); ?></p>
					<?php endif; ?>
				</div>
				<?php if ( ! empty( $cards ) ) : ?>
					<div class="recent-highlights__carousel-container swiper-container">
						<div class="recent-highlights__cards swiper-wrapper" role="list">
							<?php foreach ( $cards as $card ) : ?>
								<?php echo static::render_card( $card, $placeholder_image ); ?>
							<?php endforeach; ?>
						</div>
						<?php if ( count( $cards ) > 1 ) : ?>
							<div class="swiper-pagination"></div>
							<div class="recent-highlights__carousel-panes" aria-hidden="true">
								<div class="recent-highlights__carousel-pane recent-highlights__carousel-pane--left"></div>
								<div class="recent-highlights__carousel-pane recent-highlights__carousel-pane--right"></div>
							</div>
						<?php endif; ?>
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
	public static function render_card( $post, $placeholder_image ) {
		$post     = get_post( $post );
		$title    = esc_html( $post->post_title );
		$excerpt  = $post->post_excerpt;
		$link     = esc_attr( get_the_permalink( $post ) );
		$category = get_the_category( $post );

		// Trim excerpt
		$excerpt = ! empty( $excerpt ) ? esc_html( wp_trim_words( wp_strip_all_tags( $excerpt ), 30 ) ) : '';

		$img_variant = Thumbnail::variant( false, Thumbnail::TYPE_SQUARE, Thumbnail::SIZE_MD );
		$thumb       = Thumbnail::post( $post, $img_variant );

		$aria_label = esc_attr( 'click to read more about ' . $title );
		$class      = array( 'recent-highlights__card swiper-slide' );
		if ( empty( $thumb ) ) {
			$class[] = 'recent-highlights__card--no-image';
		};

		$attrs = array(
			'class' => implode( ' ', $class ),
			'role'  => 'listitem',
		);

		ob_start();
		?>
			<div <?php echo static::render_attrs( $attrs ); ?>>
				<div class="recent-highlights__image">
					<?php if ( ! empty( $thumb ) ) : ?>
						<?php echo $thumb; ?>
					<?php else : ?>
						<img src="<?php echo $placeholder_image; ?>" alt="">
					<?php endif; ?>
				</div>
				<div class="recent-highlights__body">
					<?php if ( ! empty( $category ) ) : ?>
						<p class="recent-highlights__eyebrow"><?php echo $category[0]->name; ?></p>
					<?php endif; ?>
					<h3 class="recent-highlights__title"><?php echo $title; ?></h3>
					<?php if ( ! empty( $excerpt ) ) : ?>
						<p class="recent-highlights__excerpt"><?php echo $excerpt; ?></p>
					<?php endif; ?>
					<a href="<?php echo $link; ?>" class="recent-highlights__cta" aria-label="<?php echo $aria_label; ?>">Read More</a>
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
