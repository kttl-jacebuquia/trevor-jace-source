import $ from 'jquery';
import tippy, {roundArrow} from 'tippy.js';
import {copyToClipboard} from 'plugin/js/main/utils';

const hasNavigatorShare = !!navigator.share;

export default function sharingMore(button, content, tippyOptions = {}) {
	// Use native sharing if available
	if (hasNavigatorShare) {
		$(button).on('click', () => navigator.share({
			url: $('head link[rel="canonical"]').attr('href') || window.location.href,
		}));
	} else {
		// Init tippy
		tippy(button, Object.assign({
			content,
			interactive: true,
			placement: 'right',
			appendTo: 'parent',
			arrow: roundArrow,
		}, tippyOptions));

		// Event Handlers
		$(content).find('tr[data-row]').on('click', (e) => {
			e.preventDefault;
			const type = $(e.currentTarget).attr('data-row');
			const canonicalURL = $(content).attr('data-url') || document.querySelector('link[rel="canonical"]').href;

			switch (type) {
				case 'facebook':
				case 'twitter':
					$(`.post-social-share-btn[data-type="${type}"]`).click();
					break;
				case 'clipboard':
					copyToClipboard(canonicalURL);
					const $elem = $(content).find('[data-row="clipboard"] > td:nth-child(2)');
					const copiedCB = $elem.data('copiedCB');
					copiedCB && clearTimeout(copiedCB);
					$elem.data('copiedCB', setTimeout(() => $elem.text('Copy Link'), 2000));
					$elem.text('Link Copied!');
					break;
				case 'email':
					window.location = `mailto:?body=${canonicalURL}`;
					break;
			}
		});
	}
}
