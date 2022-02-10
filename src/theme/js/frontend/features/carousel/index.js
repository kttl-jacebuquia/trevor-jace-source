import whatInput from 'what-input';
import $ from 'jquery';
import Swiper from 'swiper';

const onSlideChange = (swiper) => {
	swiper.slides.forEach((slide, index) => {
		$(slide).attr('aria-hidden', index !== swiper.activeIndex);
	});
};

export function carousel($element, option = {}) {
	let _el;

	if (typeof $element === 'string') {
		_el = '#' + $element;
	} else if (typeof $element === 'object') {
		if ('jquery' in $element) {
			_el = $element[0];
		} else if ($element instanceof window.HTMLElement) {
			_el = $element;
		} else {
			return null;
		}
	} else {
		return null;
	}

	const swiper = new Swiper(_el, {
		// Optional parameters
		direction: 'horizontal',
		simulateTouch: false,
		loop: true,
		// If we need pagination
		pagination: {
			el: '.swiper-pagination',
			clickable: true,
			bulletElement: 'button',
		},
		a11y: true,
		...option,
	});

	return swiper;
}

export function carouselNavigator($element) {
	const onAfterInit = (swiper) => {
		Array.from(swiper.pagination.bullets).forEach((bullet, index) => {
			bullet.setAttribute(
				'aria-label',
				`click to go to charity navigator slide ${index + 1}`
			);
		});
	};

	new Swiper($element, {
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
		},
	});
}

export function generateSwiperArrows(
	leftPaneSelector,
	rightPaneSelector,
	eBase
) {
	const panesContainer = $(leftPaneSelector, eBase).parent();
	const iconTopOffset = 5;
	const iconLeftOffset = 25;
	const leftPane = eBase.querySelector(leftPaneSelector);
	const rightPane = eBase.querySelector(rightPaneSelector);
	let iconWrapper;
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
	window.document.addEventListener('mousemove', (e) => {
		const leftPaneRect = eBase
			.querySelector(leftPaneSelector)
			.getBoundingClientRect();
		const rightPaneRect = eBase
			.querySelector(rightPaneSelector)
			.getBoundingClientRect();

		/**
		 * if the mouse is inside the left pane,
		 * generate and animate the swiper button.
		 */
		if (
			e.clientX >= leftPaneRect.left &&
			e.clientX <= leftPaneRect.right &&
			e.clientY >= leftPaneRect.top &&
			e.clientY <= leftPaneRect.bottom
		) {
			if (typeof iconWrapper === 'undefined') {
				generateSwiperButton(leftPaneSelector, e);
			}
			animateSwiperButton(leftPaneSelector, e);
		} else if (
			/**
			 * if the mouse is inside the right pane,
			 * generate and animate the swiper button.
			 */
			e.clientX >= rightPaneRect.left &&
			e.clientX <= rightPaneRect.right &&
			e.clientY >= rightPaneRect.top &&
			e.clientY <= rightPaneRect.bottom
		) {
			if (typeof iconWrapper === 'undefined') {
				generateSwiperButton(rightPaneSelector, e);
			}
			animateSwiperButton(rightPaneSelector, e);
		} else {
			/**
			 * if the mouse is neither in the left or right pane,
			 * reset the values.
			 */
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

	window.addEventListener('resize', (e) => {
		let desktop = window.matchMedia('(min-width: 1024px)');
		if (desktop.matches) {
			panesContainer.removeClass('is-mobile-breakpoint');
		} else {
			panesContainer.addClass('is-mobile-breakpoint');
		}
	});
}

const onSwiperBreakpointChange = (swiper, breakpointParams) => {
	// If all slides are visible in a single view,
	// remove aria-hidden
	if (breakpointParams.slidesPerView === swiper.slides.length) {
		swiper.slides.each((slide) => {
			slide.removeAttribute('aria-hidden');
		});
	}
};

export const initializeCarousel = (carouselSettings) => {
	/**
	 * Initialize remaining carousels
	 */
	let swiper;
	const baseSelector = carouselSettings.base_selector;
	const options = carouselSettings.options || {};
	const breakpoint = carouselSettings.options.breakpoint;
	const baseContainer = document.querySelector(baseSelector);

	options.on.afterInit = function (_swiper) {
		document
			.querySelectorAll('.carousel-testimonials .card-post')
			.forEach((elem) => {
				if (elem.tagBoxEllipsis) {
					elem.tagBoxEllipsis.calc();
				}
			});

		applySlidesA11y(_swiper);
		checkNavigationArrows(_swiper);
		handleNavigationArrows(_swiper);
		checkPagination(_swiper);
	};

	options.on.destroy = (_swiper) => {
		checkPagination(_swiper);
	};

	options.on.resize = (_swiper) => {
		checkPagination(_swiper);
	};

	options.on.slidePrevTransitionEnd = (_swiper) => {
		applySlidesA11y(_swiper);
		// Focus on the first focusable slide
		setTimeout(() => {
			const lastFocusableSlide = [..._swiper.slides]
				.filter((el) => !el.getAttribute('aria-hidden'))
				.shift();
			lastFocusableSlide?.firstElementChild.focus();
			_swiper.navigatedByNav = false;
		}, 150);
	};

	options.on.slideNextTransitionEnd = (_swiper) => {
		applySlidesA11y(_swiper);

		// Focus on the last focusable slide
		setTimeout(() => {
			const firstFocusableSlide = [..._swiper.slides]
				.filter((el) => !el.getAttribute('aria-hidden') )
				.pop();
			const bounds = firstFocusableSlide.getBoundingClientRect();
			firstFocusableSlide?.firstElementChild.focus();
			_swiper.navigatedByNav = false;
		}, 150);
	};

	options.on.slideChangeTransitionEnd = function (_swiper) {
		checkNavigationArrows(_swiper);
	};

	options.on.activeIndexChange = function (_swiper) {
		const nextButton = _swiper.navigation.nextEl;
		const carouselParentContainer =
			_swiper.$el[0].parentElement.parentElement;

		// only apply hide the next button on 2nd to the last index on post-carousels
		if (
			Array.from(carouselParentContainer.classList).includes(
				'post-carousel'
			)
		) {
			if (_swiper.activeIndex === _swiper.slides.length - 2) {
				nextButton.classList.add('should-hide');
			} else {
				nextButton.classList.remove('should-hide');
			}
		}

		$(_swiper.el.parentElement)
			.find('.swiper-pagination-bullet')
			.each(function (index, bullet) {
				const addOrRemoveMethod =
					index === swiper.activeIndex ? 'add' : 'remove';
				bullet.classList[addOrRemoveMethod](
					'swiper-pagination-bullet-active'
				);
			});
	};

	options.on.breakpoint = onSwiperBreakpointChange;

	function checkPagination(_swiper) {
		if (
			_swiper.pagination.el &&
			_swiper.pagination.el.children.length > 1
		) {
			[..._swiper.pagination.el.children].forEach((button, index) => {
				button.setAttribute(
					'aria-label',
					`click to view slide number ${index + 1}`
				);
			});

			_swiper.el.classList.add('carousel--paginated');
			_swiper.el.parentElement?.classList.add('carousel--paginated');
		} else {
			_swiper.el.classList.remove('carousel--paginated');
			_swiper.el.parentElement?.classList.remove('carousel--paginated');
		}
	}

	function applySlidesA11y(_swiper) {
		const windowWidth = window.innerWidth;

		// aria-hide slides that are not fully displayed
		Array.from(_swiper.slides).forEach((slide) => {
			const { left, right } = slide.getBoundingClientRect();
			const slideContent = slide.firstElementChild;
			const tagsBox = slide.querySelector('.tags-box');

			if (left < 0 || right > windowWidth) {
				slide.setAttribute('aria-hidden', 'true');
				slideContent.setAttribute('tabindex', -1);
				// Make contents untabbable
				[...slide.querySelectorAll('a,button')].forEach((el) =>
					el.setAttribute('tabindex', -1)
				);
				tagsBox?.setAttribute('aria-hidden', true);
			} else {
				slide.removeAttribute('aria-hidden');
				slideContent.setAttribute('tabindex', 0);
				[...slideContent.querySelectorAll('a,button')].forEach((el) =>
					el.removeAttribute('tabindex')
				);
				tagsBox?.removeAttribute('aria-hidden');
			}
		});
	}

	/**
	 * Show/Hide prev/next nav button depending on whether the first/last slides are visible in the view
	 * @param {object} _swiper
	 */
	function checkNavigationArrows(_swiper) {
		if (_swiper.navigation?.nextEl || _swiper.navigation?.prevEl) {
			const slidesCount = _swiper.slides.length;
			const { activeIndex } = _swiper;

			const isFirstSlideActive = activeIndex === 0;
			const isLastSlideActive = activeIndex === slidesCount - 1;

			_swiper.navigation?.prevEl.classList.toggle(
				'invisible',
				isFirstSlideActive
			);

			_swiper.navigation?.nextEl.classList.toggle(
				'invisible',
				isLastSlideActive
			);
		}
	}

	/**
	 * Handles additional operations for the navigation
	 */
	function handleNavigationArrows(_swiper) {
		_swiper.navigation?.prevEl?.addEventListener('click', () => {
			_swiper.navigatedByNav = true;
		});
		_swiper.navigation?.nextEl?.addEventListener('click', () => {
			_swiper.navigatedByNav = true;
		});
	}

	function init() {
		if ((!swiper || swiper.destroyed) && baseContainer) {
			const carouselContainer = baseContainer.querySelector(
				'.carousel-container'
			);
			swiper = new window.trevorWP.vendors.Swiper(
				carouselContainer,
				options
			);
		}
	}

	if (breakpoint && breakpoint in window.trevorWP.matchMedia) {
		window.trevorWP.matchMedia[breakpoint](init, function () {
			if (swiper) {
				swiper.destroy(true, true);
			}
		});
	} else {
		init();
	}
};
