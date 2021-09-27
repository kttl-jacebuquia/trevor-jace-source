/**
 * NOTE
 *
 * Once fully implemented, consider dropping out ../tag-box-ellipsis
 */

import Component from '../../Component';

export default class TagsBox extends Component {
	// Defines the element selector which will initialize this component
	static selector = '.tags-box';

	// Defines children that needs to be queried as part of this component
	static children = {
		content: '.tags-box__contents',
		tags: ['.tag-box'],
	};

	// Defines initial State
	state = {
		overflowing: false,
		expanded: false,
	};

	constructor(element) {
		super(element);
	}

	// Will be called upon component instantiation
	afterInit() {
		this.generateToggleButton();
		this.bindToggle();
		this.bindResize();
	}

	generateToggleButton() {
		// Toggle button
		this.toggleButton = Object.assign(document.createElement('button'), {
			className: 'tags-box__toggle',
			type: 'button',
			'aria-label': 'click to expand tags',
			'aria-expanded': false,
		});
	}

	bindToggle() {
		this.toggleButton.addEventListener(
			'click',
			this.toggleExpanded.bind(this)
		);
	}

	toggleExpanded() {
		this.setState({
			expanded: !this.state.expanded,
		});
	}

	bindResize() {
		const observer = new ResizeObserver(this.computeLayout.bind(this));
		observer.observe(this.element);
	}

	// Triggers when state is change by calling this.setState()
	componentDidUpdate() {
		this.computeLayout();
	}

	computeLayout() {
		const { expanded } = this.state;
		const containerHeight = this.element.offsetHeight;
		const containerWidth = this.element.offsetWidth;

		// Clear out toggle button first
		if (this.children.content.contains(this.toggleButton)) {
			this.children.content.removeChild(this.toggleButton);
		}

		// Toggle expanded class
		this.element.classList.toggle('tags-box--expanded', expanded);

		// If not overflowing, no need to compute and add toggle button
		const overflowing =
			this.element.scrollHeight > this.element.offsetHeight;
		if (!overflowing) {
			return;
		}

		// Expanded
		if (expanded) {
			// Move toggle button to the end of the box
			this.children.content.appendChild(this.toggleButton);
		}
		// Collapsed
		else {
			// Get either the first overflowing tag, or the rightmost tag.
			// Then insert the button before it
			this.children.tags.some((tag) => {
				const tagOffsetRight = tag.offsetLeft + tag.offsetWidth;
				if (
					tag.offsetTop > containerHeight ||
					tagOffsetRight > containerWidth - 65
				) {
					tag.insertAdjacentElement('beforebegin', this.toggleButton);
					return true;
				}
				tag.dataset.withinView = true;
			});
		}
	}
}

// Uncomment this section if this component is intended
// to initialize on DOM load.
TagsBox.init();