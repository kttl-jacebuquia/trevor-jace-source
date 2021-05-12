<?php /** Donate: Fundraise */ ?>
<?php get_header(); ?>
<?php

use \TrevorWP\Theme\Helper;
use \TrevorWP\Theme\Customizer\Fundraise;

?>
	<main id="site-content" role="main" class="site-content">

		<?php /** Page Header */ ?>
		<?php echo Fundraise::get_component( Fundraise::SECTION_HEADER )->render( array( 'bg' => 'teal-dark' ) ); ?>

		<?php /** Featured Text */ ?>
		<?php $title = Fundraise::get_val( Fundraise::SETTING_FEATURED_TEXT_TITLE ); ?>
		<?php $desc = Fundraise::get_val( Fundraise::SETTING_FEATURED_TEXT_DESC ); ?>
		<div class="featured-text">
			<div class="container mx-auto text-center">
				<h3 class="heading"><?php echo esc_html( $title ); ?></h3>
				<p><?php echo $desc; ?></p>
			</div>
		</div>

		<?php /* How your Money is Used */ ?>
		<?php
		echo Helper\Audit_Block::render(
			$title        = Fundraise::get_val( Fundraise::SETTING_THREE_TITLE ),
			$audit_data   = Fundraise::get_val( Fundraise::SETTING_THREE_DATA ),
			$custom_class = 'audit-fundraise'
		);
		?>

		<?php /* ONE COLUMN */ ?>
		<?php $content = Fundraise::get_val( Fundraise::SETTING_ONE_DATA ); ?>
		<div class="one-column featured-content">
			<div class="container mx-auto text-center">

				<?php foreach ( $content as $col ) : ?>
					<div class="one-column__content featured-content__item text-center">
						<h2 class="page-sub-title centered text-teal-dark"><?php echo esc_html( $col['title'] ); ?></h2>
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
								<h3 class="heading"><?php echo esc_html( $link_title ); ?></h3>
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
							<h3 class="heading"><?php echo Fundraise::get_val( Fundraise::SETTING_PARTNER_TITLE ); ?></h3>
							<p><?php echo wp_filter_kses( Fundraise::get_val( Fundraise::SETTING_PARTNER_DESC ) ); ?></p>
							<div><a href="<?php echo esc_url( Fundraise::get_val( Fundraise::SETTING_PARTNER_CTA_LINK ) ); ?>" class="btn"><?php echo esc_html( Fundraise::get_val( Fundraise::SETTING_PARTNER_CTA ) ); ?></a></div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<?php /** Fundraiser Success Stories */ ?>
		<div class="text-teal-dark bg-teal-tint bg-white pb-px100 lg:pb-px120 success-stories">
			<?php
			echo \TrevorWP\Theme\Helper\Carousel::posts(
				( new WP_Query() )->query(
					array(
						'post_type'      => \TrevorWP\CPT\Donate\Fundraiser_Stories::POST_TYPE,
						'posts_per_page' => - 1,
						'post_status'    => 'publish',

					)
				),
				array(
					'title'    => Fundraise::get_val( Fundraise::SETTING_SUCCESS_STORIES_TITLE ),
					'subtitle' => Fundraise::get_val( Fundraise::SETTING_SUCCESS_STORIES_DESC ),
				)
			)
			?>

			<div class="text-center">
			<a href="#!"
					class="inline-block rounded-px10 py-px12 px-px32 md:px-px24 lg:py-px20 lg:px-px40 capitalize text-white bg-teal-dark text-center font-bold text-px16 leading-px22 md:leading-px20 lg:text-px20">Become A Fundraiser</a>
			</div>
		</div>

		<?php /** Top Lists */ ?>
		<div class="top-lists bg-white text-teal-dark">
			<div class="container mx-auto">
				<h2 class="page-sub-title centered top-lists__title">
					<?php echo Fundraise::get_val( Fundraise::SETTING_TOP_LIST_TITLE ); ?>
				</h2>
				<?php if ( ! empty( $desc = Fundraise::get_val( Fundraise::SETTING_TOP_LIST_DESC ) ) ) { ?>
					<p class="page-sub-title-desc centered top-lists__desc"><?php echo $desc; ?></p>
				<?php } ?>
			</div>

			<?php /** Top Individuals */ ?>
			<?php
			echo \TrevorWP\Theme\Helper\Carousel::fundraisers(
				\TrevorWP\Classy\Content::get_fundraisers(
					$individual_camp_id = Fundraise::get_val( Fundraise::SETTING_TOP_LIST_CAMPAIGN_ID ),
					Fundraise::get_val( Fundraise::SETTING_TOP_LIST_COUNT ),
					true
				),
				array(
					'title'        => 'Top Individuals',
					'card_options' => array(
						'placeholder_image' => Fundraise::get_val( Fundraise::SETTING_TOP_LIST_PLACEHOLDER_LOGO ),
					),
					'class'        => 'top-individuals',
				)
			)
			?>
			<div class="text-center view-all">
				<a href="<?php echo esc_url( "https://give.thetrevorproject.org/campaign/fundraise-for-trevor/c{$individual_camp_id}/search?type=individual" ); ?>"
				   class="wave-underline font-bold text-px24 leading-px34 tracking-px05 border-b-2 border-teal-dark md:text-px18 lg:text-px20 lg:leading-px24 lg:tracking-em005"
				   target="_blank"
				   rel="noopener nofollow noreferrer">
					View All
				</a>
			</div>

			<?php /** Top Teams */ ?>
			<?php
			echo \TrevorWP\Theme\Helper\Carousel::fundraisers(
				\TrevorWP\Classy\Content::get_fundraising_teams(
					Fundraise::get_val( Fundraise::SETTING_TOP_LIST_CAMPAIGN_ID ),
					Fundraise::get_val( Fundraise::SETTING_TOP_LIST_COUNT ),
					true
				),
				array(
					'title'        => 'Top Teams',
					'card_options' => array(
						'placeholder_image' => Fundraise::get_val( Fundraise::SETTING_TOP_LIST_PLACEHOLDER_LOGO ),
					),
					'class'        => 'top-teams',
				)
			)
			?>
			<div class="text-center view-all">
				<a href="<?php echo esc_url( "https://give.thetrevorproject.org/campaign/fundraise-for-trevor/c{$individual_camp_id}/search?type=team" ); ?>"
				   class="wave-underline font-bold text-px24 leading-px34 tracking-px05 border-b-2 border-teal-dark md:text-px18 lg:text-px20 lg:leading-px24 lg:tracking-em005"
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
					<h3 class="heading"><?php echo esc_html( $questions_title ); ?></h3>
					<p><?php echo esc_html( $questions_desc ); ?></p>
					<div><a href="<?php echo esc_url( $questions_cta_link ); ?>" class="btn"><?php echo esc_html( $questions_cta ); ?></a></div>
				</div>
			</div>
			</div>

		<?php /* Recirculation */ ?>
		<?php
		echo Fundraise::get_component( Fundraise::SECTION_OTHER )->render(
			array(
				'cards'   => array(
					'fundraiser',
					'counselor',
				),
				'options' => array(
					'container' => 'other-ways',
				),
			)
		)
		?>
	</main>

<?php get_footer(); ?>
