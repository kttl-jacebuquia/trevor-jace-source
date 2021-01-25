<?php namespace TrevorWP\Theme\Helper;

/**
 * Carousel Helper
 */
class Carousel {
	/**
	 * Posts carousel.
	 *
	 * @param array $posts
	 * @param array $options
	 *
	 * @return string
	 */
	public static function posts( array $posts, array $options = [] ): string {
		$options = array_merge( array_fill_keys( [
				'id', // Main wrapper DOM id
				'title',
				'subtitle',
				'class'
		], null ), [
				'title_cls'        => '',
				'print_js'         => true,
				'hide_cat_eyebrow' => false,
				'swiper'           => [], // swiper options
		], $options );

		# ID check
		$id = &$options['id'];
		if ( empty( $id ) ) {
			$id = uniqid( 'posts-carousel-' );
		}

		if ( $options['print_js'] ) {
			add_action( 'wp_footer', function () use ( $options ) {
				self::print_js( "#{$options['id']} .carousel-full-width-wrap", array_merge( $options['swiper'], [
						'onlyMd' => $options['onlyMd'] ?? null,
				] ) );
			}, PHP_INT_MAX >> 2, 0 );
		}

		# Extra Classes
		$ext_cls = [
				'post-carousel',
				( "card-count-" . count( $posts ) ),
		];
		if ( ! empty( $options['onlyMd'] ) ) {
			$ext_cls[] = 'only-md';
		}

		ob_start(); ?>
		<div class="carousel-wrap <?= implode( ' ', $ext_cls ) ?>"
			 id="<?= esc_attr( $id ) ?>">
			<h2 class="carousel-title <?= $options['title_cls']; ?>"><?= $options['title'] ?></h2>
			<p class="carousel-subtitle <?= $options['title_cls']; ?>"><?= esc_html( $options['subtitle'] ) ?></p>

			<div class="carousel-full-width-wrap">
				<div class="carousel-container">
					<div class="swiper-wrapper">
						<?php foreach ( $posts as $post ) { ?>
							<div class="swiper-slide">
								<?= Card::post( $post, [
										'hide_cat_eyebrow' => $options['hide_cat_eyebrow']
								] ) ?>
							</div>
						<?php } ?>
					</div>
				</div>
				<div class="swiper-button-prev">
					<i class="trevor-ti-arrow-left"></i>
				</div>
				<div class="swiper-button-next">
					<i class="trevor-ti-arrow-right"></i>
				</div>
				<div class="swiper-pagination"></div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Big image carousel.
	 *
	 * @param array $data
	 * @param array $options
	 *
	 * @return string
	 */
	public static function big_img( array $data, array $options = [] ): string {
		$options = array_merge( array_fill_keys( [
				'id', // Main wrapper DOM id
				'title',
				'subtitle',
				'class'
		], null ), [
				'title_cls' => '',
				'print_js'  => true,
				'swiper'    => [], // swiper options
		], $options );

		# ID check
		$id = &$options['id'];
		if ( empty( $id ) ) {
			$id = uniqid( 'big-img-carousel-' );
		}

		if ( $options['print_js'] ) {
			add_action( 'wp_footer', function () use ( $options ) {
				self::print_js( "#{$options['id']}", array_merge( [], $options['swiper'] ) );
			}, PHP_INT_MAX >> 2, 0 );
		}

		# Extra Classes
		$ext_cls = [
				'big-img-carousel',
				( "card-count-" . count( $data ) ),
		];

		ob_start(); ?>
		<div class="carousel-wrap <?= implode( ' ', $ext_cls ) ?>"
			 id="<?= esc_attr( $id ) ?>">
			<h2 class="carousel-title <?= $options['title_cls']; ?>"><?= $options['title'] ?></h2>
			<p class="carousel-subtitle <?= $options['title_cls']; ?>"><?= esc_html( $options['subtitle'] ) ?></p>

			<div class="carousel-full-width-wrap">
				<div class="container carousel-container">
					<div class="swiper-wrapper">
						<?php

						foreach ( $data as $entry ) {
							if ( empty( $entry['img'] ) || empty( $entry['img']['id'] ) || ! wp_attachment_is_image( $entry['img']['id'] ) ) {
								continue;
							}
							?>
							<div class="swiper-slide">
								<figure>
									<div class="img-wrap">
										<?= wp_get_attachment_image( $entry['img']['id'], 'large' ) ?>
									</div>
									<?php if ( ! empty( $entry['caption'] ) ) { ?>
										<figcaption><?= $entry['caption'] ?></figcaption>
									<?php } ?>
								</figure>
							</div>
						<?php } ?>
					</div>
				</div>
				<div class="swiper-button-prev">
					<i class="trevor-ti-arrow-left"></i>
				</div>
				<div class="swiper-button-next">
					<i class="trevor-ti-arrow-right"></i>
				</div>
				<div class="swiper-pagination"></div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * @param string $base_selector
	 * @param array $options
	 */
	public static function print_js( string $base_selector, array $options = [] ): void {
		$options = array_merge( [
				'slidesPerView' => 1,
				'spaceBetween'  => 20,
				'observer'      => true,
				'pagination'    => [
						'el'        => "{$base_selector} .swiper-pagination",
						'clickable' => true,
				],
				'navigation'    => [
						'nextEl' => "{$base_selector} .swiper-button-next",
						'prevEl' => "{$base_selector} .swiper-button-prev",
				],
				'on'            => new \stdClass()
		], $options );
		?>
		<script><?php /* TODO: Instead of printing this for each carousel, create a controller & use that. */ ?>
			(function () {
				var swiper;
				var options = <?= json_encode( $options )?>;
				options.on.init = function () {
					document.querySelectorAll('<?= esc_js( $base_selector )?> .card-post').forEach(elem => {
						elem.tagBoxEllipsis && elem.tagBoxEllipsis.calc();
					});
				}

				function init() {
					if (!swiper || swiper.destroyed) {
						swiper = new trevorWP.vendors.Swiper('<?= esc_js( $base_selector )?> .carousel-container', options);
					}
				}

				<?php if(! empty( $options['onlyMd'] )){ ?>
				trevorWP.matchMedia.carouselWith3Cards(init, function () {
					swiper && swiper.destroy();
				});
				<?php }else{ ?>
				init();
				<?php } ?>
			})();
		</script>
		<?php
	}
}
