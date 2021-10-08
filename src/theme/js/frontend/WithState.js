/**
 * Simulates React class state implementation
 */
const STATE_CHANGE_EVENT = Symbol();

export default class WithState {
	// Define this in child class
	state = {};

	constructor() {
		// Will serve as custom event name for each instance
		this[STATE_CHANGE_EVENT] = String(Math.random());

		document.addEventListener(this[STATE_CHANGE_EVENT], ({ detail }) =>
			this.componentDidUpdate(detail)
		);
	}

	/**
	 *
	 * @param {object} stateMap
	 */
	setState(stateMap) {
		const statesToChange = Object.entries(stateMap)
			.filter(
				([stateKey, stateValue]) =>
					stateKey in this.state &&
					this.state[stateKey] !== stateValue
			)
			.map(([stateKey, stateValue]) => ({
				[stateKey]: stateValue,
			}));

		if (statesToChange.length) {
			const stateChange = statesToChange.reduce(
				(all, change) => ({
					...all,
					...change,
				}),
				{}
			);

			this.state = {
				...this.state,
				...stateChange,
			};

			const stateChangeEvent = new CustomEvent(this[STATE_CHANGE_EVENT], {
				detail: stateChange,
			});
			document.dispatchEvent(stateChangeEvent);
		}
	}

	componentDidUpdate(changedState) {
		// Override this method in child class
	}

	onStateChange(callback) {
		if ( typeof callback === 'function' ) {
			document.addEventListener(this[STATE_CHANGE_EVENT], () =>
				callback(this.state)
			);
		}
	}
}
