import React from 'react';
import {addFilter} from "@wordpress/hooks";
import {Fragment} from "@wordpress/element";
import {createHigherOrderComponent} from "@wordpress/compose";

const controllerMap = {
	'core/heading': require('./core-heading').default
}

addFilter(
	'editor.BlockEdit',
	'trevor/block-edit',
	createHigherOrderComponent((BlockEdit) => (props) => {
		const {name} = props;

		if (!(name in controllerMap)) {
			return <BlockEdit {...props} />;
		}

		const Controller = controllerMap[name];

		const {placement} = Controller;
		const controller = <Controller {...props}/>;

		return (
			<Fragment>
				{placement === 'top' && controller}
				<BlockEdit {...props} />
				{placement !== 'top' && controller}
			</Fragment>
		);
	}, 'trevorBlockEdit')
);
