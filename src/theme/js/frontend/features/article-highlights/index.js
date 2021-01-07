import $ from 'jquery';

class ArticleHighlight {
	constructor($source) {
		this.$source = $source;
		const $realDest = $(`${$source.find('.highlight-link').attr('href')}`);
		const text = $realDest.text();
		this.$destination = $('<span>').addClass('highlight-wrapper').text(text).appendTo($realDest.text(''));

		this.$source.hover(this.hoverIn, this.hoverOut);
	}

	hoverIn = (e) => {
		this.$destination.addClass('link-hover-active');
	}

	hoverOut = (e) => {
		this.$destination.removeClass('link-hover-active');
	}
}

export default function articleHighlights($list) {
	$list.find('.post-highlight').each((idx, elem) => new ArticleHighlight($(elem)));
}
