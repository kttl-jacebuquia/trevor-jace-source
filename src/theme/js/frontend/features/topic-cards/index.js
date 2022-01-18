import Swiper from 'swiper';

const MORE_CARDS_ENDPOINT = '/wp-json/trevor/v1/post-cards';
class TopicCards {
	static selector = '.topic-cards';
	static cardsContainer = '.topic-cards__grid';
	static loadMoreSelector = '.topic-cards__load-more';
	static itemSelector = '.topic-cards__item';
	static itemsPerView = 6;

	static CLASSNAME_CAROUSEL = 'topic-cards--carousel';

	// Loaded posts for all instances
	static loadedPosts = [];

	// Loaded posts for current instance
	currentPosts = [];

	constructor(element) {
		this.element = element;
	}

	init() {
		this.cardsContainer = this.element.querySelector(
			TopicCards.cardsContainer
		);

		this.carouselPagination =
			this.element.querySelector('.swiper-pagination');

		const isCarousel = this.element.classList.contains(
			TopicCards.CLASSNAME_CAROUSEL
		);

		if (isCarousel) {
			this.initializeCarousel();
		}

		this.saveInitialPosts();
		this.initializeLoadMore();
	}

	initializeCarousel() {
		new Swiper(this.cardsContainer, {
			slidesPerView: 1,
			spaceBetween: 12,
			navigation: {
				nextEl: '.swiper-button-next',
				prevEl: '.swiper-button-prev',
			},
			simulateTouch: false,
			pagination: {
				el: this.carouselPagination,
				clickable: true,
				bulletElement: 'button',
			},
			breakpoints: {
				768: {
					spaceBetween: 28,
				},
				1024: {
					spaceBetween: 28,
				},
			},
			on: {
				slideChangeTransitionEnd:
					this.onSlideChangeTransitionEnd.bind(this),
			},
		});
	}

	saveInitialPosts() {
		const { postType } = this.element.dataset;
		const { postIds } = this.cardsContainer.dataset;
		const [...items] = this.element.querySelectorAll(
			TopicCards.itemSelector
		);

		if (postType) {
			this.postType = postType;
		}

		if (postIds) {
			this.postIds = postIds;
		}

		items.forEach((itemElement) => {
			const { post } = itemElement.dataset;
			if (post) {
				this.savePostID(Number(post));
			}
		});
	}

	/**
	 * Initializes Load More Button
	 *
	 * NOTE:
	 * There is no display limit in Topic Cards, so all cards show up on initial load.
	 * Load More functionality pulls in additional cards aside from the ones set in the CMS.
	 */
	initializeLoadMore() {
		this.loadMore = this.element.querySelector(TopicCards.loadMoreSelector);

		if (this.loadMore) {
			this.loadMore.addEventListener('click', (e) => {
				e.preventDefault();
				this.loadMoreItems();
			});
		}
	}

	savePostID(...postIDs) {
		this.currentPosts.push(...postIDs);
		TopicCards.loadedPosts.push(...postIDs);
	}

	async loadMoreItems() {
		// Build out GET params
		const paramsObj = {
			include: this.postIds,
			exclude: TopicCards.loadedPosts.join(','),
			post_type: this.postType,
			card_options: JSON.stringify({
				class: ['topic-cards__item'],
				attr: { role: 'listitem' },
			}),
			count: TopicCards.itemsPerView + 1, // Extra 1 just to determine if there's any post to load next
		};
		const paramsString = Object.entries(paramsObj)
			.reduce((allParams, keyValuePair) => {
				allParams.push(keyValuePair.join('='));
				return allParams;
			}, [])
			.join('&');

		try {
			const response = await fetch(
				[MORE_CARDS_ENDPOINT, paramsString].join('?')
			);
			const data = await response.json();
			this.processResponse(data);
		} catch (err) {
			console.warn(err);
		}
	}

	processResponse(responseData) {
		if (!responseData.success || !responseData.cards_rendered.length) {
			return;
		}

		// Exclude the extra last card. it's only needed for checking for more available items
		const newCards = responseData.cards_rendered.slice(
			0,
			TopicCards.itemsPerView
		);
		const newCardsHtml = newCards.join('');
		this.cardsContainer.innerHTML += newCardsHtml;

		// Save new IDs, excluding the extra last one
		const newIDs = responseData.cards_ids.slice(0, TopicCards.itemsPerView);
		this.savePostID(...newIDs);

		// Check if will remove load more button
		if (
			responseData.cards_rendered.length <= TopicCards.itemsPerView &&
			this.loadMore
		) {
			// Remove load more including its parent wrapper
			this.loadMore.parentElement.parentElement.removeChild(
				this.loadMore.parentElement
			);
		}
	}

	onSlideChangeTransitionEnd(swiper) {
		const windowWidth = window.innerWidth;
		const [lastSlide] = swiper.slides.slice(-1);
		const lastSlideBounds = lastSlide.getBoundingClientRect();

		swiper.navigation?.nextEl?.classList.toggle(
			'invisible',
			lastSlideBounds.right <= windowWidth
		);
	}

	static initializeInstances() {
		const [...elements] = document.querySelectorAll(this.selector);

		elements.forEach((element) => {
			const instance = new TopicCards(element);
			instance.init();
		});
	}
}

export default TopicCards;
