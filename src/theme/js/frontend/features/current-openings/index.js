import initContent from './content';

export default class CurrentOpenings {
	static context = `.js-current-openings`;

	static init() {
		initContent(this.context);
	}
}
