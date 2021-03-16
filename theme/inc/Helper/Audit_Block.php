<?php namespace TrevorWP\Theme\Helper;

/**
 * Circulation Card Helper
 */
class Audit_Block {

	/**
	 * @var array
	 */


	public function __construct() {}

	/**
	 * @return string|null
	 */

	public function render( $title, $data, $custom_class ): ?string {
		ob_start(); ?>

		<div class="audit <?= $custom_class ?>">
			<div class="container mx-auto">
				<h3 class="page-sub-title centered text-white mb-px80 md:mb-px70 lg:mb-px90"><?= $title ?></h3>
				<div class="audit--card text-center grid grid-cols-1 gap-y-6 max-w-lg mx-auto lg:grid-cols-3 lg:gap-x-7 lg:max-w-none xl:max-w-px1240">

					<div class="audit-holder mobile-only">
						<div class="audit-container swiper-container" id="audit-<?php echo esc_attr( uniqid() ); ?>">
							<div class="audit-wrapper swiper-wrapper">
								<?php foreach ( $data as $audit ) : ?>
									<div class="audit--card__item swiper-slide text-center">
										<?php if ( $audit['img'] ) : ?>
											<div class="audit--card__image">
												<img src="<?php echo esc_url( $audit['img']['url'] ); ?>" alt="<?php echo esc_attr( $audit['desc'] ); ?>">
											</div>
										<?php endif; ?>

										<p><?php echo esc_html( $audit['desc'] ); ?></p>
									</div>
								<?php endforeach; ?>
							</div>
							<div class="swiper-pagination"></div>
						</div>
					</div>

					<?php foreach ( $data as $audit ) : ?>
						<div class="audit--card__item swipe-slide text-center flex flex-col justify-between hidden lg:flex">
							<?php if ( $audit['img'] ) : ?>
								<img src="<?php echo esc_url( $audit['img']['url'] ); ?>" alt="<?php echo esc_attr( $audit['desc'] ); ?>">
							<?php endif; ?>

							<p><?php echo esc_html( $audit['desc'] ); ?></p>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
		
		<?php return ob_get_clean();
	}
}
