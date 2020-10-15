import React from 'react';
import {ErrorMessage, Field, Formik, Form} from 'formik';
import * as Yup from 'yup';

class AnalyticsSettings extends React.Component {
	refForm = React.createRef();

	render() {
		const {options: {page: {form_action, nonce}, analytics}} = this.props;
		return <div>
			<h2>Analytics Settings</h2>

			<Formik initialValues={analytics}
					validate={this.validate}
					onSubmit={this.handleSubmit}
					validationSchema={Yup.object({
						view_id: Yup.string().required('Required'),
						page_view_timeout: Yup.number().min(0).required('Required')
					})}>
				{({}) => {
					return <Form action={`${form_action}&section=analytics`} method="post" ref={this.refForm}>
						<input type="hidden" name="nonce" value={nonce}/>

						<table className="form-table">
							<tbody>
							<tr>
								<th scope="row"><label htmlFor="view_id">View ID</label></th>
								<td>
									<Field type="text" name="view_id" className="regular-text" id="view_id"/>
									<p className="description">
										<a href="https://stackoverflow.com/a/47921777"
										   rel="noopener" target="_blank">Steps to get View ID</a>
									</p>
									<ErrorMessage name="view_id" component="p" className="description error"/>
								</td>
							</tr>
							<tr>
								<th scope="row"><label htmlFor="page_view_timeout">Page View Timeout</label></th>
								<td>
									<Field type="text" name="page_view_timeout" className="regular-text"
										   id="page_view_timeout"/>
									<p className="description">
										Required time (as seconds) needs to past to be able to count a post as viewed.
									</p>
									<ErrorMessage name="page_view_timeout" component="p" className="description error"/>
								</td>
							</tr>
							</tbody>
						</table>
						<p className="submit">
							<button type="submit" className="button button-primary">Save Changes</button>
						</p>
					</Form>
				}}
			</Formik>
		</div>
	}

	validate = (values) => {
		console.log('validate', values);
	}

	handleSubmit = () => {
		this.refForm.current.submit();
	}
}

export default AnalyticsSettings;
