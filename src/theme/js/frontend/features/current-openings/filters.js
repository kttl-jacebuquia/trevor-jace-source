import $ from 'jquery';
import FilterNavigationItem from './filter-navigation-item';
import { mobileAndTablet } from './../../match-media';

const SELECTOR = `.filter`;

export function initFilterNavigation(context) {
	$(context)
		.find(SELECTOR)
		.each((index, el) => {
			const filterOptions = el.querySelector(`${SELECTOR}__navigation`);
			const filterButton = el.querySelector(`${SELECTOR}__header`);

			mobileAndTablet(
				() => {
					$(filterButton).on('click', function(e) {
						e.stopPropagation();

						$(el).toggleClass('filter--expanded');

						if ($(el).siblings('.filter').hasClass('filter--expanded')) {
							$(el).siblings('.filter').removeClass('filter--expanded');
						}
					});
				},
				() => {
					$(el).hover(function() {
						$(this).addClass('filter--expanded');

						filterButton.setAttribute(
							'aria-expanded',
							el.classList.contains('filter--expanded')
						);
					}, function() {
						$(this).removeClass('filter--expanded');

						filterButton.setAttribute('filter--expanded', false);
					});
				}
			)

			const filterDropdown = new FilterNavigationItem(filterOptions);
			filterDropdown.init();
		});

	// Handle click outside.
	mobileAndTablet(
		() => {
			$(document).click(function(e) {
				if ($('.filter--expanded').length) {
					const elemClasses = e.target.classList;

					if (
						!elemClasses.contains('filter')
						&& !elemClasses.contains('filter__navigation__item')
						&& !elemClasses.contains('filter__header')
						&& !elemClasses.contains('trevor-ti-caret-down')
					) {
						$('.filter--expanded').removeClass('filter--expanded');
					}
				}
			});
		},
		() => {}
	);
}

export function getFiltersByGroup(context, group) {
	const filters = [];
	$(context)
		.find(`[data-option-group="${group}"] ${SELECTOR}__navigation__item`)
		.each((index, el) => {
			const value = $(el).data('option-value');
			const isAllOption = value.indexOf('all-') >= 0;
			if (!isAllOption) {
				filters.push(value);
			}
		});
	return filters;
}

export function getActiveFilters(context) {
	const activeFilters = {};
	$(context)
		.find(`${SELECTOR}__navigation__item[aria-checked="true"]`)
		.each((index, el) => {
			const $item = $(el);
			const value = $item.data('option-value');
			const group = $item
				.closest(`${SELECTOR}__navigation`)
				.data('option-group');
			activeFilters[group] = [];
			activeFilters[group].push(value);
		});
	return activeFilters;
}
