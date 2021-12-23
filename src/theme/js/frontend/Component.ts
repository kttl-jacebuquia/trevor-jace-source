/**
 Component

 Aims to implement component functionalities
 Refer to the bottom of the page for sample use
*/
import WithState from './WithState';
const instancesStore = Symbol('instancesStore');

export default class Component extends WithState {
	children?: { [key: string]: HTMLElement | HTMLElement[] };
	members?: { [key: string]: any };
	static selector: string;

	static isDOMReady = false;

	constructor(public element: HTMLElement) {
		super();
	}

	// Should be called static through child component
	// in order to load component in DOM
	static init() {
		if (this.selector) {
			if (this.isDOMReady) {
				this.initializeInstances();
			} else {
				window.document.addEventListener('DOMContentLoaded', () => {
					this.isDOMReady = true;
					this.initializeInstances();
				});
			}
		}
	}

	static initializeInstances() {
		// Get elements matching child component's selector, if any
		const [...elements] = document.querySelectorAll(this.selector);

		// Initialize each element as component
		return elements.forEach((element) => {
			const existingElementInstance =
				this[instancesStore] &&
				this[instancesStore].find(
					(instance) => instance.element === element
				);

			// Ensure element is only initialized once for the same component
			if (existingElementInstance) {
				return;
			}

			this.initializeWithElement(element);
		});
	}

	static initializeWithElement(
		element: HTMLElement,
		options?: { [key: string]: any }
	) {
		return this.initializeComponentWithElement(this, element, options);
	}

	static initializeComponentWithElement(ComponentClass, element, options) {
		// Add instances store to component class
		if (!ComponentClass[instancesStore]) {
			ComponentClass[instancesStore] = [];

			// Allow for component class to get instances
			ComponentClass.getInstances = () => ComponentClass[instancesStore];
		}

		const instance = new ComponentClass(element, options);

		// Add this instance into instances store
		ComponentClass[instancesStore].push(instance);

		// Add root element
		instance.element = element;

		// Add reference to the component's class
		instance.componentClass = ComponentClass;

		// Add reference to the instance object
		instance.element.component = instance;

		// Query children if supplied
		if (ComponentClass.children) {
			instance.children = {};

			Object.entries(ComponentClass.children).forEach(
				([key, selector]) => {
					const query = Array.isArray(selector)
						? Array.from(
								instance.element.querySelectorAll(selector[0])
						  )
						: instance.element.querySelector(selector);
					instance.children[key] = query;
				}
			);
		}

		// Attach event handlers if supplied
		if (instance.eventHandlers) {
			Object.entries(instance.eventHandlers).forEach(
				([childKey, eventHandlers]) => {
					if (!(childKey in instance.children)) {
						return;
					}

					const matchedChild = instance.children[childKey];
					const children = Array.isArray(matchedChild)
						? matchedChild
						: [matchedChild];

					children.forEach((child) => {
						Object.entries(eventHandlers).forEach(
							([eventName, eventHandler]) => {
								if (child instanceof HTMLElement) {
									child.addEventListener(
										eventName,
										eventHandler.bind(instance)
									);
								}
							}
						);
					});
				}
			);
		}

		// Query members (sub-components) if supplied
		if (ComponentClass.members) {
			instance.members = {};

			Object.entries(ComponentClass.members).forEach(
				([key, memberClass]) => {
					if (Array.isArray(memberClass)) {
						const [subComponentClass] = memberClass;

						if (!subComponentClass.selector) {
							return;
						}

						const [...elements] = instance.element.querySelectorAll(
							subComponentClass.selector
						);

						instance.members[key] = elements.map((memberElement) =>
							ComponentClass.initializeComponentWithElement(
								subComponentClass,
								memberElement
							)
						);
					} else {
						const memberElement = instance.element.querySelector(
							memberClass.selector
						);
						instance.members[key] =
							memberElement &&
							ComponentClass.initializeComponentWithElement(
								memberClass,
								memberElement
							);
					}
				}
			);
		}

		// Call init of new instance if there is any
		if (typeof instance.afterInit === 'function') {
			instance.afterInit();
		}

		// Dispatch instantiation event in order to pickup by other components
		const instantiationEvent = new window.CustomEvent(
			'componentinitialize',
			{
				detail: instance,
			}
		);
		element.dispatchEvent(instantiationEvent);

		return instance;
	}

	static getInstanceByElement(element) {
		return new Promise((resolve) => {
			const instance = this[instancesStore]?.find(
				(_instance) => _instance.element === element
			);

			// If instance is already present for the element, return it
			if (instance) {
				resolve(instance);
			}
			// Else, wait for instantiation
			else {
				element.addEventListener('componentinitialize', (e) => {
					resolve(e.detail);
				});
			}
		});
	}

	on(event: string, eventHandler: (args: any) => any) {
		this.element?.addEventListener(event, eventHandler);
	}

	emit(event: string, data: any) {
		const newEvent = new window.CustomEvent(event, { detail: data });
		this.element?.dispatchEvent(newEvent);
	}

	getClass() {
		return this.constructor;
	}
}

/**
 // Sample Component
 // components/SomeComponent/index.js

  import Component from '@base/js/component';

  export default class SomeComponent extends Component {

    // Defines the element selector which will initialize this component
    static selector = '.some-component';

    // Defines children that needs to be queried as part of this component
    static children = {
      // body: ".some-component__body" // Queries a single element
      // items: [".some-component__items"] // Queries a an array of elements
    };

    // Defines sub components that needs to be queried as part of this component
    static members = {
      // subComponent: SomeComponent, // Maps as a single subcomponent instance
      // subComponents: [SomeComponent], // Maps as an array of subcomponent instances
    };

    // Defines event handlers to attach to children
    eventHandlers = {
      // body: {
      //  click: this.onBodyClick // Attaches onBodyClick method to this.children.body
      // }
    };

    // Defines initial State
    state = {
      someState: false
    };

    // constructor(element) {
    //  super(element);
    // }

    // Will be called upon component instantiation
    afterInit() {
      // console.log(`Component initialized`, this.element);
    }

    // Triggers when state is change by calling this.setState()
    componentDidUpdate (changedState) {
      // State change updates
    }
  }

  // Uncomment this section if this component is intended
  // to initialize on DOM load.
  // SomeComponent.init();

*/
