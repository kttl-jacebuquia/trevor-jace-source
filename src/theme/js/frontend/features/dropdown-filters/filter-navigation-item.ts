import $ from 'jquery';
export default class FilterNavigationItem {
	selector = `.filter__navigation`;
	options: { [key: string]: any } = {};

	constructor(context, options) {
		this.context = context;
		this.$items = $(this.context).find(`${this.selector}__item`);
		this.options = options;
	}

	init() {
		this.initializeObserver();
		this.initializeEventHandler();
	}

	initializeObserver() {
		const itemMutationObserver = new MutationObserver(([mutation]) => {
			if (mutation.attributeName === 'aria-checked') {
				this.updateHeader(mutation.target);
			}
		});

		this.$items.each((index, el) => {
			itemMutationObserver.observe(el, { attributes: true });
		});
	}

	initializeEventHandler() {
		const { onSelect } = this.options;

		this.$items.on('click', function () {
			const item = this;
			$(item).attr('aria-checked', 'true');
			$(item).siblings().attr('aria-checked', 'false');

			if (typeof onSelect === 'function') {
				onSelect();
			}
		});
	}

	updateHeader(item) {
		const $headerContainer = $(item).closest(`.filter`);
		const $header = $headerContainer.find(`.filter__header`);
		const itemLabel = $(item).text();
		$header.attr('aria-label', itemLabel);
		$header.find('span').text(itemLabel);
		$headerContainer.removeClass('filter--expanded');
	}
}
