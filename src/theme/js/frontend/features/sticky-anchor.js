import Component from '../Component';
import { all as handleBreakpointChange } from '../match-media';

export default class StickyAnchor extends Component {
	// Defines the element selector which will initialize this component
	static selector = '.floating-crisis-btn';

	// Defines initial State
	state = {
		hasCollision: false,
	};

	constructor(element) {
		super(element);
	}

	// Will be called upon component instantiation
	afterInit() {
		this.initializeIntersections();
	}

	initializeIntersections() {
		handleBreakpointChange(this.onBreakpointChange.bind(this));
	}

	onBreakpointChange() {
		// Disconnect any existing observers
		if (this.intersectionObserver instanceof IntersectionObserver) {
			this.intersectionObserver.disconnect();
		}

		// Get viewport data to compute for intersection margins
		const { innerWidth: viewportWidth, innerHeight: viewportHeight } =
			window;

		// Get button's box data to compute for intersections
		const bottomOffset = this.getBottomOffsetStyle();
		const {
			height: buttonHeight,
			left: buttonLeft,
			right,
		} = this.element.getBoundingClientRect();

		// Compute intersection margins
		const intersectionMargins = [
			-(viewportHeight - buttonHeight - bottomOffset) + 'px',
			-(viewportWidth - right) + 'px',
			-(bottomOffset) + 'px',
			-buttonLeft + 'px',
		].join(' ');

		// Get all elements having bg-orange classnames,
		// track their intersection with the button
		// to determine the button theme to apply
		const [...orangeElements] = document.querySelectorAll('.bg-orange');

		// Filter only orange elements that can possibly intersect with the button
		// this allows for a more accurate list of elements to observe.
		const possibleCollisions = orangeElements.filter((element) => {
			const { right: elementRight } = element.getBoundingClientRect();
			return elementRight > buttonLeft;
		});

		// Create a new intersectionObserver
		this.intersectionObserver = new IntersectionObserver(
			this.onCollision.bind(this),
			{
				rootMargin: intersectionMargins,
			}
		);

		// Observe possible colliding elements
		possibleCollisions.forEach((element) =>
			this.intersectionObserver.observe(element)
		);
	}

	// Gets the bottom offset from computed CSS styles
	// Since button will stop being sticky when scrolled down to the footer,
	// getBoundingClientRect() will not be reliable for the bottom offset
	getBottomOffsetStyle() {
		const rootFontSizePx = window.getComputedStyle(document.body).getPropertyValue('font-size');
		const buttonStyles = window.getComputedStyle(this.element);
		const bottomOffsetRem = buttonStyles.getPropertyValue(
			'--button-bottom-offset'
		);''

		// Return value as pixels
		return parseFloat(bottomOffsetRem) * parseFloat(rootFontSizePx);
	}

	onCollision(intersectionEntries) {
		const hasCollision = intersectionEntries.some(
			({ isIntersecting }) => isIntersecting
		);

		this.setState({ hasCollision });
	}

	// Triggers when state is change by calling this.setState()
	componentDidUpdate(stateChange) {
		if ('hasCollision' in stateChange) {
			this.element.classList.toggle(
				'floating-crisis-btn--light',
				stateChange.hasCollision
			);
		}
	}
}

// Uncomment this section if this component is intended
// to initialize on DOM load.
StickyAnchor.init();
