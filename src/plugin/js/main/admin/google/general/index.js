import React from 'react';
import {Formik} from 'formik';

class GeneralSettings extends React.Component {
	render() {
		const {options: {auth: {auth_url}}} = this.props;

		return <div>
			<h2>General Settings</h2>
			<p>You have already authorized. Please click <a href={auth_url}>here</a> to re-authorize.</p>
		</div>
	}
}

export default GeneralSettings;
