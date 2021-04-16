import 'what-input';
import $ from 'jquery';
import * as features from './features';
import * as vendors from './vendors';
import * as matchMedia from './match-media';
import './nav';
import singlePages from './single-pages';
import {infoBoxesCarousel} from "./features";

Object.assign(
	window.trevorWP = window.trevorWP || {},
	{features, vendors, matchMedia}
);

const $body = $('body');
const isSingle = $body.hasClass('single');
const isPage = $body.hasClass('page');
const isFAQPresent = $('.faq');
const isCardPresent = $('.card-collection');
const isDonate = !isSingle && $body.hasClass('is-donate');
const auditSlider = document.querySelectorAll('.audit-container');
const isNavigator = document.querySelectorAll('.navigator-container');
const inputSearchField = $("#rc-search-main");
const bigImageCarousels = $('.big-img-carousel.body-carousel');
const isPhoneField = $(".phone-number-format") ? true : false;
const $showAllTilesBtn = $(".tile-grid-container + .view-all-container .view-all-cta");

let faqTrigger = $('.faq-list__heading');

// Tag Box Ellipsis
features.tagBoxEllipsis($('.card-post'));

// Ajax Pagination
features.ajaxPagination();

// Sticky Anchor
features.stickyAnchor();

features.showAllTiles($showAllTilesBtn);


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
} else if (isPage) {
	infoBoxesCarousel();
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

if (isDonate) {
	features.toggleFrequency();
	features.toggleAmount();
	features.displayAmountAction();
	features.displayCurrency();
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
 * Search bar autocomplete function
 * @todo: move under is-rc
 */
$(() => {
	const terms = ['Gay', 'Transgender', 'Bisexual', 'Suicide', 'Nonbinary'];
	const searchCancelIcon = $('.icon-search-cancel');
	const maxInputSize = 35;
	const form = $('.search-form');
	const hiddenInputField = $("input[name='s']", form);

	if (hiddenInputField.val()) {
		inputSearchField.parent().addClass('input-has-value');
		inputSearchField.html(hiddenInputField.val());
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
				hiddenInputField.val(ui.item.value);
				moveCursorToEnd($(this)[0]);
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
			const inputText = $(this).html();
			if (!inputText) {
				$(this).parent().removeClass('input-has-value');
				hiddenInputField.val('');
			} else {
				$(this).parent().addClass('input-has-value');
				hiddenInputField.val(inputText);
			}
		})
		.on("keypress", function (event) {
			if (event.keyCode === 13) {
				form.submit();
				return false;
			}
			if ($(this).html().length >= maxInputSize) {
				event.preventDefault();
				return false;
			}
		});

	searchCancelIcon.on("click", function (e) {
		inputSearchField.html("");
		inputSearchField.parent().removeClass('input-has-value');
		inputSearchField.focus();
	});

	function moveCursorToEnd(el) {
		setTimeout(() => {
			if (el.innerText && document.createRange) {
				let selection = document.getSelection();
				let range = document.createRange();

				range.setStart(el.childNodes[0], el.innerText.length);
				range.collapse(true);
				selection.removeAllRanges();
				selection.addRange(range);
			}
		}, 100);
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

if (isPhoneField) {
	features.phoneFormat($(".phone-number-format"));
}

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
