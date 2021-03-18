import React from 'react';
import BaseField from './base';

export default class Input extends BaseField {
	render() {
		const {input_type = 'text', value = ''} = this.props;

		return <input type={input_type} defaultValue={['checkbox', 'radio'].indexOf(input_type) === -1 ? value : 1}
					  onChange={this.handleChange} ref={this.fieldRef}/>
	}

	static renderValue(field, value) {
		const {input_type = 'text'} = field;

		if (['checkbox', 'radio'].indexOf(input_type) !== -1) {
			return value ? 'True' : 'False';
		}

		return value || <em>N/A</em>;
	}
}
