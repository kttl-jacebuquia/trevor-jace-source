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
		label?: HTMLElement;
		dummyLabelActive?: HTMLElement | null;
		dummyLabelDefault?: HTMLElement | null;
	};

	static ACTIVATED_CLASSLIST = ACTIVATED_CLASSLIST;

	// Defines the element selector which will initialize this component
	static selector = '.floating-label-input';

	// Dummy labels className
	static dummyActiveLabelClassName =
		'floating-label-input__dummy-label-active';
	static dummyDefaultLabelClassName =
		'floating-label-input__dummy-label-default';

	// Defines children that needs to be queried as part of this component
	static children = {
		input: 'input, select, textarea',
		label: `label:not([class*="dummy"])`,
		dummyLabelActive: `.${FloatingLabelInput.dummyActiveLabelClassName}`,
		dummyLabelDefault: `.${FloatingLabelInput.dummyDefaultLabelClassName}`,
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

		this.determineActivatedOption();

		if (this.options.activated || this.children?.input?.value) {
			this.state.activated = true;
			this.element.classList.add(ACTIVATED_CLASSLIST);
		}

		// Create dummy label
		this.renderDummyLabel();

		// Pickup classname changes brought by external components
		const observer = new window.MutationObserver(() => this.onMutate());
		observer.observe(this.element, { attributes: true });

		if ('textarea' === this.element.dataset.inputTag) {
			this.handleResize();
		}
	}

	// Performs input type check to determine
	// Whether the field should be activated by default
	determineActivatedOption() {
		// Checkbox/Radio doesn't need activated state
		if (/radio|checkbox/i.test(this.element?.dataset.inputType || '')) {
			this.options.activated = false;
		}
		// For input non-radio/checkbox tags,
		// always activate if label is too long
		else if (
			/input/i.test(this.element?.dataset.inputTag || '') &&
			!/radio|checkbox/i.test(this.element?.dataset.inputType || '')
		) {
			if (
				(this.children?.label?.offsetHeight || 0) >
				(this.children?.input?.offsetHeight || 0)
			) {
				this.options.activated = true;
			}
		}
	}

	onInputBlur() {
		const willActivate =
			'activated' in this.options
				? this.options.activated
				: this.children?.input?.value;

		this.setState({
			activated: Boolean(willActivate),
		});
	}

	onInputFocus() {
		const willActivate =
			'activated' in this.options ? this.options.activated : true;

		this.setState({
			activated: willActivate,
		});
	}

	onMutate() {
		const willActivate =
			'activated' in this.options
				? this.options.activated
				: this.element.classList.contains(ACTIVATED_CLASSLIST);

		// For some reason, setState doesn't trigger componentDidUpdate here,
		// So manually updating state and UI
		if (
			willActivate &&
			!this.element.classList.contains(ACTIVATED_CLASSLIST)
		) {
			this.element.classList.add(ACTIVATED_CLASSLIST);
			this.state.activated = true;
		}
	}

	onResize() {
		if (
			'textarea' === this.element.dataset.inputTag &&
			this.children?.dummyLabelDefault
		) {
			// Set label height in order to pickup by CSS
			// This allows textarea to expand on very long labels
			this.element.style.setProperty(
				'--label-height-default',
				this.children.dummyLabelDefault.getBoundingClientRect().height +
					'px'
			);
		}
	}

	handleResize() {
		const resizeObserver = new window.ResizeObserver(
			([{ target }]: ResizeObserverEntry[]) => {
				// Resize can also be triggered by the children,
				// Make sure to pickup only the ones triggered by the root element
				if (target === this.element) {
					this.onResize();
				}
			}
		);
		resizeObserver.observe(this.element);
	}

	/**
	 * Renders dummy labels whose
	 * sole purpose is for layout computations.
	 */
	renderDummyLabel() {
		const label = this.element.querySelector('label');

		if (label && !('activated' in this.options)) {
			// Clone element, including its children
			const dummyLabelActive = label.cloneNode(true) as HTMLElement;
			const dummyLabelDefault = label.cloneNode(true) as HTMLElement;

			// Additional className to dummy label
			dummyLabelActive.classList.add(
				FloatingLabelInput.dummyActiveLabelClassName
			);
			dummyLabelDefault.classList.add(
				FloatingLabelInput.dummyDefaultLabelClassName
			);

			// Render into DOM
			label.insertAdjacentElement('beforebegin', dummyLabelActive);
			label.insertAdjacentElement('beforebegin', dummyLabelDefault);
		}

		if (this.children) {
			this.children.dummyLabelActive = this.element.querySelector(
				FloatingLabelInput.children.dummyLabelActive
			);
			this.children.dummyLabelDefault = this.element.querySelector(
				FloatingLabelInput.children.dummyLabelDefault
			);
		}
	}

	// Triggers when state is change by calling this.setState()
	componentDidUpdate() {
		if (!/checkbox|radio/i.test(this.element.dataset.inputType)) {
			this.element.classList.toggle(
				ACTIVATED_CLASSLIST,
				this.state.activated
			);
		}
	}
}

// Uncomment this section if this component is intended
// to initialize on DOM load.
FloatingLabelInput.init();
