import $ from 'jquery';

export default function faqToggle($btn) {
	const slideDuration = 600;
	$btn.toggleClass('is-open');
	$btn.next().slideToggle(slideDuration);
}
