import Component from '../Component';

export default class OverflowFade extends Component {
	static CLASS_OVERFLOW_TOP = 'overflow-fade--top';
	static CLASS_OVERFLOW_BOTTOM = 'overflow-fade--bottom';
	static CLASS_OVERFLOW_LEFT = 'overflow-fade--left';
	static CLASS_OVERFLOW_RIGHT = 'overflow-fade--right';

	constructor(element: HTMLElement, config?: object) {
		super(element);
		this.element?.classList.add('overflow-fade');
		this.initializeScrollHandler();
	}

	initializeScrollHandler() {
		this.element?.addEventListener('scroll', this.handleScroll.bind(this));
		// Initial scroll
		this.handleScroll();
	}

	handleScroll() {
		if (!this.element) {
			return;
		}

		const { width, height } = this.element.getBoundingClientRect();
		const [scrollHeight, scrollWidth, scrollTop, scrollLeft] = [
			this.element.scrollHeight,
			this.element.scrollWidth,
			this.element.scrollTop,
			this.element.scrollLeft,
		];

		// Toggle classes depending on scroll overflow
		this.element.classList.toggle(
			OverflowFade.CLASS_OVERFLOW_TOP,
			scrollTop > 0
		);
		this.element.classList.toggle(
			OverflowFade.CLASS_OVERFLOW_BOTTOM,
			scrollTop < scrollHeight - height
		);
		this.element.classList.toggle(
			OverflowFade.CLASS_OVERFLOW_LEFT,
			scrollLeft > 0
		);
		this.element.classList.toggle(
			OverflowFade.CLASS_OVERFLOW_RIGHT,
			scrollLeft < scrollWidth - Math.ceil(width)
		);
	}
}
