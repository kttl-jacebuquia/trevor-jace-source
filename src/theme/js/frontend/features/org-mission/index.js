import $ from 'jquery';
import Component from '../../Component';
import { carousel } from '../carousel';
import { mobileAndSmallDesktop } from './../../match-media';

export default class OrgMission extends Component {
	static selector = '.org-mission';

	// Defines children that needs to be queried as part of this component
	static children = {
		carouselContainer: '.swiper-container',
		carouselSlides: ['.swiper-slide'],
		prevEl: '.swiper-button-prev',
		nextEl: '.swiper-button-next',
		pagination: '.swiper-pagination',
	};

	swiperOptions = {
		loop: false,
		slidesPerView: 1,
		simulateTouch: false,
		breakpoints: {
			300: {
				spaceBetween: 18,
			},
			768: {
				spaceBetween: 28,
			},
			1024: {
				spaceBetween: 28,
			},
		},
		on: {
			init: this.onSwiperInit.bind(this),
			destroy: this.onSwiperDestroy.bind(this),
		},
	};

	// Will be called upon component instantiation
	afterInit() {
		const $swiperContainer = $(this.children.carouselContainer);

		this.swiperOptions.navigation = {
			nextEl: this.children.nextEl,
			prevEl: this.children.prevEl,
		};

		this.swiperOptions.pagination = {
			el: this.children.pagination,
			clickable: true,
			bulletElement: 'button',
		};

		if (this.children.carouselSlides.length) {
			mobileAndSmallDesktop(
				() =>
					!this.swiper &&
					carousel($swiperContainer, this.swiperOptions),
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
