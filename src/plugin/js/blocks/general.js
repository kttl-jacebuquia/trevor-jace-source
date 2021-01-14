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
	TextControl,
} from "@wordpress/components";
import FileFiled from "./fields/file";

const {editorBlocksData} = TrevorWP.screen;
const {metaKeys} = editorBlocksData;
const {[metaKeys.KEY_HEADER_BG_CLR]: BG_COLOR_DATA} = editorBlocksData;
const META_KEY_MAP = {
	headerType: metaKeys.KEY_HEADER_TYPE,
	headerBgColor: metaKeys.KEY_HEADER_BG_CLR,
	lengthInd: metaKeys.KEY_LENGTH_IND,
	showShare: metaKeys.KEY_HEADER_SNOW_SHARE,
	reRecirculationCards: metaKeys.KEY_RECIRCULATION_CARDS,
};
const BG_COLOR_HEX_2_NAME_MAP = (() => {
	const out = {};
	Object.keys(BG_COLOR_DATA.colors).forEach(key => out[BG_COLOR_DATA.colors[key].color] = key);
	return out;
})();

class PostSidebar extends React.Component {
	handleHeaderTypeChange = (newVal) => this.props.updatePostMeta('headerType', newVal);
	handleShowShare = (newVal) => this.props.updatePostMeta('showShare', newVal);
	handleHeaderBgColor = (colorHex) => {
		this.props.updatePostMeta('headerBgColor', BG_COLOR_HEX_2_NAME_MAP[colorHex] || null);
	}
	handleReRecirculationCardsChange = (newVal) => this.props.updatePostMeta('reRecirculationCards', newVal);
	handleLengthIndChange = (newVal) => this.props.updatePostMeta('lengthInd', newVal);
	handleSlugChange = (slug) => this.props.editPost({slug});

	constructor(...args) {
		super(...args);

		this.selectOptions = {
			headerTypes: (() => {
				const {types} = editorBlocksData[META_KEY_MAP.headerType];

				return Object.keys(types).map(key => {
					const config = types[key];
					return {value: key, label: config.name}
				})
			})(),
			contentLength: (() => {
				const {settings} = editorBlocksData[META_KEY_MAP.lengthInd];

				return Object.keys(settings).map(key => {
					const config = settings[key];

					return {value: key, label: config.name};
				})
			})(),
			reRecirculationCards: (() => {
				const {settings} = editorBlocksData[META_KEY_MAP.reRecirculationCards];

				return Object.keys(settings).map(key => {
					const config = settings[key];

					return {value: key, label: config.name};
				})
			})()
		};
	}

	render() {
		const {
			headerType,
			headerBgColor,
			lengthInd,
			postType,
			showShare,
			slug,
			reRecirculationCards,
		} = this.props;

		console.log({reRecirculationCards})

		const {supports: headerSupports = []} = editorBlocksData[META_KEY_MAP.headerType].types[headerType] || {};

		return <>
			<PluginDocumentSettingPanel name="trevor-entry-general" icon="admin-settings" title="General">
				{/* Slug */}
				<TextControl label="Slug" value={slug} onChange={this.handleSlugChange}/>

				{/* File */}
				{-1 !== editorBlocksData.collections.fileEnabled.indexOf(postType) &&
				<FileFiled
					key="fileInput"
					metaKey={TrevorWP.screen.editorBlocksData.metaKeys['KEY_FILE']}
					label="PDF Version"
					allowedTypes={['application/pdf']}
				/>}
			</PluginDocumentSettingPanel>
			<PluginDocumentSettingPanel name="trevor-entry-header" icon="store" title="Header">
				{/* Header Type */}
				<SelectControl
					label="Header Type"
					value={headerType}
					options={this.selectOptions.headerTypes}
					onChange={this.handleHeaderTypeChange}
				/>
				{/* Header BG Color */}
				{-1 !== headerSupports.indexOf('bg-color') &&
				<BaseControl label="Background Color">
					<ColorPalette
						colors={Object.values(BG_COLOR_DATA.colors)}
						clearable={false}
						value={(BG_COLOR_DATA.colors[headerBgColor] || {color: BG_COLOR_DATA.colors[BG_COLOR_DATA.default]}).color}
						disableCustomColors={true}
						onChange={this.handleHeaderBgColor}
					/>
				</BaseControl>
				}

				{/* Sharing Box */}
				<CheckboxControl label="Show Sharing"
								 checked={showShare}
								 onChange={this.handleShowShare}/>

				{/* Content Length */}
				<SelectControl
					label="Content Length"
					value={lengthInd}
					options={this.selectOptions.contentLength}
					onChange={this.handleLengthIndChange}
				/>
				<SelectControl label="Recirculation Cards"
							   value={reRecirculationCards}
							   options={this.selectOptions.reRecirculationCards}
							   onChange={this.handleReRecirculationCardsChange}
							   help="You may use CTRL-Click (Windows) or CMD-Click (Mac) to de/select"
							   multiple/>
			</PluginDocumentSettingPanel>
		</>
	}
}

registerPlugin('trevor-article-custom', {
	render: compose(
		// Select
		withSelect(select => {
			const {getEditedPostAttribute, getCurrentPostType, getEditedPostSlug} = select('core/editor');
			const postID = getEditedPostAttribute('id');
			const metaData = getEditedPostAttribute('meta');
			const slug = getEditedPostSlug();
			const normalTitle = getEditedPostAttribute('title');
			const template = getEditedPostAttribute('template');
			const postType = getCurrentPostType();

			const out = {postID, normalTitle, template, postType, slug};

			Object.keys(META_KEY_MAP).forEach(varName => out[varName] = metaData[META_KEY_MAP[varName]]);

			return out;
		}),
		// Dispatch
		withDispatch(dispatch => {
			const {editPost} = dispatch('core/editor');

			return {
				updatePostMeta(metaKey, value) {
					editPost({meta: {[META_KEY_MAP[metaKey]]: value}});
				},
				editPost
			};
		})
	)(PostSidebar)
});
