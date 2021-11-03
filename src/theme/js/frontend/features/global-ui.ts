/**
 * Handles all GLOBAL UI functionalities (body scroll, etc.)
 */
import $ from 'jquery';

const $window: JQuery = $(window);
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
		$body.removeClass('is-modal-open');
		$root[0].style.setProperty('--fixed-body-top', `0px`);
		$root.css('scroll-behavior', 'auto');
		window.scrollTo(0, fixedBodyScroll || 0);
		$root.css('scroll-behavior', '');
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
