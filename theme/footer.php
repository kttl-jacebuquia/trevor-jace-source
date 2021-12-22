<?php wp_footer();

use \TrevorWP\Theme\Helper\Main_Header;
use \TrevorWP\Theme\ACF\Options_Page;

$data                   = Options_Page\Footer::get_footer();
$footer_external_script = Options_Page\External_Scripts::get_external_script( 'BODY_BOTTOM' );

?>

<?php if ( ! is_page_template( 'template-thank-you.php' ) ) : ?>
<footer class="w-full flex flex-col justify-center sticky-cta-anchor <?php echo is_404() ? 'hidden' : ''; ?>">
	<div class="container mx-auto site-content-inner footer-inner lg2:flex lg2:flex-row">
		<div class="col">
			<div class="logo-wrap">
				<a href="<?php echo get_home_url(); ?>" class="logo" rel="home">
					<?php echo Main_Header::render_logo( array( 'footer__logo' ) ); ?>
				</a>
			</div>

			<?php if ( ! empty( $data['description'] ) ) : ?>
				<p class="mb-9 text-px18 leading-px26 tracking-em_001 md:mr-10 lg:mr-px145">
					<?php echo esc_html( $data['description'] ); ?>
				</p>
			<?php endif; ?>

			<form class="newsletter-form" novalidate>
				<legend><?php echo esc_html( $data['newsletter_title'] ); ?></legend>
				<fieldset class="floating-label-input">
					<label for="newsletter">Email Address</label>
					<input type="email" id="newsletter" placeholder="Email Address" />
				</fieldset>
				<button type="submit" class="btn btn-secondary">Subscribe</button>
				<div class="newsletter-form__message" role="alert" aria-live="polite"></div>
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
								<a
									href="<?php echo esc_url( $link['url'] ); ?>"
									aria-label='<?php echo esc_html( array_pop( explode( '-', $link['icon'] ) ) ); ?> link'
									>
									<i
									aria-hidden="true"
									class="
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
<?php endif; ?>
<?php echo $footer_external_script; ?>
</body>
</html>
