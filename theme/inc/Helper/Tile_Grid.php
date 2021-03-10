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
		$options['tileMethod'] = 'post';

		return self::custom( $posts, $options );
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
				'class',
				'tileClass',
		], null ), [
				'smAccordion' => false,
				'tileMethod'  => 'custom',
		], $options );

		# class
		$cls = [ 'tile-grid' ];
		if ( ! empty( $options['class'] ) ) {
			$cls = array_merge( $cls, $options['class'] );
		}

		$tile_options = [ 'accordion' => false, 'class' => $options['tileClass'] ];

		if ( $options['smAccordion'] ) {
			$tile_options['accordion'] = true;

			$cls[] = 'sm-accordion';
		}

		ob_start(); ?>
		<div class="<?= implode( ' ', $cls ) ?>">
			<div class="tile-grid-inner">
				<?php if ( ! empty( $options['title'] ) ) { ?>
					<div class="tile-grid-header">
						<h2 class="tile-grid-title"><?= $options['title'] ?></h2>
						<?php if ( ! empty( $options['desc'] ) ) { ?>
							<p class="tile-grid-desc"><?= $options['desc'] ?></p>
						<?php } ?>
					</div>
				<?php } ?>
				<div class="tile-grid-container">
					<?php foreach ( $data as $key => $entry ) {
						$tile_method = $options['tileMethod'];
						echo Tile::$tile_method( $entry, $key, $tile_options );
					} ?>
				</div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}
}
