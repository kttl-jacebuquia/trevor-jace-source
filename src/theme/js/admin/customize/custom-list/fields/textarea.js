import React from 'react';
import BaseField from "./base";

export default class Textarea extends BaseField {
	render() {
		const {value} = this.props;

		return <textarea onChange={this.handleChange} className="widefat" defaultValue={value} ref={this.fieldRef}/>
	}

	static renderValue(field, value) {
		return value || <em>N/A</em>;
	}
}
