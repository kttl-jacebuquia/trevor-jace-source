import {addFilter} from "@wordpress/hooks";
import React from "react";
import {SelectControl} from "@wordpress/components";
import {compose} from "@wordpress/compose";
import {withDispatch, withSelect} from "@wordpress/data";
import {htmlDecode} from 'plugin/js/main/utils';

const {editorBlocksData} = TrevorWP.screen;
const {metaKeys, catTax} = editorBlocksData;
const {KEY_MAIN_CATEGORY: MAIN_CAT_META_KEY} = metaKeys;
let fCatCache = null;

const MainCategoryInput = compose(
	withSelect(select => {
		const {getEditedPostAttribute} = select('core/editor');
		const postID = getEditedPostAttribute('id');
		const cats = getEditedPostAttribute(catTax);
		const metaData = getEditedPostAttribute('meta');
		const mainCat = metaData[MAIN_CAT_META_KEY];

		const allCats = wp.data.select('core').getEntityRecords('taxonomy', catTax, {
			per_page: -1,
			include: cats,
		}) || [];

		return {
			postID,
			cats,
			allCats,
			mainCat
		}
	}),
	withDispatch(dispatch => {
		const {editPost} = dispatch('core/editor');

		return {editPost}
	})
)(class extends React.Component {
	handleChange = (selected) => this.setMainCat(parseInt(selected));

	componentDidUpdate({}) {
		const {cats, mainCat} = this.props;

		if (mainCat) {
			if (cats.length === 0) {
				// No categories left
				this.setMainCat(0);
			} else if (-1 === cats.indexOf(mainCat)) {
				// Prev is deleted, pick the first one
				this.setMainCat(cats[0]);
			}
		} else {
			if (cats.length) {
				// Pick the first one
				this.setMainCat(cats[0]);
			}
		}
	}

	render() {
		const {OriginalComponent, originalProps} = this.props;

		return <>
			<OriginalComponent {...originalProps}
							   terms={originalProps.terms}/>
			{this.renderInput()}
		</>
	}

	renderInput() {
		const {allCats, mainCat} = this.props;

		if (allCats.length < 2) {
			return null;
		}

		return <SelectControl label="Main Category"
							  value={mainCat}
							  options={allCats.map(cat => ({label: htmlDecode(cat.name), value: cat.id}))}
							  onChange={this.handleChange}/>
	}

	setMainCat = (cat) => this.props.editPost({meta: {[MAIN_CAT_META_KEY]: fCatCache = cat}});
});


addFilter(
	'editor.PostTaxonomyType',
	'trvr/main-category',
	OriginalComponent => originalProps => {
		if (originalProps && originalProps.slug === catTax) {
			return <MainCategoryInput originalProps={originalProps}
									  OriginalComponent={OriginalComponent}/>
		} else {
			return <OriginalComponent {...originalProps}/>
		}
	}
);
