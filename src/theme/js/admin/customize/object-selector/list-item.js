import React from 'react';

class ListItem extends React.Component {
	render() {
		const {objId, name} = this.props;

		return <li data-id={objId} className="list-item">
			<div className="post-name">{name}</div>
			<div className="post-detail-wrap">
				<strong className="post-id">#{objId}</strong>
				&nbsp;
				<em className="post-type">{this.getTypeDisplay()}</em>
				<a href="#" className="del-link" onClick={this.handleRemove}>
					<span className="dashicons dashicons-no-alt"/>
				</a>
			</div>
		</li>
	}

	getTypeDisplay() {
		const {object_type, pt, tax} = this.props;

		switch (object_type) {
			case 'post':
				return (TrevorWP.common.post_types[pt] || {}).label || 'N/A';
			case 'taxonomy':
				return (TrevorWP.common.taxonomies[tax] || {}).label || 'N/A';
			default:
				return object_type;
		}
	}

	handleRemove = (e) => {
		e.preventDefault();
		this.props.onRemove(this.props.objId);
	}
}

export default ListItem;
