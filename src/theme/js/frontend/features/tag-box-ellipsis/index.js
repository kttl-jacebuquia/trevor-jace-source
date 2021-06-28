import $ from 'jquery';
import debounce from 'lodash/debounce';
import WithState from '../../WithState';
import * as focusTrap from 'focus-trap';

const resizeObserver = new ResizeObserver(function (entries) {
	entries.forEach(elem => {
		const {target: {tagBoxEllipsis}} = elem;

		if (!tagBoxEllipsis) return;

		tagBoxEllipsis.calc();
	})
});

class TagBoxEllipsis extends WithState {
	state = {
		expanded: false,
	};

	// Replace on init
	focusTrap = null;

	constructor(elem) {
		super();

		elem.tagBoxEllipsis = this;
		resizeObserver.observe(elem);
		this.elem = elem;
		this.$ = $(this.elem);
		this.$box = this.$.find('.tags-box');
		this.$boxes = this.$box.find('.tag-box');
		this.$box.css('position', 'relative');
		this.$textContainer = this.$.find('.card-text-container');
		this.title = this.$box.data('title');

		/**
		 * calulate the distance between the tags box and its previous sibling
		 * (there must be margin-top: auto CSS on the box)
		 * then set the top margin of the box after it's been expanded.
		 */
		this.boxTopMargin = this.$box.offset().top - (this.$box.prev().offset().top + this.$box.prev().outerHeight(true));

		this.$ellipsis = $(`<button href="#" class="tag-box ellipsis" aria-label="Show more tags for ${this.title}"></button>`)
			.on('click', this.toggleClick)
			.appendTo(this.$box);

		this.calc();
		this.handleResize();
		this.handleCardHover();

		this.focusTrap = focusTrap.createFocusTrap(this.$box[0], {
			initialFocus: this.$boxes[0],
		});
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
		if (mobile.matches) {
			this.$box.css('marginTop', `${this.boxTopMargin}px`);
		}

		this.setState({
			expanded: !this.state.expanded
		});
	}

	componentDidUpdate(stateChange) {
		if ( 'expanded' in stateChange ) {
			this.onExpandedStateChange(stateChange.expanded);
		}
	}

	onExpandedStateChange(isExpanded) {
		const ariaLabel = isExpanded ?
			`Hide tags for ${this.title}` :
			`Show more tags for ${this.title}`;

		this.$box.attr('aria-expanded', isExpanded);

		$(this.elem).toggleClass('show-tags', isExpanded);
		this.$box.toggleClass('show-all', isExpanded);
		this.$ellipsis.attr('aria-label', ariaLabel);

		this.focusTrap[ isExpanded ? 'activate' : 'deactivate' ]();
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
