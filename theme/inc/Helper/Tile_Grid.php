<?php namespace TrevorWP\Theme\Helper;


class Tile_Grid {
	public static function posts( array $posts, array $options = [] ): string {
		$options = array_merge( array_fill_keys( [
				'id', // Main wrapper DOM id
				'title',
				'desc',
				'class'
		], null ), $options );

		# ID check
		$id = &$options['id'];
		if ( empty( $id ) ) {
			$id = uniqid( 'tile-grid-' );
		}

		$ext_cls = [ 'post-grid' ];

		ob_start(); ?>
		<div class="container mx-auto mt-5 mb-20 lg:mb-48 <?= implode( ' ', $ext_cls ) ?>"
		<?php foreach ( $posts as $post ) {
			echo Tile::post( $post, [] );
		} ?>
		</div>
		<?php
		return ob_get_clean();
	}
}
