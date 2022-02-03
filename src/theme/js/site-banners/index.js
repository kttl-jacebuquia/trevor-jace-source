import $ from 'jquery';
import SiteBanner from './banner-manager';

window.trevorWP = window.trevorWP || {};
window.trevorWP.siteBanners = () => {
	const bannersById = {};

	const onRemove = ({ id }) => {
		// Remove closed banner
		delete bannersById[id];

		// Focus on next available banner
		const availableBanner = Object.values(bannersById)[0];

		if (availableBanner) {
			availableBanner.$banner.focus();
		} else {
			// Focus on skip to main link instead if no more banners
			const skipToMain = document.querySelector('#skip-to-main');
			skipToMain.focus();
		}
	};

	$.get('/wp-json/trevor/v1/site-banners').then((resp) => {
		if (resp.success) {
			resp.banners.forEach((bannerObj) => {
				const banner = new SiteBanner(bannerObj);
				banner.on('remove', onRemove);
				bannersById[banner.id] = banner;
			});
		}
	});
};
