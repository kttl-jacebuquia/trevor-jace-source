<?php namespace TrevorWP\Theme\Helper;

use TrevorWP\Exception\Internal;

/**
 * Info Boxes
 */
class Info_Boxes {
	/* Box Type */
	const BOX_TYPE_IMG = 'img';
	const BOX_TYPE_TEXT = 'text';

	/* Break Behaviour */
	const BREAK_BEHAVIOUR_GRID_1_2_2 = 'grid-1-2-2';
	const BREAK_BEHAVIOUR_GRID_1_2_3 = 'grid-1-2-3';
	const BREAK_BEHAVIOUR_GRID_1_2_4 = 'grid-1-2-4';
	const BREAK_BEHAVIOUR_GRID_2_2_4 = 'grid-2-2-4';
	const BREAK_BEHAVIOUR_CAROUSEL = 'carousel';

	/**
	 * @param array $box
	 *  title: string
	 *  desc: string
	 * @param array $options
	 *
	 * @return string
	 */
	public static function render_box_text( array $box, array $options = [] ): string {
		$text = (string) @$box['text'];
		$desc = (string) @$box['desc'];
		$cls  = array_merge( [ 'info-box-text' ], (array) @$options['box_text_cls'] );
		ob_start(); ?>
		<div class="<?= esc_attr( implode( ' ', $cls ) ) ?>">
			<?= esc_html( $text ) ?>
		</div>
		<?php return self::_render_box( 'text', ob_get_clean(), $desc, $options );
	}

	/**
	 * @param array $box
	 *  title: string
	 *  desc: string
	 * @param array $options
	 *
	 * @return string
	 */
	public static function render_box_img( array $box, array $options = [] ): string {
		$img_id = empty( $box['img'] ) ? 0 : (int) @$box['img']['id'];
		$desc   = (string) @$box['desc'];
		$cls    = array_merge( [ 'info-box-img' ], (array) @$options['box_text_cls'] );
		ob_start(); ?>
		<div class="<?= esc_attr( implode( ' ', $cls ) ) ?>">
			<?= wp_get_attachment_image( $img_id, 'medium' ) ?>
		</div>
		<?php return self::_render_box( 'text', ob_get_clean(), $desc, $options );
	}

	/**
	 * Renders a single box.
	 *
	 * @param string $type img|text
	 * @param string $top_container Rendered top part.
	 * @param string $desc Description text.
	 *
	 * @return false|string
	 */
	protected static function _render_box( string $type, string $top_container, string $desc, array $options = [] ) {
		$cls = array_merge( [
				'info-box',
				"type-{$type}",
		], (array) @$options['box_cls'] );

		ob_start(); ?>
		<div class="<?= esc_attr( implode( ' ', $cls ) ) ?>">
			<div class="info-box-top">
				<?= $top_container ?>
			</div>
			<p class="info-box-desc"><?= esc_html( $desc ) ?></p>
		</div>
		<?php return ob_get_clean();
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

		ob_start(); ?>
		<div class="<?= esc_attr( implode( ' ', $cls ) ) ?>">
			<h2 class="info-boxes-title"><?= esc_html( $options['title'] ) ?></h2>
			<?php if ( ! empty( $options['desc'] ) ) { ?>
				<p class="info-boxes-desc"><?= esc_html( $options['desc'] ) ?></p>
			<?php } ?>

			<div class="<?= esc_attr( implode( ' ', $container_cls ) ) ?>">
				<?php foreach ( $boxes as $box ) {
					echo call_user_func( [ self::class, $renderer ], $box, [
							'box_cls'      => $options['box_cls'],
							'box_text_cls' => $options['box_text_cls'],
							'box_desc_cls' => $options['box_desc_cls'],
					] );
				} ?>
			</div>

			<?php if ( $is_carousel ) { ?>
				<div class="swiper-pagination"></div>
			<?php } ?>
		</div>
		<?php return ob_get_clean();
	}
}
