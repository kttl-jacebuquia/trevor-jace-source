<?php wp_footer();

use \TrevorWP\Theme\Customizer;

$social_media_accounts = array(
	array(
		'url'  => Customizer\Social_Media_Accounts::get_val( Customizer\Social_Media_Accounts::SETTING_FACEBOOK_URL ),
		'icon' => 'trevor-ti-facebook',
	),
	array(
		'url'  => Customizer\Social_Media_Accounts::get_val( Customizer\Social_Media_Accounts::SETTING_TWITTER_URL ),
		'icon' => 'trevor-ti-twitter',
	),
	array(
		'url'  => Customizer\Social_Media_Accounts::get_val( Customizer\Social_Media_Accounts::SETTING_INSTAGRAM_URL ),
		'icon' => 'trevor-ti-instagram',
	),
	array(
		'url'  => Customizer\Social_Media_Accounts::get_val( Customizer\Social_Media_Accounts::SETTING_TIKTOK_URL ),
		'icon' => 'trevor-ti-tiktok',
	),
	array(
		'url'  => Customizer\Social_Media_Accounts::get_val( Customizer\Social_Media_Accounts::SETTING_YOUTUBE_URL ),
		'icon' => 'trevor-ti-youtube',
	),
	array(
		'url'  => Customizer\Social_Media_Accounts::get_val( Customizer\Social_Media_Accounts::SETTING_LINKEDIN_URL ),
		'icon' => 'trevor-ti-linkedin',
	),
);

$data = \TrevorWP\Theme\ACF\Options_Page\Footer::get_footer();

?>

<footer class="w-full flex flex-col justify-center sticky-cta-anchor">
	<div class="container mx-auto site-content-inner lg:flex lg:flex-row">
		<div class="col">
			<div class="logo-wrap">
				<a href="<?php echo \TrevorWP\Theme\Util\Tools::get_relative_home_url(); ?>" class="logo" rel="home">
					<i class="logo-text trevor-ti-logo-text"></i>
					<i class="logo-icon trevor-ti-logo-icon"></i>
				</a>
			</div>
			
			<?php if ( ! empty( $data['description'] ) ) : ?>
				<p class="mb-9 text-px18 leading-px26 tracking-em_001 md:mr-10 lg:mr-60">
					<?php echo esc_html( $data['description'] ); ?>
				</p>
			<?php endif; ?>

			<form>
				<legend><?php echo esc_html( $data['newsletter_title'] ); ?></legend>
				<fieldset>
					<label for="newsletter" class="sr-only">Email Address</label>
					<input type="email" id="newsletter" placeholder="Email Address"/>
				</fieldset>
				<button type="submit" class="btn btn-secondary">Subscribe</button>
			</form>
		</div>
		<div class="col">
			<?php
				wp_nav_menu(
					array(
						'menu_class'     => 'site-links',
						'theme_location' => 'footer',
					)
				)
				?>
			<?php if ( ! empty( $data['social_media_links'] ) ) : ?>
				<ul class="social-links">
					<?php foreach ( $data['social_media_links'] as $link ) : ?>
						<?php if ( ! empty( $link['url'] ) ) : ?>
							<li>
								<a href="<?php echo esc_url( $link['url'] ); ?>">
									<i class="
									<?php
									echo esc_attr(
										implode(
											' ',
											array(
												$link['icon'],
												'text-white',
											)
										)
									);
									?>
										"></i>
								</a>
							</li>
						<?php endif; ?>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>
	</div>
</footer>

<?php echo Customizer\External_Scripts::get_val( Customizer\External_Scripts::SETTING_BODY_BTM ); ?>
</body>
</html>
