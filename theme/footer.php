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
			<p class="mb-9 text-px18 leading-px26 tracking-em_001 md:mr-10 lg:mr-60">The Trevor Project is the leading
				national organization providing crisis intervention and suicide prevention services to lesbian, gay,
				bisexual, transgender, queer & questioning youth.</p>

			<form>
				<legend>Sign Up For Our Newsletter</legend>
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
			<ul class="social-links">
				<?php foreach ( $social_media_accounts as $account ) : ?>
					<?php if ( ! empty( $account['url'] ) ) : ?>
						<li>
							<a href="<?php echo esc_url( $account['url'] ); ?>">
								<i class="
								<?php
								echo esc_attr(
									implode(
										' ',
										array(
											$account['icon'],
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
		</div>
	</div>
</footer>

<?php echo Customizer\External_Scripts::get_val( Customizer\External_Scripts::SETTING_BODY_BTM ); ?>
</body>
</html>
