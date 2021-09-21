import $ from 'jquery';
import Component from '../../Component';
import { carousel } from '../carousel';
import { mobileAndSmallDesktop } from './../../match-media';

export default class FeaturedCardThreeUp extends Component {
	static selector = '.featured-card-3up--carousel';

	// Defines children that needs to be queried as part of this component
	static children = {
		carouselContainer: ".swiper-container",
		carouselSlides: [".swiper-slide"],
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
			}
		},
		on: {
			init: this.onSwiperInit.bind(this),
			destroy: this.onSwiperDestroy.bind(this),
		},
	}

	// Will be called upon component instantiation
	afterInit() {
		const $swiperContainer = $(this.children.carouselContainer);

		if (this.children.carouselSlides.length) {
			mobileAndSmallDesktop(
				() => (
					!this.swiper &&
					carousel($swiperContainer, this.swiperOptions)
				),
				() => this.swiper && this.swiper.destroy()
			);
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
