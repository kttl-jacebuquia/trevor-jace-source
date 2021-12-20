import Component from '../../Component';

class FAQ extends Component {
	static selector = '.faqs';
	static classNameItemExpanded = 'is-open';

	static children = {
		items: ['.faq-list__item'],
	};

	afterInit() {
		this.handleItems();
	}

	// Attaches handlers for each item's expand/collapse state
	handleItems() {
		(this.children?.items as HTMLElement[]).forEach((item) =>
			this.handleItem(item)
		);
	}

	handleItem(item: HTMLElement) {
		const toggle: HTMLElement | null =
			item.querySelector('.faq-list__toggle');
		const content: HTMLElement | null =
			item.querySelector('.faq-list__content');

		// Handle toggle click
		toggle?.addEventListener('click', () => this.toggleItem(item));

		// Handle content transition
		content?.addEventListener('transitionend', (e) => {
			if (e.currentTarget === e.target) {
				this.onItemTransition(item);
			}
		});
	}

	toggleItem(item: HTMLElement) {
		const isExpanded = item.classList.contains(FAQ.classNameItemExpanded);

		if (isExpanded) {
			this.collapseItem(item);
		}
		// To animate expand, manually set the content height
		else {
			this.expandItem(item);
		}
	}

	expandItem(item: HTMLElement) {
		const content: HTMLElement | null =
			item.querySelector('.faq-list__content');
		const targetHeight = content?.scrollHeight || 0;

		content?.style.setProperty('height', `${targetHeight}px`);
		item.classList.add(FAQ.classNameItemExpanded);
	}

	collapseItem(item: HTMLElement) {
		const content: HTMLElement | null =
			item.querySelector('.faq-list__content');

		// Apply current height first, to allow animating into zero
		const currentHeight = content?.scrollHeight || 0;
		content?.style.setProperty('height', `${currentHeight}px`);

		// Ensure animation stack doesn't run in parallel with the operation above
		setTimeout(() => {
			content?.style.setProperty('height', '0px');
			item.classList.remove(FAQ.classNameItemExpanded);
		}, 10);
	}

	onItemTransition(item: HTMLElement) {
		const isExpanded = item.classList.contains(FAQ.classNameItemExpanded);

		if (isExpanded) {
			const content: HTMLElement | null =
				item.querySelector('.faq-list__content');
			content?.style.setProperty('height', 'auto');
		}
	}
}

FAQ.init();

export default FAQ;
