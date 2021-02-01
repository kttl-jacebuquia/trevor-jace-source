<?php namespace TrevorWP\Theme\Helper;


use TrevorWP\CPT;
use TrevorWP\Util\Tools;

class Tile {
	/**
	 * @param \WP_Post $post
	 * @param array $options
	 *
	 * @return string
	 */
	public static function post( \WP_Post $post, int $key, array $options = [] ): string {
		$data = [
				'title'   => $post->post_title,
				'desc'    => $post->post_excerpt,
				'cta_txt' => 'Read More',
				'cta_url' => get_permalink( $post )
		];

		if ( $post->post_type == CPT\Get_Involved\Bill::POST_TYPE ) {
			$data['title_top'] = \TrevorWP\Meta\Post::get_bill_id( $post->ID );
		}

		if ( in_array( $post->post_type, [ CPT\Get_Involved\Bill::POST_TYPE, CPT\Get_Involved\Letter::POST_TYPE ] ) ) {
			$id            = uniqid( 'post-' );
			$options['id'] = $id;

			echo ( new \TrevorWP\Theme\Helper\Modal( CPT\Get_Involved\Bill::render_modal( $post ), [
					'target' => "#{$id} a",
					'id'     => "{$id}-content"
			] ) )->render();

			add_action( 'wp_footer', function () use ( $id ) {
				?>
				<script>jQuery(function () {
						trevorWP.features.sharingMore(
								document.querySelector('#<?= $id ?>-content .post-share-more-btn'),
								document.querySelector('#<?= $id ?>-content .post-share-more-content'),
								{appendTo: document.querySelector('#<?= $id ?>-content')}
						);
					})</script>
				<?php
			}, 10, 0 );
		}

		return self::custom( $data, $key, $options );
	}

	/**
	 * @param array $data
	 * @param array $options
	 *
	 * @return string
	 */
	public static function accordion( array $data, int $key, array $options = [] ): string {
		$options = array_merge( array_fill_keys( [
				'class'
		], null ), $options );

		# class
		$cls = [ 'tile-accordion-item', 'relative' ];
		if ( ! empty( $options['class'] ) ) {
			$cls = array_merge( $cls, $options['class'] );
		}

		ob_start();
		?>
		<div class="js-accordion <?= implode( ' ', $cls ) ?>">
			<h2 class="accordion-header" id="heading-<?= $key ?>">
				<button class="accordion-button text-px20 leading-px26 py-8 px-7 w-full text-left border-b border-blue_green border-opacity-20 flex justify-between items-center font-semibold md:pt-9 md:mb-3 md:border-0 md:pb-0 md:text-px24 md:leading-px28" type="button" aria-expanded="false" aria-controls="collapse-<?= $key ?>">
					<?= $data['title'] ?>
				</button>
			</h2>
			<div id="collapse-<?= $key ?>" class="accordion-collapse collapse" aria-labelledby="heading-<?= $key ?>">
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

	/**
	 * @param array $data
	 * @param array $options
	 *
	 * @return string
	 */
	public static function custom( array $data, int $key, array $options = [] ): string {
		if ( $options['accordion'] ) {
			return self::accordion( $data, $key, $options );
		}

		$options = array_merge( array_fill_keys( [
				'class',
				'attr'
		], null ), $options );

		# class
		$cls = [ 'tile', 'relative' ];
		if ( ! empty( $options['class'] ) ) {
			$cls = array_merge( $cls, $options['class'] );
		}

		$attr          = (array) $options['attr'];
		$attr['class'] = implode( ' ', $cls );
		if ( ! empty( $options['id'] ) ) {
			$attr['id'] = $options['id'];
		}

		ob_start();
		?>
		<div <?= Tools::flat_attr( $attr ) ?>>
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
						<a href="<?= @$data['cta_url'] ?>" class="tile-cta stretched-link">
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
