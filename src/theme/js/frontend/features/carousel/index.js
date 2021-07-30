import whatInput from 'what-input';
import $ from 'jquery';
import Swiper from 'swiper';

const onSlideChange = (swiper) => {
	swiper.slides.forEach((slide, index) => {
		$(slide).attr('aria-hidden', index !== swiper.activeIndex);
	});
}


export function carousel($element, option = {}) {
	let _el;

	if ( typeof $element === 'string' ) {
		_el = '#' + $element;
	}
	else if ( typeof $element === 'object'  ) {
		if ( 'jquery' in $element ) {
			_el = $element[0];
		}
		else if ( $element instanceof HTMLElement ) {
			_el = $element;
		}
		else {
			return null;
		}
	}
	else {
		return null;
	}

	const swiper = new Swiper(_el, {
		// Optional parameters
		direction: 'horizontal',
		loop: true,
		// If we need pagination
		pagination: {
			el: '.swiper-pagination',
			clickable: true,
			bulletElement: 'button',
		},
		...option,
	});

	return swiper;
}



export function carouselNavigator($element, $option) {

	const onAfterInit = swiper => {
		Array.from(swiper.pagination.bullets).forEach((bullet, index) => {
			bullet.setAttribute('aria-label', `click to go to charity navigator slide ${index + 1}`);
		});
	}

	const swiper = new Swiper($element, {
		// Optional parameters
		slidesPerView: 1,
		pagination: {
			el: '.swiper-pagination',
			clickable: true,
			bulletElement: 'button',
		},
		on: {
			init: onSlideChange,
			afterInit: onAfterInit,
			slideChange: onSlideChange,
		}
	});

}

export function generateSwiperArrows(leftPaneSelector, rightPaneSelector, eBase) {
	const panesContainer = $(leftPaneSelector, eBase).parent();
	const iconTopOffset = 5;
	const iconLeftOffset = 25;
	let iconWrapper = undefined;
	const leftPane = eBase.querySelector(leftPaneSelector);
	const rightPane = eBase.querySelector(rightPaneSelector);
	leftPane.innerHTML = '';
	rightPane.innerHTML = '';

	/**
	 * set the direction if not defined
	 */
	if (typeof leftPane.dataset.direction === 'undefined') {
		leftPane.dataset.direction = 'left';
	}
	if (typeof rightPane.dataset.direction === 'undefined') {
		rightPane.dataset.direction = 'right';
	}

	/**
	 * mousemove on document is used to detect if the mouse
	 * is inside the panes because pane.onmousemove will not
	 * work properly as the icon is directly under the cursor.
	 */
	document.addEventListener('mousemove', e => {
		const leftPaneRect = eBase.querySelector(leftPaneSelector).getBoundingClientRect();
		const rightPaneRect = eBase.querySelector(rightPaneSelector).getBoundingClientRect();

		/**
		 * if the mouse is inside the left pane,
		 * generate and animate the swiper button.
		 */
		if (
			e.clientX >= leftPaneRect.left && e.clientX <= leftPaneRect.right &&
			e.clientY >= leftPaneRect.top && e.clientY <= leftPaneRect.bottom
		) {
			if (typeof iconWrapper === 'undefined') {
				generateSwiperButton(leftPaneSelector, e);
			}
			animateSwiperButton(leftPaneSelector, e);
		}
		/**
		 * if the mouse is inside the right pane,
		 * generate and animate the swiper button.
		 */
		else if (
			e.clientX >= rightPaneRect.left && e.clientX <= rightPaneRect.right &&
			e.clientY >= rightPaneRect.top && e.clientY <= rightPaneRect.bottom
		) {
			if (typeof iconWrapper === 'undefined') {
				generateSwiperButton(rightPaneSelector, e);
			}
			animateSwiperButton(rightPaneSelector, e);
		}
		/**
		 * if the mouse is neither in the left or right pane,
		 * reset the values.
		 */
		else {
			leftPane.innerHTML = '';
			rightPane.innerHTML = '';
			iconWrapper = undefined;
		}
	});

	/**
	 *
	 * @param {className} paneSelector
	 * @param {object} event
	 *
	 * This will generate the swiper button
	 * in the cursor position inside the pane.
	 */
	function generateSwiperButton(paneSelector, event) {
		const pane = document.querySelector(paneSelector);
		iconWrapper = document.createElement('div');
		iconWrapper.classList.add('swiper-button-wrapper');
		iconWrapper.classList.add('absolute');
		const icon = document.createElement('i');
		const direction = pane.dataset.direction;
		icon.classList.add(`trevor-ti-arrow-${direction}`);
		icon.setAttribute('aria-hidden', true);
		iconWrapper.appendChild(icon);
		pane.appendChild(iconWrapper);
		$('.swiper-button-wrapper', pane).offset({
			top: event.pageY - iconTopOffset,
			left: event.pageX - iconLeftOffset,
		});
	}

	/**
	 *
	 * @param {className} paneSelector
	 * @param {object} event
	 *
	 * This will animate the swiper button
	 * on mousemove event.
	 */
	function animateSwiperButton(paneSelector, event) {
		const pane = document.querySelector(paneSelector);
		if (whatInput.ask('intent') === 'mouse') {
			panesContainer.removeClass('is-mobile-breakpoint');
			$('.swiper-button-wrapper', pane).offset({
				top: event.pageY - iconTopOffset,
				left: event.pageX - iconLeftOffset,
			});
		} else {
			panesContainer.addClass('is-mobile-breakpoint');
		}
	}

	window.addEventListener('resize', e => {
		let desktop = window.matchMedia('(min-width: 1024px)');
		if (desktop.matches) {
			panesContainer.removeClass('is-mobile-breakpoint');
		} else {
			panesContainer.addClass('is-mobile-breakpoint');
		}
	});
}

export const initializeCarousel = (carouselSettings) => {
	/**
	 * Initialize remaining carousels
	 */
	 let swiper;
	 let base_selector = carouselSettings.base_selector;
	 let options = carouselSettings.options || {};
	 let breakpoint = carouselSettings.options.breakpoint;
	 const baseContainer = document.querySelector(base_selector);

	 options.on.init = function (swiper) {
		 document.querySelectorAll('.carousel-testimonials .card-post').forEach(elem => {
			 elem.tagBoxEllipsis && elem.tagBoxEllipsis.calc();
		 });
	 }

	 options.on.activeIndexChange = function (swiper) {
		 let nextButton = swiper.navigation.nextEl;
		 let carouselParentContainer = swiper.$el[0].parentElement.parentElement;

		 // only apply hide the next button on 2nd to the last index on post-carousels
		 if (Array.from(carouselParentContainer.classList).includes('post-carousel')) {
			 if (swiper.activeIndex === swiper.slides.length - 2) {
				 nextButton.classList.add('should-hide');
			 } else {
				 nextButton.classList.remove('should-hide');
			 }
		 }

		 jQuery(swiper.el.parentElement).find('.swiper-pagination-bullet').each(function (index, bullet) {
			 const addOrRemoveMethod = index === swiper.activeIndex ? 'add' : 'remove';
			 bullet.classList[addOrRemoveMethod]('swiper-pagination-bullet-active');
		 });
	 }

	 function init() {
		 if ((!swiper || swiper.destroyed) && baseContainer) {
			 const carouselContainer = baseContainer.querySelector('.carousel-container');
			 swiper = new trevorWP.vendors.Swiper(carouselContainer, options);
		 }
	 }

	 if ( breakpoint && breakpoint in trevorWP.matchMedia ) {
		 trevorWP.matchMedia.[breakpoint](init, function () {
			 swiper && swiper.destroy(true, true);
		 });
	 }
	 else {
		 init();
	 }
}
