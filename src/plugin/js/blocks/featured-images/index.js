import React from "react";
import {addFilter} from "@wordpress/hooks";
import {BaseControl} from "@wordpress/components";
import FileFiled from '../fields/file';

addFilter(
	"editor.PostFeaturedImage",
	"trevor/wrap-post-featured-image",
	OriginalComponent => props => {
		return (
			<>
				<BaseControl label="Vertical Image">
					<OriginalComponent {...props}/>
				</BaseControl>
				<FileFiled
					key="horizontal"
					metaKey={TrevorWP.screen.editorBlocksData.metaKeys['KEY_IMAGE_HORIZONTAL']}
					label="Horizontal Image"
					allowedTypes={['image']}
				/>
				<FileFiled
					key="square"
					metaKey={TrevorWP.screen.editorBlocksData.metaKeys['KEY_IMAGE_SQUARE']}
					label="Square Image"
					allowedTypes={['image']}
				/>
			</>
		);
	}
);
