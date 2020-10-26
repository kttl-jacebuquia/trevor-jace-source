import $ from 'jquery';

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

		this.$ellipsis = $('<a href="#" class="tag-box ellipsis">&#8230;</a>')
			.on('click', this.toggleClick)
			.appendTo(this.$box);

		this.calc();
	}

	calc() {
		const newRowHeight = this.$boxes.first().outerHeight();

		if (this.rowHeight !== newRowHeight) {
			this.$box.css('height', this.rowHeight = newRowHeight);
		}

		let showEllipsis = false;
		let $firstRowLastBox = null;

		for (let i = 0; i < this.$boxes.length; i++) {
			const box = this.$boxes.get(i);

			if (box.offsetTop > 0) {
				showEllipsis = true;
				break;
			}

			$firstRowLastBox = $(box);
		}

		const marginR = parseInt($firstRowLastBox.css('margin-right'));

		if (showEllipsis) {
			this.$box.css('padding-right', this.$ellipsis.outerWidth() + marginR);
			this.$ellipsis.show();
			const marginL = $firstRowLastBox.get(0).offsetLeft + $firstRowLastBox.outerWidth() + marginR;
			console.log($firstRowLastBox.get(0).offsetLeft, $firstRowLastBox.outerWidth(), marginR);
			this.$ellipsis.css('left', marginL);
			this.$ellipsis.css('right', 'auto');
			console.log();
		} else {
			this.$ellipsis.hide();
		}

		console.log('showEllipsis', {showEllipsis});
	}

	toggleClick = (e) => {
		e.preventDefault();

		this.$box.toggleClass('show-all');
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
