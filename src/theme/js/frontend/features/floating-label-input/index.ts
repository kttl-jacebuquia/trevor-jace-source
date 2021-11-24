// Sample Component
// components/SomeComponent/index.js

import Component from '../../Component';

const ACTIVATED_CLASSLIST = 'floating-label-input--activated';

/**
 * @param {Boolean} options.activated - Whether the field will be activated always
 */

export default class FloatingLabelInput extends Component {
	options: { [key: string]: any } = {};
	children?: {
		input?: HTMLInputElement | HTMLElement;
	};

	// Defines the element selector which will initialize this component
	static selector = '.floating-label-input';

	// Defines children that needs to be queried as part of this component
	static children = {
		input: 'input, select, textarea',
	};

	// Defines event handlers to attach to children
	eventHandlers = {
		input: {
			focus: this.onInputFocus,
			blur: this.onInputBlur,
		},
	};

	// Defines initial State
	state = {
		activated: false,
	};

	constructor(element: HTMLElement, options: { [key: string]: any }) {
		super(element);

		const dataOptions = JSON.parse(element.dataset.options || '{}');

		const mergedOptions = {
			...options,
			...dataOptions,
		};

		this.options = mergedOptions;
	}

	// Will be called upon component instantiation
	afterInit() {
		if (this.children?.input) {
			this.element.dataset.inputTag =
				this.children?.input?.tagName?.toLowerCase();
			this.element.dataset.inputType =
				this.children?.input?.type?.toLowerCase() || '';
		}

		// Select tag always activated
		if (/select/i.test(this.element?.dataset.inputTag || '')) {
			this.options.activated = true;
		}

		if (this.options.activated || this.children?.input?.value) {
			this.state.activated = true;
			this.element.classList.add(ACTIVATED_CLASSLIST);
		}

		// Pickup classname changes brought by external components
		const observer = new window.MutationObserver(() => this.onMutate());
		observer.observe(this.element, { attributes: true });
	}

	onInputBlur() {
		this.setState({
			activated: this.options.activated || this.children?.input?.value,
		});
	}

	onInputFocus() {
		this.setState({
			activated: true,
		});
	}

	onMutate() {
		this.setState({
			activated:
				this.options.activated ||
				this.element.classList.contains(ACTIVATED_CLASSLIST),
		});
	}

	// Triggers when state is change by calling this.setState()
	componentDidUpdate() {
		this.element.classList.toggle(
			ACTIVATED_CLASSLIST,
			this.state.activated
		);
	}
}

// Uncomment this section if this component is intended
// to initialize on DOM load.
FloatingLabelInput.init();
