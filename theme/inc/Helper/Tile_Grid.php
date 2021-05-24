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
	public static function posts( array $posts, array $options = array() ): string {
		$options['tileMethod'] = 'post';

		return self::custom( $posts, $options );
	}

	public static function staff( array $posts, array $options = array() ): string {
		$options['tileMethod'] = 'staff';

		return self::custom( $posts, $options );
	}

	public static function financial_report( array $posts, array $options = array() ): string {
		$options['tileMethod'] = 'financial_report';

		return self::custom( $posts, $options );
	}

	public static function research( array $posts, array $options = array() ): string {
		$options['tileMethod'] = 'research';

		return self::custom( $posts, $options );
	}

	public static function event( array $posts, array $options = array() ): string {
		$options['tileMethod'] = 'event';

		return self::custom( $posts, $options );
	}

	/**
	 * @param array $data
	 * @param array $options
	 *
	 * @return string
	 */
	public static function custom( array $data, array $options = array() ): string {
		$options = array_merge(
			array_fill_keys(
				array(
					'title',
					'desc',
					'class',
					'gridClass',
					'tileClass',
				),
				null
			),
			array(
				'smAccordion' => false,
				'tileMethod'  => 'custom',
			),
			$options
		);

		# class
		$cls = array( 'tile-grid' );
		if ( ! empty( $options['class'] ) ) {
			$cls = array_merge( $options['class'], $cls );
		}

		# gridClass
		$grid_class = array( 'tile-grid-container' );
		if ( ! empty( $options['gridClass'] ) ) {
			$grid_class = array_merge( $grid_class, $options['gridClass'] );
		}

		$tile_options = array(
			'accordion' => false,
			'class'     => $options['tileClass'],
		);

		if ( $options['smAccordion'] ) {
			$tile_options['accordion'] = true;

			$cls[] = 'sm-accordion';
		}

		ob_start(); ?>
		<div class="<?php echo implode( ' ', $cls ); ?>">
			<div class="tile-grid-inner">
				<?php if ( ! empty( $options['title'] ) ) { ?>
					<div class="tile-grid-header">
						<h2 class="page-sub-title centered <?php echo ( ! empty( $options['title_cls'] ) ? $options['title_cls'] : '' ); ?>"><?php echo $options['title']; ?></h2>
						<?php if ( ! empty( $options['desc'] ) ) { ?>
							<p class="page-sub-title-desc centered"><?php echo $options['desc']; ?></p>
						<?php } ?>
					</div>
				<?php } ?>
				<div class="<?php echo implode( ' ', $grid_class ); ?>">
					<?php
					foreach ( $data as $key => $entry ) {
						$tile_method = $options['tileMethod'];

						$tile_options['attr'] = ! empty( $entry['tile_attr'] ) ? $entry['tile_attr'] : null;

						if ( is_array( $entry ) ) {
							$tile_options['cta_cls'] = ( isset( $entry['cta_cls'] ) ) ? $entry['cta_cls'] : array();
							$tile_options['class']   = ( isset( $entry['tile_cls'] ) ) ? $entry['tile_cls'] : array();
						}

						echo Tile::$tile_method( $entry, $key, $tile_options );
					}
					?>
				</div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}
}
