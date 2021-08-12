import $ from 'jquery';
import { initFilterNavigation } from './filters';
import initListing from './listing';
import moment from 'moment';

class ADPContent {
	constructor(context) {
		this.context = context;
		this.$context = $(this.context);

		this.$filterLocations = this.$context.find(
			`[data-option-group="locations"]`
		);
		this.$filterDepartments = this.$context.find(
			`[data-option-group="departments"]`
		);

		this.endpoint = this.$context.data('endpoint');

		this.$listingInfo = this.$context.find(`.listing__info`);
		this.$listingContent = this.$context.find(`.listing__content`);
	}

	init() {
		this.initializeContent();
	}

	initializeContent() {
		$.getJSON(this.endpoint, (response) => {
			const data = response.data;

			this.populateListingInfo(this.$listingInfo, data.total_jobs);

			this.populateFilterItems(this.$filterLocations, data.locations);
			this.populateFilterItems(this.$filterDepartments, data.departments);
			initFilterNavigation(this.context);

			this.populateJobItems(this.$listingContent, data.jobs);
			initListing(this.context);
		});
	}

	populateListingInfo($container, total) {
		const text = total > 1 ? 'jobs' : 'job';
		$container.html(`Currently viewing <span>${total} ${text}</span>`);
	}

	populateFilterItems($container, items) {
		items.forEach((item) => {
			$container.append(`
				<li class="filter__navigation__item"
					data-option-value="${this.sanitizeOptionValue(item)}"
					role="menuitemcheckbox"
					aria-checked="false">
					${item}
				</li>
			`);
		});
	}

	populateJobItems($container, items) {
		items.forEach((item) => {
			$container.append(this.generateJobItemMarkup(item));
		});
	}

	generateJobItemMarkup(item) {
		const departments = this.getDepartments(item.organizationalUnits);
		const locations = this.getLocations(item.requisitionLocations);
		const classes = this.generateJobItemClasses(departments, locations);
		return `
			<div class="listing__item show ${classes.join(' ')}">
				<div class="listing__item-inner">
					${this.generateEyebrowMarkup(departments, locations)}
					${this.generateTitleMarkup(item)}
					<time class="listing__item__date">
						${this.getDateAgo(item.requisitionStatusCode.effectiveDate)}
					</time>
				</div>
				<div class="listing__item__cta">
					<a href="${item.links[item.links.length - 1].href}"
					target="_blank">Apply Now</a>
				</div>
			</div>
		`;
	}

	getDepartments(units) {
		const departments = [];
		units.forEach((unit) => {
			if ('Department' !== unit.typeCode.codeValue) {
				return;
			}
			const shortName = unit.nameCode
				? unit.nameCode.shortName
				: undefined;
			const longName = unit.nameCode ? unit.nameCode.longName : undefined;
			const department = shortName ?? longName;
			if (department) {
				departments.push(department);
			}
		});
		return departments;
	}

	getLocations(requisitionLocations) {
		const locations = [];
		requisitionLocations.forEach((requisitionLocation) => {
			const shortName = requisitionLocation.nameCode
				? requisitionLocation.nameCode.shortName
				: undefined;
			const longName = requisitionLocation.nameCode
				? requisitionLocation.nameCode.longName
				: undefined;
			const address = requisitionLocation.address.countryCode;
			const location = shortName ?? longName ?? address;
			if (location) {
				locations.push(location);
			}
		});
		return locations;
	}

	generateJobItemClasses(departments, locations) {
		const classes = [];
		[...departments, ...locations].forEach((value) => {
			classes.push(this.sanitizeOptionValue(value));
		});
		return classes;
	}

	generateEyebrowMarkup(departments, locations) {
		let markup = `<p class="listing__item__eyebrow">`;
		[...departments, ...locations].forEach((item) => {
			markup += `<span>${item}</span>`;
		});
		markup += `</p>`;
		return markup;
	}

	generateTitleMarkup(item) {
		const workTypeMarkup = item.workerTypeCode
			? `<span>(${item.workerTypeCode.shortName})</span>`
			: '';
		return `<h3 class="listing__item__title">
					${item.job.jobTitle}
					${workTypeMarkup}
				</h3>
		`;
	}

	sanitizeOptionValue(string) {
		return string.replaceAll(',', '').replaceAll(' ', '-').toLowerCase();
	}

	getDateAgo(value) {
		const date = moment(value),
			now = moment(),
			days = now.diff(date, 'days');

		return days > 1
			? `${days} ${days === 1 ? ' day' : ' days'} ago`
			: `just now`;
	}
}

export default function initContent(context) {
	const content = new ADPContent(context);
	content.init();
}
