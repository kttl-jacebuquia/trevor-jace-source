import React from 'react';
import {TextControl} from '@wordpress/components';
import {Dashicon} from "@wordpress/components";

export default class extends React.Component {
	handleTitleChange = (title) => this.props.setAttributes({title});
	handleUrlChange = (url) => this.props.setAttributes({url});

	render() {
		const {className} = this.props;

		return <div className={className}>
			<div className="inner-wrapper">
				{this.renderContentContainer()}
			</div>
		</div>
	}

	renderContentContainer() {
		const {isSelected, attributes: {title, url}} = this.props;

		if (isSelected) {
			return <>
				<TextControl type="text"
							 placeholder="Title"
							 label="Title"
							 defaultValue={title}
							 autoComplete="off"
							 onChange={this.handleTitleChange}
							 required/>
				<TextControl value={url}
							 placeholder="https://"
							 label="Url"
							 type="url"
							 autoComplete="off"
							 onChange={this.handleUrlChange}
							 required/>
			</>
		} else {
			return <>
				<a href={url} target="_blank" rel="noopener noreferrer nofollow">
					<h7>{title || <em>No title</em>}</h7>
				</a>
			</>
		}
	}
}
