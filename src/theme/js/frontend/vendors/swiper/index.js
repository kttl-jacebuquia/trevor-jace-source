import {
	Swiper,
	Navigation,
	Pagination,
	Scrollbar,
	Controller,
	EffectFade,
	A11y,
} from 'swiper';
import A11yExtended from './a11y-extended';

Swiper.use([
	Navigation,
	Pagination,
	Scrollbar,
	Controller,
	EffectFade,
	A11y,
	A11yExtended,
]);

export default Swiper;
