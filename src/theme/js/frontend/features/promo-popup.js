import $ from 'jquery';
import modal from './modal';

const MODAL_DELAY = 2000;
const POPUP_MODAL_DISMISSED_KEY = 'promo-popup-modal-dismissed';

class PromoPopup {
	static selector = '.promo-popup-modal';

	static init() {
		this.canShow = !localStorage.getItem(POPUP_MODAL_DISMISSED_KEY);

		this.$content = $(this.selector);

		if ( this.$content.length ) {
			modal(this.$content, {
				onInit: this.onInit.bind(this),
				onClose: this.onClose.bind(this),
			});
		}
	}

	static showWhenAvailable(withDelay = false) {
		if ( this.modal && this.canShow ) {
			if (withDelay) {
				setTimeout(() => this.modal.open(), MODAL_DELAY)
			}
			else {
				this.modal.open();
			}
		}
	}

	static onInit(modal) {
		this.modal = modal;
	}

	static onClose() {
		localStorage.setItem(POPUP_MODAL_DISMISSED_KEY, true);
	}
}

PromoPopup.init();

export default PromoPopup;
