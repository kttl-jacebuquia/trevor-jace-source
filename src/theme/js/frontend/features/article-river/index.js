import $ from 'jquery';
import Component from '../../Component';
import { carousel } from '../carousel';
import { scrollIntoView } from '../global-ui';

const ENDPOINT = '/wp-json/trevor/v1/article-river-entries';

export default class ArticleRiver extends Component {
	static selector = '.article-river';

	// Defines children that needs to be queried as part of this component
	static children = {
		paginationContainer: '.article-river__pagination .swiper-container',
		paginationPages: ['.article-river__pagination-page'],
		listContainer: '.article-river__list',
		sortLabel: '.article-river__sort-label',
		sortOptions: ['.article-river__sort-option'],
	};

	swiperOptions = {
		loop: false,
		slidesPerView: 4,
		slidesPerGroup: 4,
		pagination: false,
		on: {
			init: this.onSwiperInit.bind(this),
		},
	};

	constructor(element) {
		super(element);
		this.paginationType = element.dataset.paginationType;
		this.filtertype = element.dataset.filtertype;
		this.category = element.dataset.category;
		this.limit = element.dataset.limit;
	}

	// Will be called upon component instantiation
	afterInit() {
		const $paginationContainer = $(this.children.paginationContainer);
		const nextEl = this.element.querySelector(
			'.article-river__pagination-nav--next'
		);
		const prevEl = this.element.querySelector(
			'.article-river__pagination-nav--prev'
		);

		const navigationOptions = {
			nextEl,
			prevEl,
		};

		if (this.children.paginationPages.length > 4) {
			carousel($paginationContainer, {
				...this.swiperOptions,
				navigation: navigationOptions,
			});
		}

		if (this.paginationType === 'ajax') {
			this.bindPagination();
		}

		if (this.children.sortOptions.length) {
			this.bindSort();
		}
	}

	bindPagination() {
		// Initial page
		this.currentPage = 1;

		this.children.paginationPages.forEach((pagination) => {
			pagination.addEventListener('click', this.onPageClick.bind(this));
		});
	}

	bindSort() {
		// Initial sort
		this.sort = 'desc';

		this.children.sortOptions.forEach((sortOption) => {
			sortOption.addEventListener(
				'click',
				this.onSortOptionClick.bind(this)
			);
		});
	}

	onSortOptionClick(e) {
		e.preventDefault();
		const option = e.currentTarget;
		const { sort } = option.dataset;
		const activeOption = this.children.sortOptions.find((sortOption) =>
			sortOption.classList.contains('active')
		);

		if (activeOption === option) {
			return;
		}

		this.sort = sort;
		this.children.sortLabel.textContent = option.textContent;

		activeOption.classList.remove('active');
		option.classList.add('active');
		this.getAndRenderPageData();
	}

	onPageClick(e) {
		e.preventDefault();

		const pagination = e.currentTarget;
		const { page } = pagination.dataset;
		const activePagination =
			this.children.paginationPages[this.currentPage - 1];

		if (activePagination === pagination) {
			return;
		}

		this.currentPage = Number(page);

		activePagination.classList.remove('active');
		pagination.classList.add('active');

		// Scroll to top of component
		scrollIntoView(this.element, () => {
			this.getAndRenderPageData();
		});
	}

	async getAndRenderPageData() {
		// Ensure page is a number
		const page = Number(this.currentPage) || 1;
		const sort = this.sort || 'desc';
		const dataHTML = await this.requestPageData({
			page,
			sort,
			limit: this.limit,
			category: this.category,
		});

		// Replace items in view
		window.setTimeout(() => {
			this.children.listContainer.innerHTML = dataHTML;
		});
	}

	async requestPageData(requestOptions) {
		const params = Object.entries(requestOptions)
			.reduce(
				(paramsList, entry) => paramsList.concat(entry.join('=')),
				[]
			)
			.join('&');
		const response = await window.fetch([ENDPOINT, params].join('?'));
		const { entries_html } = await response.json();
		return entries_html || '';
	}

	onSwiperInit(swiper) {
		this.swiper = swiper;
	}
}

// Uncomment this section if this component is intended
// to initialize on DOM load.
ArticleRiver.init();
