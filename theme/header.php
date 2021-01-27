<!doctype html>
<?php

use TrevorWP\Theme\Customizer;
use \TrevorWP\Theme\Util\Is;

$is_rc = Is::rc();
?>
<html class="no-js" <?php language_attributes(); ?>>
<head>
	<?php /* TODO: Remove GTM */ ?>
	<!-- Google Tag Manager -->
	<script>(function (w, d, s, l, i) {
			w[l] = w[l] || [];
			w[l].push({
				'gtm.start':
						new Date().getTime(), event: 'gtm.js'
			});
			var f = d.getElementsByTagName(s)[0],
					j = d.createElement(s), dl = l != 'dataLayer' ? '&l=' + l : '';
			j.async = true;
			j.src =
					'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
			f.parentNode.insertBefore(j, f);
		})(window, document, 'script', 'dataLayer', 'GTM-KQFZF3W');</script>
	<!-- End Google Tag Manager -->
	<script>(function (e) {
			e.className = e.className.replace(/\bno-js\b/, 'js');
		})(document.documentElement)</script>
	<?= Customizer\External_Scripts::get_val( Customizer\External_Scripts::SETTING_HEAD_TOP ) ?>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<meta name="ac-ajax-url" content="<?= admin_url( 'admin-ajax.php?action=autocomplete-test' ) ?>"/>
	<meta name="ac2-ajax-url" content="<?= admin_url( 'admin-ajax.php?action=highlight-search-test' ) ?>"/>
	<?php wp_head(); ?>
	<?= Customizer\External_Scripts::get_val( Customizer\External_Scripts::SETTING_HEAD_BTM ) ?>
</head>
<body <?php body_class(); ?>>
<?php if ( ! empty( $gradient_type = \TrevorWP\Theme\Util\Tools::get_body_gradient_type() ) ) { ?>
	<div id="bg-wrap">
		<div id="bg-gradient" class="gradient-type-<?= esc_attr( $gradient_type ) ?>"></div>
	</div>
<?php } ?>
<?= Customizer\External_Scripts::get_val( Customizer\External_Scripts::SETTING_BODY_TOP ) ?>
<!--[if IE]>
<p class="browserupgrade">
	You are using an <strong>outdated</strong> browser.
	Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.
</p>
<![endif]-->
<div id="top-bar" class="top-bar">
	<div class="top-bar-inner container">
		<div class="logo-icon">
			<a href="<?= home_url( $is_rc ? \TrevorWP\CPT\RC\RC_Object::PERMALINK_BASE : '' ) ?>">
				<i class="trevor-ti-logo-icon"></i>
			</a>
		</div>
		<ul class="switcher">
			<li>
				<a href="<?= esc_attr( home_url( \TrevorWP\CPT\RC\RC_Object::PERMALINK_BASE ) ) ?>"
				   class="<?= $is_rc ? 'active' : '' ?>">Find Support</a>
			</li>
			<li>
				<a href="<?= esc_attr( home_url() ) ?>"
				   class="<?= $is_rc ? '' : 'active' ?>">Explore The Organization</a>
			</li>
		</ul>
		<div class="cta-wrap">
			<a href="<?= esc_attr( home_url( \TrevorWP\CPT\RC\RC_Object::PERMALINK_GET_HELP ) ) ?>"
			   class="btn bg-orange text-white">Reach a Counselor</a>
			<a href="<?= esc_attr( home_url( TrevorWP\CPT\Donate\Donate_Object::PERMALINK_DONATE ) ) ?>"
			   class="btn bg-white text-orange border-2" rel="noopener nofollow">Donate</a>
		</div>
	</div>
</div>
<header id="top-nav" class="top-nav">
	<input id="top-nav-open" type="checkbox" class="hidden">
	<div class="top-nav-inner container <?= 'text-' . \TrevorWP\Theme\Helper\Main_Header::get_text_color(); ?>">
		<div class="logo-wrap">
			<a href="<?= home_url( $is_rc ? \TrevorWP\CPT\RC\RC_Object::PERMALINK_BASE : '' ) ?>" class="logo" rel="home">
				<i class="logo-text trevor-ti-logo-text"></i>
				<i class="logo-icon trevor-ti-logo-icon"></i>
			</a>
		</div>

		<div class="opener-wrap">
			<div class="opener"><i class="trevor-ti-hamburger-menu"></i></div>
		</div>

		<div class="menu-wrap flex pt-4 items-start lg:pt-0">
			<?php wp_nav_menu( [
					'menu_class'      => 'main-menu',
					'container_class' => 'main-menu-container',
					'theme_location'  => $is_rc ? 'header-support' : 'header-organization'
			] ); ?>

			<button class="search-button w-10 h-10 ml-5 mb-2 rounded-full bg-blue-dark hidden md:block">
				<i class="trevor-ti-search"></i>
			</button>
		</div>
	</div>
</header>
<?php wp_body_open(); ?>
