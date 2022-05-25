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
const $burgerNavMenuWrap = $('.menu-wrap', $burgerNav);
const $burgerNavMenuContainer = $('.main-menu-container', $burgerNav);
const $burgerNavControls = $('.burger-nav-controls');
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
const $currentMenu = $body.hasClass('is-rc')
	? $('.main-menu-container-resources', $topNav)
	: $('.main-menu-container-organization', $topNav);

// tablet and mobile breakpoint flag
let isLargeBreakpoint = false;

// Will store last scroll value before scroll fixing
let fixedBodyScroll = 0;

// Save last focused element before nav expands
let lastFocusedElement;

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

const toggleNav = async (willOpen) => {
	const navWillOpen =
		typeof willOpen === 'boolean'
			? willOpen
			: !$body.hasClass('is-navigation-open');

	if (navWillOpen) {
		$burgerNav.prop('hidden', false);
	}

	// A slight delay in order for computed layout to be applied first
	// before the next animation runs
	await new Promise((resolve) => setTimeout(resolve, 10));

	$body.toggleClass('is-navigation-open', navWillOpen);
	resetNavState();

	if (navWillOpen) {
		fixedBodyScroll = $window.scrollTop();

		$root
			.get(0)
			.style.setProperty('--fixed-body-top', `-${fixedBodyScroll}px`);
		$body.addClass('is-fixed');

		// Save last focused element
		lastFocusedElement = document.activeElement;

		// Hide everything from screenreaders except burger menu and controls
		[...document.body.children].forEach((child) => {
			if (!/burger-nav/i.test(child.className)) {
				// Retain aria-hidden attribute from elements that already have it
				if (child.getAttribute('aria-hidden') === 'true') {
					child.dataset.retainAriaHidden = true;
				} else {
					child.setAttribute('aria-hidden', true);
				}
			}
		});

		// Focus on first focusable element within burger nav controls
		setTimeout(
			() => $burgerNavControls.get(0).firstElementChild?.focus(),
			300
		);
	} else {
		$body.removeClass('is-fixed');
		$root.get(0).style.setProperty('--fixed-body-top', `0px`);

		$root.css('scroll-behavior', 'auto');
		window.scrollTo(0, fixedBodyScroll);
		$root.css('scroll-behavior', '');

		// Show previously hidden elements
		[...document.body.children].forEach((child) => {
			if (!child.dataset.retainAriaHidden) {
				child.removeAttribute('aria-hidden');
			}
		});

		if (lastFocusedElement instanceof window.HTMLElement) {
			lastFocusedElement.focus();
		}
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
const onSwitcherClick = async (e) => {
	/*
		Proceed with redirect if:
		1. On medium desktop and up; or
		2. Clicked link is already active
	*/
	if (isLargeBreakpoint || e.currentTarget.classList.contains('active')) {
		return;
	}

	let activeMenuIndex;

	e.preventDefault();
	$burgerNav.toggleClass(
		'is_rc',
		$(e.currentTarget).hasClass('switcher-link-rc')
	);
	$switcherLinks.each((index, link) => {
		$(link).toggleClass('active', link === e.currentTarget);

		if (link === e.currentTarget) {
			activeMenuIndex = index;
		}
	});

	burgerMenuContainerA11y(activeMenuIndex);
	resetNavState(true);
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
	const $submenu = $menuItemParent.find('.sub-menu');
	const $currentActiveMenuItem = $tier1Links.filter('.active');

	// Update new active menu item
	if ($menuItemParent[0] !== $currentActiveMenuItem[0]) {
		$menuItemParent.addClass('active');
		$currentActiveMenuItem.removeClass('active');
		setTimeout(() => {
			$submenu.get(0).querySelector('a')?.focus();
		}, 300);
	}

	// Tier2 view state
	$burgerNav.addClass('tier-two-visible');
};
const onBackToTier1Click = (e) => {
	e.preventDefault();
	resetNavState();
};
const resetNavState = (noFocusOnActiveMenu = false) => {
	const $currentActiveMenuItem = $tier1Links.filter('.active');
	const menuItemLink = $currentActiveMenuItem.get(0)?.firstElementChild;
	$burgerNav.removeClass('tier-two-visible');
	$currentActiveMenuItem.removeClass('active');

	if (!noFocusOnActiveMenu) {
		setTimeout(() => menuItemLink?.focus(), 300);
	}
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
const onBurgerMenuIntersect = ([{ isIntersecting }]) => {
	// Add hidden attribute when burger nav is hidden
	// for a11y considerations
	if (!isIntersecting) {
		$burgerNav.prop('hidden', true);
	}
};
const handleBurgerMenuTransition = () => {
	const intersectionObserver = new window.IntersectionObserver(
		onBurgerMenuIntersect
	);
	intersectionObserver.observe($burgerNavMenuWrap.get(0));
};
const applyBurgerMenuContainerA11y = (menuContainer, isActive) => {
	const isHidden = !isActive;

	if (isHidden) {
		menuContainer.setAttribute('aria-hidden', 'true');
	} else {
		menuContainer.removeAttribute('aria-hidden');
	}
};
const burgerMenuContainerA11y = (activeMenuIndex = 0) => {
	$burgerNavMenuContainer.each((index, el) => {
		applyBurgerMenuContainerA11y(el, index === activeMenuIndex);
	});
};

$(isBodyOnTop); // On load
$(isScrolling); // On load
$(navBlur);
$(duplicateTier1Text);
$(handleBurgerMenuTransition);
$(() => burgerMenuContainerA11y(0));
$(window).on('scroll', isBodyOnTop); // On scroll
$(window).on('scroll', isScrolling); // On scroll
$navOpener.on('click', onBurgerClick); // Burger nav click
$navCloser.on('click', onBurgerCloseClick); // Burger nav click
$switcherLinks.on('click', onSwitcherClick); // Switch link click
$tier1Links.find('> a').on('click', onTier1LinkClick); // Tier-1 link click
$backToTier1.on('click', onBackToTier1Click);

mobileAndSmallDesktop(onSmallBreakpointsMatch, onNotSmallBreakpointsMatch);
