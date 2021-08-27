import $ from 'jquery';
import {infoBoxesCarousel} from "theme/js/frontend/features";

export const ALL = {
	// 'is-gi_ect': require('./ect').default,
	// 'is-donate': require('./donate').default,
}

export default () => {
	const $body = $('body');
	const isSinglePage = $body.hasClass('is-single-page');

	if (isSinglePage) {
		// todo: this class is available on only new pages
		infoBoxesCarousel();
	}

	Object.keys(ALL).forEach(pageClass => {
		if ($body.hasClass(pageClass)) {
			ALL[pageClass]();
			return false;
		}
	})
}
