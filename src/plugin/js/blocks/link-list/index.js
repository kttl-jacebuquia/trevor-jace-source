import React from 'react';
import {InnerBlocks} from '@wordpress/block-editor'
import {registerBlockType} from '@wordpress/blocks';
import icon from '!!@svgr/webpack!assets/clipboard-list-solid.svg';
import edit from './edit';

// Child
import './list-item';

// Register
export default registerBlockType('trevor/link-list', {
	title: 'Link List (Trevor)',
	icon,
	category: 'trevor',
	attributes: {
		title: {type: 'string'},
	},
	edit,
	save: () => <InnerBlocks.Content/>
});
