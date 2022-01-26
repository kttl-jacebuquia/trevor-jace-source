import mapRenderer from './renderer';

const DOWNLOAD_BUTTON_CLASS_EXPANDED = 'ect-map__download--expanded';

export default function (moduleElement) {
	const mapContainer = moduleElement.querySelector('.ect-map__map');
	const downloadButton = moduleElement.querySelector('.ect-map__download');
	let sheetKey;

	// Chart-extracted data, update on chart load
	let chart,
		statesList,
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

	function onChartLoaded([chartInstance, [data1], updatedDate]) {
		chart = chartInstance;
		statesList = [...data1];
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
		downloadButton.addEventListener('click', onDownloadClick);
		document.body.addEventListener('click', () =>
			toggleDownloadOptions(false)
		);

		const exportButtons = Array.from(
			downloadButton.querySelectorAll('[data-export-type]')
		);

		exportButtons.forEach((button) => {
			button.addEventListener('click', onExportClick);
		});
	}

	function onDownloadClick(e) {
		e.preventDefault();
		e.stopPropagation();
		toggleDownloadOptions();
	}

	function toggleDownloadOptions(willExpand = null) {
		const shouldExpand =
			typeof willExpand === 'boolean'
				? willExpand
				: !downloadButton.classList.contains(
						DOWNLOAD_BUTTON_CLASS_EXPANDED
				  );
		downloadButton.classList.toggle(
			DOWNLOAD_BUTTON_CLASS_EXPANDED,
			shouldExpand
		);
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

	// Get and render map data
	fetchChartData();
}
