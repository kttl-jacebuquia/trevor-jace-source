import Swiper from 'theme/js/frontend/vendors/swiper';
import {generateSwiperArrows} from '../carousel/index';
import $ from 'jquery';

export default function testimonialsCarousel(id) {
	const eBase = document.getElementById(id);
	if (!eBase) {
		console.log('Missing element:', id);
		return;
	}

	const leftPaneSelector = '.carousel-left-arrow-pane';
	const rightPaneSelector = '.carousel-right-arrow-pane';
	const panesContainer = $('.panes-container', eBase);
	

	const imgWrap = eBase.querySelector('.carousel-testimonials-img-wrap');
	const txtWrap = eBase.querySelector('.carousel-testimonials-txt-wrap');

	const imgSwiper = new Swiper(imgWrap.querySelector('.swiper-container'), {
		slidesPerView: 1,
		effect: 'fade',
	});
	const txtSwiper = new Swiper(txtWrap.querySelector('.swiper-container'), {
		slidesPerView: 1,
		autoHeight: true,
		autoplay: true,
		pagination: {
			el: txtWrap.querySelector('.swiper-pagination'),
			clickable: true,
		},
		navigation: {
			nextEl: '.carousel-right-arrow-pane',
			prevEl: '.carousel-left-arrow-pane',
		},
		on: {
			init: () => {
				checkArrow();
			},
			resize: () => {
				checkArrow();
			}
		}
	});

	function checkArrow () {
		let desktop = window.matchMedia('(min-width: 1024px)');
		if (desktop.matches) {
			panesContainer.removeClass('is-mobile-breakpoint');
		} else {
			panesContainer.addClass('is-mobile-breakpoint');
		}
	}

	/**
	 * Swiper arrows with animation
	 */
	generateSwiperArrows(leftPaneSelector, rightPaneSelector, eBase);

	imgSwiper.controller.control = txtSwiper;
	txtSwiper.controller.control = imgSwiper;
}
