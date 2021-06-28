<?php namespace TrevorWP\Theme\Helper;

use \TrevorWP\Theme\Helper;
use TrevorWP\Theme\ACF\Field_Group;

/**
 * Page Header Helpers
 */
class Page_Header {
	/**
	 * Text Only Page Header
	 *
	 * @param array $options
	 *
	 * @return string
	 */
	public static function text( array $options ): string {

		$options = array_merge(
			array_fill_keys(
				array(
					'title_top',
					'title',
					'desc',
					'cta_txt',
					'cta_url',
					'styles',
					'buttons',
				),
				null
			),
			array(),
			$options
		);

		$hero_cls = implode(
			' ',
			array(
				'hero page-header type-text flex items-start text-white h-auto',
				'min-h-px611 pt-px105',
				'md:px-px110 md:pt-px75',
				'xl:px-px311 xl:pt-px105 xl:justify-start xl:mt-0',
			)
		);

		$heading_cls = implode(
			' ',
			array(
				'flex flex-col font-bold',
				'text-px32 leading-px40 mb-px20',
				'md:text-px36 md:leading-px42 md:mb-5 md:inline-block md:mb-px20',
				'xl:mb-7 xl:text-px46 xl:leading-px56 xl:tracking-em005 xl:mb-px28',
			)
		);

		$desc_cls = implode(
			' ',
			array(
				'hero__description',
				'text-px18 leading-px24',
				'md:tracking-px05 md:mb-px30 md:mx-px60',
				'xl:max-w-2xl xl:text-px24 xl:leading-px34 xl:tracking-normal',
			)
		);

		ob_start();
		?>
		<header class="header-container header-container--text w-full <?php echo esc_attr( implode( ' ', (array) $options['styles'] ) ); ?>">
			<div class="<?php echo esc_attr( $hero_cls ); ?>">
				<div class="hero--inner mx-auto text-center site-content-inner items-center w-full">
					<?php if ( ! empty( $options['title_top'] ) ) { ?>
						<p class="uppercase text-px16 md:text-px14 leading-px24 md:leading-px18 mb-2.5"><?php echo $options['title_top']; ?></p>
					<?php } ?>
					<h1 class="<?php echo esc_attr( $heading_cls ); ?>"><?php echo $options['title']; ?></h1>
					<p class="<?php echo esc_attr( $desc_cls ); ?>"><?php echo $options['desc']; ?></p>

					<?php
					if ( ! empty( $options['buttons'] ) ) {
						echo Field_Group\Button_Group::render( false, $options['buttons'], array() );
					}
					?>
				</div>
			</div>
		</header>
		<?php
		return ob_get_clean();
	}

	/**
	 * Split Image Page Header
	 *
	 * @param array $options
	 *
	 * @return string
	 */
	public static function split_img( array $options ): string {
		$options = array_merge(
			array_fill_keys(
				array(
					'title_top',
					'title',
					'desc',
					'img_id',
					'cta_txt',
					'cta_url',
					'styles',
					'buttons',
				),
				null
			),
			array(),
			$options
		);

		$hero_cls = implode(
			' ',
			array(
				'page-header type-split-img pt-px40',
				'md:pt-px25',
				'xl:mt-0 xl:pt-px18',
			)
		);

		$heading_cls = implode(
			' ',
			array(
				'heading-lg-tilted page-header-title',
				'text-px32 leading-px40',
				'md:leading-px42',
				'xl:text-px56 xl:leading-px66',
			)
		);

		$desc_cls = implode(
			' ',
			array(
				'page-header-desc',
				'pt-0 mt-px12 mb-0 text-px18 leading-px26 tracking-px05 pb-0',
				'md:mt-px15 md:text-px16 md:leading-px22 md:tracking-em001',
			)
		);

		ob_start();
		?>
		<div class="header-container w-full header-container--split-img <?php echo esc_attr( implode( ' ', (array) $options['styles'] ) ); ?>">
			<div class="<?php echo esc_attr( $hero_cls ); ?>">
				<div class="page-header-inner">
					<div class="page-header-content-wrap">
						<?php if ( ! empty( $options['title_top'] ) ) { ?>
							<div class="page-header-title-top"><?php echo esc_html( $options['title_top'] ); ?></div>
						<?php } ?>
						<h1 class="<?php echo esc_attr( $heading_cls ); ?>">
							<?php # Unescaping title to support <tilt> ?>
							<?php echo $options['title']; ?>
						</h1>
						<?php if ( ! empty( $options['desc'] ) ) { ?>
							<p class="<?php echo esc_attr( $desc_cls ); ?>"><?php echo $options['desc']; ?></p>
						<?php } ?>
						<?php
						if ( ! empty( $options['buttons'] ) ) {
							$wrap_cls = array( $options['buttons']['class'] );
							echo Field_Group\Button_Group::render( false, $options['buttons'], compact( 'wrap_cls' ) );
						}
						?>
					</div>
					<div class="page-header-img-wrap">
						<div class="page-header-img-inner">
							<div class="page-header-img-inner__wrapper" data-aspectRatio="1:1">
							<?php
							echo Thumbnail::print_img_variants(
								array(
									array(
										intval( $options['img_id'] ),
										Thumbnail::variant(
											Thumbnail::SCREEN_SM,
											null,
											Thumbnail::SIZE_MD,
											array(
												'class' => array(
													'page-header-img',
												),
											)
										),
									),
								)
							)
							?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Image BG Page header.
	 *
	 * @param array $options
	 *
	 * @return string|null
	 * @todo: Complete this implementation by moving all classnames to scss file
	 */
	public static function img_bg( array $options ): ?string {
		ob_start();
		?>
		<div class="page-header type-img-bg">
			<div class="page-header-content-wrap">
				<h1 class="heading-lg-tilted page-header-title">
					<?php # Leave unescape in order to support <tilt> ?>
					<?php echo $options['title']; ?>
				</h1>
				<?php if ( $options['desc'] ) { ?>
					<p class="page-header-desc"><?php echo $options['desc']; ?></p>
				<?php } ?>
				<?php
				if ( ! empty( $options['buttons'] ) ) {
					echo Field_Group\Button_Group::render( false, $options['buttons'], array() );
				}
				?>
			</div>

			<?php if ( $options['img_id'] ) { ?>
				<div class="page-header-img-wrap">
					<?php
					echo wp_get_attachment_image(
						$options['img_id'],
						'full',
						false,
						array(
							'class' => implode(
								' ',
								array(
									'object-center',
									'object-cover',
								)
							),
						)
					)
					?>
				</div>
			<?php } ?>
		</div>
		<?php
		return ob_get_clean();
	}

	public static function split_carousel( array $options ): string {
		$options = array_merge(
			array_fill_keys(
				array(
					'title',
					'desc',
					'cta_txt',
					'cta_url',
					'carousel_data',
					'swiper',
					'styles',
					'buttons',
				),
				null
			),
			array(),
			$options
		);
		ob_start();
		?>
		<div class="header-container w-full <?php echo esc_attr( ( ! empty( $options['styles'] ) ) ? implode( ' ', $options['styles'] ) : '' ); ?>">
			<div class="page-header type-split-carousel">
				<div class="page-header-inner">
					<div class="page-header-content-wrap">
						<?php if ( ! empty( $options['title_top'] ) ) { ?>
							<p class="page-header-title-top uppercase text-px16 md:text-px14 leading-px24 md:leading-px18 mb-2.5"><?php echo esc_html( $options['title_top'] ); ?></p>
						<?php } ?>
						<h1 class="heading-lg-tilted page-header-title">
							<?php echo $options['title']; ?>
						</h1>
						<p class="page-header-desc"><?php echo $options['desc']; ?></p>
						<?php
						if ( ! empty( $options['buttons'] ) ) {
							echo Field_Group\Button_Group::render( false, $options['buttons'], array() );
						}
						?>
					</div>
					<div class="page-header-img-wrap">
						<?php if ( ! empty( $options['carousel_data'] ) ) { ?>
							<?php
							echo Helper\Carousel::big_img(
								$options['carousel_data'],
								array(
									'class'  => array( 'text-white', 'header-carousel' ),
									'swiper' => $options['swiper'],
								)
							)
							?>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	public static function horizontal( array $options ): string {
		$options = array_merge(
			array_fill_keys(
				array(
					'title',
					'title_cls',
					'desc',
					'desc_cls',
					'img_id',
					'styles',
					'buttons',
				),
				null
			),
			array(),
			$options
		);

		# Header classnames.
		$header_cls = 'page-header type-horizontal xl:mt-px49 xl:px-px140';

		# Title classnames
		$title_cls = array_merge( $options['title_cls'], array( 'heading-lg-tilted page-header-title  tracking-normal' ) );
		$title_cls = implode( ' ', $title_cls );

		# Description classnames
		$desc_cls   = array( 'page-header-desc pb-0 pt-px12 text-px18 leading-px26' );
		$desc_cls[] = 'md:pt-px10 md:text-px16 md:leading-px22';
		$desc_cls[] = 'xl:pt-px14 xl:text-px26 xl:leading-px36 xl:tracking-em005';
		$desc_cls   = array_merge( $options['desc_cls'], $desc_cls );
		$desc_cls   = implode( ' ', $desc_cls );

		ob_start();
		?>
		<div class="header-container w-full header-container--horizontal <?php echo esc_html( implode( ' ', $options['styles'] ) ); ?>">
			<div class="<?php echo esc_html( $header_cls ); ?>">
				<div class="page-header-inner">
					<?php if ( ! empty( $options['title'] ) || ! empty( $options['desc'] ) ) { ?>
						<div class="page-header-content-wrap">
							<?php if ( ! empty( $options['title'] ) ) { ?>
								<h1 class="<?php echo esc_html( $title_cls ); ?>"><?php echo $options['title']; ?></h1>
							<?php } ?>
							<?php if ( ! empty( $options['desc'] ) ) { ?>
								<p class="<?php echo esc_html( $desc_cls ); ?>"><?php echo $options['desc']; ?></p>
							<?php } ?>
							<?php
							if ( ! empty( $options['buttons'] ) ) {
								echo Field_Group\Button_Group::render( false, $options['buttons'], array() );
							}
							?>
						</div>
					<?php } ?>
					<?php if ( ! empty( $options['img_id'] ) ) { ?>
						<div class="page-header-image">
							<?php
							echo wp_get_attachment_image(
								$options['img_id'],
								'full',
								false,
								array(
									'class' => implode(
										' ',
										array(
											'object-center',
											'object-cover',
											'w-full',
										)
									),
								)
							);
							?>
						</div>
					<?php } ?>
				</div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	public static function multi_image_text( array $options ): string {
		$options = array_merge(
			array_fill_keys(
				array(
					'title',
					'desc',
					'cta_txt',
					'cta_url',
					'carousel_data',
					'swiper',
					'styles',
					'buttons',
				),
				null
			),
			array(),
			$options
		);

		list( $text_color, $bg_color ) = $options['styles'];

		$container_classnames = implode(
			' ',
			array( 'header-container', 'w-full', 'header-container--multi-image', $text_color, $bg_color ),
		);

		$header_classnames = implode(
			' ',
			array( 'page-header', 'type-multi-image' ),
		);

		ob_start();
		?>
		<header class="<?php echo esc_attr( $container_classnames ); ?>">
			<div class="<?php echo esc_attr( $header_classnames ); ?>">
				<div class="page-header-inner">
					<div class="page-header-content-wrap">
						<h1 class="heading-lg-tilted page-header-title">
							<?php echo $options['title']; ?>
						</h1>
						<p class="page-header-desc"><?php echo $options['desc']; ?></p>
					</div>
					<?php if ( ! empty( $options['images'] ) ) : ?>
						<div class="page-header-img-wrap">
							<div class="page-header-images">
								<?php foreach ( $options['images'] as $image ) : ?>
									<?php if ( ! empty( $image['url'] ) ) : ?>
										<img aria-hidden="true" src="<?php echo esc_url( $image['url'] ); ?>" class="block mx-auto" alt="<?php echo ( ! empty( $image['alt'] ) ) ? esc_attr( $image['alt'] ) : esc_attr( $options['title'] ); ?>">
									<?php endif; ?>
								<?php endforeach; ?>
							</div>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</header>
		<?php
		return ob_get_clean();
	}
}
