import React from 'react';
import {InspectorControls} from '@wordpress/block-editor';
import {BaseControl, PanelBody, PanelRow} from "@wordpress/components";
import {AutoCompleteField} from 'plugin/js/blocks/fields';
import postAutoCompleter from 'plugin/js/blocks/misc/post-auto-completer';

export default class extends React.Component {
	resetMeta = () => this.props.setAttributes({meta: {}});

	metaChangeHandler = metaKey => e => {
		this.props.setAttributes({
			meta: {
				...this.props.attributes.meta,
				[metaKey]: e.target ? e.target.value : e // Support both DOM onChange & direct set
			}
		});
	}

	onArticleSelect = ({label, value}) => {
		this.metaChangeHandler('id')(value);
		this.metaChangeHandler('title')(label);
	}

	render() {
		const {attributes: {meta: {id, title} = {}} = {}, panelTitle, apiOptions, className} = this.props;

		return <>
			<InspectorControls>
				<PanelBody
					title={panelTitle}
					icon="media-document"
					initialOpen={true}
				>
					<PanelRow>
						{id
							? <BaseControl label="Selected" className="widefat">
								<br/>
								<a href="#" title="Click to change"
								   onClick={this.resetMeta}>
									<span>#{id}</span>
									&nbsp;
									<strong>{title}</strong>
								</a>
							</BaseControl>
							: <AutoCompleteField label="Entry"
												 help="Please start to type to search."
												 autoCompleter={postAutoCompleter('Trevor_rc_glossary', apiOptions)}
												 onSelect={this.onArticleSelect}/>}

					</PanelRow>
				</PanelBody>
			</InspectorControls>
			<div className={className}>
				{id
					? <div>Glossary: <span>#{id}</span> <strong>{title}</strong></div>
					: <em>No item selected.</em>}
			</div>
		</>
	}
}
