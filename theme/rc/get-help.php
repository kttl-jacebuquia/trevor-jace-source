<?php /* Resources Center: Get Help */ ?>
<?php get_header(); ?>
<?php

use TrevorWP\Theme\Customizer\Resource_Center;
use \TrevorWP\Theme\Helper\Circulation_Card;
use TrevorWP\Theme\Helper\Thumbnail;
use TrevorWP\Theme\Customizer;
use TrevorWP\Theme\Helper;

?>
<main id="site-content" role="main" class="site-content get-help">
	<div>
		<div class="container mx-auto text-center site-content-inner">
			<h1 class="heading-lg-tilted text-indigo mb-4"><?php echo Resource_Center::get_val( Resource_Center::SETTING_GET_HELP_1_TITLE ); ?></h1>
			<p class="text-px18 font-normal mx-4 mb-10 text-indigo md:text-px20 md:leading-px30 md:mx-px88 md:mb-12 lg:text-px22 lg:leading-px36 lg:mb-14 xl:mx-52"><?php echo Resource_Center::get_val( Resource_Center::SETTING_GET_HELP_1_DESC ); ?></p>
			<div class="flex flex-col md:flex-row justify-center space-y-5 md:space-y-0 md:space-x-6 md:mx-px60 lg:mx-40 lg2:mx-60 xl:mx-80">
				<a href="#"
				   class="btn bg-orange text-white flex flex-col text-px20 leading-px22 pt-4 pb-5 px-4 flex-1 justify-center md:p-8 lg:p-6">
					<i class="trevor-ti-chat text-white text-px52 my-2 lg:text-px64 lg:mb-4"></i>
					<?php echo Resource_Center::get_val( Resource_Center::SETTING_GET_HELP_CHAT_BTN_CTA ); ?>
				</a>
				<a href="<?php echo Resource_Center::get_val( Resource_Center::SETTING_GET_HELP_CALL_CTA_ACTION ); ?>"
				   class="btn bg-orange text-white flex flex-col text-px20 leading-px22 pt-4 pb-5 px-4 flex-1 justify-center md:p-8 lg:p-6">
					<i class="trevor-ti-call text-white text-px42 my-2 lg:text-px64 lg:mb-4"></i>
					<?php echo Resource_Center::get_val( Resource_Center::SETTING_GET_HELP_CALL_BTN_CTA ); ?>
				</a>
				<a href="#"
				   class="btn bg-orange text-white flex flex-col text-px20 leading-px22 pt-4 pb-5 px-4 flex-1 justify-center md:p-8 lg:p-6">
					<i class="trevor-ti-smartphone text-white text-px46 my-2 lg:text-px64 lg:mb-4"></i>
					<?php echo Resource_Center::get_val( Resource_Center::SETTING_GET_HELP_TEXT_BTN_CTA ); ?>
				</a>
			</div>

				<div class="mt-12 text-center animate-bounce lg:mt-px60">
					<i class="trevor-ti-chevron-down text-indigo text-4xl text-center lg:text-5xl bouncing-arrow cursor-pointer"></i>
				</div>

			<div class="mt-7 mb-14 text-center text-indigo md:mb-20 lg:mt-32 xl:mx-52">
				<h2 class="font-bold text-px28 leading-px38 tracking-px_015 mb-4 md:text-px32 md:leading-px42 lg:text-px32 lg:leading-px56"><?php echo Resource_Center::get_val( Resource_Center::SETTING_GET_HELP_2_TITLE ); ?></h2>
				<p class="font-normal text-px20 leading-px30 md:mx-6 lg:text-px22 lg:leading-px36 lg:mx-0"><?php echo Resource_Center::get_val( Resource_Center::SETTING_GET_HELP_2_DESC ); ?></p>
			</div>

			<div class="flex flex-col mb-px72 md:flex-row xl:mx-px106 lg:mb-28">
				<div class="bg-white rounded-px10 mb-7 md:flex-1 md:mb-0" data-aspectRatio="1:1">
					<?php
					echo Thumbnail::print_img_variants(
						array(
							array(
								intval( Customizer\Resource_Center::get_val( Customizer\Resource_Center::SETTING_GET_HELP_TEXT_IMG ) ),
								Helper\Thumbnail::variant(
									Helper\Thumbnail::SCREEN_MD,
									null,
									Helper\Thumbnail::SIZE_MD,
									array(
										'class' => array(
											'object-center',
											'object-cover',
											'rounded-px10',
										),
									)
								),
							),
						)
					)
					?>
				</div>
				<div class="text-center text-indigo px-5 flex flex-col md:flex-1 md:justify-center md:items-center">
					<div class="mx-4 lg:mx-14">
						<h3 class="font-semibold text-px24 leading-px28 mb-5 md:text-px28 md:leading-px38 lg:text-px30 lg:leading-px40 xl:text-px40 xl:leading-px48"><?php echo Resource_Center::get_val( Resource_Center::SETTING_GET_HELP_TEXT_TITLE ); ?></h3>
						<p class="text-px18 leading-px24 mb-6 md:text-px16 lg:text-px18 lg:leading-px26 lg:mx-10 xl:text-px22 xl:leading-px32"><?php echo Resource_Center::get_val( Resource_Center::SETTING_GET_HELP_TEXT_DESC ); ?></p>
					</div>
					<a href="<?php echo Resource_Center::get_val( Resource_Center::SETTING_GET_HELP_TEXT_CTA_ACTION ); ?>"
					   class="btn bg-orange text-white inline-block text-center text-px18 self-center leading-px24 mb-4 md:text-px16 md:leading-px22"><?php echo Resource_Center::get_val( Resource_Center::SETTING_GET_HELP_TEXT_CTA ); ?></a>
					<a href="#" class="btn-link text-px18 leading-px24 self-center" id="modal-btn-text">What to expect
						+</a>
				</div>
			</div>
			<div class="flex flex-col mb-px72 md:flex-row-reverse xl:mx-px106 lg:mb-28">
				<div class="bg-white rounded-px10 mb-7 md:flex-1 md:mb-0" data-aspectRatio="1:1">
					<?php
					echo Thumbnail::print_img_variants(
						array(
							array(
								intval( Customizer\Resource_Center::get_val( Customizer\Resource_Center::SETTING_GET_HELP_CALL_IMG ) ),
								Helper\Thumbnail::variant(
									Helper\Thumbnail::SCREEN_MD,
									null,
									Helper\Thumbnail::SIZE_MD,
									array(
										'class' => array(
											'object-center',
											'object-cover',
											'rounded-px10',
										),
									)
								),
							),
						)
					)
					?>
				</div>
				<div class="text-center text-indigo md:px-5 flex flex-col md:flex-1 md:justify-center md:items-center">
					<div class="mx-4 lg:mx-12">
						<h3 class="font-semibold text-px24 leading-px28 mb-5 md:text-px28 md:leading-px38 lg:text-px30 lg:leading-px40 xl:text-px40 xl:leading-px48"><?php echo Resource_Center::get_val( Resource_Center::SETTING_GET_HELP_CALL_TITLE ); ?></h3>
						<p class="text-px18 leading-px24 mb-6 md:text-px16 lg:text-px18 lg:leading-px26 lg:mx-10 xl:text-px22 xl:leading-px32"><?php echo Resource_Center::get_val( Resource_Center::SETTING_GET_HELP_CALL_DESC ); ?></p>
					</div>
					<a href="#"
					   class="btn bg-orange text-white inline-block text-center text-px18 self-center leading-px24 mb-4 md:text-px16 md:leading-px22"><?php echo Resource_Center::get_val( Resource_Center::SETTING_GET_HELP_CALL_CTA ); ?></a>
					<a href="#" class="btn-link text-px18 leading-px24 self-center" id="modal-btn-call">What to expect
						+</a>
				</div>
			</div>
			<div class="flex flex-col mb-20 md:flex-row xl:mx-px106 lg:mb-28">
				<div class="bg-white rounded-px10 mb-7 md:flex-1 md:mb-0" data-aspectRatio="1:1">
					<?php
					echo Thumbnail::print_img_variants(
						array(
							array(
								intval( Customizer\Resource_Center::get_val( Customizer\Resource_Center::SETTING_GET_HELP_CHAT_IMG ) ),
								Helper\Thumbnail::variant(
									Helper\Thumbnail::SCREEN_MD,
									null,
									Helper\Thumbnail::SIZE_MD,
									array(
										'class' => array(
											'object-center',
											'object-cover',
											'rounded-px10',
										),
									)
								),
							),
						)
					)
					?>
				</div>
				<div class="text-center text-indigo px-5 flex flex-col md:flex-1 md:justify-center md:items-center">
					<div class="mx-4 lg:mx-14">
						<h3 class="font-semibold text-px24 leading-px28 mb-5 md:text-px28 md:leading-px38 lg:text-px30 lg:leading-px40 xl:text-px40 xl:leading-px48"><?php echo Resource_Center::get_val( Resource_Center::SETTING_GET_HELP_CHAT_TITLE ); ?></h3>
						<p class="text-px18 leading-px24 mb-6 md:text-px16 lg:text-px18 lg:leading-px26 lg:mx-10 xl:text-px22 xl:leading-px32"><?php echo Resource_Center::get_val( Resource_Center::SETTING_GET_HELP_CHAT_DESC ); ?></p>
					</div>
					<a href="#"
					   class="btn bg-orange text-white inline-block text-center text-px18 self-center leading-px24 mb-4 md:text-px16 md:leading-px22"><?php echo Resource_Center::get_val( Resource_Center::SETTING_GET_HELP_CHAT_CTA ); ?></a>
					<a href="#" class="btn-link text-px18 leading-px24 self-center" id="modal-btn-chat">What to expect
						+</a>
				</div>
			</div>

			<div class="flex flex-col items-center bg-white rounded-px10 mb-20 py-14 px-7 text-indigo md:py-12 md:px-10 md:mb-px88 xl:mx-px106 lg:px-px106 lg:py-px72 lg:mb-28">
				<p class="text-px18 leading-px22 tracking-px05 mb-6 md:text-px20 md:leading-px30 md:mb-7 lg:text-px20 lg:leading-px32"><?php echo Resource_Center::get_val( Resource_Center::SETTING_GET_HELP_NOTIFICATION_TXT ); ?></p>
				<a href="#" class="btn-link text-px18 leading-px24 self-center lg:text-px22 lg:leading-px26">Learn
					more</a>
			</div>
		</div>
	</div>

	<?php
	echo Helper\Circulation_Card::render_circulation(
		Resource_Center::get_val( Resource_Center::SETTING_GET_HELP_CIRCULATION_TITLE ),
		Resource_Center::get_val( Resource_Center::SETTING_GET_HELP_CIRCULATION_DESC ),
		array(
			'trevorspace',
			'rc',
		),
		array(
			'wrapper' => 'text-indigo',
		)
	);
	?>

</main>
<?php foreach ( array( 'TEXT', 'CALL', 'CHAT' ) as $k ) { ?>
	<?php ob_start(); ?>
	<div class="what-to-expect-modal-content mx-auto">
		<div class="container mx-auto h-full">
			<h2 class="step-list-title">What to Expect</h2>
			<p class="step-list-info">Our services are 24/7/365, nationwide, 100% free & confidential.</p>
			<div class="step-list">
				<?php for ( $i = 1; $i < 4; $i ++ ) { ?>
					<div class="step-row">
						<div class="step-num-wrap"><span class="step-num"><?php echo $i; ?></span></div>
						<div class="step-content"><?php echo Resource_Center::get_val( constant( Resource_Center::class . "::SETTING_GET_HELP_{$k}_STEP{$i}" ) ); ?></div>
					</div>
				<?php } ?>
			</div>
		</div>
		<div class="terms-wrap">
			<div class="container mx-auto">
				<h3 class="terms-title">Terms of Service Summary</h3>
				<p class="terms-content">
					<?php echo Resource_Center::get_val( constant( Resource_Center::class . "::SETTING_GET_HELP_{$k}_TERMS" ) ); ?>
				</p>
			</div>
		</div>
	</div>
	<?php
	echo ( new \TrevorWP\Theme\Helper\Modal(
		ob_get_clean(),
		array(
			'target' => '#modal-btn-' . strtolower( $k ),
		)
	) )->render()
	?>
<?php } ?>

<?php get_footer(); ?>
