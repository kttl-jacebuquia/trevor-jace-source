import $ from 'jquery';
import * as focusTrap from 'focus-trap';

const $window = $(window);
const $document = $(document);

const FOCUS_TRAP_KEY = Symbol();

/**
 * OPTIONS
 *
 * onInit - Callback when modal initializes
 * onOpen - Callback when modal opens
 * onClose - Callback when modal closes
 */
class Modal {
	static bodyActiveClass = 'modal-active';
	static modalActiveClass = 'is-active';
	static overlayClass = 'modal-overlay';
	static closeSelector = '.js-modal-close,.modal-close';
	static renderedModalContents = [];

	constructor($content, options = {}) {
		this.$content = $content;
		this.options = Object.assign({}, options);
		this.$overlay = $('<div>').addClass('modal-overlay').prependTo(this.$content);
		this.$content.find(this.constructor.closeSelector).on('click', this.close.bind(this));

		// Create focus trap
		this[FOCUS_TRAP_KEY] = focusTrap.createFocusTrap(this.$content[0], {
			initialFocus: '.modal-container',
			onPostDeactivate: () => {
				// If focus remains inside the modal, remove focus
				if ( this.$content[0].contains(document.activeElement) ) {
					setTimeout(() => {
						document.querySelector('a[href],button').focus();
					}, 500);
				}
			}
		});

		this.$content.on('transitionend', this.onTransitionEnd.bind(this));

		this.constructor.renderedModalContents.push($content[0]);

		// Ensure that this modal is an initial child of the body
		document.body.appendChild(this.$content[0]);

		if ( typeof this.options.onInit === 'function' ) {
			this.options.onInit(this);
		}
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
		this[FOCUS_TRAP_KEY].deactivate();
		this.toggleBackgroundElements(true);

		$('#blur3px').parent().remove();

		if ( typeof this.options.onClose === 'function' ) {
			this.options.onClose({
				initiator: e ? e.currentTarget : null,
				modalContent: this.$content[0],
			});
		}
	}

	onAfterOpen() {
		this.toggleBackgroundElements(false);
		this[FOCUS_TRAP_KEY].activate();
	}

	onAfterClose() {

	}

	onTransitionEnd(e) {
		if ( e.target === e.currentTarget ) {
			if ( this.isActive() ) {
				this.onAfterOpen();
			}
			else {
				this.onAfterClose();
			}
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
		this.$content.find(this.constructor.closeSelector).off('click', this.close);
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

	// Disables VO access to the elements behind the modal (background elements)
	toggleBackgroundElements(willEnable = true) {
		const backgroundElementsSelector = [
			':not(.modal)',
			':not(noscript)',
			':not(style)',
			':not(script)',
			':not([aria-hidden="true"])',
		].join(',');

		if ( willEnable ) {
			if ( this.$backgroundElements ) {
				this.$backgroundElements.removeAttr('aria-hidden');
			}
		}
		else {
			this.$backgroundElements = (
				$(document.body.children)
				.filter((index, element) => (
					$(element).is(backgroundElementsSelector)
					&& element !== this.$content[0]
				))
				.attr('aria-hidden', true)
			);
		}
	}
}


export default function modal($content, options, $targets) {
	const controller = Modal.renderedModalContents.includes($content[0])
		? Modal.renderedModalContents.find(content => content === $content[0])
		: new Modal($content, options);

	if ( $targets && $targets.length ) {
		$targets.on('click', controller.open);
	}

	return () => $targets.off('click', controller.open);
}
