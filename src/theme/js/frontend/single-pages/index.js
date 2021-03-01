import $ from 'jquery';

export const ALL = {
	'is-gi_ect': require('./ect').default,
	'is-donate': require('./donate').default,
}

export default () => {
	const $body = $('body');
	Object.keys(ALL).forEach(pageClass => {
		if ($body.hasClass(pageClass)) {
			ALL[pageClass]();
			return false;
		}
	})
}
