// Expand/collapse functionality for Circle of Light cards (copied from FAQs)
import Component from '../../Component';

class CircleOfLight extends Component {
	static selector = '.circle-of-light';
	static classNameItemExpanded = 'is-open';

	static children = {
		items: ['.circle-of-light__card'],
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
			item.querySelector('.circle-of-light__card-toggle');
		const content: HTMLElement | null =
			item.querySelector('.circle-of-light__card-body');

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
		const isExpanded = item.classList.contains(CircleOfLight.classNameItemExpanded);

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
			item.querySelector('.circle-of-light__card-body');
		const targetHeight = content?.scrollHeight || 0;

		content?.style.setProperty('height', `${targetHeight}px`);
		item.classList.add(CircleOfLight.classNameItemExpanded);
	}

	collapseItem(item: HTMLElement) {
		const content: HTMLElement | null =
			item.querySelector('.circle-of-light__card-body');

		// Apply current height first, to allow animating into zero
		const currentHeight = content?.scrollHeight || 0;
		content?.style.setProperty('height', `${currentHeight}px`);

		// Ensure animation stack doesn't run in parallel with the operation above
		setTimeout(() => {
			content?.style.setProperty('height', '0px');
			item.classList.remove(CircleOfLight.classNameItemExpanded);
		}, 10);
	}

	onItemTransition(item: HTMLElement) {
		const isExpanded = item.classList.contains(CircleOfLight.classNameItemExpanded);

		if (isExpanded) {
			const content: HTMLElement | null =
				item.querySelector('.circle-of-light__card-body');
			content?.style.setProperty('height', 'auto');
		}
	}
}

CircleOfLight.init();

export default CircleOfLight;
