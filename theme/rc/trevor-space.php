<?php /* Resources Center: Trevorspace */ ?>
<?php get_header(); ?>
<?php

use TrevorWP\Theme\Customizer\Resource_Center;
use TrevorWP\Theme\Helper\Circulation_Card;
use TrevorWP\Theme\Helper\Thumbnail;
use TrevorWP\Theme\Customizer;
use TrevorWP\Theme\Helper;

$user_count       = get_option( \TrevorWP\Main::OPTION_KEY_TREVORSPACE_ACTIVE_COUNT, 0 );
$user_threshold   = 30;
$online_count_txt = $user_count > $user_threshold
		? sprintf( '%d members currently online', $user_count )
		: '';

?>

<main id="site-content" role="main" class="site-content">
	<div>
		<div class="container mx-auto text-center site-content-inner">
			<h5 class="text-px16 leading-px24 mb-2 text-indigo md:mb-4">
				<i class="trevor-ti-online mr-2"></i> <?= $online_count_txt ?>
			</h5>
			<h1 class="heading-lg-tilted text-indigo mb-4"><?= Resource_Center::get_val( Resource_Center::SETTING_TREVORSPACE_TITLE ) ?></h1>
			<p class="text-px18 mb-7 text-indigo md:text-px20 md:leading-px30 md:mx-px88 md:mb-5 lg:text-px26 lg:leading-px36 lg:mb-14 lg:mx-64"><?= Resource_Center::get_val( Resource_Center::SETTING_TREVORSPACE_DESC ) ?></p>

			<a href="<?= Resource_Center::get_val( Resource_Center::SETTING_TREVORSPACE_JOIN_URL ) ?>"
			   rel="noreferrer noopener"
			   target="_blank"
			   class="block text-white font-bold text-px18 leading-px24 bg-indigo py-4 px-8 rounded-px10 mx-auto md:text-px16 md:leading-px22 lg:mb-9 lg:text-px22 lg:leading-px22 lg:px-20 lg:py-6">
				<?= Resource_Center::get_val( Resource_Center::SETTING_TREVORSPACE_JOIN_CTA ) ?>
			</a>
			<div class="login-row mt-5 mb-20 text-indigo md:mb-28 lg:mb-40">
				Already member?
				<a href="<?= Resource_Center::get_val( Resource_Center::SETTING_TREVORSPACE_LOGIN_URL ) ?>"
				   rel="noreferrer noopener" target="_blank" class="btn-link">
					<?= Resource_Center::get_val( Resource_Center::SETTING_TREVORSPACE_LOGIN_CTA ) ?>
				</a>
			</div>

			<div class="flex flex-col mb-14 md:flex-row lg:flex-row-reverse lg:mx-px106 lg:mb-28">
				<div class="bg-white rounded-px10 mb-7 md:flex-1 md:mb-0" data-aspectRatio="1:1">
					<?= wp_get_attachment_image( Customizer\Resource_Center::get_val( Customizer\Resource_Center::SETTING_TREVORSPACE_1_IMG ), 'medium', false, [
							'class' => implode( ' ', [
									'object-center',
									'object-cover',
									'rounded-px10',
							] )
					] ) ?>
				</div>
				<div class="text-center text-indigo px-5 md:flex-1 md:flex md:flex-col md:justify-center md:text-left md:pl-11 md:pr-0 lg:pl-0 lg:pr-16">
					<div class="text-indigo font-bold text-px14 leading-px18 tracking-px05 mb-2 md:tracking-em001 uppercase lg:mb-4">
						<?= Resource_Center::get_val( Resource_Center::SETTING_TREVORSPACE_1_TITLE_TOP ) ?>
					</div>
					<h3 class="font-bold text-px34 leading-px40 mb-5 md:text-px30 md:leading-px40 md:mb-4 lg:text-px40 lg:leading-px50">
						<?= Resource_Center::get_val( Resource_Center::SETTING_TREVORSPACE_1_TITLE ) ?>
					</h3>
					<p class="text-px20 leading-px30 md:text-px18 lg:text-10 lg:text-px22 lg:leading-px32">
						<?= Resource_Center::get_val( Resource_Center::SETTING_TREVORSPACE_1_DESC ) ?>
					</p>
				</div>
			</div>
			<div class="flex flex-col mb-14 md:flex-row-reverse lg:flex-row lg:mx-px106 lg:mb-28">
				<div class="bg-white rounded-px10 mb-7 md:flex-1 md:mb-0" data-aspectRatio="1:1">
					<?= wp_get_attachment_image( Customizer\Resource_Center::get_val( Customizer\Resource_Center::SETTING_TREVORSPACE_2_IMG ), 'medium', false, [
							'class' => implode( ' ', [
									'object-center',
									'object-cover',
									'rounded-px10',
							] )
					] ) ?>
				</div>
				<div class="text-center text-indigo px-5 md:flex-1 md:flex md:flex-col md:justify-center md:text-left md:pl-0 md:pr-11 lg:pl-16 lg:pr-0">
					<div class="text-indigo font-bold text-px14 leading-px18 tracking-px05 mb-2 md:tracking-em001 uppercase lg:mb-4">
						<?= Resource_Center::get_val( Resource_Center::SETTING_TREVORSPACE_2_TITLE_TOP ) ?>
					</div>
					<h3 class="font-bold text-px34 leading-px40 mb-5 md:text-px30 md:leading-px40 md:mb-4 lg:text-px40 lg:leading-px50">
						<?= Resource_Center::get_val( Resource_Center::SETTING_TREVORSPACE_2_TITLE ) ?>
					</h3>
					<p class="text-px20 leading-px30 mb-6 md:leading-px32 lg:text-px22 lg:leading-px32">
						<?= Resource_Center::get_val( Resource_Center::SETTING_TREVORSPACE_2_DESC ) ?>
					</p>
				</div>
			</div>
			<div class="flex flex-col mb-20 md:flex-row md:mb-28 lg:flex-row-reverse lg:mx-px106">
				<div class="bg-white rounded-px10 mb-7 md:flex-1 md:mb-0" data-aspectRatio="1:1">
					<?= wp_get_attachment_image( Customizer\Resource_Center::get_val( Customizer\Resource_Center::SETTING_TREVORSPACE_3_IMG ), 'medium', false, [
							'class' => implode( ' ', [
									'object-center',
									'object-cover',
									'rounded-px10',
							] )
					] ) ?>
				</div>
				<div class="text-center text-indigo px-5 md:flex-1 md:flex md:flex-col md:justify-center md:text-left md:pl-11 md:pr-0 lg:pr-16 lg:pl-0">
					<div class="text-indigo font-bold text-px14 leading-px18 tracking-px05 mb-2 md:tracking-em001 uppercase lg:mb-4">
						<?= Resource_Center::get_val( Resource_Center::SETTING_TREVORSPACE_3_TITLE_TOP ) ?>
					</div>
					<h3 class="font-bold text-px34 leading-px40 mb-5 md:text-px30 md:leading-px40 md:mb-4 lg:text-px40 lg:leading-px50">
						<?= Resource_Center::get_val( Resource_Center::SETTING_TREVORSPACE_3_TITLE ) ?>
					</h3>
					<p class="text-px20 leading-px30 mb-6 md:text-px18 lg:text-px22 lg:leading-px32">
						<?= Resource_Center::get_val( Resource_Center::SETTING_TREVORSPACE_3_DESC ) ?>
					</p>
				</div>
			</div>
		</div>
	</div>

	<div class="bg-white">
		<div class="container mx-auto text-center text-indigo site-content-inner pt-20 pb-16 lg:pt-36 lg:pb-28">
			<h3 class="text-px40 leading-px52 tracking-px_015 font-semibold mb-4 md:text-px32 md:leading-px42 lg:text-px46 lg:leading-px56">
				<?= Resource_Center::get_val( Resource_Center::SETTING_TREVORSPACE_CIRCULATION_TITLE ) ?>
			</h3>
			<p class="text-px20 leading-px30 tracking-em005 mb-14 md:text-px16 md:leading-px22 md:mb-px50 md:mx-24 lg:text-px26 lg:leading-px36 lg:mb-20 lg:mx-64">
				<?= Resource_Center::get_val( Resource_Center::SETTING_TREVORSPACE_CIRCULATION_DESC ) ?>
			</p>

			<div class="grid grid-cols-1 gap-y-6 max-w-lg mx-auto lg:grid-cols-2 lg:gap-x-7 lg:max-w-none">
				<?= Circulation_Card::render_get_help(); ?>
				<?= Circulation_Card::render_rc(); ?>
			</div>
		</div>
	</div>
</main>

<?php get_footer(); ?>
