<!doctype html>
<?php

use TrevorWP\Theme\Customizer;
use \TrevorWP\Theme\Util\Is;

$is_support = Is::support();
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
<?= Customizer\External_Scripts::get_val( Customizer\External_Scripts::SETTING_BODY_TOP ) ?>
<!--[if IE]>
<p class="browserupgrade">
	You are using an <strong>outdated</strong> browser.
	Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.
</p>
<![endif]-->
<header id="top-bar" class="top-bar">
	<div class="title-bar">
		<div class="container title-bar-inner">
			<div class="logo-wrap">
				<a href="<?= get_home_url() ?>" rel="home">The Trevor Project</a>
			</div>
			<label class="menu-icon" for="top-bar-open"></label>
		</div>
	</div>
	<input id="top-bar-open" type="checkbox" class="hidden">
	<div class="top-bar-inner">
		<div class="container top-bar-ceil">
			<ul class="switcher">
				<li><a href="<?= esc_attr( get_post_type_archive_link( \TrevorWP\CPT\Support_Resource::POST_TYPE ) ) ?>"
					   class="<?= $is_support ? 'active' : '' ?>">Support Center</a></li>
				<li><a href="<?= esc_attr( get_home_url() ) ?>"
					   class="<?= $is_support ? '' : 'active' ?>">The Organization</a></li>
			</ul>
			<div class="cta-wrap">
				<a href="#" class="btn bg-blue-dark p-4 text-white">Talk to a Counselor Now</a>
				<a href="#" class="btn rounded-full p-2 border border-blue-dark text-blue-dark" rel="noopener nofollow">Donate</a>
			</div>
		</div>
		<div class="container top-bar-center">
			<div class="logo-wrap">
				<a href="<?= esc_attr( get_home_url() ) ?>" class="logo" rel="home">
					THE TREVOR<br>
					PROJECT
				</a>
			</div>

			<?php wp_nav_menu( [
					'menu_class'      => 'main-menu',
					'container_class' => 'main-menu-container',
					'theme_location'  => $is_support ? 'header-support' : 'header-organization'
			] ); ?>

			<button class="search-button w-10 h-10 self-end ml-5 mb-2 text-white rounded-full bg-blue-dark hidden md:block">
				<i class="trevor-ti-search-solid"></i>
			</button>
		</div>
		<?php if ( $is_support ) { ?>
			<div class="top-bar-floor">
				<div class="featured-tags">
					<a class="tag-box">Understanding Suicide</a>
					<a class="tag-box">Coming Out</a>
					<a class="tag-box ">Intersectionality</a>
				</div>
			</div>
		<?php } ?>
	</div>
</header>
<?php wp_body_open(); ?>
