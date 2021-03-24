<?php namespace TrevorWP\Theme\Helper;

use TrevorWP\Exception\Internal;

/**
 * Info Boxes
 */
class Info_Boxes {
	/* Box Type */
	const BOX_TYPE_IMG = 'img';
	const BOX_TYPE_TEXT = 'text';
	const BOX_TYPE_BOTH = 'both';

	/* Break Behaviour */
	const BREAK_BEHAVIOUR_GRID_1_2_2 = 'grid-1-2-2';
	const BREAK_BEHAVIOUR_GRID_1_2_3 = 'grid-1-2-3';
	const BREAK_BEHAVIOUR_GRID_1_2_4 = 'grid-1-2-4';
	const BREAK_BEHAVIOUR_GRID_2_2_4 = 'grid-2-2-4';
	const BREAK_BEHAVIOUR_CAROUSEL = 'carousel';

	/**
	 * @param array $box
	 * @param array $options
	 *
	 * @return string
	 */
	public static function render_box_text( array $box, array $options = [] ): string {
		$text = (string) @$box['txt'];
		$cls  = array_merge( [ 'info-box-text' ], (array) @$options['box_text_cls'] );
		ob_start(); ?>
		<div class="<?= esc_attr( implode( ' ', $cls ) ) ?>">
			<?= esc_html( $text ) ?>
		</div>
		<?php return ob_get_clean();
	}

	/**
	 * @param array $box
	 * @param array $options
	 *
	 * @return string
	 */
	public static function render_box_img( array $box, array $options = [] ): string {
		$img_id = empty( $box['img'] ) ? 0 : (int) @$box['img']['id'];
		$cls    = array_merge( [ 'info-box-img' ], (array) @$options['box_img_cls'] );
		ob_start(); ?>
		<div class="<?= esc_attr( implode( ' ', $cls ) ) ?>">
			<?= wp_get_attachment_image( $img_id, 'medium' ) ?>
		</div>
		<?php return ob_get_clean();
	}

	/**
	 * @param array $box
	 * @param array $options
	 *
	 * @return string
	 */
	public static function render_box_both( array $box, array $options = [] ) {
		return static::render_box_img( $box, $options ) . static::render_box_text( $box, $options );
	}

	/**
	 * @param array $boxes
	 * @param array $options
	 *  $box_type string Required. text|img
	 *   text: A big text with description.
	 *   img: An image with description.
	 *  $break_behaviour Required. carousel|grid
	 *   grid-(sm-col)-(md-col)-(xl-col):
	 *   carousel: Returns to carousel on md
	 *
	 * @return string
	 * @throws Internal
	 */
	public static function render( array $boxes, array $options ): string {
		$options = array_merge( array_fill_keys( [
				'title',
				'desc',
				'box_type',
				'break_behaviour',
		], null ), [
				'ext_cls'       => [],
				'container_cls' => [],
				'box_cls'       => [],
				'box_top_cls'   => [],
				'box_desc_cls'  => [],
		], $options );

		# Check the box renderer
		if ( ! method_exists( self::class, $renderer = "render_box_{$options['box_type']}" ) ) {
			throw new Internal( 'Unknown type provided.' );
		}

		# Main classes
		$cls = array_merge( [
				'info-boxes',
				"box-type-{$options['box_type']}",
				"break-{$options['break_behaviour']}",
		], $options['ext_cls'] );

		# Container classes
		$container_cls = array_merge( [
				'info-boxes-container',
		], $options['container_cls'] );

		# Carousel
		if ( $is_carousel = $options['break_behaviour'] == 'carousel' ) { // if carousel
			$options['box_cls'][] = 'swiper-slide';
			$container_cls[]      = 'swiper-wrapper';
			$cls[]                = 'swiper-container';
		} else {
			$cls[] = 'break-grid';
		}

		$box_cls = array_merge( [
				'info-box',
				"type-{$options['box_type']}",
		], (array) @$options['box_cls'] );

		$desc_cls = array_merge( [ 'info-box-desc' ], (array) @$options['box_desc_cls'] );

		ob_start(); ?>
		<div class="<?= esc_attr( implode( ' ', $cls ) ) ?>">
			<h2 class="info-boxes-title"><?= esc_html( $options['title'] ) ?></h2>
			<?php if ( ! empty( $options['desc'] ) ) { ?>
				<p class="info-boxes-desc"><?= esc_html( $options['desc'] ) ?></p>
			<?php } ?>

			<div class="<?= esc_attr( implode( ' ', $container_cls ) ) ?>">
				<?php foreach ( $boxes as $box ) { ?>
					<div class="<?= esc_attr( implode( ' ', $box_cls ) ) ?>">
						<div class="info-box-top">
							<?= call_user_func( [ self::class, $renderer ], $box, [
									'box_cls'      => $options['box_cls'],
									'box_img_cls'  => $options['box_img_cls'],
									'box_text_cls' => $options['box_text_cls'],
									'box_desc_cls' => $options['box_desc_cls'],
							] ); ?>
						</div>
						<p class="<?= esc_attr( implode( ' ', $desc_cls ) ) ?>"><?= esc_html( @$box['desc'] ) ?></p>
					</div>
				<?php } ?>
			</div>

			<?php if ( $is_carousel ) { ?>
				<div class="swiper-pagination"></div>
			<?php } ?>
		</div>
		<?php return ob_get_clean();
	}
}
