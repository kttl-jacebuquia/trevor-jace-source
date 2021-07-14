import $ from 'jquery';
import {onlyXLarge} from "theme/js/frontend/match-media";
import Swiper from "swiper";

const afterInit = swiper => {
	Array.from(swiper.pagination.bullets).forEach((bullet, index) => {
		bullet.setAttribute('aria-label', `click to go to slide ${index + 1}`);
	});

	slideChange(swiper);
};

const slideChange = swiper => {
	Array.from(swiper.slides).forEach((slide, index) => {
		slide.setAttribute('aria-hidden', index !== swiper.activeIndex);
	});
}

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
						bulletElement: 'button'
					},
					on: {
						afterInit,
						slideChange,
					}
				});
			});
	});
}
