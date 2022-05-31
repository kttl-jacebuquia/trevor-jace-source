import tabFocus from 'ally.js/maintain/tab-focus';
import Component from '../../Component';

const FOCUS_TRAP_KEY = Symbol();

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

	// Will be called upon component instantiation
	afterInit() {
		this.element.setAttribute('tabindex', '0');
		this.generateToggleButton();
		this.bindToggle();
		this.handleMutation();
		this.bindResize();
		this.element.classList.add('tags-box--initialized');
	}

	handleMutation() {
		// Listen to change in the aria-hidden attribute in order to hide
		// focusable elements as well
		const observer = new window.MutationObserver(this.onMutate.bind(this));
		observer.observe(this.element, { attributes: true });

		// Initial call
		this.onMutate();
	}

	onMutate() {
		const ariaHidden = this.element.getAttribute('aria-hidden');

		if (ariaHidden) {
			this.toggleButton?.setAttribute('tabindex', -1);
		} else {
			this.toggleButton?.removeAttribute('tabindex');
		}
	}

	generateToggleButton() {
		// Toggle button
		this.toggleButton = Object.assign(document.createElement('button'), {
			className: 'tags-box__toggle',
			type: 'button',
			'aria-label': 'click to expand tags',
			'aria-expanded': false,
			'aria-controls': this.element.id,
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
		const observer = new window.ResizeObserver(
			this.computeLayout.bind(this)
		);
		observer.observe(this.element);
	}

	// Triggers when state is change by calling this.setState()
	componentDidUpdate(changedStates) {
		this.computeLayout();

		if ('expanded' in changedStates) {
			this.toggleFocusTrap();
			this.toggleButton?.focus();
		}
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
			this.toggleButton.setAttribute(
				'aria-label',
				'click to collapse tags group'
			);
			this.toggleButton.setAttribute('aria-expanded', 'true');
		}
		// Collapsed
		else {
			// Get either the first overflowing tag, or the rightmost tag.
			// Then insert the button before it
			this.children.tags.some((tag) => {
				const tagOffsetRight = tag.offsetLeft + tag.offsetWidth;
				if (
					tag.offsetTop > containerHeight ||
					tagOffsetRight >= containerWidth - 65
				) {
					tag.insertAdjacentElement('beforebegin', this.toggleButton);
					return true;
				}
				tag.dataset.withinView = true;
			});
			this.toggleButton.setAttribute(
				'aria-label',
				'click to expand tags group'
			);
			this.toggleButton.setAttribute('aria-expanded', 'false');
		}
	}

	toggleFocusTrap() {
		if (this.state.expanded) {
			// Ensure existing handle is disengaged first
			this[FOCUS_TRAP_KEY]?.disengage();

			// Initialize a new one
			this[FOCUS_TRAP_KEY] = tabFocus({
				context: this.element,
			});
		} else {
			// Disengage focus trap
			this[FOCUS_TRAP_KEY]?.disengage();
		}
	}
}

// Uncomment this section if this component is intended
// to initialize on DOM load.
TagsBox.init();
