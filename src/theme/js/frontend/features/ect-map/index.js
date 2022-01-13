import $ from 'jquery';
import mapRenderer from './renderer';
import debounce from 'lodash/debounce';

export default function (moduleElement) {
	const mapForm = moduleElement.querySelector('.ect-map__search');
	const mapContainer = moduleElement.querySelector('.ect-map__map');
	const downloadButton = moduleElement.querySelector('.ect-map__download');
	let stateFilter = '',
		sheetKey;

	// Chart-extracted data, update on chart load
	let chart,
		statesList,
		municipalitiesList1,
		municipalitiesList2,
		exportDate = '';

	const chartAccessibilityOptions = {
		description:
			'Interactive map showing legislation status across different states and localities.',
	};

	const mapPointAccessibilityOptions = {
		point: {
			descriptionFormatter: (pointData) => {
				const { explainer, stateName, locale, ST, code } = pointData;

				// Statewide Legislation
				if (stateName) {
					const state = statesList.find(
						(stateData) => code === stateData.code
					);
					return `${state.stateName} ${state.explainer}`;
				}

				// Local Protection
				if (locale) {
					const state = statesList.find(
						({ code }) => code === `us-${ST.toLowerCase()}`
					);
					return `${locale} ${state?.stateName || ''} ${explainer}`;
				}

				return '';
			},
		},
	};

	const onSheetsFetchError = (err) => {
		console.error(err);

		mapContainer.html(
			'<div class="loading">' +
				'<i class="icon-frown icon-large"></i> ' +
				'Error loading data from Google Spreadsheets' +
				'</div>'
		);
	};

	const fetchChartData = async () => {
		// Get states data
		Promise.all([
			window
				.fetch(
					'/wp-admin/admin-ajax.php?action=google_sheets&range=States'
				)
				.then((response) => response.json()),
			window
				.fetch(
					'/wp-admin/admin-ajax.php?action=google_sheets&range=Municipalities'
				)
				.then((response) => response.json()),
			window
				.fetch(
					'/wp-admin/admin-ajax.php?action=google_sheets&get_option=sheet_key'
				)
				.then((response) => response.json()),
		])
			.then(
				([statesRangeData, municipalitiesRangeData, sheetKeyData]) => {
					sheetKey = sheetKeyData.data.sheet_key;

					return [
						statesRangeData.data.values,
						municipalitiesRangeData.data.values,
					];
				}
			)
			.then(([statesDataColumns, municipalitiesDataColumns]) =>
				// Render map using client's codes
				mapRenderer(
					mapContainer,
					statesDataColumns,
					municipalitiesDataColumns
				)
			)
			.then(onChartLoaded)
			.catch(onSheetsFetchError);
	};

	function onChartLoaded([
		chartInstance,
		[data1, data2, data3],
		updatedDate,
	]) {
		chart = chartInstance;
		statesList = [...data1];
		municipalitiesList1 = [...data2];
		municipalitiesList2 = [...data3];
		exportDate = updatedDate;

		applyChartAccessibility();
		updateMapNavigation();
		applyExporting();

		moduleElement.classList.add('ect-map--loaded');
	}

	function applyChartAccessibility() {
		// Update general chart options
		chart.update({
			accessibility: chartAccessibilityOptions,
		});

		// Apply per-series option
		// series[0] is just the base map, no need to update
		chart.series.slice(1).forEach((series) =>
			series.update({
				accessibility: mapPointAccessibilityOptions,
			})
		);
	}

	function updateMapNavigation() {
		chart.update({
			mapNavigation: {
				enableMouseWheelZoom: false,
				enabled: true,
			},
		});
	}

	function applyExporting() {
		const exportButtons = Array.from(
			downloadButton.querySelectorAll('[data-export-type]')
		);

		exportButtons.forEach((button) => {
			button.addEventListener('click', onExportClick);
		});
	}

	// Export functionalities
	// NOTE: Extracted from client's code,
	// restructured to integrate with custom download button
	function onExportClick(e) {
		e.preventDefault();
		const { currentTarget } = e;
		const { exportType } = currentTarget.dataset;
		let type;

		switch (exportType) {
			case 'tabular':
				window.open(
					`https://docs.google.com/spreadsheets/d/${sheetKey}/view`
				);
				return;
			case 'pdf':
				type = 'application/pdf';
				break;
			case 'png':
				type = 'image/png';
				break;
			case 'svg':
				type = 'image/svg+xml';
				break;
		}

		if (type) {
			chart.exportChart({
				type,
				filename: 'TTP CT Map ' + exportDate,
			});
		}
	}

	function filterMap() {
		const statePattern = new RegExp(stateFilter, 'i');
		const filteredStateBySearch = stateFilter
			? statesList.filter(({ stateName, ...stateData }) =>
					statePattern.test(stateName)
			  )
			: statesList;
		const matchedStateAbbrevs = filteredStateBySearch.map(({ code }) =>
			code.split('-')[1].toUpperCase()
		);

		chart.series.forEach((series, index) => {
			let seriesData;

			switch (index) {
				// Filter Municipalities
				case 1:
					seriesData = municipalitiesList1.filter(({ ST }) =>
						matchedStateAbbrevs.includes(ST)
					);
					break;
				case 2:
					seriesData = municipalitiesList2.filter(({ ST }) =>
						matchedStateAbbrevs.includes(ST)
					);
					break;
				// Filter states
				case 3:
					seriesData = statesList.filter(({ stateName }) =>
						statePattern.test(stateName)
					);
					break;
			}

			series.setData(seriesData);
		});
	}

	function onFormInput() {
		stateFilter = mapForm.ect_map_search.value;
		filterMap();
	}

	mapForm.addEventListener('input', debounce(onFormInput, 300));

	// Get and render map data
	fetchChartData();
}
