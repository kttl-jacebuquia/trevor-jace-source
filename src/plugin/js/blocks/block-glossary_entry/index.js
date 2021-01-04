import React from 'react';
import {InnerBlocks} from '@wordpress/block-editor'
import {registerBlockType} from '@wordpress/blocks';
import icon from '!!@svgr/webpack!assets/book-solid.svg';
import edit from './edit';

// Register
export default registerBlockType('trevor/glossary-entry', {
	title: 'Glossary Item (Trevor)',
	icon,
	category: 'trevor',
	attributes: {
		meta: {type: 'object', default: {}}
	},
	edit,
	save: () => <InnerBlocks.Content/>
});
