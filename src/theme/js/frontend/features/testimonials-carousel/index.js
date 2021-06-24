import Swiper from 'theme/js/frontend/vendors/swiper';
import {generateSwiperArrows} from '../carousel/index';
import $ from 'jquery';
import debounce from 'lodash/debounce';

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

	let imgSwiper = initImageSwiper();
	let textSwiper = initTextSwiper();
	let currentSlide = 0;

	/**
	 * reinitialize the swiper on resize
	 */
	$(window).on('resize', debounce(
		() => {
			currentSlide = textSwiper.activeIndex;
			imgSwiper.destroy(true, true);
			imgSwiper = initImageSwiper();
			textSwiper.destroy(true, true);
			textSwiper = initTextSwiper();
			imgSwiper.controller.control = textSwiper;
			textSwiper.controller.control = imgSwiper;
			textSwiper.slideTo(currentSlide, 300, false);
		},
		500
	));

	function initImageSwiper () {
		return new Swiper(imgWrap.querySelector('.swiper-container'), {
			slidesPerView: 1,
			effect: 'fade',
		});
	}

	function initTextSwiper () {
		const options = {
			slidesPerView: 1,
			autoplay: true,
			updateOnWindowResize: true,
		};

		if (txtWrap.querySelectorAll(".swiper-container .swiper-slide").length > 1) {
			options.pagination = {
				el: txtWrap.querySelector('.swiper-pagination'),
				clickable: true,
				bulletElement: 'button',
			}

			options.navigation = {
				nextEl: '.carousel-right-arrow-pane',
				prevEl: '.carousel-left-arrow-pane',
			};

			options.on = {
				init: () => {
					checkArrow();
				},
				afterInit: (swiper) => {
					applyA11y();
					onSlideChange(swiper)
				},
				slideChange: (swiper) => {
					onSlideChange(swiper, true);
				},
				resize: () => {
					checkArrow();
				},
			};
		} else {
			panesContainer.addClass("hidden");
		}

		return new Swiper(txtWrap.querySelector(".swiper-container"), options);
	}

	function checkArrow() {
		let desktop = window.matchMedia('(min-width: 1024px)');
		if (desktop.matches) {
			panesContainer.removeClass('is-mobile-breakpoint');
		} else {
			panesContainer.addClass('is-mobile-breakpoint');
		}
	}

	function applyA11y() {
		const $bullets = $(eBase).find('.swiper-pagination-bullet');

		$bullets.each((index, bullet) => {
			$(bullet)
				.attr('tabindex', "0")
				.attr('aria-label', 'click to go to slide ' + (index + 1))
		});
	}

	function onSlideChange (swiper, focus = false) {
		[...swiper.slides].forEach((slide, index) => {
			const isActiveIndex = index === swiper.activeIndex;

			if ( isActiveIndex ) {
				$(slide)
					.attr('tabindex', 0)
					.removeAttr('aria-hidden')

				if ( focus ) { $(slide).focus(); }
			}
			else {
				$(slide)
					.attr('tabindex', -1)
					.attr('aria-hidden', true);
			}
		});
		console.log(swiper.activeIndex, swiper.slides);
	}

	/**
	 * Swiper arrows with animation
	 */
	generateSwiperArrows(leftPaneSelector, rightPaneSelector, eBase);

	imgSwiper.controller.control = textSwiper;
	textSwiper.controller.control = imgSwiper;
}
