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
		const filterHeaderMutationObserver = new MutationObserver(
			([mutation]) => {
				if (mutation.attributeName === 'aria-label') {
					this.hideAllItems();
					this.showItems();
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

	showItems() {
		const activeFilters = getActiveFilters(this.$context);
		const itemSelector = this.generateSelector(activeFilters);
		this.$context
			.find(`${this.selector}__item${itemSelector}`)
			.addClass('show');
	}

	generateSelector(activeFilters) {
		const classes = [];
		for (const filterGroup in activeFilters) {
			const filterItems = activeFilters[filterGroup];
			filterItems.forEach((filterItem) => {
				const isAllOption = filterItem.indexOf('all-') >= 0;
				if (!isAllOption) {
					classes.push(filterItem);
				}
			});
		}
		return classes.length
			? classes.map((_class) => `[class*="${_class}"]`)
			: '';
	}
}

export default function initListing(context) {
	const listing = new Listing(context);
	listing.init();
}
