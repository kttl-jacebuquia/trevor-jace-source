import React from 'react';
import {InnerBlocks, InspectorControls} from '@wordpress/block-editor';
import {TextControl} from '@wordpress/components';

export default class extends React.Component {
	handleTitleChange = (title) => this.props.setAttributes({title});

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
						<strong>{title || <em>No title</em>}</strong>
					</>
				}
				<InnerBlocks allowedBlocks={['core/list']}
							 template={[['core/list', {}]]}
							 templateLock={true}/>
			</div>
		</>
	}
}
