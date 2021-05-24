import $ from 'jquery';

const $window = $(window);
const $document = $(document);

/**
 * OPTIONS
 *
 * onOpen - Callback when modal opens
 * onClose - Callback when modal closes
 */
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
		e && e.preventDefault() && e.stopPropagation();
		$document.on('keydown', this._handleKeyDown);
		this.$overlay.on('click', this.close);
		this.$content.addClass('is-active');
		document.body.classList.add(this.constructor.bodyActiveClass);

		this.toggleBodyFix(true);

		this.$blurFilter = $('<svg width="0" height="0" style="position:absolute"><filter id="blur3px"><feGaussianBlur in="SourceGraphic" stdDeviation="3"></feGaussianBlur></filter></svg>').prependTo('.site-content');

		if ( typeof this.options.onOpen === 'function' ) {
			this.options.onOpen({
				initiator: e.currentTarget,
				modalContent: this.$content[0],
			});
		}
	}

	close = (e) => {
		e && e.preventDefault();

		this.$overlay.off('click', this.close);
		this.$content.removeClass('is-active');
		document.body.classList.remove(this.constructor.bodyActiveClass);

		this.toggleBodyFix(false);

		$('#blur3px').parent().remove();

		if ( typeof this.options.onClose === 'function' ) {
			this.options.onClose({
				initiator: e.currentTarget,
				modalContent: this.$content[0],
			});
		}
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

	toggleBodyFix(willFix) {
		const bodyWillFix = typeof willFix === 'boolean' ? willFix : !$body.hasClass('is-modal-open');
		const $root = $('html');
		const $body = $('body');

		if ( bodyWillFix ) {
			this.fixedBodyScroll = $window.scrollTop();
			$body[0].style.setProperty('--fixed-body-top', `-${this.fixedBodyScroll}px`);
			setTimeout(() => $body.addClass('is-modal-open'), 0);
		}
		else {
			$body.removeClass('is-modal-open');
			$root[0].style.setProperty('--fixed-body-top', `0px`);
			$root.css('scroll-behavior', 'auto');
			window.scrollTo(0, this.fixedBodyScroll);
			$root.css('scroll-behavior', '');
		}
	}
}


export default function modal($content, options, $targets) {
	const controller = new Modal($content, options);

	$targets.on('click', controller.open);

	return () => $targets.off('click', controller.open);
}
