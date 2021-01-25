import React from 'react';

export default class BaseField extends React.Component {
	fieldRef = React.createRef();

	handleChange = () => {
		const {onChange, fieldKey} = this.props;

		onChange(fieldKey, this.fieldRef.current.value);
	}
}
