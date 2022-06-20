import $ from 'jquery';
import WithState from '../../WithState';
import { mobileAndTablet } from '../../match-media';

import FilterNavigationItem from './filter-navigation-item';

import type {
	DropdownFilterField,
	DropdownFiltersOptions,
	DropdownFiltersState
} from './index.d';

export default class DropdownFilters extends WithState<DropdownFiltersState> {
	container: HTMLElement;
	element: HTMLElement;
	fields: DropdownFilterField[];
	headline: string;
	options: DropdownFiltersOptions;
	filters: { [id: string]: FilterNavigationItem } = {};

	state = {
		activeFilters: {},
	};

	constructor(container: HTMLElement, options?: DropdownFiltersOptions) {
		super();

		this.container = container;
		this.fields = options?.fields || [];
		this.headline = options?.headline || 'Filters';
		this.options = options || {};

		this.element = Object.assign(document.createElement('div'), {
			className: `filters ${this.options.class || ''}`,
			role: 'menubar',
			ariaLabel: this.headline,
		});
	}

	init() {
		this.render();
		this.handleOutsideClick();
	}

	renderFilterOptions(filterField: DropdownFilterField): HTMLLIElement[] {
		const initialOption =
			this.options.initialFilters &&
			filterField.id in this.options.initialFilters
				? this.options.initialFilters[filterField.id]
				: '';

		// Prepend "All" option
		const options = [
			['', filterField.allLabel],
			...Object.entries(filterField.options),
		];

		return options.map(([filterOptionValue, filterOptionLabel]) => {
			const optionElementHtml = `
					<li class="filter__navigation__item"
						data-option-value="${filterOptionValue}"
						role="menuitemcheckbox"
						aria-checked="${initialOption === filterOptionValue}">
						${filterOptionLabel}
					</li>
					`;

			const optionElement = Object.assign(
				document.createElement('template'),
				{
					innerHTML: optionElementHtml.trim(),
				}
			).content.firstChild as HTMLLIElement;

			return optionElement;
		});
	}

	renderFilter(filterField: DropdownFilterField): HTMLLIElement {
		const hasActiveFilter = (this.options?.initialFilters || {})[
			filterField.id
		]
			? true
			: false;
		const buttonLabel = hasActiveFilter
			? filterField.options[
					(this.options.initialFilters || {})[
						filterField.id as string
					]
			  ]
			: filterField.buttonLabel;

		const html = `
		<li class="filter" role="none" data-filter-field="${filterField.id}">
			<button class="filter__header"
					role="menuitem"
					aria-haspopup="true"
					aria-expanded="false"
					aria-label="${filterField.allLabel}"
					type="button">
				<span>${buttonLabel}</span>
				<i class="trevor-ti-caret-down"></i>
			</button>

			<div class="filter__content">
				<ul class="filter__navigation"
					role="menu"
					data-option-group="${filterField.id}"
					aria-label="Locations">
				</ul>
			</div>
		</li>
		`;

		const filterFieldElement = Object.assign(
			document.createElement('template'),
			{
				innerHTML: html.trim(),
			}
		).content.firstChild as HTMLLIElement;

		const filterContent = filterFieldElement.querySelector(
			'.filter__navigation'
		);

		const filterOptionsElements = this.renderFilterOptions(filterField);

		// Append options
		filterOptionsElements.forEach((optionElement) =>
			filterContent?.appendChild(optionElement)
		);

		// Bind filter dropdown
		this.bindFilter(filterFieldElement);

		return filterFieldElement;
	}

	render() {
		this.container.appendChild(this.element);

		// Render each filter
		(this.options.fields || []).forEach((field) => {
			const fieldElement = this.renderFilter(field);
			this.element.appendChild(fieldElement);
		});
	}

	bindFilter(filterElement: HTMLElement) {
		const filterOptions =
			filterElement.querySelector(`.filter__navigation`);
		const filterButton = filterElement.querySelector(`.filter__header`);

		mobileAndTablet(
			() => {
				$(filterButton).on('click', function (e) {
					e.stopPropagation();

					$(filterElement).toggleClass('filter--expanded');

					if (
						$(filterElement)
							.siblings('.filter')
							.hasClass('filter--expanded')
					) {
						$(filterElement)
							.siblings('.filter')
							.removeClass('filter--expanded');
					}
				});
			},
			() => {
				$(filterElement).hover(
					function () {
						$(this).addClass('filter--expanded');

						filterButton.setAttribute(
							'aria-expanded',
							filterElement.classList.contains('filter--expanded')
						);
					},
					function () {
						$(this).removeClass('filter--expanded');

						filterButton.setAttribute('filter--expanded', false);
					}
				);
			}
		);

		const filterDropdown = new FilterNavigationItem(filterOptions, {
			onSelect: this.onFilterSelect.bind(this),
		});
		filterDropdown.init();

		this.filters[filterElement?.dataset?.filterField || ''] =
			filterDropdown;
	}

	handleOutsideClick() {
		// Handle click outside.
		mobileAndTablet(
			() => {
				$(document).click(function (e) {
					if ($('.filter--expanded').length) {
						const elemClasses = e.target.classList;

						if (
							!elemClasses.contains('filter') &&
							!elemClasses.contains('filter__navigation__item') &&
							!elemClasses.contains('filter__header') &&
							!elemClasses.contains('trevor-ti-caret-down')
						) {
							$('.filter--expanded').removeClass(
								'filter--expanded'
							);
						}
					}
				});
			},
			() => {}
		);
	}

	getActiveFilters() {
		const activeFilters: { [key: string]: any[] } = {};

		$(this.element)
			.find(`.filter__navigation__item[aria-checked="true"]`)
			.each((index, el) => {
				const $item = $(el);
				const value = $item.data('option-value') as string;

				if (value) {
					const group = $item
						.closest(`.filter__navigation`)
						.data('option-group');
					activeFilters[group] = [];
					activeFilters[group].push(value);
				}
			});
		return activeFilters;
	}

	onFilterSelect() {
		if (typeof this.options.onChange === 'function') {
			const activeFilters: { [key: string]: string } = {};

			Object.entries(this.getActiveFilters()).forEach(([key, values]) => {
				if (values && values[0]) {
					activeFilters[key] = values[0] as string;
				}
			});
			this.options.onChange(activeFilters);
		}
	}
}
