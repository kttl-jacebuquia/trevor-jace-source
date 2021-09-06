<?php

namespace TrevorWP\Theme\ACF\Field_Group;

use TrevorWP\CPT\Team;
use TrevorWP\Theme\Helper;

class Staff_Module extends A_Field_Group implements I_Block, I_Renderable {

	const FIELD_TITLE                  = 'title';
	const FIELD_DESCRIPTION            = 'description';
	const FIELD_DESKTOP_TEXT_ALIGNMENT = 'desktop_text_alignment';
	const FIELD_DISPLAY_TYPE           = 'display_type';
	const FIELD_NUM_COLS               = 'number_columns';
	const FIELD_NUM_DISPLAY_LIMIT      = 'number_display_limit';
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
		$num_cols               = static::gen_field_key( static::FIELD_NUM_COLS );
		$num_display_limit      = static::gen_field_key( static::FIELD_NUM_DISPLAY_LIMIT );
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
				static::FIELD_NUM_COLS               => array(
					'key'               => $num_cols,
					'name'              => static::FIELD_NUM_COLS,
					'label'             => 'Number of Columns (Desktop)',
					'type'              => 'button_group',
					'required'          => true,
					'choices'           => array(
						3 => '3',
						4 => '4',
					),
					'default_value'     => 3,
					'wrapper'           => array(
						'width' => '50%',
					),
					'conditional_logic' => array(
						array(
							array(
								'field'    => $display_type,
								'operator' => '==',
								'value'    => 'grid',
							),
						),
					),
				),
				static::FIELD_NUM_DISPLAY_LIMIT      => array(
					'key'               => $num_display_limit,
					'name'              => static::FIELD_NUM_DISPLAY_LIMIT,
					'label'             => 'Display Limit',
					'instructions'      => 'Number of items to be displayed before clicking <i>Load More</i>',
					'type'              => 'number',
					'default_value'     => 8,
					'wrapper'           => array(
						'width' => '50%',
					),
					'conditional_logic' => array(
						array(
							array(
								'field'    => $display_type,
								'operator' => '==',
								'value'    => 'grid',
							),
						),
					),
				),
				static::FIELD_GROUP                  => array(
					'key'           => $group,
					'name'          => static::FIELD_GROUP,
					'label'         => 'Group',
					'type'          => 'select',
					'choices'       => $terms_options,
					'return_format' => 'array',
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
		$num_cols               = static::get_val( static::FIELD_NUM_COLS );
		$num_display_limit      = static::get_val( static::FIELD_NUM_DISPLAY_LIMIT );
		$group                  = static::get_val( static::FIELD_GROUP );

		$cards          = array();
		$has_more_items = false;
		$card_type      = 'researchers';

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
			$args           = array(
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
			$cards          = get_posts( $args );
			$has_more_items = count( $cards ) > 50;
		}

		$styles = $desktop_text_alignment;

		$tile_options = array();

		ob_start();
		?>
		<div class="staff js-staff <?php echo esc_attr( $card_type ); ?> <?php echo esc_attr( $styles ); ?> is-<?php echo $display_type; ?> text-<?php echo $desktop_text_alignment; ?> <?php echo ( 'grid' === $display_type ) ? 'staff-col-' . $num_cols : ''; ?>">
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
								<div class="staff__card" data-staff-part="<?php echo $card_ctr < intval( $num_display_limit ) ? 'first' : 'last'; ?>">
									<?php echo Helper\Tile::staff( $card, $key, $tile_options ); ?>
								</div>
								<?php $card_ctr++; ?>
							<?php endforeach; ?>
						</div>
						<?php if ( intval( $num_display_limit ) < count( $cards ) ) : ?>
							<div class="staff__load-more-container mt-px40 md:mt-px22 lg:mt-px50">
								<button class="staff__load-more text-center text-px24 leading-px32 tracking-em005 border-b-px4">
									<span class="pb-px4">Load More</span>
								</button>
							</div>
						<?php endif; ?>
					<?php elseif ( 'list' === $display_type ) : ?>
						<div class="staff__list">
							<?php $card_ctr = 0; foreach ( $cards as $key => $card ) : ?>
								<?php list( $role ) = wp_get_post_terms( $card->ID, Team::TAXONOMY_ROLE ); ?>
								<div class="staff__list-item">
									<strong class="staff__list-item-name"><?php echo esc_html( $card->post_title ); ?></strong>
									<?php if ( ! empty( $role ) ) : ?>
										&nbsp;<span class="staff__list-item-role"><?php echo esc_html( $role->name ); ?></span>
									<?php endif; ?>
								</div>
								<?php $card_ctr++; ?>
							<?php endforeach; ?>
						</div>
						<?php if ( $has_more_items ) : ?>
							<div class="staff__load-more-container mt-px40 md:mt-px22 lg:mt-px50">
								<button class="staff__load-more staff__list-load-more text-center text-px24 leading-px32 tracking-em005 border-b-px4">
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
