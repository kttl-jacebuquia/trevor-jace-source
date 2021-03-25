import $ from 'jquery';
import SiteBanner from "./banner-manager";

window.trevorWP = window.trevorWP || {};
window.trevorWP.siteBanners = () => {
	$.get('/wp-json/trevor/v1/site-banners')
		.then(resp => {
			if (resp.success) {
				resp.banners.forEach(bannerObj => new SiteBanner(bannerObj));
			}
	});
}
