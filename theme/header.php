<!doctype html>
<?php use TrevorWP\Theme\Customizer; ?>
<html class="no-js" <?php language_attributes(); ?>>
<head>
	<?= Customizer\External_Scripts::get_val( Customizer\External_Scripts::SETTING_HEAD_TOP ) ?>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
	<?= Customizer\External_Scripts::get_val( Customizer\External_Scripts::SETTING_HEAD_BTM ) ?>
</head>
<body <?php body_class(); ?>>
<?= Customizer\External_Scripts::get_val( Customizer\External_Scripts::SETTING_BODY_TOP ) ?>
<!--[if IE]>
<p class="browserupgrade">
	You are using an <strong>outdated</strong> browser.
	Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.
</p>
<![endif]-->
<header id="top-bar" class="top-bar">
	<div class="title-bar">
		<div class="logo-wrap">
			<a href="<?= get_home_url() ?>" rel="home">The Trevor Project</a>
		</div>
		<div class="menu-icon"></div>
	</div>
	<div class="container top-bar-inner">
		<div class="top-bar-ceil">
			<ul class="switcher">
				<li><a href="#" class="active">Support Center</a></li>
				<li><a href="#">The Organization</a></li>
			</ul>
			<div class="cta-wrap">
				<a href="#" class="btn bg-blue-dark p-4">Talk to a Counselor Now</a>
				<a href="#" class="btn rounded-full p-2 border border-blue-dark text-blue-dark" rel="noopener nofollow">Donate</a>
			</div>
		</div>
		<div class="top-bar-center">
			<div class="logo-wrap">
				<a href="#" class="logo">
					THE TREVOR<br>
					PROJECT
				</a>
			</div>

			<?php wp_nav_menu( [
					'menu_class'      => 'main-menu',
					'container_class' => 'main-menu-container',
					'theme_location'  => 'header-menu'
			] ); ?>
		</div>
		<div class="top-bar-bottom"></div>
	</div>
</header>
<?php wp_body_open(); ?>
