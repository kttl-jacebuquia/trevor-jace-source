/**
 * Simulates React class state implementation
 */
export default class WithState {
	// Define this in child class
	state = {}

	/**
	 *
	 * @param {object} stateMap
	 */
	setState(stateMap) {
		const statesToChange = Object.entries(stateMap)
				.filter(([stateKey, stateValue]) => (
					stateKey in this.state && this.state[stateKey] !== stateValue
				))
				.map(([stateKey, stateValue]) => ({
					[stateKey]: stateValue
				}));

		if ( statesToChange.length ) {
			const stateChange = statesToChange.reduce((all, change) => ({
				...all,
				...change,
			}), {});

			this.state = {
				...this.state,
				...stateChange,
			};

			this.componentDidUpdate(stateChange);
		}
	}

	componentDidUpdate(changedState) {
		// Override this method in child class
	}
}
