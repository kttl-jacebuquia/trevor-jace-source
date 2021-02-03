<?php /* Get Involved: Volunteer */ ?>
<?php get_header(); ?>
<?php

use \TrevorWP\Theme\Helper;
use \TrevorWP\Theme\Customizer\Volunteer;

?>
<main id="site-content" role="main" class="site-content">
	<?= Helper\Page_Header::img_bg( [
			'title'   => Volunteer::get_val( Volunteer::SETTING_HOME_HERO_TITLE ),
			'cta_txt' => Volunteer::get_val( Volunteer::SETTING_HOME_HERO_CTA ),
			'img_id'  => Volunteer::get_val( Volunteer::SETTING_HOME_HERO_IMG ),
	] ) ?>

	<div class="pt-20 pb-24 text-white lg:pt-px140 lg:pb-48">
		<div class="container mx-auto site-content-inner text-center">
			<h2 class="font-semibold text-px32 leading-px42 mb-3.5 md:mx-40 lg:text-px46 lg:leading-px56"><?= Volunteer::get_val( Volunteer::SETTING_HOME_TITLE ); ?></h2>
			<p class="text-px18 leading-px26 mb-px60 font-normal md:mx-9 md:mb-20 lg:text-px24 lg:leading-px36 lg:mb-px120 lg:mx-44"><?= Volunteer::get_val( Volunteer::SETTING_HOME_DESC ); ?></p>

			<div class="flex flex-col lg:container mb-px72 md:mb-20 md:flex-row lg:mb-28">
				<div class="bg-white rounded-px10 mb-7 md:flex-1 md:mb-0 lg:max-w-px500" data-aspectRatio="1:1">
					<?= Helper\Thumbnail::print_img_variants( [
							[
									intval( Volunteer::get_val( Volunteer::SETTING_HOME_1_IMG ) ),
									Helper\Thumbnail::variant( Helper\Thumbnail::SCREEN_MD, null, Helper\Thumbnail::SIZE_MD, [
											'class' => [
													'object-center',
													'object-cover',
													'rounded-px10',
											]
									] ),
							],
					] ) ?>
				</div>
				<div class="text-center md:flex-1 md:flex md:flex-col md:justify-center md:items-center">
					<p class="text-px24 leading-px28 tracking-em001 md:leading-px32 mx-4 lg:text-px26 lg:leading-px36 lg:mx-auto lg:max-w-sm">
						<?= Volunteer::get_val( Volunteer::SETTING_HOME_1_DESC ) ?>
					</p>
				</div>
			</div>
			<div class="flex flex-col lg:container mb-px72 md:mb-20 md:flex-row-reverse lg:mb-28">
				<div class="bg-white rounded-px10 mb-7 md:flex-1 md:mb-0 lg:max-w-px500" data-aspectRatio="1:1">
					<?= Helper\Thumbnail::print_img_variants( [
							[
									intval( Volunteer::get_val( Volunteer::SETTING_HOME_2_IMG ) ),
									Helper\Thumbnail::variant( Helper\Thumbnail::SCREEN_MD, null, Helper\Thumbnail::SIZE_MD, [
											'class' => [
													'object-center',
													'object-cover',
													'rounded-px10',
											]
									] ),
							],
					] ) ?>
				</div>
				<div class="text-center md:flex-1 md:flex md:flex-col md:justify-center md:items-center">
					<p class="text-px24 leading-px28 tracking-em001 md:leading-px32 mx-4 lg:text-px26 lg:leading-px36 lg:mx-auto lg:max-w-sm">
						<?= Volunteer::get_val( Volunteer::SETTING_HOME_2_DESC ) ?>
					</p>
				</div>
			</div>
			<div class="flex flex-col lg:container md:flex-row">
				<div class="bg-white rounded-px10 mb-7 md:flex-1 md:mb-0 lg:max-w-px500" data-aspectRatio="1:1">
					<?= Helper\Thumbnail::print_img_variants( [
							[
									intval( Volunteer::get_val( Volunteer::SETTING_HOME_3_IMG ) ),
									Helper\Thumbnail::variant( Helper\Thumbnail::SCREEN_MD, null, Helper\Thumbnail::SIZE_MD, [
											'class' => [
													'object-center',
													'object-cover',
													'rounded-px10',
											]
									] ),
							],
					] ) ?>
				</div>
				<div class="text-center md:flex-1 md:flex md:flex-col md:justify-center md:items-center">
					<p class="text-px24 leading-px28 tracking-em001 md:leading-px32 mx-4 lg:text-px26 lg:leading-px36 lg:mx-auto lg:max-w-sm">
						<?= Volunteer::get_val( Volunteer::SETTING_HOME_3_DESC ) ?>
					</p>
				</div>
			</div>
		</div>

	</div>

	<?= Helper\Carousel::testimonials(
			Volunteer::get_val( Volunteer::SETTING_HOME_TESTIMONIALS ),
			[]
	) ?>

	<div class="pt-20 pb-24 text-white lg:pt-36">
		<div class="container mx-auto site-content-inner text-center">
			<h2 class="font-semibold text-px32 leading-px42 mb-3.5 lg:text-px46 lg:leading-px56">
				<?= Volunteer::get_val( Volunteer::SETTING_HOME_COUNSELOR_TITLE ) ?>
			</h2>
			<p class="text-px18 leading-px26 mb-px50 md:mx-20 lg:text-px26 lg:leading-px36 lg:mb-px60 lg:mx-44">
				<?= Volunteer::get_val( Volunteer::SETTING_HOME_COUNSELOR_DESC ) ?>
			</p>

			<div class="mx-auto grid grid-cols-1 gap-y-7 gap-x-8 md:w-3/4 lg:w-full lg:grid-cols-2 xl:w-3/4">
				<div class="bg-white rounded-px10 pt-9 px-7 pb-14 text-teal-dark md:text-left md:p-9 lg:p-12 flex flex-col">
					<h3 class="text-px26 leading-px36 mb-2 lg:text-px30 lg:leading-px40 font-semibold mx-12 md:mx-0">
						<?= Volunteer::get_val( Volunteer::SETTING_HOME_COUNSELOR_SPECS_TITLE ) ?>
					</h3>
					<p class="text-px16 leading-px24 mb-7 lg:text-px18 lg:leading-px26">
						<?= Volunteer::get_val( Volunteer::SETTING_HOME_COUNSELOR_SPECS_DESC ) ?>
					</p>
					<ul class="mb-6 text-left list-check">
						<li class="text-px14 leading-px20 mb-3.5 lg:text-px18 lg:leading-px26">
							Minimum one year committment
						</li>
						<li class="text-px14 leading-px20 mb-3.5 lg:text-px18 lg:leading-px26">
							Access to a private space
						</li>
						<li class="text-px14 leading-px20 mb-3.5 lg:text-px18 lg:leading-px26">
							Pass a background check
						</li>
						<li class="text-px14 leading-px20 mb-3.5 lg:text-px18 lg:leading-px26">
							Access to a personal computer with broadband connection
						</li>
					</ul>
					<a href="#"
					   class="text-px20 leading-px26 border-b border-teal-dark mt-7 self-center font-bold md:self-start md:mt-0">See
						Details</a>
				</div>

				<div class="bg-white rounded-px10 pt-9 px-7 pb-7 text-teal-dark md:text-left md:p-9 lg:p-12">
					<h3 class="text-px26 leading-px36 mb-2 lg:text-px30 lg:leading-px40 font-semibold">
						<?= Volunteer::get_val( Volunteer::SETTING_HOME_COUNSELOR_APPLY_TITLE ) ?>
					</h3>
					<p class="text-px16 leading-px24 mb-7 lg:text-px18 lg:leading-px26">
						<?= Volunteer::get_val( Volunteer::SETTING_HOME_COUNSELOR_APPLY_DESC ) ?>
					</p>
					<ol class="mb-6 text-left list-decimal">
						<li class="text-px14 leading-px20 mb-3.5 lg:text-px18 lg:leading-px26">
							Click on “Apply Today” and register on our volunteer portal.
						</li>
						<li class="text-px14 leading-px20 mb-3.5 lg:text-px18 lg:leading-px26">
							Complete and submit a volunteer application.
						</li>
						<li class="text-px14 leading-px20 mb-3.5 lg:text-px18 lg:leading-px26">
							Our volunteer recruitment teamwill review and then reach outwithin weeks or months depending
							on capacity.
						</li>
						<li class="text-px14 leading-px20 mb-3.5 lg:text-px18 lg:leading-px26">
							Once you’re accepted intotraining, you’ll need to completea background check for $55 (before
							taxes).
						</li>
					</ol>
					<p class="text-left text-px14 leading-px18">*Financial assistance is available.</p>
				</div>
			</div>

			<div class="justify-center py-10">
				<a href="#"
				   class="inline-block font-bold text-teal-dark bg-white py-3 px-8 rounded-px10 md:px-8 lg:text-px20 lg:leading-px26 lg:py-5 lg:px-10">
					<?= Volunteer::get_val( Volunteer::SETTING_HOME_COUNSELOR_CTA ) ?>
				</a>
			</div>
		</div>
	</div>

	<div class="pt-20 pb-24 text-white lg:pt-24 bg-teal-dark">
		<div class="container mx-auto site-content-inner text-center">
			<h2 class="font-semibold text-px32 leading-px42 mb-3.5">
				<?= Volunteer::get_val( Volunteer::SETTING_HOME_REASONS_TITLE ) ?>
			</h2>
			<p class="text-px18 leading-px26 mb-px50 md:mx-20 lg:text-px26 lg:leading-px36 lg:mb-px60 lg:mx-44">
				<?= Volunteer::get_val( Volunteer::SETTING_HOME_REASONS_DESC ) ?>
			</p>

			<div class="grid grid-cols-2 gap-y-12 gap-x-3 md:mx-20 lg:grid-cols-4">
				<div>
					<p class="volunteer-icon volunteer-icon-1 text-px20 leading-px30 lg:text-px22 lg:leading-px32px">
						<?= Volunteer::get_val( Volunteer::SETTING_HOME_REASONS_TXT_1 ) ?>
					</p>
				</div>
				<div>
					<p class="volunteer-icon volunteer-icon-2 text-px20 leading-px30 lg:text-px22 lg:leading-px32px">
						<?= Volunteer::get_val( Volunteer::SETTING_HOME_REASONS_TXT_2 ) ?>
					</p>
				</div>
				<div>
					<p class="volunteer-icon volunteer-icon-3 text-px20 leading-px30 lg:text-px22 lg:leading-px32px">
						<?= Volunteer::get_val( Volunteer::SETTING_HOME_REASONS_TXT_3 ) ?>
					</p>
				</div>
				<div>
					<p class="volunteer-icon volunteer-icon-4 text-px20 leading-px30 lg:text-px22 lg:leading-px32px">
						<?= Volunteer::get_val( Volunteer::SETTING_HOME_REASONS_TXT_4 ) ?>
					</p>
				</div>
			</div>
		</div>
	</div>

	<div class="pt-20 pb-24 text-indigo bg-white lg:pt-24">
		<div class="container mx-auto site-content-inner text-center">
			<h2 class="font-semibold text-px32 leading-px42 mb-3.5 mb-px60 mx-4 md:mx-20 md:mb-10 lg:text-px46 lg:leading-px56 lg:mb-20">
				There are other ways to help.</h2>

			<div class="grid grid-cols-1 gap-y-6 max-w-lg mx-auto lg:grid-cols-2 lg:gap-x-7 lg:max-w-none xl:max-w-px1240">
				<?= Helper\Circulation_Card::render_donation(); ?>
				<?= Helper\Circulation_Card::render_fundraiser(); ?>
			</div>
		</div>
	</div>

</main>
<?php get_footer(); ?>
