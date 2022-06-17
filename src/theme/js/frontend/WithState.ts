/**
 * Simulates React class state implementation
 */
const STATE_CHANGE_EVENT = Symbol();

export type State = { [key: string]: any };

export default class WithState<StateType> {
	// Define this in child class
	state?: StateType;
	[STATE_CHANGE_EVENT]: string;

	constructor() {
		// Will serve as custom event name for each instance
		this[STATE_CHANGE_EVENT] = String(Math.random());

		document.addEventListener(
			this[STATE_CHANGE_EVENT],
			({ detail }: CustomEventInit) =>
				this.componentDidUpdate(detail as StateType)
		);
	}

	/**
	 *
	 * @param {object} stateMap
	 */
	setState(stateMap: Partial<StateType>) {
		if (this.state) {
			const statesToChange = Object.entries(stateMap)
				.filter(
					([stateKey, stateValue]) =>
						stateKey in (this.state || {}) &&
						(this.state || {})[stateKey] !== stateValue
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

				const stateChangeEvent = new CustomEvent(
					this[STATE_CHANGE_EVENT],
					{
						detail: stateChange,
					}
				);
				document.dispatchEvent(stateChangeEvent);
			}
		}
	}

	componentDidUpdate(changedState: Partial<StateType>) {
		// Override this method in child class
	}

	onStateChange(callback: Function) {
		if (typeof callback === 'function') {
			document.addEventListener(this[STATE_CHANGE_EVENT], () =>
				callback(this.state)
			);
		}
	}
}
