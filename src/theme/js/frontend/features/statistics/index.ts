import $ from 'jquery';
import Swiper, { SwiperOptions } from 'swiper';
import Component from '../../Component';
import { mobileOnly, mobileAndTablet } from '../../match-media';
import { carousel } from '../';

class Statistics extends Component {
	swiper: Swiper | null = null;
	children?: {
		cards: HTMLElement[];
		swiperContainer?: HTMLElement;
	};

	swiperOptions: SwiperOptions = {
		loop: false,
		on: {
			init: (_swiper) => this.onSwiperInit(_swiper),
			destroy: () => this.onSwiperDestroy(),
		},
	};

	static selector = '.statistics';

	static children = {
		swiperContainer: '.swiper-container',
		cards: ['.swiper-slide'],
	};

	afterInit() {
		if (!this.children?.swiperContainer) {
			return;
		}

		if (this.children?.cards.length) {
			const { carouselLayout } = this.element.dataset;
			const carouselMedia =
				carouselLayout === 'mobile_only' ? mobileOnly : mobileAndTablet;

			carouselMedia(
				() =>
					!this.swiper &&
					carousel(
						$(this.children?.swiperContainer),
						this.swiperOptions
					),
				() => this.swiper && this.swiper.destroy()
			);
		}
	}

	onSwiperInit(_swiper: Swiper) {
		this.swiper = _swiper;
	}

	onSwiperDestroy() {
		this.swiper = null;
	}
}

Statistics.init();
