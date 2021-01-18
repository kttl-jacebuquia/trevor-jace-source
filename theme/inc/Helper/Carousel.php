<?php namespace TrevorWP\Theme\Helper;

/**
 * Carousel Helper
 */
class Carousel {
	/**
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
		<div class="container mx-auto mt-5 mb-20 lg:mb-48 <?= implode( ' ', $ext_cls ) ?>"
			 id="<?= esc_attr( $id ) ?>">
			<h2 class="mb-4 text-white font-extrabold text-px32 leading-px40 md:font-bold md:leading-px42 lg:text-px46 lg:leading-px56 lg:-tracking-em001 <?= $options['title_cls']; ?>"><?= $options['title'] ?></h2>
			<p class="mb-12 text-white text-left mb-5 text-px20 leading-px26 md:text-px22 md:leading-px32 md:mb-14 <?= $options['title_cls']; ?>"><?= esc_html( $options['subtitle'] ) ?></p>

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
	 * @param string $base_selector
	 * @param array $options
	 */
	public static function print_js( string $base_selector, array $options = [] ): void {
		$breakpoints = [
				'768'  => [
						'spaceBetween' => 20,
				],
				'1440' => [
						'spaceBetween' => 30,
				]
		];

		// Merge breakpoints
		if ( ! empty( $options['breakpoints'] ) ) {
			foreach ( $options['breakpoints'] as $br_point => $br_options ) {
				$breakpoints[ $br_point ] = $br_options;
			}
		}

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
		],
				$options,
				[ 'breakpoints' => $breakpoints ],
		);
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
				jQuery(function () {
					trevorWP.matchMedia.carouselWith3Cards(init, function () {
						swiper && swiper.destroy();
					});
				})
				<?php }else{ ?>
				init();
				<?php } ?>
			})();
		</script>
		<?php
	}
}
