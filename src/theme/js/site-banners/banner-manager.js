import $ from 'jquery';

export default class SiteBanner {
	constructor(bannerObj) {
		this.$container = $('#siteBannerContainer');
		this.$banner = null;
		this.bannerObj = bannerObj;
		this.sessionName = `sessionBannerObj-${this.bannerObj.id}`;
		this.containerHeight = 0;

		if (!this.isClosed()) {
			this.build();
			this.insert();
		}
	}

	insert() {

		if (this.$banner.data('type') === 'custom') {
			this.$banner.prependTo(this.$container);
		} else {
			this.$banner.appendTo(this.$container);
		}

		this.updateSiteBannerCSSvariable();
	}

	build() {
		const {bannerObj} = this;

		this.$banner = $("<div/>", {
			class: `site-banner__${bannerObj.type}`,
			id: `site-banner-${bannerObj.id}`,
			'data-type': bannerObj.type,
			'data-id': bannerObj.id
		});
		const $container = $("<div class='site-banner__container'>" +
			"<i class='site-banner__warning'></i>" +
			"</div>").appendTo(this.$banner);

		// Text
		const $text = $("<p class='site-banner__text'></p>").appendTo($container);

		// Title
		$("<span/>", {
			class: 'site-banner__title',
			text: `${bannerObj.title} `,
		}).appendTo($text);

		// Description
		$("<span/>", {
			class: 'site-banner__description',
			text: bannerObj.desc,
		}).appendTo($text);

		$("<button role='button' class='site-banner__close-btn'>" +
			"<i class='trevor-ti-x text-indigo'></i>" +
			"</button>")
			.on('click', this.handleCloseClick)
			.appendTo($container);
	}

	handleCloseClick = (e) => {
		e.preventDefault();

		sessionStorage.setItem(this.sessionName, Date.now());
		this.remove();
		this.updateSiteBannerCSSvariable();
	}

	isClosed() {
		return Object.keys(sessionStorage).includes(this.sessionName);
	}

	remove() {
		this.$banner.remove();
	}

	updateSiteBannerCSSvariable() {
		const root = document.documentElement;
		this.containerHeight = this.$container.height();
		root.style.setProperty('--site-banner-height', `${this.containerHeight}px`);
	}
}
