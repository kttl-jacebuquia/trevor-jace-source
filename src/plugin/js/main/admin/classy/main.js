import React from 'react';
import {connect} from 'react-redux';
import CredentialsForm from './credentials-form';

class ClassyAdmin extends React.Component {
	render() {
		const {hasCredentials} = this.props;

		return <>
			<CredentialsForm/>
			{hasCredentials && <>
				<hr/>

			</>}
		</>
	}
}

export default connect(
	state => {
		const {credentials: {clientId, clientSecret} = {}} = state;
		const hasCredentials = clientId && clientSecret;

		return {hasCredentials}
	},
)(ClassyAdmin);
