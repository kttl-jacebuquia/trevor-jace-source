<?php namespace TrevorWP\Theme\Helper;


class Tile {
	public static function post( $post, array $options = [] ): string {
		$data = [];

		// TODO: Produce data from the post
		return self::custom( $data );
	}

	public static function custom( array $data ): string {
		# class
		$cls = [ 'tile' ];
		if ( ! empty( $data['class'] ) ) {
			$cls = array_merge( $cls, $data['class'] );
		}

		ob_start();
		?>
		<div class="<?= implode( ' ', $cls ) ?>">
			<div class="tile-inner">
				<div class="tile-title"><?= $data['title'] ?></div>
				<?php if ( ! empty( $data['desc'] ) ) { ?>
					<div class="tile-desc"><?= $data['desc'] ?></div>
				<?php } ?>

				<?php if ( ! empty( $data['cta_txt'] ) ) { ?>
					<div class="tile-cta-wrap">
						<a href="<?= @$data['cta_url'] ?>" class="tile-cta">
							<span><?= $data['cta_txt'] ?></span>
						</a>
					</div>
				<?php } ?>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}
}
