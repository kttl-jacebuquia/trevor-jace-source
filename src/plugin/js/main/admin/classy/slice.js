import {createSlice} from '@reduxjs/toolkit'

const defSlice = createSlice({
	name: 'classy',
	initialState: {
		credentials: {
			clientId: null,
			clientSecret: null
		}
	},
	reducers: {
		init(state, action) {
			state = action.payload;
		},
		setCredentials(state, action) {
			const {clientId, clientSecret} = action.payload;
			state.credentials = {clientId, clientSecret};
		}
	}
});

export const {init, setCredentials} = defSlice.actions;

export default defSlice.reducer;
