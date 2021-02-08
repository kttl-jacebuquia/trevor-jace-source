<?php /* Donate: Fundraise */ ?>
<?php get_header(); ?>
<?php

use \TrevorWP\Theme\Helper;
use \TrevorWP\Theme\Customizer\Fundraise;

?>
	<main id="site-content" role="main" class="site-content">

		<?php /* QUESTIONS */ ?>
		<?php $questions_title = Fundraise::get_val( Fundraise::SETTING_QUESTIONS_TITLE ); ?>
		<?php $questions_desc = Fundraise::get_val( Fundraise::SETTING_QUESTIONS_DESC ); ?>
		<?php $questions_cta = Fundraise::get_val( Fundraise::SETTING_QUESTIONS_CTA ); ?>
		<?php $questions_cta_link = Fundraise::get_val( Fundraise::SETTING_QUESTIONS_CTA_LINK ); ?>
		<div class="questions">
			<div class="container mx-auto text-center">
				<div class="one-up-card light my-8 mx-auto md:w-3/4 md:my-6 lg:w-full xl:w-3/4 text-center">
					<h3><?= $questions_title ?></h3>	
					<p><?= $questions_desc ?></p>
					<div><a href="<?= $questions_cta_link?>" class="btn"><?= $questions_cta ?></a></div>
				</div>
			</div>
		</div>

		<?php /* Recirculation */ ?>
		<?php $circulation_title = Fundraise::get_val( Fundraise::SETTING_OTHER_TITLE ); ?>
		<?php /* Two Up: Other Ways to Help   */ ?>
		<div class="other-ways">
			<div class="container mx-auto text-center">
				<h3 class="mb-px60 md:mb-px40 lg:mb-px90"><?= $circulation_title ?></h3>
				<div class="grid grid-cols-1 gap-y-6 max-w-lg mx-auto lg:grid-cols-2 lg:gap-x-7 lg:max-w-none xl:max-w-px1240">
					<?= Helper\Circulation_Card::render_fundraiser(); ?>
					<?= Helper\Circulation_Card::render_counselor(); ?>
				</div>
			</div>
		</div>
	</main>

<?php get_footer();
