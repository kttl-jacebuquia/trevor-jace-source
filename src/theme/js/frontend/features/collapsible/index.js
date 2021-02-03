import $ from 'jquery';

class Collapsible {
	static collapseActiveClass = 'show';
	static collapseButton = 'accordion-button';
	static collapseBody = 'accordion-collapse';
	static collapseContainer = 'js-accordion';

	constructor($content, options) {
		this.$content = $content;
		this.$button = this.$content.find(`.${this.constructor.collapseButton}`);
		this.$container = null;
		this.$body = null;
		this.options = Object.assign({}, options);
		this.$button.on('click', this.toggle);
	}

	toggle = (e) => {
		e && e.preventDefault();
		this.$button = e.currentTarget;
		this.$container = this.$button.closest(`.${this.constructor.collapseContainer}`);
		this.$body = this.$container.querySelector(`.${this.constructor.collapseBody}`);
		if (this.isActive()) {
			// close collapse
			this.$container.classList.remove(this.constructor.collapseActiveClass);
			this.$button.setAttribute('aria-expanded', false);
		} else {
			// open collapse
			this.$container.classList.add(this.constructor.collapseActiveClass);
			this.$button.setAttribute('aria-expanded', true);
		}
	}

	isActive() {
		return this.$container.classList.contains(this.constructor.collapseActiveClass);
	}

	destroy = () => {
		this.$button.off('click', this.toggle);
	}
}


export default function collapsible($content, options) {
	const controller = new Collapsible($content, options);
}
