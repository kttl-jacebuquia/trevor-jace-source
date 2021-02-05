<?php wp_footer();

use \TrevorWP\Theme\Customizer;
use \TrevorWP\Theme\Util\Is;

?>

<footer class="w-full flex flex-col justify-center">
	<div class="container mx-auto site-content-inner lg:flex lg:flex-row">
		<div class="col">
			<div class="logo-wrap">
				<a href="<?php echo home_url( Is::rc() ? \TrevorWP\CPT\RC\RC_Object::PERMALINK_BASE : '' ); ?>"
					class="logo"
					rel="home">
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
			<ul class="site-links">
				<li><a href="#">Contact us</a></li>
				<li><a href="#">Blog</a></li>
				<li><a href="#">Events</a></li>
				<li><a href="#">Press</a></li>
				<li><a href="#">Careers</a></li>
				<li><a href="#">Terms of Service</a></li>
				<li><a href="#">Privacy Policy</a></li>
			</ul>
			<ul class="social-links">
				<?php
					$facebook_url  = Customizer\Social_Media_Accounts::get_val( Customizer\Social_Media_Accounts::SETTING_HOME_FACEBOOK_URL );
					$twitter_url   = Customizer\Social_Media_Accounts::get_val( Customizer\Social_Media_Accounts::SETTING_HOME_TWITTER_URL );
					$instagram_url = Customizer\Social_Media_Accounts::get_val( Customizer\Social_Media_Accounts::SETTING_HOME_INSTAGRAM_URL );
					$tiktok_url    = Customizer\Social_Media_Accounts::get_val( Customizer\Social_Media_Accounts::SETTING_HOME_TIKTOK_URL );
					$youtube_url   = Customizer\Social_Media_Accounts::get_val( Customizer\Social_Media_Accounts::SETTING_HOME_YOUTUBE_URL );
					$linkedin_url  = Customizer\Social_Media_Accounts::get_val( Customizer\Social_Media_Accounts::SETTING_HOME_LINKEDIN_URL );

					$social_media_accounts = array(
						array(
							'url'  => $facebook_url,
							'icon' => 'trevor-ti-facebook',
						),
						array(
							'url'  => $twitter_url,
							'icon' => 'trevor-ti-twitter',
						),
						array(
							'url'  => $instagram_url,
							'icon' => 'trevor-ti-instagram',
						),
						array(
							'url'  => $tiktok_url,
							'icon' => 'trevor-ti-tiktok',
						),
						array(
							'url'  => $youtube_url,
							'icon' => 'trevor-ti-youtube',
						),
						array(
							'url'  => $linkedin_url,
							'icon' => 'trevor-ti-linkedin',
						),
					);

					foreach ( $social_media_accounts as $account ) {
						if ( ! empty( $account['url'] ) ) {
							?>
							<li><a href="<?php echo esc_url( $account['url'] ); ?>"><i class="<?php echo implode( ' ', array( esc_attr( $account['icon'] ), 'text-white' ) ); ?>"></i></a></li>
							<?php
						}
					}
					?>
			</ul>
		</div>
	</div>
</footer>

<?php echo Customizer\External_Scripts::get_val( Customizer\External_Scripts::SETTING_BODY_BTM ); ?>
</body>
</html>
