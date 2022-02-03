import $ from 'jquery';

export default class SiteBanner {
	constructor(bannerObj) {
		this.$container = $('#siteBannerContainer');
		this.$banner = null;
		this.bannerObj = bannerObj;
		this.sessionName = `sessionBannerObj-${this.bannerObj.id}`;
		this.containerHeight = 0;
		this.id = bannerObj.id;

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
		const { bannerObj } = this;

		this.$banner = $('<div/>', {
			class: `site-banner__${bannerObj.type}`,
			id: `site-banner-${bannerObj.id}`,
			'data-type': bannerObj.type,
			'data-id': bannerObj.id,
			tabindex: 0,
		});
		const $container = $(
			"<div class='site-banner__container'>" +
				"<i class='site-banner__warning'></i>" +
				'</div>'
		).appendTo(this.$banner);

		// Text
		const $text = $("<p class='site-banner__text'></p>").appendTo(
			$container
		);

		// Title
		if (bannerObj.title) {
			$('<span/>', {
				class: 'site-banner__title',
				text: `${bannerObj.title} `,
			}).appendTo($text);
		}

		// Description
		if (bannerObj.desc) {
			$('<span/>', {
				class: 'site-banner__description',
				html: bannerObj.desc,
			}).appendTo($text);
		}

		$(
			"<button aria-label='click to close banner' class='site-banner__close-btn'>" +
				"<i class='trevor-ti-x text-indigo'></i>" +
				'</button>'
		)
			.on('click', this.handleCloseClick)
			.appendTo($container);
	}

	handleCloseClick = (e) => {
		e.preventDefault();

		sessionStorage.setItem(this.sessionName, Date.now());
		this.remove();
		this.updateSiteBannerCSSvariable();
	};

	isClosed() {
		return Object.keys(sessionStorage).includes(this.sessionName);
	}

	remove() {
		this.$banner.remove();
		this.$banner.trigger('remove');
	}

	updateSiteBannerCSSvariable() {
		const root = document.documentElement;
		this.containerHeight = this.$container.height();
		root.style.setProperty(
			'--site-banner-height',
			`${this.containerHeight}px`
		);
	}

	on(event, callback) {
		this.$banner.on(event, () => {
			callback.apply(this, [this]);
		});
	}
}
