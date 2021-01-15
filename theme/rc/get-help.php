<?php /* Resources Center: Get Help */ ?>
<?php get_header(); ?>
<?php

use TrevorWP\Theme\Customizer\Resource_Center;
use \TrevorWP\Theme\Helper\Circulation_Card;

?>
<main id="site-content" role="main" class="site-content">
	<div>
		<div class="container mx-auto text-center site-content-inner">
			<h1 class="heading-lg-tilted text-indigo mb-4"><?= Resource_Center::get_val( Resource_Center::SETTING_GET_HELP_1_TITLE ) ?></h1>
			<p class="text-px18 mx-4 mb-10 text-indigo md:text-px20 md:leading-px30 md:mx-px88 md:mb-12 lg:text-px26 lg:leading-px36 lg:mb-14 lg:mx-52"><?= Resource_Center::get_val( Resource_Center::SETTING_GET_HELP_1_DESC ) ?></p>
			<div class="flex flex-col md:flex-row justify-center space-y-5 md:space-y-0 md:space-x-6 md:mx-px60 lg:mx-80">
				<a href="#"
				   class="btn flex flex-col text-px20 leading-px22 pt-4 pb-5 px-4 flex-1 justify-center md:p-8">
					<i class="trevor-ti-chat text-white text-px52 my-2"></i>
					<?= Resource_Center::get_val( Resource_Center::SETTING_GET_HELP_CHAT_BTN_CTA ) ?>
				</a>
				<a href="#"
				   class="btn flex flex-col text-px20 leading-px22 pt-4 pb-5 px-4 flex-1 justify-center md:p-8">
					<i class="trevor-ti-call text-white text-px42 my-2"></i>
					<?= Resource_Center::get_val( Resource_Center::SETTING_GET_HELP_CALL_BTN_CTA ) ?>
				</a>
				<a href="#"
				   class="btn flex flex-col text-px20 leading-px22 pt-4 pb-5 px-4 flex-1 justify-center md:p-8">
					<i class="trevor-ti-smartphone text-white text-px46 my-2"></i>
					<?= Resource_Center::get_val( Resource_Center::SETTING_GET_HELP_TEXT_BTN_CTA ) ?>
				</a>
			</div>

			<div class="mt-12 text-center animate-bounce lg:mt-px60">
				<i class="trevor-ti-chevron-down text-4xl text-center lg:text-5xl"></i>
			</div>

			<div class="mt-7 mb-14 text-center text-indigo md:mb-20 lg:mt-32 lg:mx-52">
				<h2 class="font-bold text-px28 leading-px38 tracking-px_015 mb-4 md:text-px32 md:leading-px42 lg:text-px46 lg:leading-px56"><?= Resource_Center::get_val( Resource_Center::SETTING_GET_HELP_2_TITLE ) ?></h2>
				<p class="text-px20 leading-px30 md:mx-6 lg:text-px26 lg:leading-px36 lg:mx-0"><?= Resource_Center::get_val( Resource_Center::SETTING_GET_HELP_2_DESC ) ?></p>
			</div>

			<div class="flex flex-col mb-px72 md:flex-row lg:mx-px106 lg:mb-28">
				<div class="bg-white rounded-px10 mb-7 md:flex-1 md:mb-0" data-aspectRatio="1:1"></div>
				<div class="text-center text-indigo px-5 md:flex-1 md:flex md:flex-col md:justify-center md:items-center">
					<div class="mx-4 lg:mx-14">
						<h3 class="font-semibold text-px24 leading-px28 mb-5 md:text-px28 md:leading-px38 lg:text-px40 lg:leading-px48"><?= Resource_Center::get_val( Resource_Center::SETTING_GET_HELP_TEXT_TITLE ) ?></h3>
						<p class="text-px18 leading-px24 mb-6 md:text-px16 lg:text-px22 lg:leading-px32 lg:mx-10"><?= Resource_Center::get_val( Resource_Center::SETTING_GET_HELP_TEXT_DESC ) ?></p>
					</div>
					<a href="<?= Resource_Center::get_val( Resource_Center::SETTING_GET_HELP_TEXT_CTA_ACTION ) ?>"
					   class="btn block text-center text-px18 leading-px24 mb-4 md:text-px16 md:leading-px22"><?= Resource_Center::get_val( Resource_Center::SETTING_GET_HELP_TEXT_CTA ) ?></a>
					<a href="#" class="btn-link text-px18 leading-px24" id="modal-btn-text">What to expect +</a>
				</div>
			</div>
			<div class="flex flex-col mb-px72 md:flex-row-reverse lg:mx-px106 lg:mb-28">
				<div class="bg-white rounded-px10 mb-7 md:flex-1 md:mb-0" data-aspectRatio="1:1"></div>
				<div class="text-center text-indigo px-5 md:flex-1 md:flex md:flex-col md:justify-center md:items-center">
					<div class="mx-4 lg:mx-12">
						<h3 class="font-semibold text-px24 leading-px28 mb-5 md:text-px28 md:leading-px38 lg:text-px40 lg:leading-px48"><?= Resource_Center::get_val( Resource_Center::SETTING_GET_HELP_CALL_TITLE ) ?></h3>
						<p class="text-px18 leading-px24 mb-6 md:text-px16 lg:text-px22 lg:leading-px32 lg:mx-10"><?= Resource_Center::get_val( Resource_Center::SETTING_GET_HELP_CALL_DESC ) ?></p>
					</div>
					<a href="#"
					   class="btn block text-center text-px18 leading-px24 mb-4 md:text-px16 md:leading-px22"><?= Resource_Center::get_val( Resource_Center::SETTING_GET_HELP_CALL_CTA ) ?></a>
					<a href="#" class="btn-link text-px18 leading-px24" id="modal-btn-call">What to expect +</a>
				</div>
			</div>
			<div class="flex flex-col mb-20 md:flex-row lg:mx-px106 lg:mb-28">
				<div class="bg-white rounded-px10 mb-7 md:flex-1 md:mb-0" data-aspectRatio="1:1"></div>
				<div class="text-center text-indigo px-5 md:flex-1 md:flex md:flex-col md:justify-center md:items-center">
					<div class="mx-4 lg:mx-14">
						<h3 class="font-semibold text-px24 leading-px28 mb-5 md:text-px28 md:leading-px38 lg:text-px40 lg:leading-px48"><?= Resource_Center::get_val( Resource_Center::SETTING_GET_HELP_CHAT_TITLE ) ?></h3>
						<p class="text-px18 leading-px24 mb-6 md:text-px16 lg:text-px22 lg:leading-px32 lg:mx-10"><?= Resource_Center::get_val( Resource_Center::SETTING_GET_HELP_CHAT_DESC ) ?></p>
					</div>
					<a href="#"
					   class="btn block text-center text-px18 leading-px24 mb-4 md:text-px16 md:leading-px22"><?= Resource_Center::get_val( Resource_Center::SETTING_GET_HELP_CHAT_CTA ) ?></a>
					<a href="#" class="btn-link text-px18 leading-px24" id="modal-btn-chat">What to expect +</a>
				</div>
			</div>

			<div class="flex flex-col items-center bg-white rounded-px10 mb-20 py-14 px-7 text-indigo md:py-12 md:px-10 md:mb-px88 lg:mx-px106 lg:px-px106 lg:py-px72 lg:mb-28">
				<p class="text-px18 leading-px22 tracking-px05 mb-6 md:text-px20 md:leading-px30 md:mb-7 lg:text-px24 lg:leading-px36"><?= Resource_Center::get_val( Resource_Center::SETTING_GET_HELP_NOTIFICATION_TXT ) ?></p>
				<a href="#" class="btn-link text-px18 leading-px24 lg:text-px22 lg:leading-px26">Learn more</a>
			</div>
		</div>
	</div>

	<!--	<div class="bg-violet-lighter">-->
	<!--		<div class="container mx-auto text-center text-indigo site-content-inner">-->
	<!--			<div class="flex flex-col items-center py-64 md:py-72 lg:py-64">-->
	<!--				<h4 class="font-semibold text-px38 leading-px48 mb-4 md:text-px32 md:leading-px42 lg:text-px46 lg:leading-px56">-->
	<? //= Resource_Center::get_val( Resource_Center::SETTING_GET_HELP_EXERCISE_TITLE ) ?><!--</h4>-->
	<!--				<p class="text-px20 leading-px30 mb-10 md:text-px16 md:leading-px22 md:mb-px30 md:mx-12 lg:text-px22 lg:leading-px34 lg:mx-80 lg:mb-10">-->
	<? //= Resource_Center::get_val( Resource_Center::SETTING_GET_HELP_EXERCISE_DESC ) ?><!--</p>-->
	<!--				<a href="#"-->
	<!--				   class="btn btn-secondary inline-flex text-px18 leading-px24 md:text-px16 md:leading-px22">-->
	<? //= Resource_Center::get_val( Resource_Center::SETTING_GET_HELP_EXERCISE_CTA ) ?><!--</a>-->
	<!--			</div>-->
	<!--		</div>-->
	<!--	</div>-->

	<div class="bg-white">
		<div class="container mx-auto text-center text-indigo site-content-inner pt-20 pb-16 lg:pt-36 lg:pb-28">
			<h3 class="text-px32 leading-px42 tracking-px05 font-semibold mb-5 lg:text-px46 lg:leading-px56"><?= Resource_Center::get_val( Resource_Center::SETTING_GET_HELP_CIRCULATION_TITLE ) ?></h3>
			<p class="text-px22 leading-px34 tracking-em005 mb-14 md:text-px16 md:leading-px22 md:mb-px50 md:mx-24 lg:text-px26 lg:leading-px36 lg:mb-20 lg:mx-64"><?= Resource_Center::get_val( Resource_Center::SETTING_GET_HELP_CIRCULATION_DESC ) ?></p>

			<div class="grid grid-cols-1 gap-y-6 max-w-lg mx-auto lg:grid-cols-2 lg:gap-x-4 lg:max-w-none">
				<?= Circulation_Card::render_trevorspace(); ?>
				<?= Circulation_Card::render_rc(); ?>
			</div>
		</div>
	</div>
</main>
<?php foreach ( [ 'TEXT', 'CALL', 'CHAT' ] as $k ) { ?>
	<?php ob_start(); ?>
	<div class="what-to-expect-modal-content mx-auto">
		<div class="container mx-auto h-full">
			<h2 class="step-list-title">What to Expect</h2>
			<p class="step-list-info">Our services are 24/7/365, nationwide, 100% free & confidential.</p>
			<div class="step-list">
				<?php for ( $i = 1; $i < 4; $i ++ ) { ?>
					<div class="step-row">
						<div class="step-num-wrap"><span class="step-num"><?= $i ?></span></div>
						<div class="step-content"><?= Resource_Center::get_val( constant( Resource_Center::class . "::SETTING_GET_HELP_{$k}_STEP{$i}" ) ) ?></div>
					</div>
				<?php } ?>
			</div>
		</div>
		<div class="terms-wrap">
			<div class="container mx-auto">
				<h3 class="terms-title">Terms of Service Summary</h3>
				<p class="terms-content">
					<?= Resource_Center::get_val( constant( Resource_Center::class . "::SETTING_GET_HELP_{$k}_TERMS" ) ) ?>
				</p>
			</div>
		</div>
	</div>
	<?= ( new \TrevorWP\Theme\Helper\Modal( ob_get_clean(), [
			'target' => '#modal-btn-' . strtolower( $k ),
	] ) )->render() ?>
<?php } ?>

<?php get_footer(); ?>
