// Vendors
import 'what-input';
import $ from 'jquery';
import * as features from './features';
import * as vendors from './vendors';
import * as matchMedia from './match-media';
import './nav';
import debounce from 'lodash/debounce';

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
const inputSearchField = $("#rc-search-main");

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

window.onload = function () {
	const footer = document.querySelector('footer');
	const crisisButton = $('.floating-crisis-btn-wrap');
	const distanceFromFooter = 40;
	let footerHeight = footer ? footer.offsetHeight : 0;

	

	let options = {
		root: null,
		rootMargin: '0px',
		threshold: 0
	}
	let callback = (entries, observer) => {
		entries.forEach(entry => {
			$(window).resize(() => {
				footerHeight = footer.offsetHeight;
			});
			if (entry.isIntersecting) {
				$body.addClass('scroll-near-bottom');
				crisisButton.css('bottom', `${footerHeight+12+distanceFromFooter}px`);
			} else {
				$body.removeClass('scroll-near-bottom');
				crisisButton.removeAttr('style');
			}
		});
	}

	let observer = new IntersectionObserver(callback, options);
	observer.observe(footer);
}

/**
 * Search bar autocomplete function
 */
$(() => {
	const terms = ['Gay', 'Transgender', 'Bisexual', 'Suicide', 'Nonbinary'];
	const searchCancelIcon = $('.icon-search-cancel');
	
	if (inputSearchField.val()) {
		inputSearchField.parent().addClass('input-has-value');
		resizeInputField(inputSearchField);
	}

	inputSearchField
	.autocomplete({
		source: function (request, response) {
			const results = $.ui.autocomplete.filter(terms, request.term);
			response(results.slice(0, 5)); // Limit the results to five.
		},
		minLength: 0,
		appendTo: "#input-suggestions",
		open: function() {
			/**
			 * The results will automatically show without a header
			 * when the user starts to type anything on the search bar.
			 */
			if ($(this).val() == '') {
				$('ul.ui-autocomplete').prepend('<li class="list-header"><h3>Popular Searches</h3></li>');
			}
		},
		select: function(event, ui) {
			$(this).addClass('has-value');
			$(this).parent().addClass('input-has-value');
			resizeInputField($(this), ui.item.value.length);
		},
	})
	/**
	 * Immediately open the jQuery autocomplete 
	 * when the user focuses the search bar
	 * even before they start typing anyting.
	 */
	.focus(function () {
		$(this).autocomplete('search', $(this).val());
	})
	.on("keyup", function (event) {
		if (!inputSearchField.val()) {
			$(this).parent().removeClass('input-has-value');
		} else {
			$(this).parent().addClass('input-has-value');
		}
		resizeInputField($(this));
	});

	searchCancelIcon.on("click", function (e) {
		inputSearchField.val("");
		inputSearchField.parent().removeClass('input-has-value');
	});

	function resizeInputField (inputField, inputSize = 0) {
		const maxSize = 30;
		const minSize = 1;
		const length = inputSize | inputField.val().length;
		const chars = length + 1;
		inputField.attr('size', Math.min(Math.max(chars, minSize), maxSize));
	}
});

/**
 * Bouncing Arrow function
 */
$(() => {
	const bouncingArrow = $('.bouncing-arrow');

	if (bouncingArrow.length) {
		bouncingArrow.on('click', () => {
			let parentElement = bouncingArrow.parent();
			let parentsNextSibling = parentElement.next();
			
			/**
			 * Find the parent container with
			 * immediate following sibling.
			 */
			while (!parentsNextSibling.length) {
				parentsNextSibling = parentElement.next();
				parentElement = parentElement.parent();
			}

			if  (parentsNextSibling.length) {
				parentsNextSibling[0].scrollIntoView({
					behavior: "smooth",
					block: "start",
				});
			}
		});
	}
});

// Simple Masking 
$("#mobilephone").blur(function(){
	var _input = $(this).val();
	var x = _input.replace(/\D/g, '').match(/(\d{0,3})(\d{0,3})(\d{0,4})/);
	var _newValue = !x[2] ? x[1] : '+1 (' + x[1] + ')-' + x[2] + (x[3] ? '-' + x[3] : '');
	$(this).val( _newValue );
});