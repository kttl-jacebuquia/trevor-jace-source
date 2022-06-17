import moment from 'moment-timezone';
import Component from '../../Component';
import { WPAjax } from '../wp-ajax';
import { slugify } from '../slugify';
import { getParams } from '../url';

import type { ClassyEvent, WithFilterValues } from './classy-event.d';
import type { EventsGridStateType, ActiveFiltersState } from './index.d';
import type { FilterOptionProp } from '../dropdown-filters/index.d';

const PARAMS_KEY = 'events-grid';

export default class EventsGrid extends Component<
	HTMLElement,
	EventsGridStateType
> {
	id: string;
	locations: FilterOptionProp[] = [];
	dates: FilterOptionProp[] = [];
	types: FilterOptionProp[] = [];
	events: (ClassyEvent & WithFilterValues)[] = [];
	eventsVisible: (ClassyEvent & WithFilterValues)[] = [];

	static selector = '.events-grid';

	static children = {
		filters: '.events-grid__filters',
		grid: '.events-grid__grid',
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
	}

	async afterInit() {
		await this.fetchItems();
		this.extractFilters();
		this.loadStateFromParams();
	}

	async fetchItems() {
		const { data } = await WPAjax({
			action: 'classy',
			params: { type: 'events' },
		});

		// Save raw events data
		this.events = data;
	}

	extractFilters() {
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

	setFilters(activeFilters: ActiveFiltersState) {
		this.setState({ activeFilters });
		this.appendHistory();
	}

	renderGrid() {
		const { location, date, type } = this.state.activeFilters;
		const activeFilterValues = [location, date, type];

		const eventsFiltered = this.events.filter(
			({ locationFilter, dateFilter, typeFilter }) =>
				[locationFilter, dateFilter, typeFilter].every(
					(filterValue, index) => {
						return (
							!activeFilterValues[index] ||
							activeFilterValues[index] === filterValue
						);
					}
				)
		);

		const renderedCards = eventsFiltered.map((eventData) =>
			this.renderEventCard(eventData)
		);

		console.log({ renderedCards });

		if (this.children?.grid) {
			(this.children.grid as HTMLElement).innerHTML =
				renderedCards.join('');
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
			id
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
		<article class="${classes.join(' ')}" id="${id}">
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
			...getParams(),
			[PARAMS_KEY]: JSON.stringify({
				id: this.id,
				filters,
				page: this.state.page,
			}),
		};

		const url = new URL(window.location.origin + window.location.pathname);
		url.search = new URLSearchParams(params).toString();

		history.pushState('', '', url);
	}

	// Extract filters and pagination from URL params if there is any
	loadStateFromParams() {
		const params = getParams();

		if (PARAMS_KEY in params) {
			const { id, filters, page = 1 } = JSON.parse(params[PARAMS_KEY]);

			// Only use filters from params if ID matches
			if (id === this.id) {
				this.setState({
					activeFilters: filters,
					page,
				});
			}
		} else {
			this.renderGrid();
			this.appendHistory();
		}
	}

	componentDidUpdate() {
		this.renderGrid();
		this.appendHistory();
	}
}

EventsGrid.init();
