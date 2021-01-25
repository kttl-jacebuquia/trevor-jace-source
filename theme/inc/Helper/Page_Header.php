<?php namespace TrevorWP\Theme\Helper;

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
					<h1 class="heading-lg-tilted page-header-title">
						<?= $options['title'] ?>
					</h1>
					<p class="page-header-desc"><?= $options['desc'] ?></p>
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
														'object-right-bottom',
														'object-cover',
														'w-full',
														'h-full',
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

	public static function img_bg() {

	}

	public static function split_carousel() {

	}
}
