import React from 'react';
import {registerBlockType} from "@wordpress/blocks";
import icon from '!!@svgr/webpack!assets/link-solid.svg';
import edit from './edit';

export const NAME = `trevor/link-list--item`;

export default registerBlockType(NAME, {
	title: 'Link-list Item',
	icon,
	category: 'trevor',
	attributes: {
		title: {type: 'string'},
		url: {type: 'url'}
	},
	parent: [`trevor/link-list`],
	edit
});
