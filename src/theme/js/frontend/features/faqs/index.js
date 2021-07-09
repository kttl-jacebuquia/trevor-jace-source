import $ from 'jquery';

export default function faqToggle($heading) {
	const slideDuration = 50;
	$heading.toggleClass('is-open');
	$heading.next().slideToggle(slideDuration);
}
