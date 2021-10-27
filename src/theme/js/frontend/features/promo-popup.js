import $ from 'jquery';
import modal from './modal';

const MODAL_DELAY = 2000;
const POPUP_MODAL_DISMISSED_KEY = 'promo-popup-modal-dismissed';

class PromoPopup {
	static selector = '.promo-popup-modal';
	static innerLinksSelector = 'a[href]';

	static init() {
		this.canShow = !localStorage.getItem(POPUP_MODAL_DISMISSED_KEY);

		this.$content = $(this.selector);
		this.$innerLinks = this.$content.find(this.innerLinksSelector);

		if (this.$content.length) {
			modal(this.$content, {
				onInit: this.onInit.bind(this),
				onClose: this.onClose.bind(this),
			});

			this.handleInnerLinks();
		}
	}

	static handleInnerLinks() {
		this.$innerLinks.on('click', this.onClose.bind(this));
	}

	static showWhenAvailable(withDelay = false) {
		if (this.modal && this.canShow) {
			if (withDelay) {
				setTimeout(() => this.modal.open(), MODAL_DELAY);
			} else {
				this.modal.open();
			}
		}
	}

	static onInit(_modal) {
		this.modal = _modal;
	}

	static onClose(e) {
		e.preventDefault();
		const { target, href } = e.currentTarget;

		if ( !href ) {
			return false;
		}

		localStorage.setItem(POPUP_MODAL_DISMISSED_KEY, true);

		// Determine if link loads in the same window or not
		if ( target === '_blank' ) {
			window.open(e.currentTarget);
		}
		else {
			window.location.href = href;
		}
	}
}

PromoPopup.init();

export default PromoPopup;
