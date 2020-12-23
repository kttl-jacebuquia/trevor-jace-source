import React from 'react';
import {addFilter} from "@wordpress/hooks";
import {TextareaControl} from '@wordpress/components';

const {InspectorAdvancedControls} = wp.blockEditor;

/**
 * Adds the description input to the heading block.
 */
export default class CoreHeading extends React.Component {
	static placement = 'top';

	render() {
		const {
			attributes,
			setAttributes,
			isSelected,
		} = this.props;

		const {
			description,
		} = attributes;

		return <>
			{isSelected &&
			<InspectorAdvancedControls>
				<TextareaControl label="Description" value={description}
								 help="Enter description to list this heading in the highlights menu. Please also make you set an unique a HTML anchor to the input below."
								 onChange={(description) => setAttributes({description})}/>
			</InspectorAdvancedControls>
			}
		</>
	}
}

/**
 * Adds the custom attributes.
 */
addFilter(
	'blocks.registerBlockType',
	'trevor/heading-custom-attributes',
	(settings) => {
		if (settings && settings.name === 'core/heading' && settings.attributes) {
			settings.attributes = Object.assign(settings.attributes, {
				description: {
					type: 'text',
					default: '',
				}
			});
		}

		return settings;
	}
);
