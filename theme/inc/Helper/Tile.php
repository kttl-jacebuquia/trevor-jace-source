<?php namespace TrevorWP\Theme\Helper;


use TrevorWP\CPT;

class Tile {
	public static function post( \WP_Post $post, array $options = [] ): string {
		$data = [
				'title'   => $post->post_title,
				'desc'    => $post->post_excerpt,
				'cta_txt' => 'Read More',
				'cta_url' => get_permalink( $post )
		];

		if ( $post->post_type == CPT\Get_Involved\Bill::POST_TYPE ) {
			$data['title_top'] = \TrevorWP\Meta\Post::get_bill_id( $post->ID );
		}

		return self::custom( $data, $options );
	}

	public static function accordion( array $data, array $options = [] ): string {
		$options = array_merge( array_fill_keys( [
				'class'
		], null ), $options );

		# class
		$cls = [ 'tile-accordion-item' ];
		if ( ! empty( $options['class'] ) ) {
			$cls = array_merge( $cls, $options['class'] );
		}

		ob_start();
		?>
		<div class="<?= implode( ' ', $cls ) ?>">
			<h2 class="accordion-header" id="headingOne">
				<button class="accordion-button text-px20 leading-px26 py-8 px-7 w-full text-left border-b border-blue_green border-opacity-20 flex justify-between font-semibold md:pt-9 md:mb-3 md:border-0 md:pb-0 md:text-px24 md:leading-px28"
						type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true"
						aria-controls="collapseOne">
					<?= $data['title'] ?>
				</button>
			</h2>
			<div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
				 data-bs-parent="#accordionExample">
				<div class="accordion-body px-7 pt-5 pb-9 bg-gray-light md:bg-transparent md:pb-36">
					<p class="text-px18 leading-px28 mb-6 md:text-px16 md:leading-px24">
						<?= $data['desc'] ?>
					</p>
					<div class="tile-cta-wrap">
						<a href="<?= @$data['cta_url'] ?>"
						   class="tile-cta font-bold text-px18 leading-px28 border-b-2 border-teal-dark">
							<span><?= $data['cta_txt'] ?></span>
						</a>
					</div>
				</div>
			</div>
		</div>
		<?php return ob_get_clean();
	}

	public static function custom( array $data, array $options = [] ): string {
		if ( $options['accordion'] ) {
			return self::accordion( $data, $options );
		}

		$options = array_merge( array_fill_keys( [
				'class'
		], null ), $options );

		# class
		$cls = [ 'tile' ];
		if ( ! empty( $options['class'] ) ) {
			$cls = array_merge( $cls, $options['class'] );
		}

		ob_start();
		?>
		<div class="<?= implode( ' ', $cls ) ?>">
			<div class="tile-inner">
				<?php if ( ! empty( $data['title_top'] ) ) { ?>
					<div class="tile-title-top"><?= $data['title_top'] ?></div>
				<?php } ?>
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
