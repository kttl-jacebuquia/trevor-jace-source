import $ from 'jquery';
import Component from '../../Component';
import { carousel } from '../carousel';
import { mobileAndTablet } from './../../match-media';

export default class OrgMission extends Component {
	static selector = '.org-mission';

	// Defines children that needs to be queried as part of this component
	static children = {
		carouselContainer: ".swiper-container",
		carouselSlides: [".swiper-slide"],
	};

	swiperOptions = {
		loop: false,
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
			mobileAndTablet(
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
OrgMission.init();
