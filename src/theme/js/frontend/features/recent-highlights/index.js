import $ from 'jquery';
import Component from '../../Component';
import { carousel } from '../carousel';

export default class RecentHighlights extends Component {
	static selector = '.recent-highlights';

	// Defines children that needs to be queried as part of this component
	static children = {
		carouselContainer: ".swiper-container",
		carouselSlides: [".swiper-slide"],
		swiperPanes: ['.recent-highlights__carousel-pane'],
	};

	swiperOptions = {
		loop: false,
		simulateTouch: false,
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
				slidesPerView: 1,
				spaceBetween: 28,
			},
		},
		on: {
			init: this.onSwiperInit.bind(this),
			activeIndexChange: this.onActiveIndexChange.bind(this),
		},
	}

	// Will be called upon component instantiation
	afterInit() {
		const $swiperContainer = $(this.children.carouselContainer);
		carousel($swiperContainer, this.swiperOptions);
	}

	onSwiperInit(swiper) {
		this.swiper = swiper;
		this.children.swiperPanes.forEach(pane => {
			const direction = pane.classList.contains('recent-highlights__carousel-pane--left') ? 'slidePrev' : 'slideNext';
			pane.addEventListener('click', () => this.swiper[direction]());
		});
		this.determineSlideBoundaries();
	}

	onActiveIndexChange() {
		this.determineSlideBoundaries();
	}

	determineSlideBoundaries() {
		this.element.classList.toggle('recent-highlights--at-first', this.swiper.activeIndex === 0);
		this.element.classList.toggle('recent-highlights--at-last', this.swiper.activeIndex === this.swiper.slides.length - 1);
	}
}

// Uncomment this section if this component is intended
// to initialize on DOM load.
RecentHighlights.init();
