import React from 'react';
import $ from 'jquery';
import postAutoCompleter from 'plugin/js/blocks/misc/post-auto-completer';
import ListItem from "./list-item";

class ObjectSelector extends React.Component {
	refPTSelect = React.createRef();
	refSearchInput = React.createRef();
	refItemList = React.createRef();

	constructor(props) {
		super(props);

		const {control: {params: {post_type}}} = this.props;

		this.isMultiPostType = Array.isArray(post_type);

		this.state = {
			list: props.control.params.default_value || {},
			selectedPT: this.isMultiPostType ? post_type[0] : post_type,
		}
	}

	componentDidMount() {
		this.$input = $(this.refSearchInput.current);
		this.$list = $(this.refItemList.current);

		// Init Autocomplete
		this.$input.autocomplete({
			source: this.getAutoCompleteHandler(),
			appendTo: '#customize-controls',
			select: (e, {item: {label: name, value: id}}) => {
				this.addItem(id, name);
				this.$input.val('');
				return false;
			}
		});

		this.$list.sortable({
			containment: "parent",
			cursor: "ns-resize",
			pointer: 'pointer',
			stop: () => {
				const oldList = this.state.list;
				const newList = {};

				this.$list.find('li').each(function () {
					const $this = $(this);
					const id = parseInt($this.attr('data-id'));
					const idx = $this.index();

					newList[id] = {...oldList[id], order: idx}
				});

				// Cancel sortable
				this.$list.sortable('cancel');

				this.setState({list: newList});
			}
		});
	}

	getAutoCompleteHandler() {
		const {selectedPT} = this.state;
		const {control: {params: {object_type, taxonomy, parent}}} = this.props;
		let collection = (object_type === 'post' ? selectedPT : taxonomy);

		// Default: Make the first letter capital
		collection = collection.charAt(0).toUpperCase() + collection.slice(1);

		// Fix irregular ones
		if (object_type === 'post') {
			if (selectedPT === 'post') {
				collection = 'Posts';
			}
		} else if (object_type === 'taxonomy') {
			if (taxonomy === 'category') {
				collection = 'Categories';
			}
		}

		if (!collection) {
			throw Error(`Unknown collection type.`);
		}

		return postAutoCompleter(collection, () => {
			const ext = {};

			if (object_type === 'taxonomy') {
				// Parent filter
				if (parent != null) {
					Object.assign(ext, {parent});
				}
			} else if (object_type === 'post') {
				// Taxonomy filter
				if (taxonomy && typeof taxonomy === 'object') {
					Object.assign(ext, taxonomy);
				}
			}

			return {exclude: Object.keys(this.state.list), ...ext}
		});
	}

	componentDidUpdate(prevProps, prevState, snapshot) {
		const {list} = this.state;
		this.$list.sortable('refresh');

		if (this.state.selectedPT !== prevState.selectedPT) {
			// Update the auto complete handler
			this.$input.autocomplete("option", "source", this.getAutoCompleteHandler())
		}

		this.props.onChange(Object
			.keys(list)
			.map(objId => ({objId, ...list[objId]}))
			.sort(({order: a}, {order: b}) => a - b)
			.map(({objId}) => objId)
			.join(',')
		);
	}

	render() {
		return <div>
			{this.renderPostTypeInput()}
			{this.renderSearchInput()}
			{this.renderList()}
		</div>
	}

	renderPostTypeInput() {
		const {control: {params: {post_type, object_type}}} = this.props;

		if (object_type !== 'post' || !this.isMultiPostType) {
			return; // single post type
		}

		return <div>
			<select ref={this.refPTSelect} onChange={this.handlePostTypeChange} style={{marginBottom: 10}}>
				<option value="" disabled>Post Type</option>
				{post_type.map(pt => <option key={pt} value={pt}>{TrevorWP.common.post_types[pt].label}</option>)}
			</select>
		</div>
	}

	renderSearchInput() {
		return <div>
			<input type="text" ref={this.refSearchInput} placeholder="Search"/>
		</div>
	}

	renderList() {
		const {list} = this.state;
		const {control: {params: {allow_order, object_type}}} = this.props;
		const keys = Object.keys(list);

		if (keys.length === 0) {
			return <p><em>Nothing selected. Please use the search input above to add an item.</em></p>
		}

		const El = allow_order ? 'ol' : 'ul';

		return <El className="object-list" ref={this.refItemList}>
			{keys
				.map(objId => ({objId, ...list[objId]}))
				.sort(({order: a}, {order: b}) => a - b)
				.map((item) =>
					<ListItem key={item.objId}
							  object_type={object_type}
							  {...item}
							  onRemove={this.removeItem}/>)}
		</El>
	}

	addItem = (id, name, order = null) => {
		const {list, selectedPT} = this.state;
		const {control: {params: {object_type, taxonomy}}} = this.props;

		const ext = {};
		if (object_type === 'post') {
			ext.pt = selectedPT;
		} else if (object_type === 'taxonomy') {
			ext.tax = taxonomy;
		}

		this.setState({
			list: {
				...list,
				[id]: {
					name,
					order: order === null ? Object.keys(list).length : order,
					...ext
				}
			}
		});
	};

	removeItem = (id) => {
		const {list: {[id]: remove, ...others}} = this.state;

		this.setState({list: others})
	};

	componentWillUnmount() {
		this.$input.autocomplete("destroy");
		this.$list.sortable("destroy");
	}

	handlePostTypeChange = () => this.setState({selectedPT: this.refPTSelect.current.value})
}

export default ObjectSelector;
