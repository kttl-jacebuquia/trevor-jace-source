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
		<div class="audit">
			<div class="container mx-auto">
				<h3 class="text-center"><?php echo esc_html( $title ); ?></h3>

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
						<div class="audit--card__item swipe-slide text-center">
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
						<h3><?php echo esc_html( $col['title'] ); ?></h3>
						<p><?php echo esc_html( $col['desc'] ); ?></p>
						<?php if ( $col['img'] ) : ?>
							<img src="<?php echo esc_url( $col['img']['url'] ); ?>" alt="<?php echo esc_attr( $col['title'] ); ?>">
						<?php endif ?>
						<a href="<?php echo esc_url( $col['cta_link'] ); ?>" class="btn"><?php echo esc_attr( $col['cta_label'] ); ?></a>
					</div>
				<?php endforeach; ?>
			</div>
		</div>	

		<?php /** GROUPED BLOCKS */ ?>
		<div class="grouped--block gradient-type-dark-green">
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
				<?php $partner_title = Fundraise::get_val( Fundraise::SETTING_PARTNER_TITLE ); ?>
				<?php $partner_desc = Fundraise::get_val( Fundraise::SETTING_PARTNER_DESC ); ?>
				<?php $partner_cta = Fundraise::get_val( Fundraise::SETTING_PARTNER_CTA ); ?>
				<?php $partner_cta_link = Fundraise::get_val( Fundraise::SETTING_PARTNER_CTA_LINK ); ?>
				<div class="partner">
					<div class="mx-auto text-center">
						<div class="one-up-card dark my-8 mx-auto md:w-3/4 md:my-6 lg:w-full xl:w-3/4 text-center">
							<h3><?php echo $partner_title; ?></h3>	
							<p><?php echo esc_html( $partner_desc ); ?></p>
							<div><a href="<?php echo esc_url( $partner_cta_link ); ?>" class="btn"><?php echo esc_attr( $partner_cta ); ?></a></div>
						</div>
					</div>
				</div>
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

		<?php /** Recirculation */ ?>
		<?php $circulation_title = Fundraise::get_val( Fundraise::SETTING_OTHER_TITLE ); ?>
		<?php /** Two Up: Other Ways to Help   */ ?>
		<div class="other-ways">
			<div class="container mx-auto text-center">
				<h3 class="mb-px60 md:mb-px40 lg:mb-px90"><?php echo esc_html( $circulation_title ); ?></h3>
				<div class="grid grid-cols-1 gap-y-6 max-w-lg mx-auto lg:grid-cols-2 lg:gap-x-7 lg:max-w-none xl:max-w-px1240">
					<?php echo Helper\Circulation_Card::render_fundraiser(); ?>
					<?php echo Helper\Circulation_Card::render_counselor(); ?>
				</div>
			</div>
		</div>
	</main>

<?php get_footer(); ?>
