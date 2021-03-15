<?php /** Donate: Fundraise */ ?>
<?php get_header(); ?>
<?php

use \TrevorWP\Theme\Helper;
use \TrevorWP\Theme\Customizer\Fundraise;

?>
	<main id="site-content" role="main" class="site-content">

	<?php /** Page Header */ ?>
	<?php
	echo Helper\Page_Header::split_img(
		array(
			'title'   => Fundraise::get_val( Fundraise::SETTING_HOME_HERO_TITLE ),
			'desc'    => Fundraise::get_val( Fundraise::SETTING_HOME_HERO_DESC ),
			'img_id'  => Fundraise::get_val( Fundraise::SETTING_HOME_HERO_IMG ),
			'cta_txt' => Fundraise::get_val( Fundraise::SETTING_HOME_HERO_CTA ),
			'cta_url' => Fundraise::get_val( Fundraise::SETTING_HOME_HERO_CTA_LINK ),
		)
	)
	?>

		<?php /** Featured Text */ ?>
		<?php $title = Fundraise::get_val( Fundraise::SETTING_FEATURED_TEXT_TITLE ); ?>
		<?php $desc = Fundraise::get_val( Fundraise::SETTING_FEATURED_TEXT_DESC ); ?>
		<div class="featured-text">
			<div class="container mx-auto text-center">
				<h3><?php echo esc_html( $title ); ?></h3>
				<p><?php echo $desc; ?></p>
			</div>
		</div>

		<?php /* How your Money is Used */ ?>
		<?php $title = Fundraise::get_val( Fundraise::SETTING_THREE_TITLE ); ?>
		<?php $audit_data = Fundraise::get_val( Fundraise::SETTING_THREE_DATA ); ?>
		<div class="audit audit-fundraise">
			<div class="container mx-auto">
				<h2 class="page-sub-title centered text-white mb-px80 md:mb-px70 lg:mb-px90"><?= esc_html( $title ); ?></h2>

				<div class="audit--card text-center grid grid-cols-1 gap-y-6 max-w-lg mx-auto lg:grid-cols-3 lg:gap-x-7 lg:max-w-none xl:max-w-px1240">

					<div class="audit-holder mobile-only">
						<div class="audit-container swiper-container" id="audit-<?php echo esc_attr( uniqid() ); ?>">
							<div class="audit-wrapper swiper-wrapper">
								<?php foreach ( $audit_data as $audit ) : ?>
									<div class="audit--card__item swiper-slide text-center">
										<?php if ( $audit['img'] ) : ?>
											<img src="<?php echo esc_url( $audit['img']['url'] ); ?>" alt="<?php echo esc_attr( $audit['desc'] ); ?>">
										<?php endif; ?>

										<p><?php echo esc_html( $audit['desc'] ); ?></p>
									</div>
								<?php endforeach; ?>
							</div>
							<div class="swiper-pagination"></div>
						</div>
					</div>

					<?php foreach ( $audit_data as $audit ) : ?>
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

		<?php /* ONE COLUMN */ ?>
		<?php $content = Fundraise::get_val( Fundraise::SETTING_ONE_DATA ); ?>
		<div class="one-column featured-content">
			<div class="container mx-auto text-center">

				<?php foreach ( $content as $col ) : ?>
					<div class="one-column__content featured-content__item text-center">
						<h2 class="page-sub-title centered text-teal-dark"><?= esc_html( $col['title'] ); ?></h2>
						<p class="page-sub-title-desc centered"><?php echo esc_html( $col['desc'] ); ?></p>
						<?php if ( $col['img'] ) : ?>
							<img src="<?php echo esc_url( $col['img']['url'] ); ?>" alt="<?php echo esc_attr( $col['title'] ); ?>">
						<?php endif ?>
						<a href="<?php echo esc_url( $col['cta_link'] ); ?>" class="btn"><?php echo esc_attr( $col['cta_label'] ); ?></a>
					</div>
				<?php endforeach; ?>
			</div>
		</div>

		<?php /** GROUPED BLOCKS */ ?>
		<div class="grouped--block bg-white	">
			<div class="container mx-auto">
				<?php /** FEATURED LINKS */ ?>
				<?php $link_title = Fundraise::get_val( Fundraise::SETTING_LINK_TITLE ); ?>
				<?php $link_desc = Fundraise::get_val( Fundraise::SETTING_LINK_DESC ); ?>
				<?php $link_cta = Fundraise::get_val( Fundraise::SETTING_LINK_DATA ); ?>

				<div class="links">
					<div class="mx-auto text-center">
						<div class="links-wrapper my-8 mx-auto md:w-3/4 md:my-6 lg:w-full xl:w-3/4 text-left">
							<div class="links-content">
								<h3><?php echo esc_html( $link_title ); ?></h3>
								<p><?php echo esc_html( $link_desc ); ?></p>
								<?php if ( $link_cta ) : ?>
									<ul>
										<?php foreach ( $link_cta as $cta ) : ?>
										<li><a href="<?php echo esc_url( $cta['link'] ); ?>"><?php echo esc_attr( $cta['label'] ); ?></a></li>
										<?php endforeach; ?>
									</ul>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>

				<?php /** BECOME A PARTNER */ ?>
				<div class="partner">
					<div class="mx-auto text-center">
						<div class="one-up-card dark my-8 mx-auto md:w-3/4 md:my-6 lg:w-full text-center">
							<h3><?= Fundraise::get_val( Fundraise::SETTING_PARTNER_TITLE ); ?></h3>
							<p><?= wp_filter_kses( Fundraise::get_val( Fundraise::SETTING_PARTNER_DESC ) ); ?></p>
							<div><a href="<?= esc_url( Fundraise::get_val( Fundraise::SETTING_PARTNER_CTA_LINK ) ); ?>" class="btn"><?= esc_html( Fundraise::get_val( Fundraise::SETTING_PARTNER_CTA ) ); ?></a></div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<?php /** Fundraiser Success Stories */ ?>
		<div class="text-teal-dark bg-teal-tint bg-white pb-px40 md:pb-0">
			<?= \TrevorWP\Theme\Helper\Carousel::posts( ( new WP_Query() )->query( [
					'post_type'      => \TrevorWP\CPT\Donate\Fundraiser_Stories::POST_TYPE,
					'posts_per_page' => - 1,
					'post_status'    => 'publish'

			] ), [
					'title'    => Fundraise::get_val( Fundraise::SETTING_SUCCESS_STORIES_TITLE ),
					'subtitle' => Fundraise::get_val( Fundraise::SETTING_SUCCESS_STORIES_DESC ),
			] ) ?>

			<? /* TODO: Add Button: Become A Fundraiser */ ?>
		</div>

		<?php /** Top Lists */ ?>
		<div class="top-lists bg-white py-24 text-teal-dark">
			<h2 class="page-sub-title centered">
				<?= Fundraise::get_val( Fundraise::SETTING_TOP_LIST_TITLE ) ?>
			</h2>
			<?php if ( ! empty( $desc = Fundraise::get_val( Fundraise::SETTING_TOP_LIST_DESC ) ) ) { ?>
				<p class="page-sub-title-desc centered"><?= $desc ?></p>
			<?php } ?>

			<?php /** Top Individuals */ ?>
			<?= \TrevorWP\Theme\Helper\Carousel::fundraisers( \TrevorWP\Classy\Content::get_fundraisers(
					$individual_camp_id = Fundraise::get_val( Fundraise::SETTING_TOP_LIST_CAMPAIGN_ID ),
					Fundraise::get_val( Fundraise::SETTING_TOP_LIST_COUNT ),
					true
			), [
					'title'  => 'Top Individuals',
					'card_options' => [
							'placeholder_logo_id' => Fundraise::get_val( Fundraise::SETTING_TOP_LIST_PLACEHOLDER_LOGO ),
					],
			] ) ?>
			<div class="text-center -mt-8">
				<a href="<?= esc_url( "https://give.thetrevorproject.org/campaign/fundraise-for-trevor/c{$individual_camp_id}/search?type=individual" ) ?>"
				   class="font-bold text-px24 leading-px34 tracking-px05 border-b-2 border-teal-dark md:text-px18 lg:text-px20 lg:leading-px24 lg:tracking-em005"
				   target="_blank"
				   rel="noopener nofollow noreferrer">
					View All
				</a>
			</div>

			<?php /** Top Teams */ ?>
			<?= \TrevorWP\Theme\Helper\Carousel::fundraisers( \TrevorWP\Classy\Content::get_fundraising_teams(
					Fundraise::get_val( Fundraise::SETTING_TOP_LIST_CAMPAIGN_ID ),
					Fundraise::get_val( Fundraise::SETTING_TOP_LIST_COUNT ),
					true
			), [
					'title'        => 'Top Teams',
					'card_options' => [
							'placeholder_logo_id' => Fundraise::get_val( Fundraise::SETTING_TOP_LIST_PLACEHOLDER_LOGO ),
					],
			] ) ?>
			<div class="text-center -mt-8">
				<a href="<?= esc_url( "https://give.thetrevorproject.org/campaign/fundraise-for-trevor/c{$individual_camp_id}/search?type=team" ) ?>"
				   class="font-bold text-px24 leading-px34 tracking-px05 border-b-2 border-teal-dark md:text-px18 lg:text-px20 lg:leading-px24 lg:tracking-em005"
				   target="_blank"
				   rel="noopener nofollow noreferrer">
					View All
				</a>
			</div>
		</div>

		<?php /** QUESTIONS */ ?>
		<?php $questions_title = Fundraise::get_val( Fundraise::SETTING_QUESTIONS_TITLE ); ?>
		<?php $questions_desc = Fundraise::get_val( Fundraise::SETTING_QUESTIONS_DESC ); ?>
		<?php $questions_cta = Fundraise::get_val( Fundraise::SETTING_QUESTIONS_CTA ); ?>
		<?php $questions_cta_link = Fundraise::get_val( Fundraise::SETTING_QUESTIONS_CTA_LINK ); ?>
		<div class="questions">
			<div class="container mx-auto text-center">
				<div class="one-up-card light my-8 mx-auto md:w-3/4 md:my-6 lg:w-full xl:w-3/4 text-center">
					<h3><?php echo esc_html( $questions_title ); ?></h3>
					<p><?php echo esc_html( $questions_desc ); ?></p>
					<div><a href="<?php echo esc_url( $questions_cta_link ); ?>" class="btn"><?php echo esc_html( $questions_cta ); ?></a></div>
				</div>
			</div>
			</div>

		<?php /* Recirculation */ ?>
		<?= Helper\Circulation_Card::render_circulation(
			Fundraise::get_val( Fundraise::SETTING_CIRCULATION_TITLE ),
			Fundraise::get_val( Fundraise::SETTING_CIRCULATION_DESC ),
			[
				'fundraiser',
				'counselor'
			],
			[
				'container' => 'other-ways',
			]
		); ?>
	</main>

<?php get_footer(); ?>
