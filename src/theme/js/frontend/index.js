import 'what-input';
import $ from 'jquery';
import * as features from './features';
import * as vendors from './vendors';
import * as matchMedia from './match-media';
import './nav';
import singlePages from './single-pages';

window.trevorWP = {features, vendors, matchMedia};

const $body = $('body');
const isSingle = $body.hasClass('single');
const isFAQPresent = $('.faq');
const isCardPresent = $('.card-collection');
const carouselTestimonial = document.querySelectorAll('.carousel-testimonials');
const auditSlider = document.querySelectorAll('.audit-container');
const isNavigator = document.querySelectorAll('.navigator-container');
const inputSearchField = $("#rc-search-main");
const bigImageCarousels = $('.big-img-carousel.body-carousel');

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
} else {
	singlePages(); // Handle single page scripts
}

// If FAQ is Present
if (isFAQPresent) {
	faqTrigger.on('click', function (e) {
		e.preventDefault();
		features.faqToggle($(this));
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

$(() => {
	if (bigImageCarousels.length) {
		$.each(bigImageCarousels, (index, element) => {
			const swiper = $('.carousel-container.swiper-container-initialized', element);
			if (swiper.length) {
				const leftPaneSelector = '.swiper-button-prev';
				const rightPaneSelector = '.swiper-button-next';
				features.generateSwiperArrows(leftPaneSelector, rightPaneSelector, element);
			}
		});
	}
});

// features.modal($('.modal'), {}, $('.modal-open'));
features.collapsible($('.js-accordion'), {});

/**
 * Crisis Button docking
 * @fixme: window.onload binding
 */
window.onload = function () {
	const stickyAnchors = $('.sticky-cta-anchor');
	const crisisButton = $('.floating-crisis-btn-wrap');
	const distanceFromElement = 40;
	const btnVerticalPadding = 24;
	let intersectedElement = undefined;
	let intersectingY = undefined;
	let leavingY = undefined;

	let options = {
		root: null,
		rootMargin: '0px',
		threshold: 0
	}

	let callback = (entries, observer) => {
		entries.forEach(entry => {
			if (entry.isIntersecting) {
				intersectingY = entry.boundingClientRect.y;
				if (typeof intersectedElement === 'undefined') {
					intersectedElement = entry.target;
				}

				if (entry.target === intersectedElement) {
					const targetRect = intersectedElement.getBoundingClientRect();
					const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
					$body.addClass('scroll-near-bottom');
					crisisButton.offset({
						top: (targetRect.top + scrollTop) - (distanceFromElement + btnVerticalPadding),
					});
				}
			} else {
				leavingY = entry.boundingClientRect.y;
				/**
				 * Reset the crisis button if the user is scrolling up
				 * AND leaves the intersected element.
				 */
				if (leavingY > intersectingY && entry.target === intersectedElement) {
					$body.removeClass('scroll-near-bottom');
					crisisButton.removeAttr('style');
				}
			}
		});
	}

	let observer = new IntersectionObserver(callback, options);
	/**
	 * Intersection Observer runs asynchronously
	 * so you can just add an item in the array (i.e., [element1, elment2, ...]).
	 * The crisis button will dock on the first element it intersects based on the DOM structure.
	 */
	if (stickyAnchors.length) {
		$.each(stickyAnchors, (index, element) => {
			if (element) observer.observe(element);
		});
	}
}

/**
 * Search bar autocomplete function
 * @todo: move under is-rc
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
			open: function () {
				/**
				 * The results will automatically show without a header
				 * when the user starts to type anything on the search bar.
				 */
				if ($(this).val() == '') {
					$('ul.ui-autocomplete').prepend('<li class="list-header"><h3>Popular Searches</h3></li>');
				}
			},
			select: function (event, ui) {
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

	function resizeInputField(inputField, inputSize = 0) {
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

			if (parentsNextSibling.length) {
				parentsNextSibling[0].scrollIntoView({
					behavior: "smooth",
					block: "start",
				});
			}
		});
	}
});

// Simple Masking
$(".phone-number-format").blur(function () {
	const _input = $(this).val();
	const _phoneNumberParts = _input.replace(/\D/g, '').match(/(\d{0,3})(\d{0,3})(\d{0,4})/);
	const _newValue = !_phoneNumberParts[2] ? _phoneNumberParts[1] : '+1 (' + _phoneNumberParts[1] + ')-' + _phoneNumberParts[2] + (_phoneNumberParts[3] ? '-' + _phoneNumberParts[3] : '');
	$(this).val(_newValue);
});

/**
 * Varying squiggly line breakers on post bottom tags.
 */
$(() => {
	// fixme: overselecting
	const tagsWaveSeparators = $(".post-bottom-tags .wp-block-separator.is-style-wave");

	// set the width on load.
	setTagsSeparatorsWidth(tagsWaveSeparators);

	// set the width on resize
	$(window).on("resize", () => {
		setTagsSeparatorsWidth(tagsWaveSeparators);
	});

	function setTagsSeparatorsWidth(separators = []) {
		if (!separators.length) {
			return;
		}

		const listContainerWidth = $(".post-bottom-tags .list-container").length ? $(".post-bottom-tags .list-container").width() + 'px' : '100%';
		$.each(separators, (index, el) => {
			el.style.width = listContainerWidth;
		});
	}
});
