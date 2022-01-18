/**
 * Handles all GLOBAL UI functionalities (body scroll, etc.)
 */
import $ from 'jquery';

const $window: JQuery<Window> = $(window);
const $root: JQuery = $(document.documentElement);
const $body: JQuery = $(document.body);

let fixedBodyScroll = $window.scrollTop();

/**
 * Disabled/enables scroll. Usefull for full page overlays
 * @param willFix whether to disable scroll or not
 */
export const toggleBodyFix = (willFix: boolean) => {
	const bodyWillFix =
		typeof willFix === 'boolean'
			? willFix
			: !$body.hasClass('is-modal-open');

	if (bodyWillFix) {
		fixedBodyScroll = $window.scrollTop();
		$body[0].style.setProperty('--fixed-body-top', `-${fixedBodyScroll}px`);
		setTimeout(() => $body.addClass('is-modal-open'), 0);
	} else {
		$root.css('scroll-behavior', 'auto');

		// Ensures scroll-behavior is applied before updating the page layout
		setTimeout(() => {
			$body.removeClass('is-modal-open');
			window.scrollTo(0, fixedBodyScroll || 0);
			$root[0].style.setProperty('--fixed-body-top', `0px`);
		}, 0);

		// Ensures page layout has been updated before reverting the smooth scroll back
		setTimeout(() => {
			$root.css('scroll-behavior', '');
		}, 10);
	}
};

export const onEscapePress = (callback: (args?: any) => any) => {
	if (typeof callback === 'function') {
		document.body.addEventListener('keyup', (e) => {
			if (/esc/i.test(e.key)) {
				callback();
			}
		});
	}
};

export const IS_SUPPORT = document.body.classList.contains('is-rc');
export const IS_RC = IS_SUPPORT;
