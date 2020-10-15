import React from 'react';
import ReactDOM from 'react-dom';
import Main from './main';

export default (elem, options) =>
	ReactDOM.render(<Main options={options}/>, elem);
