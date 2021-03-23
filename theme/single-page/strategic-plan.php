<?php /* Public Education */

use \TrevorWP\Theme\Single_Page\Strategic_Plan as Page;

?>

<?php get_header(); ?>

	<main id="site-content" role="main" class="site-content">
		<?php # Header ?>
		<?= Page::get_component( Page::SECTION_HEADER )->render( [
				'cta_url' => wp_get_attachment_url( Page::get_val( Page::SETTING_HEADER_STRATEGIC_PLAN_FILE ) )
		] ) ?>

		<?php # Info Boxes ?>
		<div class="bg-gray-light py-6 md:py-20 xl:py-36">
			<div class="container mx-auto text-teal-dark">
				<?= Page::get_component( Page::SECTION_INFO_BOXES )->render( [
						'box_text_cls' => [
								'mb-2.5',
								'font-bold',
								'text-px28',
								'leading-px34',
								'tracking-em005',
								'md:text-px30',
								'md:leading-px40',
								'md:tracking-em001',
								'xl:text-px32',
								'xl:leading-px42',
								'xl:tracking-em005'
						],
						'box_desc_cls' => [
								'font-normal',
								'text-px20',
								'leading-px26',
								'md:text-px18',
								'md:leading-px24',
								'md:tracking-px05',
								'xl:text-px22',
								'xl:leading-px32',
						]
				] ) ?>
			</div>
		</div>


		<div class="bg-white text-teal-dark py-20 xl:py-36">
			<div class="container mx-auto">
				<?php # Key Programs ?>
				<div class="flex flex-col xl:flex-none xl:grid xl:grid-cols-12 xl:gap-8 absolute-side-parent">
					<div class="w-screen h-px375 mb-10 -ml-px28 md:mb-12 md:h-px445 md:w-full md:ml-0 xl:h-px706 xl:col-span-5 xl:absolute-left">
						<?= wp_get_attachment_image( Page::get_val( Page::SETTING_KEY_PROGRAMS_IMG ), 'large', null, [ 'class' => 'h-full w-full object-cover md:rounded-px10 xl:rounded-l-none' ] ) ?>
					</div>
					<div class="xl:col-span-7 xl:col-start-7 xl:flex xl:flex-col xl:justify-center">
						<h2 class="page-sub-title centered xl:no-centered"><?= Page::get_val( Page::SETTING_KEY_PROGRAMS_TITLE ) ?></h2>
						<?php if ( $desc = Page::get_val( Page::SETTING_KEY_PROGRAMS_DESC ) ): ?>
							<p class="page-sub-title-desc centered xl:no-centered"><?= $desc ?></p>
						<?php endif; ?>
						<div class="flex flex-col md:flex-row md:flex-wrap md:justify-center">
							<?php foreach ( Page::get_val( Page::SETTING_KEY_PROGRAMS_DATA ) as $item ): ?>
								<div class="text-center my-4 md:my-6 md:w-1/2 xl:text-left">
									<a href="<?= esc_attr( @$item['href'] ) ?>"
									   class="block mb-1 font-bold text-px24 leading-px34 tracking-em005 md:mb-1.5 md:leading-px28 md:tracking-em001 xl:mb-1 xl:text-px28 xl:leading-px38">
										<?= esc_html( @$item['title'] ) ?>
									</a>
									<p class="w-3/4 mx-auto font-normal text-px18 leading-px26 tracking-em005 md:leading-px24 md:tracking-px05 xl:ml-0 xl:text-px20 xl:leading-px28 xl:tracking-em005">
										<?= esc_html( @$item['desc'] ) ?>
									</p>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				</div>

				<?php # Principles ?>
				<div class="flex flex-col-reverse mt-16 md:mt-24 xl:mt-20 xl:flex-none xl:grid xl:grid-cols-12 xl:gap-8 absolute-side-parent">
					<div class="xl:col-span-7 xl:flex xl:flex-col xl:justify-center">
						<h2 class="page-sub-title centered xl:no-centered"><?= Page::get_val( Page::SETTING_PRINCIPLES_TITLE ) ?></h2>
						<?php if ( $desc = Page::get_val( Page::SETTING_PRINCIPLES_DESC ) ): ?>
							<p class="page-sub-title-desc centered xl:no-centered"><?= $desc ?></p>
						<?php endif; ?>
						<div class="flex flex-col mt-8 md:flex-row md:w-3/4 md:mx-auto md:justify-center md:flex-wrap xl:justify-start xl:ml-0">
							<?php foreach ( Page::get_val( Page::SETTING_PRINCIPLES_DATA ) as $item ): ?>
								<div class="mb-5 text-center font-bold text-px22 leading-px28 tracking-em005 md:text-left md:mb-7 md:mr-7"><?= esc_html( @$item['name'] ) ?></div>
							<?php endforeach; ?>
						</div>
						<div class="mt-4 text-center xl:text-left">
							<a class="page-btn primary"
							   href="<?= wp_get_attachment_url( Page::get_val( Page::SETTING_HEADER_STRATEGIC_PLAN_FILE ) ) ?>">
								<?= Page::get_component( Page::SECTION_HEADER )->get_val( \TrevorWP\Theme\Customizer\Component\Header::SETTING_CTA_TXT ) ?>
							</a>
						</div>
					</div>
					<div class="w-screen h-px375 mb-10 -ml-px28 md:mb-12 md:h-px445 md:w-full md:ml-0 xl:h-px706 xl:col-span-5 xl:col-start-8 xl:absolute-right">
						<?= wp_get_attachment_image( Page::get_val( Page::SETTING_PRINCIPLES_IMG ), 'large', null, [ 'class' => 'h-full w-full object-cover md:rounded-px10 xl:rounded-r-none' ] ) ?>
					</div>
				</div>
			</div>
		</div>
	</main>

<?php get_footer();
