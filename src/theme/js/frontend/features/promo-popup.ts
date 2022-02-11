import $ from 'jquery';
import modal, { Modal } from './modal';
import { IS_SUPPORT } from './global-ui';
import { WPAjax } from './wp-ajax';
import EventBus from '../EventBus';

const MODAL_DELAY = 2000;
const POPUP_MODAL_DISMISSED_KEY = 'dismissed-promos';

declare interface PopupModalData {
	id: string | Number;
	markup: String;
}

const EVENT_BUS = new EventBus<PopupModalData>();

class PromoPopup {
	static canShow: boolean;
	static promoID: string;
	static $content: JQuery<String>;
	static $innerLinks: JQuery;
	static modal: typeof Modal;
	static lastActiveElement: HTMLElement | null;

	static selector = '.promo-popup-modal';
	static innerLinksSelector = 'a[href]';
	static fetching: boolean = true;

	static async init() {
		const modalData: PopupModalData = await this.getPromo();

		if (!modalData) {
			return;
		}

		this.canShow = !this.checkIfPromoDismissed(String(modalData?.id) || '');

		if (!this.canShow) {
			return;
		}

		this.promoID = String(modalData.id);
		this.$content = $(modalData.markup);
		this.$innerLinks = this.$content.find(this.innerLinksSelector);

		if (this.$content.length) {
			modal(this.$content, {
				onInit: this.onInit.bind(this),
				onClose: this.onClose.bind(this),
			});

			this.handleInnerLinks();
		}

		this.fetching = false;
		EVENT_BUS.emit('modal-fetched', modalData);
	}

	static handleInnerLinks() {
		this.$innerLinks.on('click', this.onLinkClick.bind(this));
	}

	static showWhenAvailable(withDelay = false) {
		if (!this.fetching) {
			if (this.modal && this.canShow) {
				if (withDelay) {
					setTimeout(() => this.modal.open(), MODAL_DELAY);
				} else {
					this.modal.open();
				}
			}
		} else {
			EVENT_BUS.on('modal-fetched', () =>
				this.showWhenAvailable(withDelay)
			);
		}
	}

	static onLinkClick(e: MouseEvent) {
		e.preventDefault();
		const { target, href } = e.currentTarget as HTMLAnchorElement;

		if (!href) {
			return false;
		}

		this.onClose();

		// Determine if link loads in the same window or not
		if (target === '_blank') {
			window.open(href);
		} else {
			window.location.href = href;
		}
	}

	static onInit(_modal: Modal) {
		this.modal = _modal;
		this.lastActiveElement =
			document.activeElement !== document.body
				? document.activeElement
				: null;
	}

	static onClose() {
		const toFocus =
			this.lastActiveElement ||
			document.querySelector('.site-banner > :first-child') ||
			(document.getElementById('skip-to-main') as HTMLElement);
		const dismissedPromos = this.getDismissedPromos();
		dismissedPromos.push(this.promoID);
		window.localStorage.setItem(
			POPUP_MODAL_DISMISSED_KEY,
			dismissedPromos.join(',')
		);
		toFocus.focus();
	}

	// Fetches for the available promo popup for the current page
	static async getPromo() {
		const data = await WPAjax({
			action: 'promo_popup',
			params: { type: IS_SUPPORT ? 'support' : 'org' },
		});

		return data?.data;
	}

	static checkIfPromoDismissed(promoID: number | string) {
		if (!promoID) {
			return false;
		}

		const dismissedPromos: string[] = this.getDismissedPromos();

		return dismissedPromos.includes(String(promoID));
	}

	static getDismissedPromos() {
		return (window.localStorage.getItem(POPUP_MODAL_DISMISSED_KEY) || '')
			.split(',')
			.filter(Boolean)
			.map((id) => String(id));
	}
}

PromoPopup.init();

export default PromoPopup;
