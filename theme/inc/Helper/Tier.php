<?php namespace TrevorWP\Theme\Helper;

use TrevorWP\Meta\Post;
use TrevorWP\Meta\Taxonomy;
use WP_Post;

/**
 * Tier Type Helper.
 */
class Tier {
	public static function partner( $data ) {
		ob_start();
		?>
	<div class="current-partners-table bg-white flex flex-col">
		<?php if ( ! empty( $data['title'] ) ) : ?>
			<h2 class="current-partners-table__title text-px32 leading-px42 md:leading-px40 lg:text-px40 lg:leading-px48 mt-px60 mb-px50 md:mb-px40 lg:mt-px100 lg:mb-px50 text-center font-bold">
				<?php echo esc_html( $data['title'] ); ?></h2>
		<?php endif; ?>

		<?php if ( ! empty( $data['tiers'] ) ) : ?>
			<div class="table-container">
				<table>
					<tbody>
					<?php foreach ( $data['tiers'] as $tier ) : ?>
						<tr class="flex flex-col md:flex-row text-center">
							<th>
								<div class="tier-name text-px20 leading-px26 font-semibold md:text-left lg:mb-0"><?php echo esc_html( $tier->name ); ?></div>
								<div class="tier-value font-normal text-px20 md:text-left leading-px26"><?php echo esc_html( get_term_meta( $tier->term_id, Taxonomy::KEY_PARTNER_TIER_NAME, true ) ); ?></div>
							</th>
							<td class="logo-size-<?php echo $tier->logo_size; ?> flex">
								<?php if ( 'text' !== $tier->logo_size ) : ?>
									<?php foreach ( $tier->posts as $post ) : ?>
										<a href="<?php echo Post::get_partner_url( $post->ID ); ?>"
											rel="nofollow noreferrer noopener"
											target="_blank"
											title="<?php echo esc_attr( $post->post_title ); ?>"
										>
											<?php echo static::render_partner_logo( $post ); ?>
										</a>
									<?php endforeach ?>
								<?php else : ?>
									<?php
									$post_count           = count( $tier->posts );
									$two_column_split     = $post_count / 2;
									$three_column_split_1 = $post_count / 3;
									$three_column_split_2 = $three_column_split_1 * 2;

									foreach ( $tier->posts as $i => $post ) :
										?>
										<a href="<?php echo Post::get_partner_url( $post->ID ); ?>" rel="nofollow noreferrer noopener" target="_blank">
											<span>
												<?php echo esc_html( $post->post_title ); ?>
											</span>
										</a>
									<?php endforeach ?>
								<?php endif ?>
							</td>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		<?php endif; ?>
	</div>
		<?php
		return ob_get_clean();
	}

	public static function grant( $data ) {
		ob_start();
		?>
		<div class="current-funders bg-white flex flex-col">
			<?php if ( ! empty( $data['title'] ) ) : ?>
				<h2 class="current-funders__title text-px32 leading-px40 md:leading-px42 mt-px60 mb-px50 md:mb-px40 lg:mt-px100 lg:mb-px50 text-center font-bold">
					<?php echo esc_html( $data['title'] ); ?></h2>
			<?php endif; ?>

			<?php if ( ! empty( $data['tiers'] ) ) : ?>
				<div class="table-container">
					<table>
						<tbody>
						<?php foreach ( $data['tiers'] as $tier ) : ?>
							<tr class="flex flex-col md:flex-row text-center">
								<th>
									<div class="tier-name text-px20 leading-px26 md:text-left font-semibold lg:mb-0"><?php echo esc_html( $tier->name ); ?></div>
									<div class="tier-value font-normal text-px20 md:text-left leading-px26 "><?php echo esc_html( get_term_meta( $tier->term_id, Taxonomy::KEY_PARTNER_TIER_NAME, true ) ); ?></div>

								</th>
								<td class="logo-size flex flex-col md:flex-row">
									<?php foreach ( $tier->posts as $post ) : ?>
										<?php if ( ! empty( Post::get_partner_url( $post->ID ) ) ) : ?>
											<a href="<?php echo Post::get_partner_url( $post->ID ); ?>" class="funder-name" rel="nofollow noreferrer noopener" target="_blank"
												title="<?php echo esc_attr( $post->title ); ?>">
												<span><?php echo esc_html( $post->post_title ); ?></span>
											</a>
										<?php else : ?>
											<div class="funder-name"><?php echo esc_html( $post->post_title ); ?></div>
										<?php endif; ?>
									<?php endforeach ?>
								</td>
							</tr>
						<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			<?php endif; ?>

			<?php if ( ! empty( $data['button']['url'] ) ) : ?>
				<a href="<?php echo esc_url( $data['button']['url'] ); ?>" target="<?php echo esc_attr( $data['button']['target'] ); ?>" class="current-funders__partner inline-block mx-auto flex-grow-0 flex-shrink-0"><?php echo esc_html( $data['button']['title'] ); ?></a>
			<?php endif; ?>
		</div>
		<?php
			return ob_get_clean();
	}

	protected static function render_partner_logo( $post ): string {
		$attrs         = array( 'class' => 'partner-logo' );
		$post_image_id = get_post_thumbnail_id( $post );
		list(
			$image_src,
			$width,
			$height
		)              = wp_get_attachment_image_src( $post_image_id, \TrevorWP\Theme\Helper\Thumbnail::SIZE_MD );
		$is_svg        = preg_match( '/\.svg$/i', $image_src );

		// Add aspect ratio class according to dimensions
		if ( $is_svg ) {
			// SVGs does not provide width and height, so we let JS compute it
			$attrs['class'] .= ' partner-logo--dynamic';
		} else {
			$attrs['data-aspect-ratio-class'] = static::get_aspect_ratio_class( $width, $height );
		}

		return get_the_post_thumbnail( $post, \TrevorWP\Theme\Helper\Thumbnail::SIZE_MD, $attrs );
	}

	// Should match JS file partner-logo.ts (getAspectRatioClass function)
	protected static function get_aspect_ratio_class( int $width, $height ): string {
		$width_height_ratio = $width / $height;

		switch ( true ) {
			case ( $width_height_ratio >= .75 && $width_height_ratio <= 1.34 ):
				return 'square';
			case ( $width_height_ratio < .75 ):
				return 'portrait';
			case $width_height_ratio > 1.34 && $width_height_ratio < 1.8:
				return 'landscape';
			case $width_height_ratio >= 1.8 && $width_height_ratio < 5:
				return 'landscape-wide';
			case $width_height_ratio >= 5:
				return 'wide';
			default:
				return '';
		}
	}
}
