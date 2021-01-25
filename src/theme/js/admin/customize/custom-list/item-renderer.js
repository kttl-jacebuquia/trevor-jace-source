import React from 'react';
import ReactDOM from 'react-dom';
import * as fieldComponents from './fields';
import FormBuilder from "./form-builder";

export default class ItemRenderer extends React.Component {
	state = {
		editing: false,
	};

	componentDidMount() {
		this.elem = ReactDOM.findDOMNode(this);

		this.elem.controller = this // attach itself to element
	}

	render() {
		const {
			state: {editing},
			props: {idx, fields, data, updateValue, removeItem}
		} = this;

		return <div className="list-item" data-idx={idx} onClick={editing ? null : this.enterEditMode}>
			{editing
				? <FormBuilder defaultValues={data}
							   fields={fields}
							   idx={idx}
							   key={'' + idx + JSON.stringify(data)}
							   updateValue={updateValue}
							   remove={removeItem}
							   close={this.exitEditMode}/>
				: Object.keys(fields).map(this.renderField)
			}
		</div>
	}

	renderField = (fieldKey) => {
		const {props: {fields, data}} = this;

		const fieldData = fields[fieldKey];
		const {label, type} = fieldData;

		let renderedValue = 'N/A';

		if ((type in fieldComponents)) {
			renderedValue = fieldComponents[type].renderValue(fieldData, data[fieldKey]);
		}

		return <div className="rendered-item-wrap">
			<div className="rendered-item-label">{label}</div>
			<div className="rendered-item-value">{renderedValue}</div>
		</div>
	}

	enterEditMode = (e) => {
		e.preventDefault();
		this.setState({editing: true});
	}

	exitEditMode = (e) => {
		e && e.preventDefault();
		this.setState({editing: false})
	}
}
