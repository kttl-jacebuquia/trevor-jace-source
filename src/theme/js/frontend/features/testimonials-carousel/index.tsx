import Swiper from 'theme/js/frontend/vendors/swiper';
import { generateSwiperArrows } from '../carousel/index';
import $ from 'jquery';
import debounce from 'lodash/debounce';
import { SwiperOptions } from 'swiper';

export default function testimonialsCarousel(eBase: HTMLElement) {
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
	$(window).on(
		'resize',
		debounce(() => {
			currentSlide = textSwiper.activeIndex;
			imgSwiper.destroy(true, true);
			imgSwiper = initImageSwiper();
			textSwiper.destroy(true, true);
			textSwiper = initTextSwiper();
			imgSwiper.controller.control = textSwiper;
			textSwiper.controller.control = imgSwiper;
			textSwiper.slideTo(currentSlide, 300, false);
		}, 500)
	);

	function initImageSwiper() {
		return new Swiper(imgWrap?.querySelector('.swiper-container'), {
			slidesPerView: 1,
			effect: 'fade',
			on: {
				afterInit: (swiper: Swiper) => {
					onImageSliderInit(swiper);
					onImageSlideChange(swiper);
				},
				slideChange: onImageSlideChange,
			},
		});
	}

	function initTextSwiper() {
		const options: SwiperOptions = {
			slidesPerView: 1,
			autoplay: true,
			updateOnWindowResize: true,
			simulateTouch: false,
		};

		if (
			(txtWrap?.querySelectorAll('.swiper-container .swiper-slide')
				?.length || 0) > 1
		) {
			options.pagination = {
				el: txtWrap?.querySelector(
					'.swiper-pagination'
				) as HTMLElement | null,
				clickable: true,
				bulletElement: 'button',
			};

			options.navigation = {
				nextEl: '.carousel-right-arrow-pane',
				prevEl: '.carousel-left-arrow-pane',
			};

			options.breakpoints = {
				300: {
					autoHeight: true,
				},
				768: {
					autoHeight: false,
				},
			};

			options.on = {
				init: () => {
					checkArrow();
				},
				afterInit: (swiper) => {
					applyA11y();
					onSlideChange(swiper);
				},
				slideChange: (swiper) => {
					onSlideChange(swiper);
				},
				slideChangeTransitionStart: (swiper) => {
					onSlideChangeTransitionStart(swiper);
				},
				slideChangeTransitionEnd: (swiper) => {
					onSlideChangeTransitionEnd(swiper);
				},
				resize: () => {
					checkArrow();
				},
			};
		} else {
			panesContainer.addClass('hidden');
		}

		return new Swiper(txtWrap?.querySelector('.swiper-container'), options);
	}

	function checkArrow() {
		const desktop = window.matchMedia('(min-width: 1024px)');
		if (desktop.matches) {
			panesContainer.removeClass('is-mobile-breakpoint');
		} else {
			panesContainer.addClass('is-mobile-breakpoint');
		}
	}

	function applyA11y() {
		const $bullets = $(eBase).find('.swiper-pagination-bullet');

		$bullets.each((index: number, bullet: HTMLElement) => {
			$(bullet)
				.attr('tabindex', '0')
				.attr('aria-label', 'click to go to slide ' + (index + 1));
		});
	}

	function onSlideChange(swiper: Swiper) {
		applyArrowsA11y(swiper);

		[...swiper.slides].forEach((slide, index) => {
			const isActiveIndex = index === swiper.activeIndex;

			if (isActiveIndex) {
				$(slide).attr('tabindex', 0).removeAttr('aria-hidden');
			} else {
				$(slide).attr('tabindex', -1).attr('aria-hidden', true);
			}
		});
	}

	// Toggles a11y on arrow panes
	function applyArrowsA11y(_swiper: Swiper) {
		const leftPane = eBase.querySelector(leftPaneSelector);
		const rightPane = eBase.querySelector(rightPaneSelector);

		if (_swiper.isBeginning) {
			leftPane?.setAttribute('aria-hidden', 'true');
		} else {
			leftPane?.removeAttribute('aria-hidden');
		}

		if (_swiper.isEnd) {
			rightPane?.setAttribute('aria-hidden', 'true');
		} else {
			rightPane?.removeAttribute('aria-hidden');
		}
	}

	function onImageSliderInit(swiper: Swiper) {
		[...swiper.slides].forEach((slide, index) => {
			// Remove aria attributes from each slide container to allow
			// Direct focus on the image
			slide.removeAttribute('role');
			slide.removeAttribute('aria-hidden');
			slide.removeAttribute('aria-label');
			slide.removeAttribute('tabindex');
		});
	}

	function onImageSlideChange(swiper: Swiper) {
		[...swiper.slides].forEach((slide, index) => {
			const isActiveIndex = index === swiper.activeIndex;

			// Remove aria attributes from each slide container to allow
			// Direct focus on the image
			slide.removeAttribute('role');
			slide.removeAttribute('aria-hidden');
			slide.removeAttribute('aria-label');
			slide.removeAttribute('tabindex');

			if (isActiveIndex) {
				$('img', slide).attr('tabindex', 0).removeAttr('aria-hidden');
			} else {
				$('img', slide).attr('tabindex', 0).removeAttr('aria-hidden');
			}
		});
	}

	function onSlideChangeTransitionStart(_swiper: Swiper) {
		eBase.classList.add('carousel-testimonials--transitioning');
	}

	function onSlideChangeTransitionEnd(swiper: Swiper) {
		swiper.slides[swiper.activeIndex].focus();

		setTimeout(() => {
			eBase.classList.remove('carousel-testimonials--transitioning');
		});
	}

	/**
	 * Swiper arrows with animation
	 */
	generateSwiperArrows(leftPaneSelector, rightPaneSelector, eBase);

	imgSwiper.controller.control = textSwiper;
	textSwiper.controller.control = imgSwiper;
}

export const initialize = () => {
	const testimonials: HTMLElement[] = Array.from(
		document.querySelectorAll('.carousel-testimonials')
	);

	testimonials.forEach((element) => {
		testimonialsCarousel(element);
	});
};

initialize();
