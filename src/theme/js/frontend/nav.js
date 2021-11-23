import $ from 'jquery';
import throttle from 'lodash/throttle';
import debounce from 'lodash/debounce';
import { mobileAndSmallDesktop } from './match-media';

const $window = $(window);
const $root = $('html');
const $body = $('body');
const $navOpener = $('.topbar-control-opener,.opener');
const $navCloser = $('.burger-nav-control-close');
const $burgerNav = $('.burger-nav');
const $ctaLinks = $('.cta-wrap');
const $switcher = $('.switcher-wrap', $burgerNav);
const $switcherLinks = $switcher.find('a');
const $topBar = $('#top-bar');
const $topbarControls = $topBar.find('.topbar-controls');
const $topbarNavWrap = $topBar.find('.topbar-nav-wrap');
const $topbarInner = $topBar.find('.top-bar-inner');
const $topNav = $('#top-nav');
const $tier1Links = $burgerNav.find('.main-menu > .menu-item');
const $backToTier1 = $('.back-to-tier1');
const $topbarLogo = $topBar.find('.logo-icon');
const $navLogo = $topNav.find('.logo');
const $currentMenu = $body.hasClass('is-rc')
	? $('.main-menu-container-resources', $topNav)
	: $('.main-menu-container-organization', $topNav);

// tablet and mobile breakpoint flag
let isLargeBreakpoint = false;

// Will store last scroll value before scroll fixing
let fixedBodyScroll = 0;

const isBodyOnTop = throttle(() => {
	const { top } = $topNav.offset();

	// Collapsed nav triggers when top nav is 90px past the viewport
	const collapsedNavTrigger = top + 90;

	$body[
		$(document).scrollTop() > collapsedNavTrigger
			? 'removeClass'
			: 'addClass'
	]('on-top');
	$body.removeClass('is-not-scrolling');
}, 100);
const isScrolling = debounce(() => {
	$body.addClass('is-not-scrolling');
}, 2000);
const toggleNav = (willOpen) => {
	const navWillOpen =
		typeof willOpen === 'boolean'
			? willOpen
			: !$body.hasClass('is-navigation-open');

	$body.toggleClass('is-navigation-open', navWillOpen);
	resetNavState();

	if (navWillOpen) {
		fixedBodyScroll = $window.scrollTop();

		$root
			.get(0)
			.style.setProperty('--fixed-body-top', `-${fixedBodyScroll}px`);
		$body.addClass('is-fixed');

		$topbarLogo
			.add($navLogo)
			.add($navOpener)
			.attr({
				['aria-hidden']: true,
				tabindex: -1,
			});
	} else {
		$body.removeClass('is-fixed');
		$root.get(0).style.setProperty('--fixed-body-top', `0px`);

		$root.css('scroll-behavior', 'auto');
		window.scrollTo(0, fixedBodyScroll);
		$root.css('scroll-behavior', '');

		$topbarLogo
			.add($navLogo)
			.add($navOpener)
			.attr({
				['aria-hidden']: null,
				tabindex: null,
			});
	}
};
const onBurgerClick = (e) => {
	e.preventDefault();
	toggleNav(true);
};
const onBurgerCloseClick = (e) => {
	e.preventDefault();
	toggleNav(false);
};
const onSwitcherClick = (e) => {
	/*
		Proceed with redirect if:
		1. On medium desktop and up; or
		2. Clicked link is already active
	*/
	if (isLargeBreakpoint || e.currentTarget.classList.contains('active')) {
		return;
	}

	e.preventDefault();
	$burgerNav.toggleClass(
		'is_rc',
		$(e.currentTarget).hasClass('switcher-link-rc')
	);
	$switcherLinks.each((index, link) => {
		$(link).toggleClass('active', link === e.currentTarget);
	});

	resetNavState();
};
const onSmallBreakpointsMatch = () => {
	isLargeBreakpoint = false;

	// Moving some elements for accessibility
	$ctaLinks.detach().insertBefore($topbarControls);
	// $switcher.detach().insertAfter($topbarControls);

	// Remove duplicate nav
	$topbarNavWrap.empty();
};
const onNotSmallBreakpointsMatch = () => {
	isLargeBreakpoint = true;

	// Moving some elements for accessibility
	$ctaLinks.detach().appendTo($topbarInner);
	// $switcher.detach().insertAfter($topbarLogo);

	// Duplicate nav
	$currentMenu.clone().appendTo($topbarNavWrap);
};
const onTier1LinkClick = (e) => {
	if (isLargeBreakpoint) {
		return;
	}

	e.preventDefault();

	const $menuItemParent = $(e.currentTarget).parent();
	const $currentActiveMenuItem = $tier1Links.filter('.active');

	// Update new active menu item
	if ($menuItemParent[0] !== $currentActiveMenuItem[0]) {
		$menuItemParent.addClass('active');
		$currentActiveMenuItem.removeClass('active');
	}

	// Tier2 view state
	$burgerNav.addClass('tier-two-visible');
};
const onBackToTier1Click = (e) => {
	e.preventDefault();
	resetNavState();
};
const resetNavState = () => {
	const $currentActiveMenuItem = $tier1Links.filter('.active');
	$burgerNav.removeClass('tier-two-visible');
	$currentActiveMenuItem.removeClass('active');
};
// Adds click-to-close for nav background blur
const navBlur = () => {
	const $navBlur = $(`<div class="burger-nav__underlay"></div>`);
	$navBlur.prependTo($burgerNav);
	$navBlur.on('click', () => toggleNav(false));
};
// Duplicates tier-1 link texts to implement desired animation
const duplicateTier1Text = () => {
	$tier1Links.find('> a').each((index, element) => {
		const { textContent } = element;
		const $duplicate = $('<div>')
			.text(textContent)
			.addClass('sub-menu__heading');
		$duplicate.appendTo(element.parentElement);
	});
};

$(isBodyOnTop); // On load
$(isScrolling); // On load
$(navBlur);
$(duplicateTier1Text);
// $body.addClass('is-not-scrolling');
$(window).on('scroll', isBodyOnTop); // On scroll
$(window).on('scroll', isScrolling); // On scroll
$navOpener.on('click', onBurgerClick); // Burger nav click
$navCloser.on('click', onBurgerCloseClick); // Burger nav click
$switcherLinks.on('click', onSwitcherClick); // Switch link click
$tier1Links.find('> a').on('click', onTier1LinkClick); // Tier-1 link click
$backToTier1.on('click', onBackToTier1Click);

mobileAndSmallDesktop(onSmallBreakpointsMatch, onNotSmallBreakpointsMatch);
