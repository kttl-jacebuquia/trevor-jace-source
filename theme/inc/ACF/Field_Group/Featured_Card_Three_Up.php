<?php namespace TrevorWP\Theme\ACF\Field_Group;

use TrevorWP\CPT\Donate\Prod_Partner;
use TrevorWP\CPT\RC;
use TrevorWP\Theme\Helper;

use TrevorWP\Theme\ACF\Field\Color;

class Featured_Card_Three_Up extends A_Field_Group implements I_Block, I_Renderable {
	const FIELD_BG_COLOR                  = 'bg_color';
	const FIELD_TEXT_COLOR                = 'text_color';
	const FIELD_TITLE                     = 'title';
	const FIELD_CARD_TYPE                 = 'card_type';
	const FIELD_ARTICLES                  = 'articles';
	const FIELD_PRODUCT_PARTNERS          = 'product_partners';
	const FIELD_BUTTON                    = 'button';
	const FIELD_DESCRIPTION               = 'description';
	const FIELD_DESKTOP_HEADING_ALIGNMENT = 'desktop_heading_alignment';
	const FIELD_MOBILE_TABLET_LAYOUT      = 'mobile_tablet_layout';

	/**
	 * @inheritDoc
	 */
	protected static function prepare_register_args(): array {
		$bg_color                  = static::gen_field_key( static::FIELD_BG_COLOR );
		$text_color                = static::gen_field_key( static::FIELD_TEXT_COLOR );
		$title                     = static::gen_field_key( static::FIELD_TITLE );
		$card_type                 = static::gen_field_key( static::FIELD_CARD_TYPE );
		$articles                  = static::gen_field_key( static::FIELD_ARTICLES );
		$product_partners          = static::gen_field_key( static::FIELD_PRODUCT_PARTNERS );
		$button                    = static::gen_field_key( static::FIELD_BUTTON );
		$description               = static::gen_field_key( static::FIELD_DESCRIPTION );
		$desktop_heading_alignment = static::gen_field_key( static::FIELD_DESKTOP_HEADING_ALIGNMENT );
		$mobile_tablet_layout      = static::gen_field_key( static::FIELD_MOBILE_TABLET_LAYOUT );

		return array(
			'title'  => 'Featured Card 3-Up Block',
			'fields' => array(
				static::FIELD_BG_COLOR                  => Color::gen_args(
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
				static::FIELD_TEXT_COLOR                => Color::gen_args(
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
				static::FIELD_DESKTOP_HEADING_ALIGNMENT => array(
					'key'           => $desktop_heading_alignment,
					'name'          => static::FIELD_DESKTOP_HEADING_ALIGNMENT,
					'label'         => 'Heading Alignment on Desktop',
					'type'          => 'radio',
					'layout'        => 'horizontal',
					'ui'            => 1,
					'default_value' => 'center',
					'choices'       => array(
						'center' => 'Center',
						'left'   => 'Left',
					),
				),
				static::FIELD_TITLE                     => array(
					'key'   => $title,
					'name'  => static::FIELD_TITLE,
					'label' => 'Title',
					'type'  => 'text',
				),
				static::FIELD_DESCRIPTION               => array(
					'key'   => $description,
					'name'  => static::FIELD_DESCRIPTION,
					'label' => 'Description',
					'type'  => 'textarea',
				),
				static::FIELD_CARD_TYPE                 => array(
					'key'           => $card_type,
					'name'          => static::FIELD_CARD_TYPE,
					'label'         => 'Card Type',
					'type'          => 'button_group',
					'choices'       => array(
						'articles'         => 'Articles',
						'product_partners' => 'Product Partners',
					),
					'default_value' => 'articles',
				),
				static::FIELD_MOBILE_TABLET_LAYOUT      => array(
					'key'           => $mobile_tablet_layout,
					'name'          => static::FIELD_MOBILE_TABLET_LAYOUT,
					'label'         => 'Cards Layout on Tablet/Mobile',
					'type'          => 'radio',
					'layout'        => 'horizontal',
					'choices'       => array(
						'grid'     => 'Grid',
						'carousel' => 'Carousel',
					),
					'default_value' => 'grid',
				),
				static::FIELD_ARTICLES                  => array(
					'key'               => $articles,
					'name'              => static::FIELD_ARTICLES,
					'label'             => 'Articles',
					'type'              => 'relationship',
					'required'          => 1,
					'post_type'         => RC\RC_Object::$PUBLIC_POST_TYPES,
					'min'               => 3,
					'max'               => 3,
					'conditional_logic' => array(
						array(
							array(
								'field'    => $card_type,
								'operator' => '==',
								'value'    => 'articles',
							),
						),
					),
				),
				static::FIELD_PRODUCT_PARTNERS          => array(
					'key'               => $product_partners,
					'name'              => static::FIELD_PRODUCT_PARTNERS,
					'label'             => 'Product Partners',
					'type'              => 'relationship',
					'required'          => 1,
					'post_type'         => array( Prod_Partner::POST_TYPE ),
					'min'               => 3,
					'max'               => 3,
					'conditional_logic' => array(
						array(
							array(
								'field'    => $card_type,
								'operator' => '==',
								'value'    => 'product_partners',
							),
						),
					),
				),
				static::FIELD_BUTTON                    => array(
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
				'title'      => 'Featured Card 3-Up Block',
				'post_types' => array( 'page' ),
			)
		);
	}

	/**
	 * @inheritDoc
	 */
	public static function render( $post = false, $data = array(), array $options = array() ): ?string {
		// Prioritize data passed through the $data and $options
		$title       = ! empty( $data ) ? $data['title'] : static::get_val( static::FIELD_TITLE );
		$description = ! empty( $data ) ? $data['description'] : static::get_val( static::FIELD_DESCRIPTION );
		$card_type   = ! empty( $data ) ? $data['card_type'] : static::get_val( static::FIELD_CARD_TYPE );
		$button      = ! empty( $data ) ? $data['button'] : static::get_val( static::FIELD_BUTTON );
		$text_color  = ! empty( $options['text_color'] ) ? $options['text_color'] : ( ! empty( static::get_val( static::FIELD_TEXT_COLOR ) ) ? static::get_val( static::FIELD_TEXT_COLOR ) : 'teal-dark' );
		$bg_color    = ! empty( $options['bg_color'] ) ? $options['bg_color'] : ( ! empty( static::get_val( static::FIELD_BG_COLOR ) ) ? static::get_val( static::FIELD_BG_COLOR ) : 'white' );
		$alignment   = ! empty( $options['alignment'] ) ? $options['alignment'] : static::get_val( static::FIELD_DESKTOP_HEADING_ALIGNMENT );
		$layout      = ! empty( $options['layout'] ) ? $options['layout'] : static::get_val( static::FIELD_MOBILE_TABLET_LAYOUT );

		$cards = array();

		if ( ! empty( $data ) ) {
			$cards = $data['cards'] ?? array();
		} elseif ( 'articles' === $card_type ) {
			$cards = static::get_val( static::FIELD_ARTICLES );
		} elseif ( 'product_partners' === $card_type ) {
			$cards = static::get_val( static::FIELD_PRODUCT_PARTNERS );
		}

		$tile_options = array();

		$attrs = array(
			'class' => implode(
				' ',
				array(
					'featured-card-3up',
					'featured-card-3up--' . $alignment,
					'featured-card-3up--' . $layout,
					'bg-' . $bg_color,
					'text-' . $text_color,
					$options['class'] ?? '',
				),
			),
		);

		ob_start();
		?>
		<div <?php echo static::render_attrs( $attrs ); ?>>
			<div class="featured-card-3up__container">
				<div class="featured-card-3up__content">
					<?php if ( ! empty( $title ) ) : ?>
						<h2 class="featured-card-3up__heading"><?php echo esc_html( $title ); ?></h2>
					<?php endif; ?>
					<?php if ( ! empty( $description ) ) : ?>
						<div class="featured-card-3up__description"><?php echo esc_html( $description ); ?></div>
					<?php endif; ?>

					<?php if ( ! empty( $cards ) ) : ?>
						<div class="featured-card-3up__items-container swiper-container">
							<div class="featured-card-3up__items swiper-wrapper" role="list">
								<?php foreach ( $cards as $key => $card ) : ?>
									<div class="featured-card-3up__item swiper-slide" role="listitem">
										<?php echo Post_Grid::render_post_card( $card, $key, $tile_options ); ?>
									</div>
								<?php endforeach; ?>
							</div>
							<?php if ( 'carousel' === $layout ) : ?>
								<div class="swiper-button swiper-button-prev">
									<div class="swiper-button-wrapper">
										<i class="trevor-ti-arrow-left"></i>
									</div>
								</div>
								<div class="swiper-button swiper-button-next">
									<div class="swiper-button-wrapper">
										<i class="trevor-ti-arrow-right"></i>
									</div>
								</div>
								<div class="swiper-pagination"></div>
							<?php endif; ?>
						</div>
					<?php endif; ?>

					<?php if ( ! empty( $button['url'] ) ) : ?>
						<div class="featured-card-3up__cta-wrap">
							<a class="featured-card-3up__cta" href="<?php echo esc_url( $button['url'] ); ?>" target="<?php echo esc_attr( $button['target'] ); ?>"><?php echo esc_html( $button['title'] ); ?></a>
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
