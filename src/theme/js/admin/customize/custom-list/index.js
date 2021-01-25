import React from 'react';
import $ from 'jquery';
import FormBuilder from './form-builder';
import ItemRenderer from "./item-renderer";


class CustomList extends React.Component {
	refItemList = React.createRef();

	constructor(props) {
		super(props);

		const {control: {params: {fields}}} = this.props;

		this.state = {
			newPanelOpen: false,
			listOpen: false,
			editing: -1,
			data: CustomList.sanitizeData(fields, props.control.params.default_value || []),
		};
	}

	componentDidMount() {
		this.$list = $(this.refItemList.current);

		this.$list.sortable({
			containment: "parent",
			cursor: "ns-resize",
			pointer: 'pointer',
			stop: () => {
				const oldList = this.state.data;
				const newList = [];

				this.$list.find('.list-item').each(function () {
					const $this = $(this);
					const oldIdx = parseInt($this.attr('data-idx'));
					// const newIdx = $this.index();
					newList.push(oldList[oldIdx]);
				});

				// Cancel sortable
				this.$list.sortable('cancel');

				this.setState(
					{data: newList},
					() => this.props.onChange(this.state.data)
				);
			}
		});
	}

	render() {
		const {
			state: {newPanelOpen, listOpen, data},
			props: {control: {params: {fields}}}
		} = this;

		return <div>
			{newPanelOpen
				? this.renderNewPanel()
				: <a href="#" onClick={this.openNewPanel}>Add New Item</a>}
			<a href="#"
			   className="toggle-item-list"
			   onClick={this.toggleList}>{listOpen ? 'Hide items' : `Show ${data.length} item${data.length > 1 ? 's' : ''}`}</a>
			<div ref={this.refItemList} style={{display: listOpen ? 'block' : 'none'}} className="item-list">
				{data.map((itemData, idx) =>
					<ItemRenderer idx={idx}
								  fields={fields}
								  key={'' + idx + JSON.stringify(data[idx])}
								  removeItem={this.removeItem}
								  updateValue={this.setItemValue}
								  data={data[idx] || {}}/>)}
			</div>

			{(listOpen || newPanelOpen) && <>
				<hr className="btm-line"/>
			</>}
		</div>
	}

	renderNewPanel() {
		const {props: {control: {params: {fields}}}} = this;

		return <div className="new-item-wrap">
			<h4>Add a New Item</h4>
			<FormBuilder fields={fields} defaultValues={{}} updateValue={this.setItemValue} idx={-1}
						 close={this.closeNewPanel}/>
		</div>
	}

	removeItem = (idx) => {
		const clonedData = [...this.state.data];
		clonedData.splice(idx, 1);

		this.setState(
			{data: clonedData},
			() => this.props.onChange(this.state.data)
		)
	}

	setItemValue = (idx, value) => {
		const {state: {data, newPanelOpen: _newPanelOpen}, props: {onChange}} = this;

		const isNew = idx === -1;
		const newPanelOpen = isNew
			? false
			: _newPanelOpen;

		if (isNew) idx = data.length;

		this.setState({
			newPanelOpen,
			data: [
				...data.slice(0, idx),
				value,
				...data.slice(idx + 1),
			]
		}, () => onChange(this.state.data));
	}

	openNewPanel = (e) => {
		e.preventDefault();
		this.setState({newPanelOpen: true, listOpen: false});
	}

	closeNewPanel = () =>
		this.setState({newPanelOpen: false});

	toggleList = (e) => {
		e.preventDefault();
		this.setState({listOpen: !this.state.listOpen});
	}

	static sanitizeData(schema, data) {
		// TODO: Sanitize data
		return data;
	}
}

export default CustomList;
