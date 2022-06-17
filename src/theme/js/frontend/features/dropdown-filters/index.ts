import WithState from '../../WithState';

import type {
	DropdownFilterField,
	DropdownFiltersOptions,
	DropdownFiltersState,
	FilterOptions,
} from './index.d';

export default class DropdownFilters extends WithState<DropdownFiltersState> {
	container: HTMLElement;
	element: HTMLElement;
	fields: DropdownFilterField[];
	headline: string;
	options: DropdownFiltersOptions;

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
	}

	renderFilterOptions(filterOptions: FilterOptions): HTMLLIElement[] {
		return Object.entries(filterOptions).map(
			([filterOptionValue, filterOptionLabel]) => {
				const optionElementHtml = `
					<li class="filter__navigation__item"
						data-option-value="${filterOptionValue}"
						role="menuitemcheckbox"
						aria-checked="true">
						${filterOptionLabel}
					</li>
					`;

				const optionElement = Object.assign(
					document.createElement('template'),
					{
						innerHTML: optionElementHtml.trim(),
					}
				).content.firstChild as HTMLLIElement;

				optionElement.addEventListener('click', (e) =>
					this.onOptionClick(e)
				);

				return optionElement;
			}
		);
	}

	renderFilter(filterField: DropdownFilterField): HTMLLIElement {
		const html = `
		<li class="filter" role="none">
			<button class="filter__header"
					role="menuitem"
					aria-haspopup="true"
					aria-expanded="false"
					aria-label="${filterField.allLabel}"
					type="button">
				<span>${filterField.buttonLabel}</span>
				<i class="trevor-ti-caret-down"></i>
			</button>

			<div class="filter__content">
				<ul class="filter__navigation"
					role="menu"
					data-option-group="locations"
					aria-label="Locations">
					<li class="filter__navigation__item"
						data-option-value=""
						role="menuitemcheckbox"
						aria-checked="true">
						${filterField.allLabel}
					</li>
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

		const filterOptionsElements = this.renderFilterOptions(
			filterField.options
		);

		// Append options
		filterOptionsElements.forEach((optionElement) =>
			filterContent?.appendChild(optionElement)
		);

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

	onOptionClick(e: Event) {
		e.preventDefault();
		console.log('clicked', e.currentTarget);
	}
}
