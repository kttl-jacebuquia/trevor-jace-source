import React from 'react';
import General from './general';
import Analytics from './analytics'
import {hot} from "react-hot-loader/root";

class GoogleAdmin extends React.Component {
	render() {
		const {options} = this.props;
		return <>
			<General options={options}/>
			<Analytics options={options}/>
		</>
	}
}

export default hot((props) => <GoogleAdmin {...props}/>);
