import $ from 'jquery';

export default function faqToggle($btn) {
	const slideDuration = 50;
	$btn.toggleClass('is-open');
	$btn.next().slideToggle(slideDuration);
}
