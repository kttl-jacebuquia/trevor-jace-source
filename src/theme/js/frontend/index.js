// Vendors
import 'what-input';
import $ from 'jquery';
import * as features from './features';
import * as vendors from './vendors';
import * as matchMedia from './match-media';
import './nav';

window.trevorWP = {features, vendors, matchMedia};

const $body = $('body');
const isSingle = $body.hasClass('single');
const isGetHelp = !isSingle && $body.hasClass('is-get-help');
const isDonate = !isSingle && $body.hasClass('is-donate');
const isTrevorspace = !isSingle && $body.hasClass('is-trevorspace');
const isFAQPresent = $('.faq');
const isCardPresent = $('.card-collection');
const carouselTestimonial = document.querySelectorAll('.carousel-testimonials');
const auditSlider = document.querySelectorAll('.audit-container');
const isNavigator = document.querySelectorAll('.navigator-container');

let faqTrigger = $('.faq-list__heading');

// Tag Box Ellipsis
features.tagBoxEllipsis($('.card-post'));

// Single (Detail) Page
if (isSingle) {
	// Floating Blocks
	features.floatingBlock(
		$('.post-content .trevor-block-floating'),
		$('.post-content-sidebar .floating-blocks-home')
	);

	// Highlights
	features.articleHighlights($('.post-highlights-list'));

	// Sharing More: Dropdown/Native
	features.sharingMore(
		document.querySelector('.post-share-more-btn'),
		document.querySelector('.post-share-more-content')
	);

	// Open social media sharing pages in a popup
	features.sharingPopUp($('.post-social-share-btn'));
}

// Get Help
isGetHelp && console.log('Get-Help page');

// Trevorspace
isTrevorspace && console.log('Trevorspace page');

// If FAQ is Present
if (isFAQPresent) {
	faqTrigger.on('click', function (e) {
		e.preventDefault();
		features.faqToggle($(this));
	});
}

if (isDonate) {
	const _frequency = $('.frequency--choice label');
	const _amount = $('.amount-choice label');

	_frequency.on('click', function (e) {
		e.preventDefault();
		features.toggleFrequency($(this));
	});

	_amount.on('click', function (e) {
		e.preventDefault();
		features.toggleAmount($(this));
	});
}

if (carouselTestimonial) {
	carouselTestimonial.forEach(_el => {
		features.carousel(_el.getAttribute('id'));
	});
}

if (isNavigator) {
	isNavigator.forEach(_el => {
		let _element = `#${_el.getAttribute('id')}`;
		features.carouselNavigator(_element);
	});
}

if (auditSlider) {
	auditSlider.forEach(_el => {
		let _element = `#${_el.getAttribute('id')}`;
		features.carouselNavigator(_element);
	});
}

if (isCardPresent) {
	let cardTrigger = $('.tile-title');

	cardTrigger.on('click', function (e) {
		e.preventDefault();
		features.cardToggle($(this));
	});
}

// features.modal($('.modal'), {}, $('.modal-open'));
features.collapsible($('.js-accordion'), {});
