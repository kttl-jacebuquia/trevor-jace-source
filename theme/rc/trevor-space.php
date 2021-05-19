<?php /* Resources Center: Trevorspace */ ?>
<?php get_header(); ?>
<?php

use TrevorWP\CPT\RC\RC_Object;
use \TrevorWP\Theme\Helper;
use TrevorWP\Theme\Customizer\Trevorspace;
use TrevorWP\Theme\Helper\Circulation_Card;

$user_count       = get_option( \TrevorWP\Main::OPTION_KEY_TREVORSPACE_ACTIVE_COUNT, 0 );
$user_threshold   = Trevorspace::get_val( Trevorspace::SETTING_COUNTER_ONLINE_THRESHOLD );
$online_count_txt = $user_count > $user_threshold
		? sprintf( '%d members currently online', $user_count )
		: Trevorspace::get_val( Trevorspace::SETTING_COUNTER_UNDER_THRESHOLD_MSG );
$page_data        = Trevorspace::get_val( Trevorspace::SETTING_HOME_DATA );
?>

<main id="site-content" role="main" class="site-content">
	<div>
		<div class="container mx-auto text-center site-content-inner">
			<h5 class="text-px16 leading-px24 mb-2 text-indigo md:mb-4">
				<i class="trevor-ti-online mr-2"></i> <?php echo $online_count_txt; ?>
			</h5>
			<h1 class="heading-lg-tilted text-indigo mb-4"><?php echo Trevorspace::get_val( Trevorspace::SETTING_HOME_TITLE ); ?></h1>
			<p class="font-normal text-px18 mb-7 text-indigo md:text-px20 md:leading-px30 md:mx-px88 md:mb-5 lg:text-px22 lg:leading-px36 lg:mb-14 lg:mx-64"><?php echo Trevorspace::get_val( Trevorspace::SETTING_HOME_DESC ); ?></p>

			<a href="<?php echo Trevorspace::get_val( Trevorspace::SETTING_HOME_JOIN_URL ); ?>"
			   rel="noreferrer noopener"
			   target="_blank"
			   class="block text-white font-bold text-px18 leading-px24 bg-indigo py-4 px-8 rounded-px10 mx-auto md:text-px16 md:leading-px22 lg:mb-9 lg:text-px18 lg:leading-px22 lg:px-20 lg:py-6">
				<?php echo Trevorspace::get_val( Trevorspace::SETTING_HOME_JOIN_CTA ); ?>
			</a>
			<div class="login-row mt-5 mb-20 text-indigo md:mb-28 lg:mb-40">
				Already member?
				<a href="<?php echo Trevorspace::get_val( Trevorspace::SETTING_HOME_LOGIN_URL ); ?>"
				   rel="noreferrer noopener" target="_blank" class="btn-link">
					<?php echo Trevorspace::get_val( Trevorspace::SETTING_HOME_LOGIN_CTA ); ?>
				</a>
			</div>

			<?php #TODO: Convert this block into a helper ?>
			<?php if ( ! empty( $page_data[0] ) ) : ?>
				<div class="flex flex-col mb-14 md:flex-row lg:flex-row-reverse lg:mx-px106 lg:mb-28">
					<div class="bg-white rounded-px10 mb-7 md:flex-1 md:mb-0" data-aspectRatio="1:1">
						<?php
						if ( ! empty( $page_data[0]['img'] ) ) {
							echo wp_get_attachment_image(
								$page_data[0]['img']['id'],
								'medium',
								false,
								array(
									'class' => implode(
										' ',
										array(
											'object-center',
											'object-cover',
											'rounded-px10',
										)
									),
								)
							);
						}
						?>
					</div>
					<div class="text-center text-indigo px-5 md:flex-1 md:flex md:flex-col md:justify-center md:text-left md:pl-11 md:pr-0 lg:pl-0 lg:pr-16">
						<div class="text-indigo font-bold text-px14 leading-px18 tracking-px05 mb-2 md:tracking-em001 uppercase lg:mb-4">
							<?php echo $page_data[0]['title_top']; ?>
						</div>
						<h3 class="font-bold text-px34 leading-px40 mb-5 md:text-px30 md:leading-px40 md:mb-4 lg:text-px32 lg:leading-px50 xl:text-px34 xl:leading-px44">
							<?php echo $page_data[0]['title']; ?>
						</h3>
						<p class="text-px20 leading-px30 md:text-px18 lg:text-10 lg:text-px22 lg:leading-px32 font-normal">
							<?php echo $page_data[0]['desc']; ?>
						</p>
					</div>
				</div>
			<?php endif; ?>
			<?php if ( ! empty( $page_data[1] ) ) : ?>
				<div class="flex flex-col mb-14 md:flex-row-reverse lg:flex-row lg:mx-px106 lg:mb-28">
					<div class="bg-white rounded-px10 mb-7 md:flex-1 md:mb-0" data-aspectRatio="1:1">
						<?php
						if ( ! empty( $page_data[1]['img'] ) ) {
							echo wp_get_attachment_image(
								$page_data[1]['img']['id'],
								'medium',
								false,
								array(
									'class' => implode(
										' ',
										array(
											'object-center',
											'object-cover',
											'rounded-px10',
										)
									),
								)
							);
						}
						?>
					</div>
					<div class="text-center text-indigo px-5 md:flex-1 md:flex md:flex-col md:justify-center md:text-left md:pl-0 md:pr-11 lg:pl-16 lg:pr-0">
						<div class="text-indigo font-bold text-px14 leading-px18 tracking-px05 mb-2 md:tracking-em001 uppercase lg:mb-4">
							<?php echo $page_data[1]['title_top']; ?>
						</div>
						<h3 class="font-bold text-px34 leading-px40 mb-5 md:text-px30 md:leading-px40 md:mb-4 lg:text-px32 lg:leading-px50 xl:text-px34 xl:leading-px44">
							<?php echo $page_data[1]['title']; ?>
						</h3>
						<p class="text-px20 leading-px30 mb-6 md:leading-px32 lg:text-px22 lg:leading-px32 font-normal">
							<?php echo $page_data[1]['desc']; ?>
						</p>
					</div>
				</div>
			<?php endif; ?>
			<?php if ( ! empty( $page_data[2] ) ) : ?>
				<div class="flex flex-col mb-20 md:flex-row md:mb-28 lg:flex-row-reverse lg:mx-px106">
					<div class="bg-white rounded-px10 mb-7 md:flex-1 md:mb-0" data-aspectRatio="1:1">
						<?php
						if ( ! empty( $page_data[2]['img'] ) ) {
							echo wp_get_attachment_image(
								$page_data[2]['img']['id'],
								'medium',
								false,
								array(
									'class' => implode(
										' ',
										array(
											'object-center',
											'object-cover',
											'rounded-px10',
										)
									),
								)
							);
						}
						?>
					</div>
					<div class="text-center text-indigo px-5 md:flex-1 md:flex md:flex-col md:justify-center md:text-left md:pl-11 md:pr-0 lg:pr-16 lg:pl-0">
						<div class="text-indigo font-bold text-px14 leading-px18 tracking-px05 mb-2 md:tracking-em001 uppercase lg:mb-4">
							<?php echo $page_data[2]['title_top']; ?>
						</div>
						<h3 class="font-bold text-px34 leading-px40 mb-5 md:text-px30 md:leading-px40 md:mb-4 lg:text-px32 lg:leading-px50 xl:text-px34 xl:leading-px44">
							<?php echo $page_data[2]['title']; ?>
						</h3>
						<p class="text-px20 leading-px30 mb-6 md:text-px18 lg:text-px22 lg:leading-px32 font-normal">
							<?php echo $page_data[2]['desc']; ?>
						</p>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>

	<?php /* Recirculation */ ?>
		<?php
		$gh_args                  = Helper\Circulation_Card::DEFAULTS['get_help'];
		$gh_args['button']['url'] = home_url( RC_Object::PERMALINK_GET_HELP );
		$rc_args                  = Helper\Circulation_Card::DEFAULTS['rc'];
		$rc_args['button']['url'] = home_url( RC_Object::PERMALINK_BASE );
		echo Helper\Circulation_Card::render_circulation(
			Trevorspace::get_val( Trevorspace::SETTING_HOME_CIRCULATION_TITLE ),
			Trevorspace::get_val( Trevorspace::SETTING_HOME_CIRCULATION_DESC ),
			array(
				'get_help' => $gh_args,
				'rc'       => $rc_args,
			),
			null,
		);
		?>
</main>

<?php get_footer(); ?>
