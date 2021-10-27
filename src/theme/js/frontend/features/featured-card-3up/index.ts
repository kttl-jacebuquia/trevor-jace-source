import $ from 'jquery';
import Component from '../../Component';
import { carousel } from '../carousel';
import {
	mobileAndTablet,
	mobileAndSmallDesktop,
	mobileOnly,
	tabletOnly,
	smallDesktopOnly
} from '../../match-media';
import Swiper from 'swiper';

const CLASSNAME_CAROUSEL = 'featured-card-3up--carousel';

export default class FeaturedCardThreeUp extends Component {
	swiper?: Swiper;

	static selector = '.featured-card-3up';

	// Defines children that needs to be queried as part of this component
	static children = {
		carouselContainer: '.swiper-container',
		carouselSlides: ['.swiper-slide'],
	};

	swiperOptions = {
		loop: false,
		navigation: {
			nextEl: '.swiper-button-next',
			prevEl: '.swiper-button-prev',
		},
		breakpoints: {
			300: {
				slidesPerView: 1,
				spaceBetween: 18,
			},
			768: {
				slidesPerView: 2,
				spaceBetween: 28,
			},
			1024: {
				slidesPerView: 2.4,
				spaceBetween: 28,
			},
		},
		on: {
			init: (_swiper: Swiper) => this.onSwiperInit(_swiper),
			destroy: (_swiper: Swiper) => this.onSwiperDestroy(_swiper),
		},
	};

	// Will be called upon component instantiation
	afterInit() {
		if (!this.children?.carouselContainer) {
			return;
		}

		const mutationObserver = new window.MutationObserver(() => {
			this.onMutate();
		});
		mutationObserver.observe(this.element, {
			attributes: true,
		} as MutationObserverInit);

		const carouselBreakpoint: string[] =
			this.element.dataset.carouselBreakpoint?.split(',') || [];
		let matchMediaScreen: (
			onMatch: (args: any) => any,
			onNoMatch: (args: any) => any
		) => any;

		if (carouselBreakpoint.length === 3) {
			matchMediaScreen = mobileAndSmallDesktop;
		} else if (carouselBreakpoint.length === 2) {
			matchMediaScreen = mobileAndTablet;
		} else if (carouselBreakpoint.length === 1) {
			switch (carouselBreakpoint[0]) {
				case 'mobile':
					matchMediaScreen = mobileOnly;
					break;
				case 'tablet':
					matchMediaScreen = tabletOnly;
					break;
				case 'small-desktop':
					matchMediaScreen = smallDesktopOnly;
					break;
				default:
			}
		}

		if (matchMediaScreen) {
			matchMediaScreen(
				() => this.element.classList.add(CLASSNAME_CAROUSEL),
				() => this.element.classList.remove(CLASSNAME_CAROUSEL)
			);
		}
	}

	onMutate() {
		const $swiperContainer = $(this.children?.carouselContainer);

		if (this.element.classList.contains(CLASSNAME_CAROUSEL)) {
			if (!this.swiper) carousel($swiperContainer, this.swiperOptions);
		} else {
			this.swiper?.destroy();
		}
	}

	onSwiperInit(swiper) {
		this.swiper = swiper;
	}

	onSwiperDestroy(swiper) {
		this.swiper = null;
	}
}

// Uncomment this section if this component is intended
// to initialize on DOM load.
FeaturedCardThreeUp.init();
