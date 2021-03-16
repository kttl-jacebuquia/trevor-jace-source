<?php namespace TrevorWP\Theme\Helper;

use TrevorWP\Exception\Internal;

/**
 * Info Boxes
 */
class Info_Boxes {
	/**
	 * @param array $box
	 *  title: string
	 *  desc: string
	 *
	 * @return string
	 */
	public static function render_box_text( array $box ): string {
		$title = (string) @$box['title'];
		$desc  = (string) @$box['desc'];
		ob_start(); ?>
		<div class="info-box-title">
			<?= esc_html( $title ) ?>
		</div>
		<?php return self::_render_box( 'text', ob_get_clean(), $desc );
	}

	/**
	 * @param array $box
	 *  img: int Img id
	 *  desc: string
	 *
	 * @return string
	 */
	public static function render_box_img( array $box ): string {
		$img_id = (int) @$box['img_id'];
		$desc   = (string) @$box['desc'];
		ob_start(); ?>
		<div class="info-box-img">
			<?= wp_get_attachment_image( $img_id, 'medium' ) ?>
		</div>
		<?php return self::_render_box( 'text', ob_get_clean(), $desc );
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
	protected static function _render_box( string $type, string $top_container, string $desc ) {
		ob_start(); ?>
		<div class="info-box <?= esc_attr( "type-{$type}" ) ?>">
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
	 *  $break_behaviour Required. col|tile|carousel
	 *   col: Returns to column on sm
	 *   tile: Returns to tile on md
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
				'ext_cls' => [],
		], $options );

		$renderer = "render_box_{$options['box_type']}";

		if ( ! method_exists( self::class, $renderer ) ) {
			throw new Internal( 'Unknown type provided.' );
		}

		$cls = array_merge( [
				'info-boxes',
				"box-type-{$options['box_type']}",
				"break-{$options['break_behaviour']}",
		], $options['ext_cls'] );

		ob_start(); ?>
		<div class="<?= esc_attr( implode( ' ', $cls ) ) ?>">
			<h2 class="page-sub-title centered"><?= esc_html( $options['title'] ) ?></h2>
			<?php if ( ! empty( $options['desc'] ) ) { ?>
				<p class="page-sub-title-desc"><?= esc_html( $options['desc'] ) ?></p>
			<?php } ?>

			<div class="info-boxes-container">
				<?php foreach ( $boxes as $box ) {
					echo call_user_func( [ self::class, $renderer ], $box );
				} ?>
			</div>
		</div>
		<?php return ob_get_clean();
	}
}
