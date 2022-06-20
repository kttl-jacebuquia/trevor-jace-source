import moment from 'moment-timezone';
import Component from '../../Component';
import { WPAjax } from '../wp-ajax';
import { slugify } from '../slugify';
import { getParams, replaceParams } from '../url';

import type { ClassyEvent, WithFilterValues } from './classy-event.d';
import type { EventsGridStateType, ActiveFiltersState } from './index.d';
import type {
	DropdownFiltersOptions,
	FilterOptionProp,
	DropdownFilterField,
	FilterOptions,
} from '../dropdown-filters/index.d';
import Pagination, { URL_PARAMS_PAGE_PLACEHOLDER } from '../pagination';
import DropdownFilters from '../dropdown-filters';

const PARAMS_KEY = 'events-grid';
const PER_PAGE = 9;

const extractDataFromUrl = (gridID = '') => {
	const params = getParams();

	if (PARAMS_KEY in params) {
		const { id, filters, page = 1 } = JSON.parse(params[PARAMS_KEY]);

		// Only use filters from params if ID matches
		if (id === gridID) {
			return {
				activeFilters: filters,
				page,
			};
		}
	}

	return null;
};

export default class EventsGrid extends Component<
	HTMLElement,
	EventsGridStateType
> {
	id: string;
	locations: FilterOptionProp[] = [];
	dates: FilterOptionProp[] = [];
	types: FilterOptionProp[] = [];
	events: (ClassyEvent & WithFilterValues)[] = [];
	eventsFiltered: (ClassyEvent & WithFilterValues)[] = [];
	pagination?: Pagination;
	filters?: DropdownFilters;
	initialized = false;

	static selector = '.events-grid';

	static children = {
		filters: '.events-grid__filters',
		grid: '.events-grid__grid',
		pagination: '.events-grid__pagination',
		empty: '.events-grid__empty',
		loading: '.events-grid__loading',
	};

	state = {
		activeFilters: {
			location: '',
			date: '',
			type: '',
		},
		page: 1,
	};

	constructor(element: HTMLElement) {
		super(element);
		this.id = this.element.id;

		// Load state from params
		const stateFromParams: EventsGridStateType | null = extractDataFromUrl(
			this.id
		);

		// Load state from params
		if (stateFromParams) {
			this.state.page = stateFromParams?.page || this.state.page;
			Object.entries(stateFromParams?.activeFilters || {}).forEach(
				([key, value]) => {
					this.state.activeFilters[key as keyof ActiveFiltersState] =
						value ||
						this.state.activeFilters[
							key as keyof ActiveFiltersState
						];
				}
			);
		}
	}

	async afterInit() {
		await this.fetchItems();

		if (this.events?.length) {
			this.extractFiltersFromEvents();
			this.renderGrid();
			this.initializePagination();
			this.initializeFilters();
		} else {
			this.showEmpty();
		}

		this.element.classList.remove('events-grid--loading');
		(this.children?.loading as HTMLElement)?.classList.add('hidden');

		this.initialized = true;
	}

	async fetchItems() {
		const { data } = await WPAjax({
			action: 'classy',
			params: { type: 'events' },
		});

		// Save raw events data
		this.events = data;
	}

	extractFiltersFromEvents() {
		// Temporary store for filter values to avoid duplicates
		const locationSlugs: string[] = [];
		const dateValues: string[] = [];
		const typeValues: string[] = [];

		this.events.forEach(
			({ city, state, type, started_at: startedAt }, index) => {
				// Build location filter value and label
				const locationName = [city, state].filter(Boolean).join(', ');
				const locationSlug = slugify(locationName);

				if (locationSlug) {
					// Save location filters
					if (!locationSlugs.includes(locationSlug)) {
						locationSlugs.push(locationSlug);
						this.locations.push({
							value: locationSlug,
							label: locationName,
						});
					}
					this.events[index].locationFilter = locationSlug;
				}

				// Build date filter value and label
				const momentInstance = moment.utc(startedAt);
				const monthYearValue = momentInstance.format('YYYY-MM');
				const monthYearLabel = momentInstance.format('MMMM YYYY');
				// Save date filter
				if (!dateValues.includes(monthYearValue)) {
					dateValues.push(monthYearValue);
					this.dates.push({
						value: monthYearValue,
						label: monthYearLabel,
					});
				}
				this.events[index].dateFilter = monthYearValue;

				// Build type filter value and label
				const typeLabel = this.typeToTitle(type || '');

				// Save date filter
				if (!typeValues.includes(type || '')) {
					typeValues.push(type || '');
					this.types.push({
						value: type || '',
						label: typeLabel,
					});
				}
				this.events[index].typeFilter = type || '';
			}
		);
	}

	initializePagination() {
		if (this.children?.pagination) {
			const options = {
				totalPages: Math.ceil(this.eventsFiltered.length / PER_PAGE),
				currentPage: this.state.page,
				onChange: this.onPaginationPageChange.bind(this),
			};

			this.pagination = new Pagination(
				this.children.pagination as HTMLElement,
				options
			);

			this.pagination.init();
		}
	}

	initializeFilters() {
		if (this.children?.filters) {
			const options: DropdownFiltersOptions = {
				fields: this.generateDropdownFilterFields(),
				onChange: this.onDropdownFilterChange.bind(this),
				initialFilters: this.state.activeFilters,
			};

			this.filters = new DropdownFilters(
				this.children.filters as HTMLElement,
				options
			);

			this.filters.init();
		}
	}

	setFilters(activeFilters: ActiveFiltersState) {
		this.setState({ activeFilters });
		this.appendHistory();
	}

	renderGrid() {
		const { location, date, type } = this.state.activeFilters;
		const activeFilterValues = [location, date, type];
		const from = (Number(this.state.page) - 1) * PER_PAGE;
		const to = from + PER_PAGE;
		const gridContainer = this.children?.grid as HTMLElement;

		this.eventsFiltered = this.events.filter(
			({ locationFilter, dateFilter, typeFilter }) => {
				return [locationFilter, dateFilter, typeFilter].every(
					(filterValue, index) => {
						return (
							!activeFilterValues[index] ||
							activeFilterValues[index] === filterValue
						);
					}
				);
			}
		);

		const eventsPaged = this.eventsFiltered.slice(from, to);

		const renderedCards = eventsPaged.map((eventData) =>
			this.renderEventCard(eventData)
		);

		if (gridContainer) {
			gridContainer.innerHTML = renderedCards.join('');
		}
	}

	renderEventCard(event: ClassyEvent & WithFilterValues) {
		const {
			team_cover_photo_url: thumbnail,
			type,
			canonical_url: url,
			name,
			started_at: startDate,
			timezone_identifier: timezone,
			address1,
			locationFilter,
			id,
		} = event;

		const { label: matchedLocation = '' } =
			this.locations.find(({ value }) => locationFilter === value) || {};
		const locationLabel = [address1, matchedLocation]
			.filter(Boolean)
			.join('<span class="event-location__separator"></span>');

		const dateTime = moment(startDate)
			.tz(timezone || '')
			.format('MMM DD, YYYY, h:MM A z');

		const labelHtml = this.renderType(type || '');
		const thumbnailHtml = this.renderThumbnail(
			thumbnail || '',
			url,
			name || ''
		);
		const titleTopHtml = this.renderEyebrow(dateTime);
		const locationHtml = this.renderLocation(locationLabel);
		const classes = [
			'events-grid__card card-post event trevor_event type-trevor_event',
			thumbnail ? 'has-post-thumbnail' : 'no-thumbnail',
		];

		return `
		<article class="${classes.join(
			' '
		)}" id="${id}" tabindex="0" aria-label="${name}">
			${labelHtml}
			<div class="card-content relative">
				<div class="card-text-container relative flex flex-col flex-initial md:flex-auto">
					${thumbnailHtml}
					${titleTopHtml}
					<h3 class="post-title">
						<a href="${url}" class="stretched-link">${name}</a>
					</h3>
					${locationHtml}
				</div>

				<a href="${url}" target="_blank" rel="noreferer" class="absolute top-0 left-0 ${'h-full w-full z-1'.replace(
			/.*/,
			''
		)}"></a>
			</div>
		</article>`;
	}

	renderLocation(locationLabel = '') {
		return `<div class="event-location">${locationLabel}</div>`;
	}

	renderEyebrow(eyebrow = '') {
		return eyebrow ? `<div class="title-top">${eyebrow}</div>` : '';
	}

	renderType(type: string) {
		return type
			? `<span class="card-label">${this.typeToTitle(type)}</span>`
			: '';
	}

	renderThumbnail(thumbnail?: string, url: string = '#', alt: string = '') {
		return thumbnail
			? `
				<div class="post-thumbnail-wrap">
					<a href="${url}">
						<img src="${thumbnail}" alt="Image for ${alt}"/>
					</a>
				</div>
	`
			: '';
	}

	typeToTitle(type: string = '') {
		return type.replace(/(^[a-z]|_[a-z])/gi, (match) =>
			match.toUpperCase().replace('_', ' ')
		);
	}

	// Updates history by including filters as parameters
	appendHistory() {
		const params = this.stateAsParams();
		const url = replaceParams(params);
		history.pushState('', '', url);
	}

	stateAsParams() {
		const filters = Object.entries(this.state.activeFilters)
			.filter(([key, value]) => value)
			.reduce(
				(validFilters, [key, value]) => ({
					...validFilters,
					[key]: value,
				}),
				{}
			);

		const params = {
			[PARAMS_KEY]: JSON.stringify({
				id: this.id,
				filters,
				page: this.state.page,
			}),
		};

		return params;
	}

	generateDropdownFilterFields(): DropdownFilterField[] {
		const fields: DropdownFilterField[] = [
			{
				id: 'type',
				buttonLabel: 'Event Type',
				allLabel: 'All Event Types',
				options: this.types.reduce((allOptions, { value, label }) => {
					allOptions[value] = label;
					return allOptions;
				}, {} as FilterOptions),
			},
			{
				id: 'location',
				buttonLabel: 'Location',
				allLabel: 'All Locations',
				options: this.locations.reduce(
					(allOptions, { value, label }) => {
						allOptions[value] = label;
						return allOptions;
					},
					{} as FilterOptions
				),
			},
			{
				id: 'date',
				buttonLabel: 'Date',
				allLabel: 'All Dates',
				options: this.dates.reduce((allOptions, { value, label }) => {
					allOptions[value] = label;
					return allOptions;
				}, {} as FilterOptions),
			},
		];

		return fields;
	}

	updatePagination() {
		if (this.pagination) {
			const currentParams = this.stateAsParams();

			if (!(PARAMS_KEY in currentParams)) {
				currentParams[PARAMS_KEY] = '{}';
			}

			const eventsGridData = JSON.parse(currentParams[PARAMS_KEY]);
			eventsGridData.page = URL_PARAMS_PAGE_PLACEHOLDER;
			currentParams[PARAMS_KEY] = JSON.stringify(eventsGridData);

			this.pagination.setURLTemplate(replaceParams(currentParams));
		}
	}

	renderUpdates(reloadPagination = false) {
		this.renderGrid();

		// Initial render doesn't need history update
		if (this.initialized) {
			this.appendHistory();
		}

		if (reloadPagination) {
			this.pagination?.setTotalPages(
				Math.ceil(this.eventsFiltered.length / PER_PAGE)
			);
		}

		this.updatePagination();
	}

	showEmpty() {
		(this.children?.empty as HTMLElement)?.classList.remove('hidden');
	}

	onPaginationPageChange(page: number) {
		this.setState({ page });
		this.element.focus();
	}

	onDropdownFilterChange(activeFilters: FilterOptions) {
		this.setState({ activeFilters, page: 1 });
	}

	componentDidUpdate(changedState: Partial<EventsGridStateType>) {
		this.renderUpdates('activeFilters' in changedState);
	}
}

EventsGrid.init();
