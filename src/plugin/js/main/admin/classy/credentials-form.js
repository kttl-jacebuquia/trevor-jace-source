import React from 'react';
import {connect} from 'react-redux';
import {setCredentials} from './slice';

class CredentialsForm extends React.Component {
	refInputClientId = React.createRef();
	refInputClientSecret = React.createRef();
	state = {
		dirty: false
	};

	render() {
		const {clientId, clientSecret, hasCredentials} = this.props;

		return <form onSubmit={this.handleSubmit} action="#" method="post">
			<table className="form-table">
				<tbody>
				<tr>
					<th scope="row">
						<label htmlFor="client_id">Client Id</label>
					</th>
					<td>
						<input name="client_id"
							   id="client_id"
							   defaultValue={clientId}
							   ref={this.refInputClientId}
							   type="text"
							   className="regular-text"/>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label htmlFor="clientSecret">Client Secret</label>
					</th>
					<td>
						<input name="clientSecret"
							   id="clientSecret"
							   defaultValue={clientSecret}
							   ref={this.refInputClientSecret}
							   type="text"
							   className="regular-text"/>
					</td>
				</tr>
				</tbody>
			</table>
			<p className="submit">
				<button type="submit" name="submit" id="submit" className="button button-primary">
					{hasCredentials ? 'Save Changes' : 'Save Credentials'}
				</button>
			</p>
		</form>
	}

	handleSubmit = (e) => {
		const {hasCredentials} = this.props;

		if (hasCredentials) {
			return; // Submit form to backend
		}

		e.preventDefault();

	}
}

export default connect(
	state => {
		const {credentials: {clientId, clientSecret} = {}} = state;
		const hasCredentials = clientId && clientSecret;

		return {
			hasCredentials,
			clientId,
			clientSecret
		}
	},
	{setCredentials}
)(CredentialsForm);
