<?php wp_footer();

use \TrevorWP\Theme\Customizer;
?>

<footer class="w-full flex flex-col justify-center">
	<div class="container mx-auto site-content-inner lg:flex lg:flex-row">
		<div class="col">
			<div class="logo-wrap">
				<a href="<?= esc_attr( get_home_url() ) ?>" class="logo" rel="home">
					<i class="logo-text trevor-ti-logo-text"></i>
					<i class="logo-icon trevor-ti-logo-icon"></i>
				</a>
			</div>
			<p class="mb-9 text-px18 leading-px26 tracking-em_001 md:mr-10 lg:mr-60">The Trevor Project is the leading national organization providing  crisis intervention and suicide prevention services to lesbian,  gay, bisexual, transgender, queer  & questioning youth.</p>

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
				<li><a href="#"><i class="trevor-ti-facebook text-white"></i></a></li>
				<li><a href="#"><i class="trevor-ti-twitter text-white"></i></a></li>
				<li><a href="#"><i class="trevor-ti-instagram text-white"></i></a></li>
				<li><a href="#"><i class="trevor-ti-tiktok text-white"></i></a></li>
				<li><a href="#"><i class="trevor-ti-youtube text-white"></i></a></li>
				<li><a href="#"><i class="trevor-ti-linkedin text-white"></i></a></li>
			</ul>
		</div>
	</div>
</footer>

<?= Customizer\External_Scripts::get_val( Customizer\External_Scripts::SETTING_BODY_BTM ) ?>
</body>
</html>
