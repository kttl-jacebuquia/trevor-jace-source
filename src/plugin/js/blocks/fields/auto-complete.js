import $ from 'jquery';
import React from 'react';
import {BaseControl} from '@wordpress/components';


export default class AutoCompleteField extends React.Component {
	inputRef = React.createRef();

	componentDidMount() {
		const {autoCompleter, onSelect} = this.props;

		// Initiate auto complete
		this._get$input().autocomplete({
			source: autoCompleter,
			select: (event, {item}) => {
				onSelect(item); // item.label && item.value
			}
		});
	}

	render() {
		const {id, label, help} = this.props;

		return <BaseControl id={id} label={label} help={help} className="widefat">
			<input type="text" className="widefat" ref={this.inputRef} autoComplete="off"/>
		</BaseControl>
	}

	componentWillUnmount() {
		this._get$input().autocomplete("destroy");
	}

	_get$input() {
		return $(this.inputRef.current);
	}
}
