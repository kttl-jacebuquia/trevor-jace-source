import React from 'react';
import {InnerBlocks} from '@wordpress/block-editor'
import {registerBlockType} from '@wordpress/blocks';
import icon from '!!@svgr/webpack!assets/to-do-list.svg';
import edit from './edit';

// Register
export default registerBlockType('trevor/bottom-list', {
	title: 'Bottom List (Trevor)',
	icon,
	category: 'trevor',
	attributes: {
		title: {type: 'text', default: ''}
	},
	edit,
	save: () => <InnerBlocks.Content/>
});
