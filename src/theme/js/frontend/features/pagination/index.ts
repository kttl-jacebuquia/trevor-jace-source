/**
 * FE Dynamic pagination.
 * The markup and styles are based off of template-parts/pagination.php,
 * but this is FE-driven and doesn't need a page reload,
 * also allows programmatic control through JS
 *
 * Refer to: template-parts/pagination.php
 */
// pagination number-pagination text-center
import WithState from '../../WithState';
import { replaceParams } from '../url';

export interface PaginationState {
	currentPage: number;
}

export interface PaginationOptions {
	urlTemplate?: string;
	totalPages?: number;
	currentPage?: number;
	onChange?: (pageNumber: number) => void;
}

export const URL_PARAMS_PAGE_PLACEHOLDER = '{{PAGE}}';

const renderPaginationElement = () => `
	<div class="pagination number-pagination text-center">
	</div>
`;

// pagination number-pagination text-center
export default class Pagination extends WithState<PaginationState> {
	container: HTMLElement;
	element: HTMLElement;
	links: HTMLElement[] = [];
	prev: HTMLElement | null = null;
	next: HTMLElement | null = null;
	options: PaginationOptions = {};

	state = {
		currentPage: 1,
	};

	constructor(container: HTMLElement, options = {}) {
		super();
		this.options = options;
		this.container = container;
		this.container.insertAdjacentHTML(
			'afterbegin',
			renderPaginationElement()
		);
		this.element = this.container.firstElementChild as HTMLElement;
	}

	init() {
		this.renderLinks();
		this.setState({ currentPage: this.options.currentPage || 1 });
	}

	setTotalPages(totalPages = 0, retainCurrentPage = false) {
		const newOptions: Partial<PaginationOptions> = { totalPages };

		if (!retainCurrentPage) {
			newOptions.currentPage = 1;
		}

		this.options = newOptions;
		this.reload();
	}

	renderLinks() {
		const { totalPages = 0, currentPage = 1 } = this.options;

		for (let page = 1; page <= totalPages; page++) {
			const link = this.generatePageLink(page, currentPage === page);
			this.element.appendChild(link);
			this.links.push(link);
		}

		// Add prev and next links if applicable
		if (totalPages > 1) {
			this.prev = this.generatePageLink(
				currentPage - 1 || 1,
				false,
				'‹',
				['prev', currentPage === 1 ? 'hidden' : ''].join(' ')
			);
			this.next = this.generatePageLink(
				currentPage + 1,
				false,
				'›',
				['next', currentPage === totalPages ? 'hidden' : ''].join(' ')
			);
			this.element.insertAdjacentElement('afterbegin', this.prev);
			this.element.appendChild(this.next);
		}
	}

	generatePageLink(
		pageNumber = 1,
		active = false,
		label = pageNumber,
		className = ''
	) {
		const classNames = ['page-numbers', active ? 'current' : '', className]
			.filter(Boolean)
			.join(' ');

		const html = `
		<a class="${classNames}" href="${this.generatePageUrl(
			pageNumber
		)}" data-page="${pageNumber}">
			${label}
		</a>
		`;

		const link = Object.assign(document.createElement('template'), {
			innerHTML: html.trim(),
		}).content.firstChild as HTMLElement;

		link.addEventListener('click', (e) => this.onPageClick(e));

		return link;
	}

	generatePageUrl(pageNumber = 1) {
		const urlTemplate =
			this.options.urlTemplate || this.generateDefaultUrlTemplate();

		if (urlTemplate) {
			return urlTemplate.replace(
				URL_PARAMS_PAGE_PLACEHOLDER,
				pageNumber + ''
			);
		}
	}

	generateDefaultUrlTemplate() {
		return replaceParams({ page: URL_PARAMS_PAGE_PLACEHOLDER });
	}

	clearLinks() {
		this.links = [];
		this.prev = null;
		this.next = null;
		this.element.innerHTML = '';
	}

	reload() {
		this.clearLinks();
		this.renderLinks();
	}

	setURLTemplate(urlTemplate = '') {
		this.options.urlTemplate =
			urlTemplate || this.generateDefaultUrlTemplate();
	}

	setCurrentPage(pageNumber = 1) {
		this.setState({ currentPage: pageNumber });
	}

	onPageClick(event: Event) {
		const { currentPage } = this.state;
		const pageNumber = Number(event.currentTarget?.dataset.page || '1');
		event.preventDefault();

		if (currentPage !== Number(pageNumber)) {
			this.setCurrentPage(pageNumber);

			if (typeof this.options.onChange === 'function') {
				this.options.onChange(pageNumber);
			}
		}
	}

	updateActiveLink() {
		const { currentPage } = this.state;

		// Update active link
		this.links.forEach((link) => {
			if (Number(link.dataset.page) === currentPage) {
				link.classList.add('current');
				link.setAttribute('aria-current', 'page');
				link.setAttribute('role', 'text');
				link.setAttribute('tabindex', '-1');
			} else {
				link.classList.remove('current');
				link.removeAttribute('aria-current');
				link.removeAttribute('role');
				link.removeAttribute('tabindex');
			}
		});
	}

	// Show/hide prev/next buttons accordingly
	// And update prev/next links
	updatePrevNextLinks() {
		const { currentPage } = this.state;

		if (this.prev) {
			this.prev.classList.toggle('hidden', currentPage === 1);
			this.prev.setAttribute(
				'href',
				this.generatePageUrl(currentPage - 1) || '#'
			);
			this.prev.dataset.page = String(currentPage - 1);
		}

		if (this.next) {
			this.next.classList.toggle(
				'hidden',
				currentPage === (this.options.totalPages || 1)
			);
			this.next.setAttribute(
				'href',
				this.generatePageUrl(currentPage + 1) || '#'
			);
			this.next.dataset.page = String(currentPage + 1);
		}
	}

	componentDidUpdate() {
		this.updateActiveLink();
		this.updatePrevNextLinks();
	}
}
