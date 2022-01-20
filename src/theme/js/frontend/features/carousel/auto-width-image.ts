import Swiper from 'swiper';
import $ from 'jquery';
import 'slick-carousel';
import Component from '../../Component';

export class AutoWidthImageCarousel extends Component {
	static selector = '.auto-width-img-carousel';

	static children = {
		carouselContainer: '.auto-width-img-carousel__carousel',
		dots: '.auto-width-img-carousel__dots',
		prevEl: '.auto-width-img-carousel__prev',
		nextEl: '.auto-width-img-carousel__next',
		slides: ['.auto-width-img-carousel__slide'],
	};

	afterInit() {
		if (this.children?.carouselContainer) {
			const $slickElement = $(this.children.carouselContainer);

			$slickElement.slick({
				variableWidth: true,
				slidesToShow: 1,
				infinite: false,
				dots: true,
				appendDots: $(this.children.dots),
				prevArrow: $(this.children.prevEl),
				nextArrow: $(this.children.nextEl),
			});
		}

		this.handleImageResize();
	}

	handleImageResize() {
		this.children?.slides.forEach((slide: HTMLElement) => {
			const image = slide.querySelector('img') as HTMLImageElement;

			if (image) {
				const resizeObserver = new window.ResizeObserver(() => {
					this.onSlideImageResize(slide);
				});

				resizeObserver.observe(image);
			}
		});
	}

	onSlideImageResize(slide: HTMLElement) {
		const image = slide.querySelector('img') as HTMLImageElement;
		slide.style.setProperty('--image-width', String(image.offsetWidth));
	}
}

AutoWidthImageCarousel.init();
