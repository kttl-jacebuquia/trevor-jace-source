import $ from 'jquery';
import Swiper from 'swiper';


export function carousel($element, $option) {

	let _el = '#' + $element;

	const swiper = new Swiper(_el, {
		// Optional parameters
		direction: 'horizontal',
		loop: true,
		// If we need pagination
		pagination: {
			el: '.swiper-pagination',
			clickable: true,
		}
	});

}


export function carouselNavigator($element, $option) {

	let desktop = window.matchMedia('(min-width: 1025px)');

	const swiper = new Swiper($element, {
		// Optional parameters
		initialSlide: 1,
		slidesPerView: 1,
		direction: 'horizontal',
		loop: true,
		centeredSlides: true,
		// If we need pagination
		pagination: {
			el: '.swiper-pagination',
			clickable: true,
		}
	});

	if (desktop.matches) {
		swiper.destroy();
	}

	$(window).on("resize", () => {
		if (desktop.matches) {
			swiper.destroy();
		}
	});

}
