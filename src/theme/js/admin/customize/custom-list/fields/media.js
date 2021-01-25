import React from 'react';
import BaseField from "./base";

export default class Media extends BaseField {
	render() {
		const {value: {url} = {}} = this.props;

		return <div>
			<div className="attachment-media-view">
				{url
					? <img className="attachment-thumb" alt="Thumbnail" src={url} onClick={this.openMediaBox}/>
					:
					<button type="button" className="upload-button button-add-media" onClick={this.openMediaBox}>Select
						image</button>}

				{url && <div className="actions">
					<button type="button" className="button remove-button" onClick={this.removeMedia}>Remove</button>
					<button type="button" className="button upload-button control-focus"
							onClick={this.openMediaBox}>Change media
					</button>
				</div>}
			</div>
		</div>
	}

	openMediaBox = (e) => {
		e.preventDefault();

		const {value: {id} = {}, onChange, fieldKey} = this.props;

		// Re-open if already initiated
		if (this.frame) {
			if (id) {
				this.frame.state().get('selection').reset([wp.media.attachment(id)]);
			}
			this.frame.open(this.attachment);

			return;
		}

		// Create a new media frame
		this.frame = wp.media({
			title: 'Select or Upload Media',
			button: {
				text: 'Use this media',
			},
			multiple: false,
		});


		this.frame.on('select', () => {
			const {id, url} = this.frame.state().get('selection').first().toJSON();
			onChange(fieldKey, {id, url});
		});

		this.frame.open();
	}

	removeMedia = e => {
		e.preventDefault();
		const {onChange, fieldKey} = this.props;

		onChange(fieldKey, {});
	}

	static renderValue(field, value) {
		if (!value || !('url' in value)) {
			return <em>N/A</em>;
		}

		return <img src={value.url} alt="Thumbnail"/>;
	}
}
