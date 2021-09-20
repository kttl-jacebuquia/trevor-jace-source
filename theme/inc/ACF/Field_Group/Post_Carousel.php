<?php namespace TrevorWP\Theme\ACF\Field_Group;

use TrevorWP\Theme\ACF\Util\Field_Val_Getter;
use TrevorWP\Theme\Helper;
use TrevorWP\Util\Tools;
use TrevorWP\CPT;

class Post_Carousel extends Post_Grid {
	const FIELD_CTA           = 'cta';
	const FIELD_BLOCK_STYLES  = 'block_styles';
	const FIELD_HEADING_ALIGN = 'heading_align';

	/** @inheritdoc */
	public static function get_block_args(): array {
		return array_merge(
			parent::get_block_args(),
			array(
				'title'      => 'Article Card Carousel',
				'category'   => 'common',
				'icon'       => 'book-alt',
				'post_types' => array( 'page' ),
			)
		);
	}

	/** @inheritdoc */
	protected static function prepare_register_args(): array {
		$cta           = static::gen_field_key( static::FIELD_CTA );
		$heading_align = static::gen_field_key( static::FIELD_HEADING_ALIGN );
		$args          = parent::prepare_register_args();
		$fields        = $args['fields'];

		// Remove unnecessary fields
		unset( $fields[ static::FIELD_NUM_COLS ] );
		unset( $fields[ static::FIELD_NUM_DISPLAY_LIMIT ] );
		unset( $fields[ static::FIELD_PAGINATION_STYLE ] );
		unset( $fields[ static::FIELD_PLACEHOLDER_IMG ] );
		unset( $fields[ static::FIELD_SHOW_EMPTY ] );
		unset( $fields[ static::FIELD_EMPTY_MESSAGE ] );

		// With additional fields
		$updated_fields = array();

		// Loop through each field so we can inject a field at specific order,
		// or edit specific fields
		foreach ( $fields as $key => $field ) {
			$updated_fields[ $key ] = $field;
			switch ( $key ) {
				case static::FIELD_DESCRIPTION:
					// Inject CTA right after description
					$updated_fields[ static::FIELD_CTA ] = Button::clone(
						array(
							'key'           => $cta,
							'name'          => static::FIELD_CTA,
							'label'         => 'CTA',
							'return_format' => 'array',
							'display'       => 'group',
							'layout'        => 'block',
						)
					);
					break;
				case static::FIELD_SOURCE:
					// Remove "Custom" Option from Source Field
					unset( $field['choices']['custom'] );
					$updated_fields[ static::FIELD_SOURCE ] = $field;
					break;
				// Add heading aligment option under Styles Tab
				case static::FIELD_BLOCK_STYLES:
					$updated_fields[ static::FIELD_HEADING_ALIGN ] = array(
						'key'           => $heading_align,
						'name'          => static::FIELD_HEADING_ALIGN,
						'label'         => 'Heading Alignment (Desktop)',
						'type'          => 'button_group',
						'default_value' => 'left',
						'choices'       => array(
							'left'   => 'Left',
							'center' => 'Center',
						),
					);
			}
		}

		$updated_fields[ static::FIELD_SOURCE ]['choices'][ static::SOURCE_TOP_INDIVIDUALS ] = 'Top Individuals';
		$updated_fields[ static::FIELD_SOURCE ]['choices'][ static::SOURCE_TOP_TEAMS ]       = 'Top Teams';

		$args['fields'] = $updated_fields;

		return $args;
	}

	/** @inheritdoc */
	public static function render( $post = false, array $data = null, array $options = array() ): ?string {
		$val             = new Field_Val_Getter( static::class, $post, $data );
		$block_styles    = static::get_val( static::FIELD_BLOCK_STYLES );
		$source          = $val->get( static::FIELD_SOURCE );
		$placeholder_img = $val->get( static::FIELD_PLACEHOLDER_IMG );
		$post_types      = ( ! empty( $val->get( static::FIELD_QUERY_PTS ) ) ) ? $val->get( static::FIELD_QUERY_PTS ) : array();
		$title           = $val->get( static::FIELD_HEADING );
		$description     = $val->get( static::FIELD_DESCRIPTION );
		$cta             = static::get_val( static::FIELD_CTA );
		$heading_align   = static::get_val( static::FIELD_HEADING_ALIGN );
		list(
			$bg_color,
			$text_color
		)                = array_values( $block_styles );

		$wrapper_attrs = DOM_Attr::get_attrs_of(
			$val->get( static::FIELD_WRAPPER_ATTR ),
			array(
				'post-carousel-container',
				'block-spacer',
				'bg-' . $bg_color,
				'text-' . $text_color,
			),
		);

		$options = array(
			'title'    => $title,
			'subtitle' => $description,
		);

		$options['class'][] = 'post-carousel--heading-desktop-' . $heading_align;

		if ( static::SOURCE_TOP_INDIVIDUALS === $source || static::SOURCE_TOP_TEAMS === $source ) {
			$posts              = static::_get_fundraisers( $val );
			$options['class'][] = ( static::SOURCE_TOP_INDIVIDUALS === $source ) ? 'top-individuals' : 'top-teams';
		} else {
			$posts = static::_get_posts( $val );

			// Manually get each post's post type if not able to get from above
			if ( empty( $post_types ) && ! empty( $posts ) ) {
				foreach ( $posts as $post ) {
					$post_types[] = get_post_type( $post );
				}
			}

			$class     = array_merge(
				array( 'mt-12', 'mb-0' ),
				$options['class'],
			);
			$title_cls = array();

			if ( in_array( CPT\Team::POST_TYPE, $post_types, true ) && 'query' === $source ) {
				$class[] = 'post-carousel--staff';
			} elseif ( in_array( CPT\Event::POST_TYPE, $post_types, true ) ) {
				$class[]     = 'post-carousel--event mb-px80';
				$title_cls[] = 'text-center';
			} elseif ( in_array( 'attachment', $post_types, true ) ) {
				$class[] = 'post-carousel--attachment';
			}

			$options['class']      = $class;
			$options['title_cls']  = implode( ' ', $title_cls );
			$options['post_types'] = $post_types;
		}

		if ( ! empty( $placeholder_img ) ) {
			$options['card_options'] = array( 'placeholder_image' => $placeholder_img );
		}

		$options['class'] = implode( ' ', $options['class'] );

		ob_start(); ?>
		<div <?php echo Tools::flat_attr( $wrapper_attrs ); ?>>
			<?php if ( static::SOURCE_TOP_INDIVIDUALS === $source || static::SOURCE_TOP_TEAMS === $source ) : ?>
				<?php echo Helper\Carousel::fundraisers( $posts, $options ); ?>
			<?php else : ?>
				<?php echo Helper\Carousel::posts( $posts, $options ); ?>
			<?php endif; ?>
			<?php if ( ! empty( $cta ) ) : ?>
				<div class="post-carousel__cta-wrap">
					<?php echo Button::render( false, $cta ); ?>
				</div>
			<?php endif; ?>
		</div>
		<?php
		return ob_get_clean();
	}
}
