import Swiper from 'theme/js/frontend/vendors/swiper';

export default function testimonialsCarousel(id) {
	const eBase = document.getElementById(id);
	if (!eBase) {
		console.log('Missing element:', id);
		return;
	}

	const imgWrap = eBase.querySelector('.carousel-testimonials-img-wrap');
	const txtWrap = eBase.querySelector('.carousel-testimonials-txt-wrap');

	const imgSwiper = new Swiper(imgWrap.querySelector('.swiper-container'), {
		slidesPerView: 1,
		effect: 'fade',
	});
	const txtSwiper = new Swiper(txtWrap.querySelector('.swiper-container'), {
		slidesPerView: 1,
		autoHeight: true,
		autoplay: true,
		pagination: {
			el: txtWrap.querySelector('.swiper-pagination'),
			clickable: true,
		},
	});

	imgSwiper.controller.control = txtSwiper;
	txtSwiper.controller.control = imgSwiper;
}
