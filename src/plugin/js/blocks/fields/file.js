import React from "react";
import {applyFilters} from "@wordpress/hooks";
import has from 'lodash/has';
import {
	BaseControl,
	Button,
	ResponsiveWrapper,
	Spinner
} from "@wordpress/components";
import {compose} from "@wordpress/compose";
import {withSelect, withDispatch} from "@wordpress/data";
import {MediaUpload} from "@wordpress/block-editor";

// https://github.com/WordPress/gutenberg/blob/master/packages/editor/src/components/post-featured-image/index.js
const FileField = compose(
	withSelect((select, {metaKey}) => {
		const {getMedia} = select('core');
		const {getEditedPostAttribute, getCurrentPostId} = select("core/editor");
		const metaData = getEditedPostAttribute("meta") || {};

		let value = metaData[metaKey] || null;
		value = parseInt(value);

		if (isNaN(value))
			value = null;

		return {
			value,
			media: value ? getMedia(value) : null,
			currentPostId: getCurrentPostId(),
		};
	}),
	withDispatch((dispatch, {metaKey}) => {
		const {editPost} = dispatch("core/editor");

		return {
			updateValue: value => editPost({meta: {[metaKey]: String(value)}})
		};
	})
)(
	class extends React.Component {
		onUpdateImage = media => this.props.updateValue(media.id);

		onRemoveImage = () => this.props.updateValue(null);

		render() {
			const {label, value, media, currentPostId, allowedTypes} = this.props;

			let mediaWidth, mediaHeight, mediaSourceUrl;
			if (media) {
				const mediaSize = applyFilters(
					'editor.PostFeaturedImage.imageSize',
					'full',
					media.id,
					currentPostId
				);

				if (has(media, ['media_details', 'sizes', mediaSize])) {
					// use mediaSize when available
					mediaWidth = media.media_details.sizes[mediaSize].width;
					mediaHeight = media.media_details.sizes[mediaSize].height;
					mediaSourceUrl = media.media_details.sizes[mediaSize].source_url;
				} else {
					// get fallbackMediaSize if mediaSize is not available
					const fallbackMediaSize = applyFilters(
						'editor.PostFeaturedImage.imageSize',
						'thumbnail',
						media.id,
						currentPostId
					);
					if (
						has(media, ['media_details', 'sizes', fallbackMediaSize])
					) {
						// use fallbackMediaSize when mediaSize is not available
						mediaWidth =
							media.media_details.sizes[fallbackMediaSize].width;
						mediaHeight =
							media.media_details.sizes[fallbackMediaSize].height;
						mediaSourceUrl =
							media.media_details.sizes[fallbackMediaSize].source_url;
					} else {
						// use full image size when mediaFallbackSize and mediaSize are not available
						mediaWidth = media.media_details.width;
						mediaHeight = media.media_details.height;
						mediaSourceUrl = media.source_url;
					}
				}
			}

			return (
				<>
					<hr/>
					<BaseControl label={label}>
						<div className="editor-post-featured-image">
							<MediaUpload
								title={label}
								onSelect={this.onUpdateImage}
								allowedTypes={allowedTypes}
								modalClass="editor-post-featured-image__media-modal"
								render={({open}) => (
									<div className="editor-post-featured-image__container">
										<Button
											className={
												!value
													? "editor-post-featured-image__toggle"
													: "editor-post-featured-image__preview"
											}
											onClick={open}
											aria-label={!value ? null : "Edit or update the image"}
										>
											{!!value && media && (
												<ResponsiveWrapper
													naturalWidth={mediaWidth}
													naturalHeight={mediaHeight}
													isInline
												>
													<img src={mediaSourceUrl} alt=""/>
												</ResponsiveWrapper>
											)}
											{!!value && !media && <Spinner/>}
											{!value && `Set ${label}`}
										</Button>
									</div>
								)}
								value={value}
							/>
							{!!value && media && !media.isLoading && (
								<>
									<br/>
									<MediaUpload
										title={label}
										onSelect={this.onUpdateImage}
										allowedTypes={allowedTypes}
										value={value}
										modalClass="editor-post-featured-image__media-modal"
										render={({open}) => (
											<Button onClick={open} isDefault isLarge>
												Replace {label}
											</Button>
										)}
									/>
								</>
							)}
							{!!value && (
								<>
									<Button onClick={this.onRemoveImage} isLink isDestructive>
										Remove {label}
									</Button>
								</>
							)}
						</div>
					</BaseControl>
				</>
			);
		}
	}
);

export default FileField;
