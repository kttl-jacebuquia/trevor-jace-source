import 'what-input';
import $ from 'jquery';
import * as features from './features';
import * as vendors from './vendors';
import * as matchMedia from './match-media';
import './nav';
import singlePages from './single-pages';
import { infoBoxesCarousel } from './features';
import modal from './features/modal';

Object.assign((window.trevorWP = window.trevorWP || {}), {
	features,
	vendors,
	matchMedia,
});

const $body = $('body');
const isSingle = $body.hasClass('single');
const isPage = $body.hasClass('page');
const isFAQPresent = $('.faq');
const isCardPresent = $('.card-collection');
const auditSlider = document.querySelectorAll('.audit-container');
const isNavigator = document.querySelectorAll('.charity-navigator-container');
const bigImageCarousels = $('.big-img-carousel');
const isPhoneField = $('.phone-number-format') ? true : false;
const $showAllTilesBtn = $(
	'.tile-grid-container + .view-all-container .view-all-cta'
);
const $fundraiserQuizButton = $('.js-fundraiser-quiz');
const $donationModalButton = $('.js-donation-modal');
const $quickExitModal = $('.js-quick-exit-modal');
const $ectMap = $('.ect-map');
const $staffCarousel = $('.staff.is-carousel');
const $staffGrid = $('.staff.is-grid');
const faqTrigger = $('.faq-list__toggle');
const $siteWideSearchInput = $('.is-site-search .search-field');

// Tag Box Ellipsis
// features.tagBoxEllipsis($('.card-post'));

// Ajax Pagination
features.ajaxPagination();

// Ajax Sort.
features.ajaxSorter();

features.showAllTiles($showAllTilesBtn);

if ($fundraiserQuizButton.length) {
	const fundraiserQuiz = new features.FundraiserQuiz();

	$fundraiserQuizButton.on('click', (e) => {
		const initialVertex = e.currentTarget.dataset.fundraiseVertex;
		const single = e.currentTarget.dataset.fundraiseSingle ? true : false;

		fundraiserQuiz.show({
			initialVertex,
			single,
		});
	});
}

if ($donationModalButton.length) {
	const $donationModal = $('#js-donation-modal');

	const options = {
		onOpen({ initiator, modalContent }) {
			const willHideDedicationField = $(initiator).data('hideDedication');

			$('.dedication', modalContent)[
				willHideDedicationField ? 'hide' : 'show'
			]();
		},
	};

	modal($donationModal, options, $donationModalButton);
}

if ($quickExitModal.length) {
	let siteVisitCount = Number(
		window.localStorage.getItem('site-visit-count') || 0
	);
	const isQuickExitModalDismissed = localStorage.getItem(
		'quick-exit-modal-dismissed'
	);
	let willShowModal = !isQuickExitModalDismissed;
	let willDelayPromo = false;

	if (isQuickExitModalDismissed) {
		siteVisitCount++;
		window.localStorage.setItem('site-visit-count', siteVisitCount);

		if (siteVisitCount === 4) {
			willShowModal = true;
		}
	}

	if (willShowModal) {
		willDelayPromo = true;
		let lastActiveElement;

		// Set the timeout longer if the page is scrolling
		// through contents (URL with hash).
		const modalTimeout = window.location.hash.length ? 1000 : 500;
		const options = {
			onInit(modal) {
				lastActiveElement =
					document.activeElement !== document.body
						? document.activeElement
						: null;
				setTimeout(() => modal.open(), modalTimeout);
			},
			onClose({ initiator }) {
				window.localStorage.setItem('quick-exit-modal-dismissed', true);
				features.PromoPopup.showWhenAvailable(willDelayPromo);
				const toFocus =
					lastActiveElement ||
					document.querySelector('.site-banner > :first-child') ||
					document.body;
				toFocus?.focus();
			},
		};

		modal($quickExitModal, options);
	} else {
		features.PromoPopup.showWhenAvailable(willDelayPromo);
	}
}

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
	const onHeadingMutate = (heading) => {
		const $heading = $(heading);
		const $button = $heading.find('button');
		const title = $button.data('title');
		const isExpanded = $heading.hasClass('is-open');
		const label = `Click to ${
			isExpanded ? 'hide' : 'show'
		} contents for ${title}`;
		$button.attr('aria-label', label).attr('aria-expanded', isExpanded);
	};

	const faqTriggerObserver = new MutationObserver(([mutation]) => {
		if (mutation.attributeName === 'class') {
			onHeadingMutate(mutation.target);
		}
	});

	faqTrigger.each((index, el) => {
		const $heading = $(el).closest('.faq-list__heading');
		faqTriggerObserver.observe($heading[0], { attributes: true });
		onHeadingMutate($heading);
	});

	faqTrigger.on('click', function (e) {
		e.preventDefault();
		const $heading = $(this).closest('.faq-list__heading');
		// features.faqToggle($heading);
	});
}

if (isNavigator) {
	isNavigator.forEach((_el) => {
		const _element = `#${_el.getAttribute('id')}`;
		features.carouselNavigator(_element);
	});
}

if (auditSlider) {
	auditSlider.forEach((_el) => {
		const _element = `#${_el.getAttribute('id')}`;
		features.carouselNavigator(_element);
	});
}

if (isCardPresent) {
	const cardTrigger = $('.tile-title');

	cardTrigger.on('click', function (e) {
		e.preventDefault();
		features.cardToggle($(this));
	});
}

(() => {
	if (bigImageCarousels.length) {
		$.each(bigImageCarousels, (index, element) => {
			const $swiper = $('.carousel-container', element);

			const onMutate = (mutations, observer) => {
				if ($swiper.hasClass('swiper-container-initialized')) {
					observer.disconnect();
					onSwiperInitialized();
				}
			};

			const onSwiperInitialized = () => {
				const swiperInstance = $swiper.get(0)?.swiper;

				if (swiperInstance) {
					swiperInstance.on('slideChangeTransitionEnd', () => {
						swiperInstance.slides[
							swiperInstance.activeIndex
						].focus();
					});
				}
			};

			if ($swiper.length) {
				const observer = new window.MutationObserver(onMutate);
				observer.observe($swiper.get(0), { attributes: true });
			}
		});
	}
})();

// features.modal($('.modal'), {}, $('.modal-open'));
features.collapsible($('.js-accordion'), {});

/**
 * Bouncing Arrow function
 */
(() => {
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
					behavior: 'smooth',
					block: 'start',
				});
			}
		});
	}
})();

if (isPhoneField) {
	features.phoneFormat($('.phone-number-format'));
}

/**
 * Varying squiggly line breakers on post bottom tags.
 */
(() => {
	// fixme: overselecting
	const tagsWaveSeparators = $(
		'.post-bottom-tags .wp-block-separator.is-style-wave'
	);

	// set the width on load.
	setTagsSeparatorsWidth(tagsWaveSeparators);

	// set the width on resize
	$(window).on('resize', () => {
		setTagsSeparatorsWidth(tagsWaveSeparators);
	});

	function setTagsSeparatorsWidth(separators = []) {
		if (!separators.length) {
			return;
		}

		const listContainerWidth = $('.post-bottom-tags .list-container').length
			? $('.post-bottom-tags .list-container').width() + 'px'
			: '100%';
		$.each(separators, (index, el) => {
			el.style.width = listContainerWidth;
		});
	}
})();

(() => {
	if ($staffCarousel.length) {
		$staffCarousel.each((idx, el) => {
			let swiper;
			const $swiperContainer = $(el).find('.swiper-container');
			const $cards = $(el).find('.swiper-slide');
			const swiperOptions = {
				loop: false,
				slidesPerView: 'auto',
				centeredSlides: false,
				navigation: {
					prevEl: $(el).find('.swiper-button-prev').get(0),
					nextEl: $(el).find('.swiper-button-next').get(0),
				},
				breakpoints: {
					1024: {
						slidesPerView: 3,
						spaceBetween: 28,
					},
				},
				on: {
					init(_swiper) {
						swiper = _swiper;
					},
					destroy() {
						swiper = null;
					},
				},
			};

			if ($cards.length) {
				matchMedia.mobileAndSmallDesktop(
					() =>
						!swiper &&
						features.carousel($swiperContainer, swiperOptions),
					() => swiper && swiper.destroy()
				);
			}
		});
	}

	if ($staffGrid.length) {
		$staffGrid.each((idx, el) => {
			const $loadMore = $(el).find('.staff__load-more');
			if ($loadMore.length) {
				$loadMore.on('click', () => {
					$(el).find("[data-staff-part='last']").fadeIn();
					$loadMore.closest('.staff__load-more-container').fadeOut();
					setTimeout(
						() =>
							el
								.querySelector("[data-staff-part='last'] a")
								.focus(),
						100
					);
				});
			}
		});
	}
})();

if ($ectMap.length) {
	$ectMap.each((index, element) => features.ectMap(element));
}

if ($siteWideSearchInput.length) {
	$siteWideSearchInput.get(0).focus();
}

features.QuickExit.init();
features.BreathingExercise.init();
features.CurrentOpenings.init();
features.TopicCards.initializeInstances();
features.CampaignForm.initializeInstances();
