import React from 'react';
import * as fieldComponents from './fields';

class FormBuilder extends React.Component {
	constructor(props) {
		super(props);

		this.state = {
			data: this.props.defaultValues || {},
		}
	}

	render() {
		const {props: {fields, idx}} = this;

		const isNew = idx === -1;

		return <form onSubmit={this.handleSave}>
			<div className="fields-wrap">
				{Object.keys(fields).map(this.renderField)}
			</div>
			<div className="form-footer">
				{!isNew &&
				<button type="button" className="button remove-button" onClick={this.handleRemove}>Remove</button>}
				<button type="submit" className="button save-button">{isNew ? 'Add' : 'Update'}</button>
				<button type="button" className="button cancel-button" onClick={this.handleClose}>Cancel</button>
			</div>
		</form>
	}

	renderField = (fieldKey) => {
		const {
			state: {data},
			props: {fields},
		} = this;

		const fieldData = fields[fieldKey];
		const {label, type, ...fieldProps} = fieldData;

		if (!(type in fieldComponents)) {
			console.log(`Unknown field type: ${type}`)
			return <div/>
		}

		const Field = fieldComponents[type];

		return <div key={fieldKey} className="field-row">
			<div className="field-title">{label}</div>
			<div className="field-wrap">
				{<Field {...fieldProps}
						value={data[fieldKey]}
						fieldKey={fieldKey}
						onChange={this.updateFieldVal}/>}
			</div>
		</div>
	}

	handleRemove = (e) => {
		e.preventDefault();
		const {idx, remove} = this.props;

		remove(idx);
	}

	handleClose = (e) => {
		e.preventDefault();

		this.props.close();
	}

	handleSave = (e) => {
		e.preventDefault();

		const {
			state: {data},
			props: {updateValue, idx, close}
		} = this;

		updateValue(idx, data);
		close();
	}

	updateFieldVal = (key, value) => {
		const {data} = this.state;

		this.setState({data: {...data, [key]: value}});
	}
}

export default FormBuilder;
