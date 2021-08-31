<?php

namespace TrevorWP\Theme\ACF\Field_Group;

use TrevorWP\CPT\Team;
use TrevorWP\Theme\Helper;

class Staff_Module extends A_Field_Group implements I_Block, I_Renderable {

	const FIELD_TITLE                  = 'title';
	const FIELD_DESCRIPTION            = 'description';
	const FIELD_DESKTOP_TEXT_ALIGNMENT = 'desktop_text_alignment';
	const FIELD_DISPLAY_TYPE           = 'display_type';
	const FIELD_GROUP                  = 'group';
	const FIELD_ENTRIES                = 'entries';

	/**
	 * @inheritDoc
	 */
	protected static function prepare_register_args(): array {
		$title                  = static::gen_field_key( static::FIELD_TITLE );
		$description            = static::gen_field_key( static::FIELD_DESCRIPTION );
		$desktop_text_alignment = static::gen_field_key( static::FIELD_DESKTOP_TEXT_ALIGNMENT );
		$display_type           = static::gen_field_key( static::FIELD_DISPLAY_TYPE );
		$group                  = static::gen_field_key( static::FIELD_GROUP );
		$entries                = static::gen_field_key( static::FIELD_ENTRIES );

		$terms = get_terms( Team::TAXONOMY_GROUP );

		$terms_options      = array();
		$post_object_fields = array();

		foreach ( $terms as $term ) {
			$terms_options[ $term->term_id ] = $term->name;

			$post_object_fields[] = array(
				'key'               => $term->slug . $term->term_id,
				'name'              => $term->slug,
				'label'             => $term->name,
				'type'              => 'post_object',
				'required'          => 1,
				'post_type'         => array( Team::POST_TYPE ),
				'taxonomy'          => array( $term->term_id ),
				'min'               => 1,
				'multiple'          => 1,
				'conditional_logic' => array(
					array(
						array(
							'field'    => $group,
							'operator' => '==',
							'value'    => $term->term_id,
						),
					),
				),
			);
		}

		return array(
			'title'  => 'Staff Module',
			'fields' => array(
				static::FIELD_TITLE                  => array(
					'key'   => $title,
					'name'  => static::FIELD_TITLE,
					'label' => 'Title',
					'type'  => 'text',
				),
				static::FIELD_DESCRIPTION            => array(
					'key'   => $description,
					'name'  => static::FIELD_DESCRIPTION,
					'label' => 'Description',
					'type'  => 'textarea',
				),
				static::FIELD_DESKTOP_TEXT_ALIGNMENT => array(
					'key'           => $desktop_text_alignment,
					'name'          => static::FIELD_DESKTOP_TEXT_ALIGNMENT,
					'label'         => 'Desktop Text Alignment',
					'type'          => 'button_group',
					'choices'       => array(
						'left'   => 'Left',
						'center' => 'Center',
					),
					'default_value' => 'center',
				),
				static::FIELD_DISPLAY_TYPE           => array(
					'key'           => $display_type,
					'name'          => static::FIELD_DISPLAY_TYPE,
					'label'         => 'Display Type',
					'type'          => 'button_group',
					'choices'       => array(
						'carousel' => 'Carousel',
						'grid'     => 'Grid',
						'list'     => 'List',
					),
					'default_value' => 'carousel',
				),
				static::FIELD_GROUP                  => array(
					'key'               => $group,
					'name'              => static::FIELD_GROUP,
					'label'             => 'Group',
					'type'              => 'select',
					'choices'           => $terms_options,
					'return_format'     => 'array',
					'conditional_logic' => array(
						array(
							array(
								'field'    => $display_type,
								'operator' => '!=',
								'value'    => 'list',
							),
						),
					),
				),
				static::FIELD_ENTRIES                => array(
					'key'               => $entries,
					'name'              => static::FIELD_ENTRIES,
					'label'             => 'Entries',
					'type'              => 'group',
					'layout'            => 'block',
					'sub_fields'        => $post_object_fields,
					'conditional_logic' => array(
						array(
							array(
								'field'    => $display_type,
								'operator' => '!=',
								'value'    => 'list',
							),
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
				'title'      => 'Staff Module',
				'post_types' => array( 'page' ),
			)
		);
	}

	/**
	 * @inheritDoc
	 */
	public static function render( $post = false, array $data = null, array $options = array() ): ?string {
		$title                  = static::get_val( static::FIELD_TITLE );
		$description            = static::get_val( static::FIELD_DESCRIPTION );
		$desktop_text_alignment = static::get_val( static::FIELD_DESKTOP_TEXT_ALIGNMENT );
		$display_type           = static::get_val( static::FIELD_DISPLAY_TYPE );
		$group                  = static::get_val( static::FIELD_GROUP );

		$cards     = array();
		$card_type = 'researchers';

		if ( ! empty( $group['label'] ) ) {
			$term = get_term( $group['value'] );

			if ( 'founder' === strtolower( $group['label'] ) ) {
				$card_type = 'founders';
			}

			$entries = static::get_val( static::FIELD_ENTRIES );

			if ( ! empty( $term ) && ! empty( $entries[ $term->slug ] ) ) {
				$cards = $entries[ $term->slug ];
			}
		}

		if ( 'list' === $display_type ) {
			$args  = array(
				'post_type'   => Team::POST_TYPE,
				'post_status' => 'publish',
				'orderby'     => 'title',
				'order'       => 'ASC',
				'tax_query'   => array(
					array(
						'taxonomy' => Team::TAXONOMY_GROUP,
						'field'    => 'slug',
						'terms'    => array( 'founder', 'researcher' ),
						'operator' => 'NOT IN',
					),
				),
			);
			// Uncomment this if the list is available.
			// $cards = get_posts( $args );
		}

		$styles = $desktop_text_alignment;

		$tile_options = array();

		ob_start();
		?>
		<div class="staff <?php echo esc_attr( $card_type ); ?> <?php echo esc_attr( $styles ); ?> is-<?php echo $display_type; ?> text-<?php echo $desktop_text_alignment; ?>">
			<div class="staff__container">
				<?php if ( ! empty( $title ) ) : ?>
					<h2 class="staff__heading"><?php echo esc_html( $title ); ?></h2>
				<?php endif; ?>

				<?php if ( ! empty( $description ) ) : ?>
					<div class="staff__description"><?php echo esc_html( $description ); ?></div>
				<?php endif; ?>

				<?php if ( ! empty( $cards ) ) : ?>
					<?php if ( 'carousel' === $display_type ) : ?>
						<div class="staff__cards-container swiper-container">
							<div class="swiper-wrapper staff__cards-wrapper">
								<?php foreach ( $cards as $key => $card ) : ?>
									<div class="staff__card swiper-slide">
										<?php echo Helper\Tile::staff( $card, $key, $tile_options ); ?>
									</div>
								<?php endforeach; ?>
							</div>
							<div class="swiper-pagination"></div>
						</div>
					<?php elseif ( 'grid' === $display_type ) : ?>
						<div class="swiper-wrapper staff__cards-wrapper">
							<?php $card_ctr = 0; foreach ( $cards as $key => $card ) : ?>
								<div class="staff__card" data-staff-part="<?php echo $card_ctr < 8 ? 'first' : 'last'; ?>">
									<?php echo Helper\Tile::staff( $card, $key, $tile_options ); ?>
								</div>
								<?php $card_ctr++; ?>
							<?php endforeach; ?>
						</div>
						<?php if ( 8 <= count( $cards ) ) : ?>
							<div class="staff__load-more-container mt-px40 md:mt-px22 lg:mt-px50">
								<button class="staff__load-more text-center text-px24 leading-px32 tracking-em005 border-b-px4">
									<span class="pb-px4">Load More</span>
								</button>
							</div>
						<?php endif; ?>
					<?php endif; ?>
				<?php endif; ?>
				<?php if ( ! empty( $button['url'] ) ) : ?>
					<div class="staff__cta-wrap">
						<a class="staff__cta" href="<?php echo esc_url( $button['url'] ); ?>" target="<?php echo esc_attr( $button['target'] ); ?>"><?php echo esc_html( $button['title'] ); ?></a>
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
