import $ from 'jquery';
import debounce from 'lodash/debounce';

const resizeObserver = new ResizeObserver(function (entries) {
	entries.forEach(elem => {
		const {target: {tagBoxEllipsis}} = elem;

		if (!tagBoxEllipsis) return;

		tagBoxEllipsis.calc();
	})
});

class TagBoxEllipsis {
	constructor(elem) {
		elem.tagBoxEllipsis = this;
		resizeObserver.observe(elem);
		this.elem = elem;
		this.$ = $(this.elem);
		this.$box = this.$.find('.tags-box');
		this.$boxes = this.$box.find('.tag-box');
		this.$box.css('position', 'relative');
		this.$textContainer = this.$.find('.card-text-container');

		/**
		 * calulate the distance between the tags box and its previous sibling
		 * (there must be margin-top: auto CSS on the box)
		 * then set the top margin of the box after it's been expanded.
		 */
		this.boxTopMargin = this.$box.offset().top - (this.$box.prev().offset().top + this.$box.prev().outerHeight(true));

		this.$ellipsis = $('<a href="#" class="tag-box ellipsis"></a>')
			.on('click', this.toggleClick)
			.appendTo(this.$box);

		this.calc();
		this.handleResize();
		this.handleCardHover();
	}

	calc() {
		let $firstRowLastBox = null;
		const showEllipsis = this.$boxes.length > 2;

		for (let i = 0; i < this.$boxes.length; i++) {
			const box = this.$boxes.get(i);

			if (box.offsetTop > 0) {
				box.style.display = 'hidden';
				break;
			}

			$firstRowLastBox = $(box);
		}

		const marginR = parseInt($firstRowLastBox.css('margin-right'));

		if (showEllipsis) {
			// this.$box.css('padding-right', this.$ellipsis.outerWidth());
			this.$ellipsis.show();
			const marginL = $firstRowLastBox.get(0).offsetLeft + $firstRowLastBox.outerWidth() + marginR;
			this.$ellipsis.css('left', marginL);
			this.$ellipsis.css('right', 'auto');
		} else {
			this.$ellipsis.hide();
		}
	}

	handleResize() {
		window.addEventListener('resize', debounce(() => {
			this.boxTopMargin = this.$box.offset().top - (this.$box.prev().offset().top + this.$box.prev().outerHeight(true));
			let tablet = window.matchMedia('(min-width: 768px)');
			if (tablet.matches) {
				this.$box.css('marginTop', '');
			}
		}, 500));
	}

	handleCardHover() {
		this.$textContainer.hover(() => {
			this.$.addClass('is-hover');
		});
		this.$textContainer.mouseleave(() => {
			this.$.removeClass('is-hover');
		});
	}

	toggleClick = (e) => {
		e.preventDefault();
		let mobile = window.matchMedia('(max-width: 767px)');
		$(this.elem).toggleClass('show-tags');
		this.$box.toggleClass('show-all');
		if (mobile.matches) {
			this.$box.css('marginTop', `${this.boxTopMargin}px`);
		}
	}
}

export default function ($elem) {
	$elem.each(function (idx, elem) {
		const $elem = $(elem);

		const $tags_box = $elem.find('.tags-box');
		if (!$tags_box.length)
			return;

		new TagBoxEllipsis(elem);
	});
}
