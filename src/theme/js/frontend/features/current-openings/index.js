import { initFilterNavigation, getActiveFilters } from './filters';
import initListing from './listing';

export default class CurrentOpenings {
	static context = `.js-current-openings`;

	static init() {
		initFilterNavigation(this.context);
		initListing(this.context);
	}
}
