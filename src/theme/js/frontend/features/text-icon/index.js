import $ from 'jquery';
import Component from '../../Component';
import { carousel } from '../carousel';
import * as matchMedia from './../../match-media';

export default class TextIcon extends Component {
	// Defines the element selector which will initialize this component
	static selector = '.text-icon--carousel';

	// Defines children that needs to be queried as part of this component
	static children = {
		carouselContainer: ".swiper-container",
		carouselSlides: [".swiper-slide"],
	};

	swiperOptions = {
		loop: false,
		on: {
			init: this.onSwiperInit.bind(this),
			destroy: this.onSwiperDestroy.bind(this),
		},
	}

	// Will be called upon component instantiation
	afterInit() {
		const $swiperContainer = $(this.children.carouselContainer);

		if (this.children.carouselSlides.length) {
			matchMedia.mobileAndTablet(
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
TextIcon.init();
