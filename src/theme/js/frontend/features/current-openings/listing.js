import $ from 'jquery';
import { getActiveFilters } from './filters';

class Listing {
	selector = `.listing`;
	filterSelector = `.filter`;

	constructor(context) {
		this.$context = $(context);
		this.$filterHeaders = this.$context.find(
			`${this.filterSelector}__header`
		);
	}

	init() {
		this.initializeObserver();
	}

	initializeObserver() {
		const filterHeaderMutationObserver = new window.MutationObserver(
			([mutation]) => {
				if (mutation.attributeName === 'aria-label') {
					this.toggleItems();
				}
			}
		);
		this.$filterHeaders.each((index, el) => {
			filterHeaderMutationObserver.observe(el, { attributes: true });
		});
	}

	hideAllItems() {
		this.$context.find(`${this.selector}__item`).removeClass('show');
	}

	toggleItems() {
		const activeFilters = getActiveFilters(this.$context);
		const filterGroupNames = Object.keys(activeFilters);

		this.$context.find(`${this.selector}__item`).each((index, element) => {
			$(element).toggleClass(
				'show',
				filterGroupNames.every(
					(groupName) =>
						!activeFilters[groupName].length ||
						/^all-.+/.test(activeFilters[groupName][0]) ||
						activeFilters[groupName].includes(
							element.dataset[groupName]
						)
				)
			);
		});
	}
}

export default function initListing(context) {
	const listing = new Listing(context);
	listing.init();
}
