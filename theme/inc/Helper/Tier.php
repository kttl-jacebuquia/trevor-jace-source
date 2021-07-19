<?php namespace TrevorWP\Theme\Helper;

use TrevorWP\Meta\Post;
use TrevorWP\Meta\Taxonomy;

/**
 * Tier Type Helper.
 */
class Tier {
	public static function partner( $data ) {
		ob_start();
		?>
	<div class="partners bg-white flex flex-col">
		<?php if ( ! empty( $data['title'] ) ) : ?>
			<h2 class="partners__title text-px32 leading-px42 md:leading-px40 lg:text-px40 lg:leading-px48 mt-px60 mb-px50 md:mb-px40 lg:mt-px100 lg:mb-px50 text-center font-bold">
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
											<?php echo get_the_post_thumbnail( $post, \TrevorWP\Theme\Helper\Thumbnail::SIZE_MD, array( 'class' => 'partner-logo' ) ); ?>
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
										<a href="<?php echo Post::get_partner_url( $post->ID ); ?>" rel="nofollow noreferrer noopener" target="_blank"><?php echo esc_html( $post->post_title ); ?></a>
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
		<div class="funders bg-white flex flex-col">
			<?php if ( ! empty( $data['title'] ) ) : ?>
				<h2 class="funders__title text-px32 leading-px40 md:leading-px42 lg:text-px40 lg:leading-px48 mt-px60 mb-px50 md:mb-px40 lg:mt-px100 lg:mb-px50 text-center font-bold">
					<?php echo esc_html( $data['title'] ); ?></h2>
			<?php endif; ?>

			<?php if ( ! empty( $data['tiers'] ) ) : ?>
				<div class="table-container">
					<table>
						<tbody>
						<?php foreach ( $data['tiers'] as $tier ) : ?>
							<tr class="flex flex-col md:flex-row text-center">
								<th>
									<div class="tier-name text-px20 leading-px26 md:text-left font-semibold lg:mb-0 lg:text-px26 lg:leading-px36"><?php echo esc_html( $tier->name ); ?></div>
									<div class="tier-value font-normal text-px20 md:text-left leading-px26 lg:text-px22 lg:leading-px32"><?php echo esc_html( get_term_meta( $tier->term_id, Taxonomy::KEY_PARTNER_TIER_NAME, true ) ); ?></div>

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
				<a href="<?php echo esc_url( $data['button']['url'] ); ?>" target="<?php echo esc_attr( $data['button']['target'] ); ?>" class="funders__partner inline-block mx-auto flex-grow-0 flex-shrink-0"><?php echo esc_html( $data['button']['title'] ); ?></a>
			<?php endif; ?>
		</div>
		<?php
			return ob_get_clean();
	}
}
