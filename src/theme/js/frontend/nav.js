import $ from 'jquery';
import throttle from 'lodash/throttle';
import debounce from 'lodash/debounce';
import { mobileAndTablet } from './match-media';

const $window = $(window);
const $root = $('html');
const $body = $('body');
const $navOpener = $('.topbar-control-opener,.opener');
const $ctaLinks = $('.cta-wrap');
const $switcher = $('.switcher-wrap');
const $switcherLinks = $switcher.find('a');
const $topBar = $('#top-bar');
const $topbarControls = $topBar.find('.topbar-controls');
const $topbarNavWrap = $topBar.find('.topbar-nav-wrap');
const $topNav = $('#top-nav');
const $mainMenus = $('.main-menu');
const $tier1Links = $mainMenus.find('> .menu-item');
const $backToTier1 = $('.back-to-tier1');
const $topbarLogo = $topBar.find('.logo-icon');
const $navLogo = $topNav.find('.logo');
const $currentMenu = $body.hasClass('is-rc') ? $('.main-menu-container-resources') : $('.main-menu-container-organization');

// tablet and mobile breakpoint flag
let isLargeBreakpoint = false;

// Will store last scroll value before scroll fixing
let fixedBodyScroll = 0;

const isBodyOnTop = throttle(
	() => {
		const { top } = $topNav.offset();

		// Collapsed nav triggers when top nav is 90px past the viewport
		const collapsedNavTrigger = top + 90;

		$body[$(document).scrollTop() > collapsedNavTrigger ? 'removeClass' : 'addClass']('on-top')
		$body.removeClass('is-not-scrolling')
	},
	100
);
const isScrolling = debounce(
	() => {
		$body.addClass('is-not-scrolling')
	},
	2000
);
const onBurgerClick = (e) => {
	e.preventDefault();

	const navWillOpen = !$body.hasClass('is-navigation-open');

	$body.toggleClass('is-navigation-open', navWillOpen);
	resetNavState();

	if ( navWillOpen ) {
		fixedBodyScroll = $window.scrollTop();

		$root.get(0).style.setProperty('--fixed-body-top', `-${fixedBodyScroll}px`);
		$body.addClass('is-fixed');

		$topbarLogo.add($navLogo).attr({
			['aria-hidden']: true,
			tabindex: -1
		});
	}
	else {
		$body.removeClass('is-fixed');
		$root.get(0).style.setProperty('--fixed-body-top', `0px`);

		$root.css('scroll-behavior', 'auto');
		window.scrollTo(0, fixedBodyScroll);
		$root.css('scroll-behavior', '');

		$topbarLogo.add($navLogo).attr({
			['aria-hidden']: null,
			tabindex: null
		});
	}

}
const onSwitcherClick = (e) => {
	if ( isLargeBreakpoint ) {
		return;
	}

	e.preventDefault();
	$topNav.toggleClass('is_rc', $(e.currentTarget).hasClass('switcher-link-rc'));
	$switcherLinks.each((index, link) => {
		$(link).toggleClass('active', link === e.currentTarget);
	});

	resetNavState();
}
const onSmallBreakpointsMatch = () => {
	isLargeBreakpoint = false;

	// Moving some elements for accessibility
	$ctaLinks.detach().appendTo($topNav);
	$switcher.detach().insertAfter($topbarControls);

	// Remove duplicate nav
	$topbarNavWrap.empty();
}
const onNotSmallBreakpointsMatch = () => {
	isLargeBreakpoint = true;

	// Moving some elements for accessibility
	$ctaLinks.detach().insertAfter($topbarLogo);
	$switcher.detach().insertAfter($topbarLogo);

	// Duplicate nav
	$currentMenu.clone().appendTo($topbarNavWrap);
}
const onTier1LinkClick = (e) => {
	if ( isLargeBreakpoint ) {
		return;
	}

	e.preventDefault();

	const $menuItemParent = $(e.currentTarget).parent();
	const $currentActiveMenuItem = $tier1Links.filter('.active');

	// Update new active menu item
	if ( $menuItemParent[0] !== $currentActiveMenuItem[0] ) {
		$menuItemParent.addClass('active');
		$currentActiveMenuItem.removeClass('active');
	}

	// Tier2 view state
	$topNav.addClass('tier-two-visible');
}
const onBackToTier1Click = (e) => {
	e.preventDefault();
	resetNavState();
}
const resetNavState = () => {
	const $currentActiveMenuItem = $tier1Links.filter('.active');
	$topNav.removeClass('tier-two-visible');
	$currentActiveMenuItem.removeClass('active');
}

$(isBodyOnTop); // On load
$(isScrolling); // On load
// $body.addClass('is-not-scrolling');
$(window).on('scroll', isBodyOnTop); // On scroll
$(window).on('scroll', isScrolling); // On scroll
$navOpener.on('click', onBurgerClick); // Burger nav click
$switcherLinks.on('click', onSwitcherClick); // Switch link click
$tier1Links.find('> a').on('click', onTier1LinkClick); // Tier-1 link click
$backToTier1.on('click', onBackToTier1Click);

mobileAndTablet(onSmallBreakpointsMatch, onNotSmallBreakpointsMatch);
