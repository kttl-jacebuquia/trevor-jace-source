import $ from 'jquery';
import {onlyXLarge} from "theme/js/frontend/match-media";
import Swiper from "swiper";

export default function infoBoxesCarousel(container = '.info-boxes.break-carousel') {
	$(container).each((idx, elem) => {
		let swiper;

		onlyXLarge(
			// >= XL
			() => swiper && swiper.destroy(),
			// < XL
			() => {
				swiper = new Swiper(elem, {
					slidesPerView: 1,
					pagination: {
						el: '.swiper-pagination',
						clickable: true,
					}
				});
			});
	});
}
