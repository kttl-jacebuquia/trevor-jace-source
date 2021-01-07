import $ from 'jquery';
import {onlyLarge} from 'theme/js/frontend/match-media';

class FloatingBlock {
	constructor($elem, $sidebar) {
		this.$elem = $elem;
		this.$sidebar = $sidebar;
		this.$contentAnchor = null;
		this.removeQListener = onlyLarge(this.moveToSidebar, this.moveToContent);
	}

	moveToSidebar = () => {
		if (this._inSideBar()) return;

		if (!this.$contentAnchor) {
			this.$contentAnchor = $('<div/>').css('display', 'none');

			this.$elem.before(this.$contentAnchor); // Put the anchor
		}

		this.$elem.detach().appendTo(this.$sidebar);
	}

	moveToContent = () => {
		if (!this._inSideBar()) return;

		// Replace the anchor with the real element
		this.$contentAnchor.replaceWith(this.$elem.detach());
		this.$contentAnchor = null;
	}

	_inSideBar() {
		return this.$sidebar.find(this.$elem).length > 0;
	}

	destroy() {
		this.removeQListener();
	}
}

export default function floatingBlock($elem, $sidebar) {
	$elem.each((idx, elem) => new FloatingBlock($(elem), $sidebar));
}
