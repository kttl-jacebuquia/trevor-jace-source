import $ from 'jquery';
import SiteBanner from './banner-manager';

window.trevorWP = window.trevorWP || {};
window.trevorWP.siteBanners = () => {
	const bannersById = {};

	const onRemove = ({ id }, event) => {
		// Remove closed banner
		delete bannersById[id];

		// Focus on next available banner
		const availableBanner = Object.values(bannersById)[0];

		if (availableBanner) {
			availableBanner.$banner.focus();
		} else if (event.data?.trueClick === false) {
			// Focus on skip to main link instead if no more banners
			// only when tabbing/voiceover was used
			document.body.focus();
		}
	};

	$.get('/wp-json/trevor/v1/site-banners').then((resp) => {
		if (resp.success) {
			// Show only up to 2 banners at a time,
			// with the Pride LP having least priority
			resp.banners
				.map((bannerObj) => new SiteBanner(bannerObj))
				.filter((banner) => banner.isRenderable())
				.slice(0, 2)
				.forEach((banner) => {
					banner.render();
					banner.on('remove', onRemove);
					bannersById[banner.id] = banner;
				});
		}
	});
};
