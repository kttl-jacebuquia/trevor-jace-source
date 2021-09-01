// staff__list-load-more
import Component from '../../Component';

export default class Staff extends Component {
	// Defines the element selector which will initialize this component
	static selector = '.js-staff';

	// Defines children that needs to be queried as part of this component
	static children = {
		list: '.staff__list',
		listLoadMore: '.staff__list-load-more',
	};

	// Defines event handlers to attach to children
	eventHandlers = {
		listLoadMore: {
			click: this.onListLoadMoreClick // Attaches onBodyClick method to this.children.body
		}
	};

	// Defines initial State
	state = {
		listExpanded: false,
	};

	onListLoadMoreClick(e) {
		e.preventDefault();
		this.setState({
			listExpanded: true,
		});
	}

	// Triggers when state is change by calling this.setState()
	componentDidUpdate(stateChange) {
		if ( 'listExpanded' in stateChange ) {
			this.children.list.classList.toggle('staff__list--expanded', stateChange.listExpanded);
		}
	}
}

// Uncomment this section if this component is intended
// to initialize on DOM load.
Staff.init();
