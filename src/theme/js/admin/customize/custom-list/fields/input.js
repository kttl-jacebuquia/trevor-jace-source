import React from 'react';
import BaseField from './base';

export default class Input extends BaseField {
	render() {
		const {inputType = 'text', value = ''} = this.props;

		return <input type={inputType} defaultValue={value} onChange={this.handleChange} ref={this.fieldRef}/>
	}

	static renderValue(field, value) {
		return value || <em>N/A</em>;
	}
}
