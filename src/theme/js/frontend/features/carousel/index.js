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
	console.log('Inside', $element)
	let desktop = window.matchMedia('(min-width: 1025px)');

	const swiper = new Swiper($element, {
		// Optional parameters
		slidesPerView: 1,
		direction: 'horizontal',
		loop: true,
		// If we need pagination
		pagination: {
			el: '.swiper-pagination',
			clickable: true,
		}
	});

	if (desktop.matches) {
		swiper.destroy();
	}

}
