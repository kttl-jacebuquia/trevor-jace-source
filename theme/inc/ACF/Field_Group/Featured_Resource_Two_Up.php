<?php namespace TrevorWP\Theme\ACF\Field_Group;

use TrevorWP\CPT\Donate\Partner_Prod;
use TrevorWP\CPT\RC\RC_Object;
use TrevorWP\Theme\Helper;
use TrevorWP\CPT;
use TrevorWP\Theme\ACF\Util\Field_Val_Getter;


use TrevorWP\Theme\ACF\Field\Color;

class Featured_Resource_Two_Up extends A_Field_Group implements I_Block, I_Renderable {
	const FIELD_BG_COLOR   = 'bg_color';
	const FIELD_TEXT_COLOR = 'text_color';
	const FIELD_EYEBROW    = 'eyebrow';
	const FIELD_TITLE      = 'title';
	const FIELD_CARDS      = 'cards';

	/**
	 * @inheritDoc
	 */
	protected static function prepare_register_args(): array {
		$bg_color   = static::gen_field_key( static::FIELD_BG_COLOR );
		$text_color = static::gen_field_key( static::FIELD_TEXT_COLOR );
		$eyebrow    = static::gen_field_key( static::FIELD_EYEBROW );
		$title      = static::gen_field_key( static::FIELD_TITLE );
		$cards      = static::gen_field_key( static::FIELD_CARDS );

		return array(
			'title'  => 'Featured Resource 2-Up Block',
			'fields' => array(
				static::FIELD_BG_COLOR   => Color::gen_args(
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
				static::FIELD_TEXT_COLOR => Color::gen_args(
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
				static::FIELD_EYEBROW    => array(
					'key'   => $eyebrow,
					'name'  => static::FIELD_EYEBROW,
					'label' => 'Eyebrow',
					'type'  => 'text',
				),
				static::FIELD_TITLE      => array(
					'key'   => $title,
					'name'  => static::FIELD_TITLE,
					'label' => 'Title',
					'type'  => 'text',
				),
				static::FIELD_CARDS      => array(
					'key'       => $cards,
					'name'      => static::FIELD_CARDS,
					'label'     => 'Cards',
					'type'      => 'relationship',
					'required'  => 1,
					'post_type' => array_merge( RC_Object::$PUBLIC_POST_TYPES, array( Partner_Prod::POST_TYPE, 'post' ) ),
					'min'       => 2,
					'max'       => 2,
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
				'title'      => 'Featured Resource 2-Up Block',
				'post_types' => array( 'page' ),
			)
		);
	}

	/**
	 * @inheritDoc
	 */
	public static function render( $post = false, array $data = null, array $options = array() ): ?string {
		$text_color = ! empty( static::get_val( static::FIELD_TEXT_COLOR ) ) ? static::get_val( static::FIELD_TEXT_COLOR ) : 'teal-dark';
		$bg_color   = ! empty( static::get_val( static::FIELD_BG_COLOR ) ) ? static::get_val( static::FIELD_BG_COLOR ) : 'white';
		$eyebrow    = static::get_val( static::FIELD_EYEBROW );
		$title      = static::get_val( static::FIELD_TITLE );
		$cards      = static::get_cards();

		$classnames = array(
			'featured-resource-2up',
			'bg-' . $bg_color,
			'text-' . $text_color,
			'text-' . ( ! empty( $eyebrow ) ? 'left' : 'center' ),
		);
		$classnames = implode( ' ', $classnames );

		$options = array();

		ob_start();
		?>
		<div class="<?php echo $classnames; ?>">
			<div class="featured-resource-2up__container">
				<div class="featured-resource-2up__content">
					<?php if ( ! empty( $eyebrow ) ) : ?>
						<span class="featured-resource-2up__eyebrow"><?php echo esc_html( $eyebrow ); ?></span>
					<?php endif; ?>
					<?php if ( ! empty( $title ) ) : ?>
						<h3 class="featured-resource-2up__title"><?php echo esc_html( $title ); ?></h3>
					<?php endif; ?>
					<?php if ( ! empty( $cards ) ) : ?>
						<div class="featured-resource-2up__cards">
							<?php foreach ( $cards as $key => $card ) : ?>
								<div class="featured-resource-2up__card">
									<?php
									$post = get_post( $card );

									switch ( get_post_type( $post ) ) {
										case CPT\Team::POST_TYPE:
											echo Helper\Tile::staff( $post, $key, $options );
											break;
										case CPT\Financial_Report::POST_TYPE:
											echo Helper\Tile::financial_report( $post, $key, $options );
											break;
										case CPT\Event::POST_TYPE:
											echo Helper\Tile::event( $post, $key, $options );
											break;
										case CPT\Post::POST_TYPE:
										case CPT\RC\Post::POST_TYPE:
											echo Helper\Card::post( $post, $key, $options );
											break;
										default:
											echo Helper\Tile::post( $post, $key, $options );
									}
									?>
								</div>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	public static function get_cards(): array {
		$cards = static::get_val( static::FIELD_CARDS );

		if ( empty( $cards ) ) {
			return array();
		}

		// Filter to show only products that are within the current date
		$filtered_entries  = array();
		$current_date_unix = time();

		foreach ( $cards as $entry ) {
			// Filter card only when it is a Product
			if ( Partner_Prod::POST_TYPE !== $entry->post_type ) {
				$filtered_entries[] = $entry;
				continue;
			}

			// Get product's start and end dates to determine if will show in FE
			$entry_val  = new Field_Val_Getter( Product::class, $entry );
			$start_date = $entry_val->get( Product::FIELD_PRODUCT_START_DATE );
			$end_date   = $entry_val->get( Product::FIELD_PRODUCT_END_DATE );

			$start_unix = strtotime( $start_date );
			$end_unix   = strtotime( $end_date );

			// If start and end dates range is within the current date,
			// include this entry to the rendered cards
			if ( $start_unix <= $current_date_unix && $end_unix >= $current_date_unix ) {
				$filtered_entries[] = $entry;
			}
		}

		return $filtered_entries;
	}

	/**
	 * @inheritDoc
	 */
	public static function render_block( $block, $content = '', $is_preview = false, $post_id = 0 ): void {
		echo static::render( $post_id, null, compact( 'is_preview' ) );
	}
}
