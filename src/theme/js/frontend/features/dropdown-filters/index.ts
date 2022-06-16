import type { DropdownFilterField, DropdownFiltersOptions } from './index.d';

export default class DropdownFilters {
	element: HTMLElement;
	fields: DropdownFilterField[];
	headline: string;
	options: DropdownFiltersOptions;

	constructor(element: HTMLElement, options?: DropdownFiltersOptions) {
		this.element = element;
		this.fields = options?.fields || [];
		this.headline = options?.headline || 'Filters';
		this.options = options || {};
	}

	init() {
		this.render();
	}

	render() {
		const html = `
		<ul class="filters ${this.options.class || ''}"
			role="menubar"
			aria-label="${this.headline}">
			<li class="filter" role="none">
				<button class="filter__header"
						role="menuitem"
						aria-haspopup="true"
						aria-expanded="false"
						aria-label="All Locations"
						type="button">
					<span>Locations</span>
					<i class="trevor-ti-caret-down"></i>
				</button>

				<div class="filter__content">
					<ul class="filter__navigation"
						role="menu"
						data-option-group="locations"
						aria-label="Locations">
						<li class="filter__navigation__item"
							data-option-value="all-locations"
							role="menuitemcheckbox"
							aria-checked="true">
							All Locations
						</li>
					</ul>
				</div>
			</li>
		</ul>`;

		this.element.innerHTML = html;
	}
}
