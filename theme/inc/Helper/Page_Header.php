<?php namespace TrevorWP\Theme\Helper;

use \TrevorWP\Theme\Helper;

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
		$options = array_merge( array_fill_keys( [
				'title_top',
				'title',
				'desc',
				'cta_txt',
				'cta_url',
		], null ), [], $options );
		ob_start();
		?>
		<div class="hero h-px600 md:h-px490 lg:h-px546 flex items-center text-white lg:justify-start">
			<div class="container mx-auto text-center site-content-inner items-center w-full">
				<?php if ( ! empty( $options['title_top'] ) ) { ?>
					<p class="uppercase text-px16 md:text-px14 leading-px24 md:leading-px18 mb-2.5"><?= $options['title_top'] ?></p>
				<?php } ?>
				<h1 class="text-px32 leading-px40 md:leading-px42 mb-2.5 md:mb-5 lg:mb-7 flex flex-col font-bold md:inline-block md:mb-px20 lg:text-px46 lg:leading-px56">
					<?= $options['title'] ?>
				</h1>
				<p class="hero__description text-px18 mb-9 md:mb-px30 lg:text-px26 lg:leading-px36 lg:max-w-2xl">
					<?= $options['desc'] ?>
				</p>

				<?php if ( ! empty( $options['cta_txt'] ) ) { ?>
					<a href="<?= empty( $options['cta_url'] ) ? '#' : $options['cta_url'] ?>"
					   class="hero__btn-1 inline-block text-teal-dark font-bold bg-white py-3 px-8 rounded-px10 md:px-8 lg:text-px18 lg:leading-px26 lg:py-5 lg:px-10">
						<?= $options['cta_txt'] ?>
					</a>
				<?php } ?>
			</div>
		</div>
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
		$options = array_merge( array_fill_keys( [
				'title_top',
				'title',
				'desc',
				'img_id',
				'cta_txt',
				'cta_url',
		], null ), [], $options );
		ob_start();
		?>
		<div class="page-header type-split-img">
			<div class="page-header-inner">
				<div class="page-header-content-wrap">
					<?php if ( ! empty( $options['title_top'] ) ) { ?>
						<div class="page-header-title-top"><?= $options['title_top'] ?></div>
					<?php } ?>
					<h1 class="heading-lg-tilted page-header-title">
						<?= $options['title'] ?>
					</h1>
					<?php if ( ! empty( $options['desc'] ) ) { ?>
						<p class="page-header-desc"><?= $options['desc'] ?></p>
					<?php } ?>
					<a href="<?= $options['cta_url'] ?>"
					   class="page-header-cta"><?= $options['cta_txt'] ?></a>
				</div>
				<div class="page-header-img-wrap">
					<div class="page-header-img-inner">
						<?= Thumbnail::print_img_variants( [
								[
										intval( $options['img_id'] ),
										Thumbnail::variant( Thumbnail::SCREEN_SM, null, Thumbnail::SIZE_MD, [
												'class' => [
														'page-header-img',
												],
										] ),
								],
						] ) ?>
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
		ob_start(); ?>
		<div class="page-header type-img-bg">
			<div class="page-header-content-wrap">
				<h1 class="heading-lg-tilted page-header-title">
					<?= $options['title'] ?>
				</h1>
				<?php if ( $options['desc'] ) { ?>
					<p class="page-header-desc"><?= $options['desc'] ?></p>
				<?php } ?>
				<?php if ( ! empty( $options['cta_txt'] ) ) { ?>
					<a href="<?= $options['cta_url'] ?>"
					   class="page-header-cta">
						<?= $options['cta_txt'] ?>
					</a>
				<?php } ?>
			</div>

			<?php if ( $options['img_id'] ) { ?>
				<div class="page-header-img-wrap">
					<?= wp_get_attachment_image( $options['img_id'], 'full', false, [
							'class' => implode( ' ', [
									'object-center',
									'object-cover',
							] )
					] ) ?>
				</div>
			<?php } ?>
		</div>
		<?php return ob_get_clean();
	}

	public static function split_carousel( array $options ): string {
		$options = array_merge( array_fill_keys( [
				'title',
				'desc',
				'cta_txt',
				'cta_url',
				'carousel_data',
				'swiper'
		], null ), [], $options );
		ob_start();
		?>
		<div class="page-header type-split-carousel">
			<div class="page-header-inner">
				<div class="page-header-content-wrap">
					<div class="page-header-title-top"></div>
					<?php if ( ! empty( $options['title_top'] ) ) { ?>
						<p class="page-header-title-top uppercase text-px16 md:text-px14 leading-px24 md:leading-px18 mb-2.5"><?= $options['title_top'] ?></p>
					<?php } ?>
					<h1 class="heading-lg-tilted page-header-title">
						<?= $options['title'] ?>
					</h1>
					<p class="page-header-desc"><?= $options['desc'] ?></p>
					<?php if ( ! empty( $options['cta_url'] ) )  {
						?>
						<a href="<?= $options['cta_url'] ?>"
							class="page-header-cta"><?= $options['cta_txt'] ?></a>
					<?php
						} ?>
				</div>
				<div class="page-header-img-wrap">
					<?= Helper\Carousel::big_img( $options['carousel_data'], [
							'class'  => 'text-white',
							'swiper' => $options['swiper']
					] ) ?>
				</div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}
}
