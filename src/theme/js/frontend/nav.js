import $ from 'jquery';
import throttle from 'lodash/throttle';
import debounce from 'lodash/debounce';

const $body = $('body');

const isBodyOnTop = throttle(
	() => {
		$body[$(document).scrollTop() > 0 ? 'removeClass' : 'addClass']('on-top')
		$body.removeClass('is-not-scrolling')
	},
	100
);
const isScrolling = debounce(
	() => {
		$body.addClass('is-not-scrolling')
	},
	2000
);

$(isBodyOnTop); // On load
$(isScrolling); // On load
// $body.addClass('is-not-scrolling');
$(window).on('scroll', isBodyOnTop); // On scroll
$(window).on('scroll', isScrolling); // On scroll
