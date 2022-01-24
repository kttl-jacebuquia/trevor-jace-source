import $ from 'jquery';
import tippy, { roundArrow } from 'tippy.js';
import { copyToClipboard } from 'plugin/js/main/utils';

const hasNavigatorShare = !!window.navigator.share;

export default function sharingMore(button, content, tippyOptions = {}) {
	// Use native sharing if available and for mobile.
	const isMobileOnly = window.matchMedia(`screen and (max-width: 767px)`);

	const options = Object.assign(
		tippyOptions,
		JSON.parse(button.dataset.tippyOptions || 'null') || {}
	);

	if (hasNavigatorShare && isMobileOnly.matches) {
		$(button).on('click', () =>
			window.navigator.share({
				url:
					$('head link[rel="canonical"]').attr('href') ||
					window.location.href,
			})
		);
	} else {
		// Init tippy
		tippy(
			button,
			Object.assign(
				{
					content,
					interactive: true,
					placement: 'right',
					appendTo: 'parent',
					arrow: roundArrow,
				},
				options
			)
		);

		// Event Handlers
		$(content)
			.find('tr[data-row]')
			.on('click', (e) => {
				const type = $(e.currentTarget).attr('data-row');
				const canonicalURL =
					$(content).attr('data-url') ||
					document.querySelector('link[rel="canonical"]')?.href ||
					document
						.querySelector('[property="og:url"]')
						?.getAttribute('content');

				switch (type) {
					case 'facebook':
					case 'twitter':
						e.preventDefault();
						$(
							`.post-social-share-btn[data-type="${type}"]`
						).click();
						break;
					case 'clipboard':
						e.preventDefault();
						copyToClipboard(canonicalURL);
						const $elem = $(content).find(
							'[data-row="clipboard"] > td:nth-child(2)'
						);
						const copiedCB = $elem.data('copiedCB');
						copiedCB && clearTimeout(copiedCB);
						$elem.css({ color: 'rgba(16, 16, 102, 0.8)' });
						$elem.data(
							'copiedCB',
							setTimeout(() => $elem.text('Copy Link'), 2000)
						);
						setTimeout(() => {
							$elem.css({ color: 'inherit' });
						}, 2000);
						$elem.text('Link Copied!');
						break;
					default:
						break;
				}
			});
	}
}
