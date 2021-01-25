<?php namespace TrevorWP\Theme\Helper;

/**
 * Tile Grid Helper
 */
class Tile_Grid {
	/**
	 * @param array $posts
	 * @param array $options
	 *
	 * @return string
	 */
	public static function posts( array $posts, array $options = [] ): string {

	}

	/**
	 * @param array $data
	 * @param array $options
	 *
	 * @return string
	 */
	public static function custom( array $data, array $options = [] ): string {
		$options = array_merge( array_fill_keys( [
				'title',
				'desc',
				'class'
		], null ), $options );

		# class
		$cls = [ 'tile-grid' ];
		if ( ! empty( $data['class'] ) ) {
			$cls = array_merge( $cls, $data['class'] );
		}

		ob_start(); ?>
		<div class="<?= implode( ' ', $cls ) ?>">
			<div class="tile-grid-inner">
				<div class="tile-grid-header">
					<h2 class="tile-grid-title"><?= $options['title'] ?></h2>
					<?php if ( ! empty( $options['desc'] ) ) { ?>
						<p class="tile-grid-desc"><?= $options['desc'] ?></p>
					<?php } ?>
				</div>
				<div class="tile-grid-container">
					<?php foreach ( $data as $entry ) {
						echo Tile::custom( $entry, [] );
					} ?>
				</div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}
}
