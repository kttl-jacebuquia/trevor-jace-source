import $ from 'jquery';

const $document = $(document);

class Modal {
	static bodyActiveClass = 'modal-active';
	static modalActiveClass = 'is-active';
	static overlayClass = 'modal-overlay';
	static closeClass = 'modal-close';

	constructor($content, options) {
		this.$content = $content;
		this.options = Object.assign({}, options);
		this.$overlay = $('<div>').addClass('modal-overlay').prependTo(this.$content);
		this.$content.find(`.${this.constructor.closeClass}`).on('click', this.close);
	}

	open = (e) => {
		e && e.preventDefault();
		$document.on('keydown', this._handleKeyDown);
		this.$overlay.on('click', this.close);
		this.$content.addClass('is-active');
		document.body.classList.add(this.constructor.bodyActiveClass);

		this.$blurFilter = $('<svg width="0" height="0" style="position:absolute"><filter id="blur3px"><feGaussianBlur in="SourceGraphic" stdDeviation="3"></feGaussianBlur></filter></svg>').prependTo('.site-content');
	}

	close = (e) => {
		e && e.preventDefault();

		this.$overlay.off('click', this.close);
		this.$content.removeClass('is-active');
		document.body.classList.remove(this.constructor.bodyActiveClass);

		$('#blur3px').parent().remove();
	}

	_handleKeyDown = (e) => {
		let isEscape;

		if ("key" in e) {
			isEscape = (e.key === "Escape" || e.key === "Esc")
		} else {
			isEscape = (e.keyCode === 27)
		}

		if (isEscape && this.isActive()) {
			this.close();
		}
	}

	isActive() {
		return this.$content.hasClass(this.constructor.modalActiveClass);
	}

	destroy = () => {
		this.close();
		this.$overlay.remove();
		this.$content.find(`.${this.constructor.closeClass}`).off('click', this.close);
	}
}


export default function modal($content, options, $targets) {
	const controller = new Modal($content, options);
	$targets.on('click', controller.open);

	return () => $targets.off('click', controller.open);
}
