import React from 'react';
import {NAME as childName} from './list-item';
import {InnerBlocks, InspectorControls} from '@wordpress/block-editor'
import {TextControl} from '@wordpress/components';

const ALLOWED_BLOCKS = [
	childName
];

export default class extends React.Component {
	handleTitleChange = (title) => this.props.setAttributes({title});
	handleCaptionChange = (caption) => this.props.setAttributes({caption});

	render() {
		const {isSelected, className, attributes: {title}} = this.props;

		return <>
			<InspectorControls>
				<div style={{padding: 16}}>
					<TextControl
						label="Title"
						value={title}
						onChange={this.handleTitleChange}
						autoComplete="off"
					/>
				</div>
			</InspectorControls>
			<div className={className}>
				{isSelected
					? <>
						<TextControl
							label="List Title"
							placeholder="List Title"
							value={title}
							onChange={this.handleTitleChange}
							autoComplete="off"
							type="text"
						/>
					</>
					: <>
						<span>{title || <em>No title</em>}</span>
					</>
				}
				<InnerBlocks allowedBlocks={ALLOWED_BLOCKS}
							 template={[[childName, {}], [childName, {}]]}
							 templateLock={false}/>
			</div>
		</>
	}
}
