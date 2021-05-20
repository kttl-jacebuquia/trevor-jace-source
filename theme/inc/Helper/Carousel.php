<?php namespace TrevorWP\Theme\Helper;

use TrevorWP\CPT;

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
	public static function posts( array $posts, array $options = array() ): string {
		$options = array_merge(
			array_fill_keys(
				array(
					'id', // Main wrapper DOM id
					'title',
					'subtitle',
					'class',
				),
				null
			),
			array(
				'title_cls'    => '',
				'print_js'     => true,
				'card_options' => array(),
				'swiper'       => array(), // swiper options
			),
			$options
		);

		if ( ! is_array( $options['title_cls'] ) ) {
			$options['title_cls'] = explode( ' ', $options['title_cls'] );
		}

		# ID check
		$id = &$options['id'];
		if ( empty( $id ) ) {
			$id = uniqid( 'posts-carousel-' );
		}

		if ( $options['print_js'] ) {
			add_action(
				'wp_footer',
				function () use ( $options ) {
					self::print_js(
						"#{$options['id']} .carousel-full-width-wrap",
						array_merge(
							$options['swiper'],
							array(
								'onlyMd' => $options['onlyMd'] ?? null,
							)
						)
					);
				},
				PHP_INT_MAX >> 2,
				0
			);
		}

		# Extra Classes
		$ext_cls = array(
			'post-carousel',
			( 'card-count-' . count( $posts ) ),
			$options['class'],
		);
		if ( ! empty( $options['onlyMd'] ) ) {
			$ext_cls[] = 'only-md';
		}

		ob_start(); ?>
		<div class="carousel-wrap <?php echo implode( ' ', $ext_cls ); ?>"
			id="<?php echo esc_attr( $id ); ?>">
			<?php if ( ! empty( $options['title'] ) ) { ?>
				<div class="carousel-header container mx-auto">
					<h2 class="page-sub-title <?php echo implode( ' ', $options['title_cls'] ); ?>"><?php echo $options['title']; ?></h2>
					<?php if ( ! empty( $options['subtitle'] ) ) { ?>
						<p class="page-sub-title-desc <?php echo implode( ' ', $options['title_cls'] ); ?>">
							<?php echo esc_html( $options['subtitle'] ); ?>
						</p>
					<?php } ?>
				</div>
			<?php } ?>

			<div class="carousel-full-width-wrap container mx-auto">
				<div class="carousel-container">
					<div class="swiper-wrapper">
						<?php
						foreach ( $posts as $key => $post ) {
							$post_type = get_post_type( $post );

							switch ( $post_type ) {
								case CPT\Team::POST_TYPE:
									$options['card_renderer'] = array( Tile::class, 'staff' );
									break;
								case CPT\Event::POST_TYPE:
									$options['card_renderer']            = array( Tile::class, 'event' );
									$options['card_options']['show_cta'] = true;
									break;
								case 'attachment':
									$options['card_renderer'] = array( Card::class, 'attachment' );
									break;
								default:
									$options['card_renderer'] = ( ! empty( $options['card_renderer'] ) ? $options['card_renderer'] : array( Card::class, 'post' ) );

							}
							?>
							<div class="swiper-slide">
								<?php echo call_user_func( $options['card_renderer'], $post, $key, $options['card_options'] ); ?>
							</div>
						<?php } ?>
					</div>
				</div>
				<div class="swiper-button swiper-button-prev">
					<div class="swiper-button-wrapper">
						<i class="trevor-ti-arrow-left"></i>
					</div>
				</div>
				<div class="swiper-button swiper-button-next">
					<div class="swiper-button-wrapper">
						<i class="trevor-ti-arrow-right"></i>
					</div>
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
	public static function big_img( array $data, array $options = array() ): string {
		$options = array_merge(
			array_fill_keys(
				array(
					'id', // Main wrapper DOM id
					'title',
					'subtitle',
					'class',
				),
				null
			),
			array(
				'title_cls' => '',
				'print_js'  => true,
				'swiper'    => array(), // swiper options
			),
			$options
		);

		# ID check
		$id = &$options['id'];
		if ( empty( $id ) ) {
			$id = uniqid( 'big-img-carousel-' );
		}

		if ( $options['print_js'] ) {
			add_action(
				'wp_footer',
				function () use ( $options ) {
					self::print_js( "#{$options['id']}", array_merge( array(), $options['swiper'] ) );
				},
				PHP_INT_MAX >> 2,
				0
			);
		}

		# Extra Classes
		$ext_cls = array(
			'big-img-carousel',
			( 'card-count-' . count( $data ) ),
			implode( ' ', $options['class'] ),
		);

		ob_start();
		?>

		<div class="carousel-wrap <?php echo implode( ' ', $ext_cls ); ?>"
			id="<?php echo esc_attr( $id ); ?>">
			<?php if ( ! empty( $options['title'] || ! empty( $options['subtitle'] ) ) ) { ?>
				<div class="carousel-header">
					<?php if ( ! empty( $options['title'] ) ) { ?>
						<h2 class="page-sub-title centered <?php echo $options['title_cls']; ?>"><?php echo $options['title']; ?></h2>
					<?php } ?>
					<?php if ( ! empty( $options['subtitle'] ) ) { ?>
						<p class="page-sub-title-desc centered <?php echo $options['title_cls']; ?>"><?php echo esc_html( $options['subtitle'] ); ?></p>
					<?php } ?>
				</div>
			<?php } ?>

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
										<?php echo wp_get_attachment_image( $entry['img']['id'], 'large' ); ?>
									</div>
									<?php if ( ! empty( $entry['caption'] ) ) { ?>
										<figcaption>
											<?php echo $entry['caption']; ?>
											<?php if ( ! empty( $entry['cta_txt'] ) && ! empty( $entry['cta_url'] ) ) : ?>
												<a href="<?php echo $entry['cta_url']; ?>"><?php echo $entry['cta_txt']; ?></a>
											<?php endif; ?>
										</figcaption>
									<?php } ?>
								</figure>
							</div>
						<?php } ?>
					</div>
				</div>
				<div class="swiper-button swiper-button-prev">
					<div class="swiper-button-wrapper">
						<i class="trevor-ti-arrow-left"></i>
					</div>
				</div>
				<div class="swiper-button swiper-button-next">
					<div class="swiper-button-wrapper">
						<i class="trevor-ti-arrow-right"></i>
					</div>
				</div>
				<div class="swiper-pagination"></div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Testimonials carousel
	 *
	 * @param array|null $data
	 * @param array $options
	 *
	 * @return string|null
	 */
	public static function testimonials( array $data = null, array $options = array() ): ?string {
		if ( empty( $data ) ) {
			return null;
		}

		$options = array_merge(
			array_fill_keys(
				array(
					'id', // Main wrapper DOM id
				),
				null
			),
			array(
				'print_js' => true,
			),
			$options
		);

		# ID check
		$id = &$options['id'];
		if ( empty( $id ) ) {
			$id = uniqid( 'big-img-carousel-' );
		}

		if ( $options['print_js'] ) {
			add_action(
				'wp_footer',
				function () use ( $id ) {
					self::print_testimonials_js( $id, array() );
				},
				PHP_INT_MAX >> 2,
				0
			);
		}

		$carousel_cls = array(
			'carousel-testimonials bg-gray-light pt-px56 pb-px70 px-0',
			'md:py-px50 lg:py-px80',
		);
		$carousel_cls = implode( ' ', $carousel_cls );

		ob_start();
		?>
		<div class="<?php echo $carousel_cls; ?>" id="<?php echo esc_attr( $id ); ?>">
			<div class="carousel-testimonials-inner">
				<div class="carousel-testimonials-img-wrap rounded-px10 overflow-hidden" data-aspectRatio="1:1">
					<div class="swiper-container h-full">
						<div class="swiper-wrapper">
							<?php foreach ( $data as $entry ) : ?>
								<div class="swiper-slide">
									<?php if ( empty( $entry['img']['id'] ) ) { ?>
										<div class="w-full h-full bg-white"></div>
									<?php } else { ?>
										<?php
										echo wp_get_attachment_image(
											$entry['img']['id'],
											'large',
											false,
											array(
												'class' => implode(
													' ',
													array(
														'object-center',
														'object-cover',
														'w-full',
														'h-full',
													)
												),
											)
										)
										?>
									<?php } ?>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
				<div class="carousel-testimonials-txt-wrap relative md:py-0">
					<div class="panes-container flex justify-between absolute h-full w-full">
						<div class="carousel-left-arrow-pane swiper-button h-full w-1/6 px-4 relative"
							data-direction="left"
							aria-label="Previous Slide" role="button"></div>
						<div class="carousel-right-arrow-pane swiper-button h-full w-1/6 px-4 relative"
							data-direction='right'
							aria-label="Next Slide" role="button"></div>
					</div>
					<div class="swiper-container h-full pt-px50 md:py-px20">
						<div class="flex flex-row justify-center w-full">
							<i class="trevor-ti-quote-open -mt-2 mr-0.5 lg:text-px28 lg:mr-2"></i>
							<i class="trevor-ti-quote-close lg:text-px28"></i>
						</div>
						<div class="swiper-wrapper">
							<?php foreach ( $data as $entry ) : ?>
								<div class="swiper-slide h-auto px-4 pt-5 pb-14 md:p-0">
									<figure class="text-center text-teal-dark flex flex-col justify-between md:w-full md:mx-auto">
										<blockquote
												class="font-bold text-center text-3xl mb-4 md:text-px20 md:leading-px26 lg:text-px30 lg:leading-px40">
											<?php echo $entry['quote']; ?>
										</blockquote>
										<?php if ( ! empty( $entry['cite'] ) ) { ?>
											<figcaption
													class="text-px18 leading-px26 lg:text-px22 lg:leading-px32">
												<?php echo $entry['cite']; ?>
											</figcaption>
										<?php } ?>
									</figure>
								</div>
							<?php endforeach; ?>
						</div>
						<div class="swiper-pagination"></div>

						<div class="swiper-button-prev"></div>
						<div class="swiper-button-next"></div>
					</div>
				</div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * @param array|null $data
	 * @param array $options
	 *
	 * @return string|null
	 */
	public static function fundraisers( array $data, array $options = array() ): ?string {
		return self::posts( $data, array_merge( $options, array( 'card_renderer' => array( Card::class, 'fundraiser' ) ) ) );
	}

	/**
	 * @param string $base_selector
	 * @param array $options
	 */
	public static function print_js( string $base_selector, array $options = array() ): void {
		$options = array_merge(
			array(
				'slidesPerView' => 1,
				'spaceBetween'  => 20,
				'centerSlides'  => true,
				'pagination'    => array(
					'el'        => "{$base_selector} .swiper-pagination",
					'clickable' => true,
				),
				'breakpoints'   => array(
					768 => array( 'spaceBetween' => 28 ),
				),
				'navigation'    => array(
					'nextEl' => "{$base_selector} .swiper-button-next",
					'prevEl' => "{$base_selector} .swiper-button-prev",
				),
				'on'            => new \stdClass(),
			),
			$options
		);
		?>
		<script>
			(function () {
				let swiper;
				let options = <?php echo json_encode( $options ); ?>;
				options.on.init = function () {
					document.querySelectorAll('.carousel-testimonials .card-post').forEach(elem => {
						elem.tagBoxEllipsis && elem.tagBoxEllipsis.calc();
					});
				}

				options.on.activeIndexChange = function (swiper) {
					let nextButton = swiper.navigation.nextEl;
					let carouselParentContainer = swiper.$el[0].parentElement.parentElement;

					// only apply hide the next button on 2nd to the last index on post-carousels
					if (Array.from(carouselParentContainer.classList).includes('post-carousel')) {
						if (swiper.activeIndex === swiper.slides.length - 2) {
							nextButton.classList.add('should-hide');
						} else {
							nextButton.classList.remove('should-hide');
						}
					}
				}

				function init() {
					if (!swiper || swiper.destroyed) {
						swiper = new trevorWP.vendors.Swiper('<?php echo esc_js( $base_selector ); ?> .carousel-container', options);
					}
				}

				<?php if ( ! empty( $options['onlyMd'] ) ) { ?>
				trevorWP.matchMedia.carouselWith3Cards(init, function () {
					swiper && swiper.destroy();
				});
				<?php } else { ?>
				init();
				<?php } ?>
			})();
		</script>
		<?php
	}

	/**
	 * @param string $id
	 * @param array $options
	 */
	public static function print_testimonials_js( string $id, array $options = array() ): void {
		?>
		<script>
			trevorWP.features.testimonialsCarousel('<?php echo esc_js( $id ); ?>')
		</script>
		<?php
	}
}
