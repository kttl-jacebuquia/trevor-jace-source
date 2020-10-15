import React from 'react';
import ReactDOM from 'react-dom';
import App from './main';
import {Provider} from "react-redux";
import {hot} from "react-hot-loader/root";
import store from "./store";

const WrappedApp = hot(() => <Provider store={store}><App/></Provider>)

export default (elem) => {
	ReactDOM.render(<WrappedApp/>, elem);
}
