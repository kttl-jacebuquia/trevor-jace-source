<!doctype html>
<?php

use TrevorWP\Theme\Customizer;
use \TrevorWP\Theme\Util\Is;

$is_rc = Is::rc();
?>
<html class="no-js" <?php language_attributes(); ?>>
<head>
	<script>(function (e) {
			e.className = e.className.replace(/\bno-js\b/, 'js');
		})(document.documentElement)</script>
	<?php echo Customizer\External_Scripts::get_val( Customizer\External_Scripts::SETTING_HEAD_TOP ); ?>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
	<?php echo Customizer\External_Scripts::get_val( Customizer\External_Scripts::SETTING_HEAD_BTM ); ?>
</head>
<body <?php body_class( 'on-top' ); ?>>
<?php $gradient_type = \TrevorWP\Theme\Util\Tools::get_body_gradient_type(); ?>
<?php if ( ! empty( $gradient_type ) ) { ?>
	<div id="bg-wrap">
		<div id="bg-gradient" class="gradient-type-<?php echo esc_attr( $gradient_type ); ?>"></div>
	</div>
<?php } ?>
<?php echo Customizer\External_Scripts::get_val( Customizer\External_Scripts::SETTING_BODY_TOP ); ?>
<!--[if IE]>
<p class="browserupgrade">
	You are using an <strong>outdated</strong> browser.
	Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.
</p>
<![endif]-->
<aside class="site-banner" id="siteBannerContainer" aria-label="site-banner"></aside>
<script>window.trevorWP.siteBanners()</script>

<?php /* Controls for expanded nav menu */ ?>
<div class="burger-nav-controls">
	<a class="btn burger-nav-control burger-nav-control-search" href="<?php echo get_search_link(); ?>">
		<i class="trevor-ti-search"></i>
	</a>
	<button class="btn burger-nav-control burger-nav-control-close">
		<i class="trevor-ti-nav-close"></i>
	</button>
</div>

<?php /* TOP BAR */ ?>
<div id="top-bar" class="top-bar">
	<div class="top-bar-inner container" role="navigation" aria-label="Top Bar Navigation">
		<div class="logo-icon">
			<a href="<?php echo \TrevorWP\Theme\Util\Tools::get_relative_home_url(); ?>">
				<i class="trevor-ti-logo-icon"></i>
			</a>
		</div>
		<div class="switcher-wrap">
			<ul class="switcher">
				<li>
					<a href="<?php echo esc_attr( home_url( \TrevorWP\CPT\RC\RC_Object::PERMALINK_BASE ) ); ?>"
					class="switcher-link-rc <?php echo $is_rc ? 'active' : ''; ?>">Find Support</a>
				</li>
				<li>
					<a href="<?php echo esc_attr( home_url( \TrevorWP\CPT\Org\Org_Object::PERMALINK_ORG_LP ) ); ?>"
					class="<?php echo $is_rc ? '' : 'active'; ?>">Explore Trevor</a>
				</li>
			</ul>
		</div>
		<div class="cta-wrap">
			<div class="cta-links">
				<a href="<?php echo esc_attr( home_url( \TrevorWP\CPT\RC\RC_Object::PERMALINK_GET_HELP ) ); ?>"
				class="btn bg-orange text-white">Reach a Counselor</a>
				<a href="<?php echo esc_attr( home_url( TrevorWP\CPT\Donate\Donate_Object::PERMALINK_DONATE ) ); ?>"
				class="btn bg-white text-orange border-2" rel="noopener nofollow">Donate</a>
				</div>
		</div>
		<div class="topbar-nav-wrap">
			<?php /* Will contain menu for desktop, for seamless transition */ ?>
		</div>
		<div class="topbar-controls">
			<a class="topbar-control-search" href="<?php echo get_search_link(); ?>">
				<i class="trevor-ti-search"></i>
			</a>
			<button class="topbar-control-opener">
				<i class="trevor-ti-hamburger-menu"></i>
			</button>
		</div>
	</div>
</div>

<?php /* TOP NAV */ ?>
<div id="top-nav" class="top-nav <?php echo $is_rc ? 'is_rc' : ''; ?>" role="navigation">
	<div class="top-nav-inner container <?php echo 'text-' . \TrevorWP\Theme\Helper\Main_Header::get_text_color(); ?>">
		<div class="logo-wrap">
			<a href="<?php echo \TrevorWP\Theme\Util\Tools::get_relative_home_url(); ?>" class="logo" rel="home">
				<i class="logo-text trevor-ti-logo-text"></i>
				<i class="logo-icon trevor-ti-logo-icon"></i>
			</a>
		</div>

		<div class="opener-wrap">
			<button type="button" class="opener"><i class="trevor-ti-hamburger-menu"></i></button>
		</div>

		<div class="menu-wrap flex items-start">
			<div class="back-to-tier1-wrap">
				<button type="button" class="back-to-tier1">BACK</button>
			</div>

			<?php
			wp_nav_menu(
				array(
					'menu_class'      => 'main-menu',
					'container_class' => 'main-menu-container main-menu-container-resources',
					'theme_location'  => 'header-support',
				)
			);
			?>

			<?php
			wp_nav_menu(
				array(
					'menu_class'      => 'main-menu',
					'container_class' => 'main-menu-container main-menu-container-organization',
					'theme_location'  => 'header-organization',
				)
			);
			?>

			<a class="search-button" href="<?php echo get_search_link(); ?>">
				<i class="trevor-ti-search"></i>
			</a>

		</div>
	</div>
</div>
<?php wp_body_open(); ?>
