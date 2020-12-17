import React from 'react';
import {PluginDocumentSettingPanel} from '@wordpress/edit-post';
import {registerPlugin} from "@wordpress/plugins";
import {compose} from "@wordpress/compose";
import {withDispatch, withSelect} from "@wordpress/data";
import {
	BaseControl,
	CheckboxControl,
	ColorPalette,
	SelectControl,
	TextareaControl,
	TextControl
} from "@wordpress/components";

const META_KEY_PREFIX = '_trevor_';

const META_KEY_MAP = {
	headerType: META_KEY_PREFIX + 'header_type',
}

class PostSidebar extends React.Component {
	handleHeaderTypeChange = (newVal) => this.props.updatePostMeta('headerType', newVal);

	render() {
		const {
			headerType,
		} = this.props;

		return <>
			<PluginDocumentSettingPanel name="trvr-entry-header" icon="store" title="Header">
				<SelectControl
					label="Header Type"
					value={headerType}
					options={(function () {
						const {types} = TrevorWP.screen.editorBlocksData[META_KEY_MAP.headerType];

						return Object.keys(types).map(key => {
							const config = types[key];
							return {value: key, label: config.name}
						})
					})()}
					onChange={this.handleHeaderTypeChange}
				/>
			</PluginDocumentSettingPanel>
		</>
	}
}

registerPlugin('hatch-babe-article-custom', {
	render: compose(
		// Select
		withSelect(select => {
			const {getEditedPostAttribute, getCurrentPostType} = select('core/editor');
			const postID = getEditedPostAttribute('id');
			const metaData = getEditedPostAttribute('meta');
			const normalTitle = getEditedPostAttribute('title');
			const template = getEditedPostAttribute('template');
			const postType = getCurrentPostType();

			const out = {postID, normalTitle, template, postType};

			Object.keys(META_KEY_MAP).forEach(varName => out[varName] = metaData[META_KEY_MAP[varName]]);

			return out;
		}),
		// Dispatch
		withDispatch(dispatch => {
			const {editPost} = dispatch('core/editor');

			return {
				updatePostMeta(key, value) {
					editPost({meta: {[META_KEY_MAP[key]]: value}});
				},
				editPost
			};
		})
	)(PostSidebar)
});
