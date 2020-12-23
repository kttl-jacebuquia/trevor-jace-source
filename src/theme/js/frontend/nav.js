import $ from 'jquery';
import throttle from 'lodash/throttle';
import './sharing';

const $body = $('body');

const isBodyOnTop = throttle(
	() => $body[$(document).scrollTop() > 0 ? 'removeClass' : 'addClass']('on-top'),
	100
);

$(isBodyOnTop); // On load
$(window).on('scroll', isBodyOnTop); // On scroll
