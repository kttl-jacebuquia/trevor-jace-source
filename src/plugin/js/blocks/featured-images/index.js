import React from "react";
import {addFilter} from "@wordpress/hooks";
import {BaseControl} from "@wordpress/components";
import FileFiled from '../fields/file';

addFilter(
	"editor.PostFeaturedImage",
	"trevor/wrap-post-featured-image",
	OriginalComponent => props => {
		const metaKeys = ((TrevorWP.screen.editorBlocksData || {}).metaKeys || {});
		return (
			<>
				<BaseControl label="Vertical Image">
					<OriginalComponent {...props}/>
				</BaseControl>
				{metaKeys['KEY_IMAGE_HORIZONTAL'] &&
				<FileFiled
					key="horizontal"
					metaKey={metaKeys['KEY_IMAGE_HORIZONTAL']}
					label="Horizontal Image"
					allowedTypes={['image']}
				/>}
				{metaKeys['KEY_IMAGE_SQUARE'] &&
				<FileFiled
					key="square"
					metaKey={metaKeys['KEY_IMAGE_SQUARE']}
					label="Square Image"
					allowedTypes={['image']}
				/>}
			</>
		);
	}
);
