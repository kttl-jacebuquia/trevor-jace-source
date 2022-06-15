import { Swiper } from 'swiper';
import { SwiperEvents, SwiperOptions } from 'swiper/types';

const SLIDE_FOCUSABLE_CHILDREN_SELECTOR = 'a,button';

export interface SwiperOptionsWithA11yExtended extends SwiperOptions {
	a11yExtended: {
		pagination?: {
			// Whether to always enable pagination
			alwaysEnable?: boolean;
			// Whether a page corresponds to a slide
			oneToOne?: boolean;
		};
	};
}

export interface SwiperWithA11yExtended extends Swiper {
	currentVisibleSlides?: Array<HTMLElement>;
	navigatedByNav?: boolean;
	params: SwiperOptionsWithA11yExtended;
	on: <E extends keyof SwiperEvents>(
		event: E,
		handler?: SwiperEvents[E] | ((swiper: SwiperWithA11yExtended) => void)
	) => void;
}

/**
 * Additional a11y functionalities for Swiper
 * Exported as Swiper module
 */
const A11yExtended = {
	name: 'a11y-extended',
	params: {
		a11yExtended: {
			pagination: {
				// Whether to enable only pagination when all cards have been shown
				alwaysEnable: false,
				oneToOne: false,
			},
		},
	},
	on: {
		init(_swiper: SwiperWithA11yExtended) {
			_swiper.currentVisibleSlides = [];

			_swiper.on('slideChangeTransitionEnd', onSlideChangeTransitionEnd);
			_swiper.on('destroy', checkPagination);
			_swiper.on('resize', checkPagination);

			applySlidesA11y(_swiper);
			checkNavigationArrows(_swiper);
			handleNavigationArrows(_swiper);
			checkPagination(_swiper);
		},
		afterInit(_swiper: SwiperWithA11yExtended) {
			if (
				!(_swiper?.params as SwiperOptionsWithA11yExtended)
					?.a11yExtended.pagination?.alwaysEnable
			) {
				togglePaginationFocusability(_swiper, false);
			}
		},
	},
	applySlidesA11y(_swiper: SwiperWithA11yExtended) {
		const windowWidth = window.innerWidth;

		// aria-hide slides that are not fully displayed
		Array.from(_swiper.slides).forEach((slide) => {
			const { left, right } = slide.getBoundingClientRect();
			const slideContent = slide.firstElementChild;
			const tagsBox = slide.querySelector('.tags-box');

			if (left < 0 || right > windowWidth) {
				slide.setAttribute('aria-hidden', 'true');
				slide.setAttribute('tabindex', '-1');
				// Make contents untabbable
				[...slide.querySelectorAll('a,button')].forEach((el) =>
					el.setAttribute('tabindex', '-1')
				);
				tagsBox?.setAttribute('aria-hidden', 'true');
			} else {
				slide.removeAttribute('aria-hidden');
				slide.setAttribute('tabindex', '0');
				[...(slideContent?.querySelectorAll('a,button') || [])].forEach(
					(el) => el.removeAttribute('tabindex')
				);
				tagsBox?.removeAttribute('aria-hidden');
			}
		});
	},
};

function onSlideChangeTransitionEnd(_swiper: SwiperWithA11yExtended) {
	applySlidesA11y(_swiper);
	checkNavigationArrows(_swiper);
	checkPaginationFocusability(_swiper);

	// Focus on the first focusable slide
	setTimeout(() => {
		// Don't include non-visible slides
		const visibleSlides = Array.from(_swiper.slides).filter(
			(el) => !el.getAttribute('aria-hidden')
		) as HTMLElement[];

		// Don't include slides from the previous set if slidesPerView > 1
		const [firstFocusableSlide] =
			(_swiper?.params.slidesPerView ?? 0) > 1
				? (visibleSlides.filter(
						(el) =>
							!_swiper.currentVisibleSlides?.includes(
								el as HTMLElement
							)
				  ) as HTMLElement[])
				: visibleSlides;

		// Focus on slide
		firstFocusableSlide.firstElementChild?.focus();

		_swiper.navigatedByNav = false;

		// Save current set
		_swiper.currentVisibleSlides = visibleSlides;
	}, 150);
}

function checkPagination(_swiper: SwiperWithA11yExtended) {
	if (_swiper.pagination.el && _swiper.pagination.el.children.length > 1) {
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

// Checks if last slide has already been visible,
// then makes pagination focusable if it is
function checkPaginationFocusability(_swiper: SwiperWithA11yExtended) {
	if (
		(_swiper.params as SwiperOptionsWithA11yExtended)?.a11yExtended
			.pagination?.alwaysEnable
	) {
		return;
	}

	const isPaginationFocusable = Boolean(
		_swiper.el.getAttribute('data-pagination-focusable')
	);

	if (!isPaginationFocusable) {
		// Check to see if last slide is now visible
		const [lastSlide] = _swiper.slides.slice(-1);
		const { right } = lastSlide.getBoundingClientRect();
		const isLastSlideVisible = right <= window.innerWidth;

		if (isLastSlideVisible) {
			togglePaginationFocusability(_swiper, true);
		} else {
			togglePaginationFocusability(_swiper, false);
		}
	}
}

function togglePaginationFocusability(
	_swiper: Swiper,
	willEnable: null | boolean = null
) {
	const paginationElement = _swiper.pagination.el;

	if (paginationElement) {
		const bullets = Array.from(paginationElement.children) as HTMLElement[];
		const willBeFocusable =
			typeof willEnable === 'boolean'
				? willEnable
				: !Boolean(
						_swiper.el.getAttribute('data-pagination-focusable')
				  );

		// Toggle focusability of the element
		if (willBeFocusable) {
			_swiper.el.setAttribute('data-pagination-focusable', 'true');
			paginationElement.removeAttribute('aria-hidden');
		} else {
			_swiper.el.removeAttribute('data-pagination-focusable');
			paginationElement.setAttribute('aria-hidden', 'true');
		}

		// Toggle focusability of the bullets
		bullets.forEach((bullet) => {
			if (willBeFocusable) {
				bullet.removeAttribute('aria-hidden');
			} else {
				bullet.setAttribute('aria-hidden', 'true');
			}

			bullet.setAttribute('tabindex', willBeFocusable ? '0' : '-1');
		});
	}
}

function applySlidesA11y(_swiper: SwiperWithA11yExtended) {
	const windowWidth = window.innerWidth;

	// aria-hide slides that are not fully displayed
	Array.from(_swiper.slides).forEach((slide, index) => {
		const { left, right } = slide.getBoundingClientRect();

		if (_swiper.params.a11yExtended?.pagination?.oneToOne) {
			if (_swiper.activeIndex === index) {
				setSlideA11y(slide, true);
			} else {
				setSlideA11y(slide, false);
			}
		} else if (left < 0 || right > windowWidth) {
			setSlideA11y(slide, false);
		} else {
			setSlideA11y(slide, true);
		}
	});
}

function setSlideA11y(slide: Element, makeFocusable: boolean) {
	if (typeof makeFocusable === 'boolean') {
		const slideContent = slide.querySelector('.card-post');
		const tagsBox = slide.querySelector('.tags-box');

		if (makeFocusable) {
			slide?.removeAttribute('aria-hidden');
			slideContent?.setAttribute('tabindex', '0');
			[
				...(slideContent?.querySelectorAll(
					SLIDE_FOCUSABLE_CHILDREN_SELECTOR
				) ?? []),
			].forEach((el) => el.setAttribute('tabindex', '0'));
			tagsBox?.removeAttribute('aria-hidden');
		} else {
			slide?.setAttribute('aria-hidden', 'true');
			slideContent?.setAttribute('tabindex', '-1');
			// Make contents untabbable
			[
				...(slideContent?.querySelectorAll(
					SLIDE_FOCUSABLE_CHILDREN_SELECTOR
				) ?? []),
			].forEach((el) => el.setAttribute('tabindex', '-1'));
			tagsBox?.setAttribute('aria-hidden', 'true');
		}
	}
}

/**
 * Show/Hide prev/next nav button depending on whether the first/last slides are visible in the view
 *
 * @param _swiper Object
 */
function checkNavigationArrows(_swiper: SwiperWithA11yExtended) {
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
 * Handles additional operations for the navigation.
 *
 * @param _swiper Swiper instance
 */
function handleNavigationArrows(_swiper: SwiperWithA11yExtended) {
	_swiper.navigation?.prevEl?.addEventListener('click', () => {
		_swiper.navigatedByNav = true;
	});
	_swiper.navigation?.nextEl?.addEventListener('click', () => {
		_swiper.navigatedByNav = true;
	});
}

export default A11yExtended;
