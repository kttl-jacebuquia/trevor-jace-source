/*
	Map generation codes provided by the client.
	Changes applied to the original codes:
	- Extracted hardcoded google sheets credentials and moved to BE
	- Moved Google Sheets request from Highcharts.data to WP AJAX
	- Updated null equations which are now empty strings due to AJAX implementation
	- Added chart load event handler
	- Disable mapNavigation.enableMouseWheelZoom option
*/
import $ from 'jquery';

/*
Fixme: Do not hardcode keys
Fixme: Get elements via arguments, do not use hardcoded id selectors
 */
export default function (moduleElement) {
	const Highcharts = window.Highcharts;

	const ectMapContainer = moduleElement.querySelector('.ect-map__map');
	const downloadButton = moduleElement.querySelector('.ect-map__download');
	const mapForm = moduleElement.querySelector('.ect-map__search');
	const [...filters] = moduleElement.querySelectorAll('.ect-map__filter');
	const PDF_FILENAME = 'Ending Conversion Therapy Map';

	var regulationPattern =
		'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAIAAAC0Ujn1AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAARhJREFUeNqs1MkKwjAQBuD2t2rBi6KIeih48f3fxbMIggvuKMW11gFFSrSTmdg55JDlYxLa3x+OJl5OlYBB1KXRE1R8voxny+wMdyx5PBabvcSlnfP1zpi0dLQ7xtPV1upSv+frTUdb9TxXRDM640rpnzrvKmhDt7pUgacp0mnsNOtW902HlXK31Zgs1tSLRD/EJ8lOkNvvtWthlUbh3yFxqfx7knxEuiPdVHiSL+rYT9M0O1WI/noJ/JwVvgzjkgBm7R8397t2041TYPZFnZbcJdHoBqqcVCUwtHkmzxkU5X7rKNA19KBYN5tiUOWkSg9UOamqQJWTqgSGKidVCQy3/1iSBHDLB4kON1eiw9m1ruIfl9/zFGAA/6QAGrgarkQAAAAASUVORK5CYII=';
	var introducedPattern =
		'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAIAAAC0Ujn1AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAANxJREFUeNq01ssOwiAQBdBCWttqXFgf0YX//1kuXPhKNDGpj1qCEE21BCiFmbtkcXIDyQyEcx4Zci1v+/MlckuaJOvFnFLSnFA4d/bvGmkvV6U09P1Zhbsa+lXX2+Mp3FVp4W52BxC3RcO6IjHgu2laY7iSRnIljeSKxhTJFY0pkmubIYFuN+3tdtAhro0OdI10uKunQVwNDeWqNKDbomHdHw3ufmkMV66CPB2I/4PjkHpUPTYyYYyB9/3cBLH8nrzd8TBfFpMYw11NC6eh6ud2073cUZY1rshbgAEADd+9Bu55XrUAAAAASUVORK5CYII=';
	var mapData = Highcharts.maps['countries/us/us-all'],
		stateFilter,
		updatedDate,
		data1 = [],
		data2 = [],
		data3 = [],
		chart,
		statesList,
		municipalitiesList1,
		municipalitiesList2;

	if ($(window).width() > 560) {
		var align = 'left',
			verticalAlign = 'bottom',
			floating = true,
			layout = 'vertical',
			tooltipW = 550;
	} else {
		var align = 'center',
			verticalAlign = 'top',
			floating = false,
			layout = 'horizontal',
			tooltipW = $(window).width() - 60;
	}

	const onSheetsFetchError = (err) => {
		console.error(err);

		$('#container').html(
			'<div class="loading">' +
				'<i class="icon-frown icon-large"></i> ' +
				'Error loading data from Google Spreadsheets' +
				'</div>'
		);
	};

	const processStatesDataColumns = (columns) => {
		$.each(columns[0], function (i, code) {
			var legType = columns[2][i];
			data1.push({
				code: 'us-' + code.toLowerCase(),
				valueName: columns[2][i],
				stateName: columns[1][i],
				note: columns[4][i],
				id: 'us-' + code.toLowerCase(),
			});
			if (legType == 'Protected') {
				data1[i].value = 2;
				data1[i].explainer =
					'protects LGBTQ young people from the dangers of conversion therapy.';
			} else if (legType == 'Introduced') {
				data1[i].value = 1;
				data1[i].explainer =
					'has legislation introduced in the state house that would protect LGBTQ young people from conversion therapy, if it passes.';
			} else if (legType == 'Partially Protected') {
				data1[i].value = 3;
				data1[i].explainer =
					'partially protects LGBTQ young people from conversion therapy.';
			}
			if (!columns[2][i]) {
				data1[i].valueName = 'No Legislation';
				data1[i].value = 0;
				data1[i].explainer =
					'offers no state-wide protections for LGBTQ young people and they can be subjected to conversion therapy.';
			}
			if (columns[3][i]) {
				data1[i].yearPassed = 'in ' + columns[3][i];
			}
		});
		var options = {
			weekday: 'long',
			year: 'numeric',
			month: 'long',
			day: 'numeric',
		};
		var updated = data1[52]['note'];
		updatedDate = formatDate(updated);
	};

	const processMunicipalitiesDataColumns = (columns2) => {
		$.each(columns2[0], function (i, code) {
			if (columns2[2][i] == 'Local Protections') {
				data2.push({
					lat: columns2[11][i],
					lon: columns2[12][i],
					ST: columns2[0][i],
					locale: columns2[1][i],
					note2: columns2[4][i],
					explainer:
						'protects LGBTQ young people who live in the community from the dangers of conversion therapy.',
				});
			}
			if (columns2[2][i] == 'Blocked by Courts') {
				data3.push({
					lat: columns2[11][i],
					lon: columns2[12][i],
					ST: columns2[0][i],
					locale: columns2[1][i],
					note2: columns2[4][i],
					explainer:
						'has laws to protect LGBTQ young people from the dangers of conversion therapy but they are currently not in effect by order of the courts.',
				});
			}
		});
	};

	function createChart() {
		$('#container').highcharts('Map', {
			title: {
				text: '',
			},
			credits: {
				enabled: false,
			},
			chart: {
				backgroundColor: 'transparent',
				padding: 0,
				animation: false,
				events: {
					render: renderLabel,
					load: onChartLoad,
				},
				style: {
					fontFamily: 'Manrope',
				},
				// styledMode: true,
			},
			plotOptions: {
				series: {
					grouping: false,
					dataLabels: {
						enabled: true,
						color: '#000',
						y: -6,
						style: {
							fontWeight: 'bold',
						},
					},
				},
			},
			mapNavigation: {
				enabled: true,
				enableMouseWheelZoom: false,
			},
			legend: {
				margin: 0,
				title: {
					style: {
						color: '#003A48',
						fontWeight: 'bold',
					},
				},
				itemStyle: {
					color: '#003A48',
					fontSize: 13,
				},
				padding: 15,
				valueDecimals: 0,
				backgroundColor: 'rgba(243,243,247,0.8)',
				symbolRadius: 2,
				align: align,
				verticalAlign: verticalAlign,
				floating: floating,
				layout: layout,
			},
			colorAxis: {
				dataClasses: [
					{
						from: 2,
						to: 2,
						name: 'Protected',
						color: '#D3DDE2',
					},
					{
						from: 3,
						to: 3,
						name: 'Partially Protected',
						color: {
							pattern: regulationPattern,
							width: 6,
							height: 6,
						},
					},
					{
						from: 1,
						to: 1,
						color: {
							color: 'rgba(0,94,103,0.5)',
							pattern: introducedPattern,
							width: 6,
							height: 6,
						},
						name: 'Introduced',
					},
					{
						from: 0,
						to: 0,
						color: '#FFF',
						name: 'No Legislation',
					},
				],
			},
			tooltip: {
				backgroundColor: '#003A48',
				borderWidth: 0,
				borderColor: '#777',
				padding: 15,
				borderRadius: 10,
				style: {
					fontSize: '16px',
					color: 'white',
					zIndex: 10,
					width: tooltipW,
				},
				useHTML: true,
			},
			exporting: {
				filename: 'TTP CT Map ' + updatedDate,
				menuItemDefinitions: {
					tabular: {
						onclick: function () {
							window.open(
								'https://docs.google.com/spreadsheets/d/1L0pIBS2QJOKz1nMkHzo_ZvdB8oOYkXVEPKHpm2kswDo/view'
							);
						},
						text: 'View Data in Table View',
					},
				},
				buttons: {
					contextButton: {
						menuItems: [
							'tabular',
							'separator',
							'downloadPNG',
							'downloadSVG',
							'downloadPDF',
						],
					},
				},
				chartOptions: {
					chart: {
						width: 1200,
						height: 630,
						style: {
							fontFamily: 'Helvetica',
						},
					},
				},
			},
			series: [
				{
					name: 'Basemap',
					type: 'map',
					mapData: mapData,
					allAreas: true,
					showInLegend: false,
					zIndex: 1,
					color: 'white',
				},
				{
					data: data2,
					zIndex: 4,
					type: 'mappoint',
					colorAxis: true,
					allAreas: false,
					name: 'Local Protections',
					color: 'rgba(0,94,103,0.5)',
					showInLegend: true,
					tooltip: {
						pointFormat:
							'{point.locale}, {point.ST}<br>' +
							'<div style="font-size:.8em;"><div class="explainer">{point.locale} {point.explainer}</div><div class="note">{point.note2}</div></div>',
					},
				},
				{
					data: data3,
					zIndex: 4,
					type: 'mappoint',
					colorAxis: true,
					allAreas: false,
					name: 'Blocked by Courts',
					color: '#FFD215',
					showInLegend: true,
					tooltip: {
						pointFormat:
							'{point.locale}, {point.ST}<br>' +
							'<div style="font-size:.8em;"><div class="explainer">{point.locale} {point.explainer}</div><div class="note">{point.note2}</div></div>',
					},
				},
				{
					data: data1,
					mapData: mapData,
					type: 'map',
					borderWidth: 1,
					borderColor: 'rgba(0,58,72,0.2)',
					zIndex: 3,
					allAreas: false,
					joinBy: ['hc-key', 'code'],
					name: 'State-Wide Legislation',
					states: {
						hover: {
							color: 'rgba(0,0,0,0.1)',
						},
					},
					dataLabels: {
						enabled: true,
						color: '#003A48',
						format: '{point.stateName}',
						style: {
							// fontWeight: 'thin',
							// textOutline: '1px',
						},
						// useHTML: true,
					},
					tooltip: {
						pointFormat:
							'{point.stateName} — {point.valueName} {point.yearPassed}<div style="font-size:.8em;"><div class="explainer">{point.stateName} {point.explainer}</div><div class="note">{point.note}</div></div>',
					},
				},
			],
		});
	}
	function renderLabel() {
		var label = this.renderer
			.label(
				'<b>TheTrevorProject.org/CTMap</b><br>Last Updated: <i>' +
					updatedDate +
					'</i>'
			)
			.css({
				color: '#003B4A',
			})
			.attr({
				padding: 0,
				'font-size': '0.8em',
				'text-align': 'right',
			})
			.add();

		label.align(
			Highcharts.extend(label.getBBox(), {
				align: 'right',
				textAlign: 'right',
				useHTML: true,
				x: 0, // offset
				verticalAlign: 'bottom',
				y: 0, // offset
			}),
			null,
			'spacingBox'
		);
	}

	function onChartLoad(event) {
		chart = event.target;
		statesList = [...data1];
		municipalitiesList1 = [...data2];
		municipalitiesList2 = [...data3];
	}

	function onDownloadClick(e) {
		e.preventDefault();
		if (chart) {
			chart.exportChart({
				type: 'application/pdf',
				filename: PDF_FILENAME,
			});
		}
	}

	// Just keep this for now in case client reverts back the status filter pills
	function onFilterClick(e) {
		e.preventDefault();

		// Active filter
		e.currentTarget.classList.add('ect-map__filter--active');

		// Deactivate other filters
		filters
			.filter((button) => button !== e.currentTarget)
			.forEach((button) =>
				button.classList.remove('ect-map__filter--active')
			);

		filterMap();
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

	function onFormInput(e) {
		e.preventDefault();
		stateFilter = e.currentTarget.ect_map_search.value;
		filterMap();
	}

	downloadButton.addEventListener('click', onDownloadClick);
	filters.forEach((filterButton) =>
		filterButton.addEventListener('click', onFilterClick)
	);
	mapForm.addEventListener('input', onFormInput);

	// Get states data
	Promise.all([
		fetch(
			'/wp-admin/admin-ajax.php?action=google_sheets&range=States'
		).then((response) => response.json()),
		fetch(
			'/wp-admin/admin-ajax.php?action=google_sheets&range=Municipalities'
		).then((response) => response.json()),
	])
		.then((responsesData) => responsesData.map(({ data }) => data.values))
		.then(([statesDataColumns, municipalitiesDataColumns]) => {
			processStatesDataColumns(statesDataColumns);
			processMunicipalitiesDataColumns(municipalitiesDataColumns);
		})
		.then(createChart)
		.catch(onSheetsFetchError);
}

function formatDate(timestamp) {
	const date = new Date(timestamp);
	const months = [
		'January',
		'February',
		'March',
		'April',
		'May',
		'June',
		'July',
		'August',
		'September',
		'October',
		'November',
		'December',
	];
	return (
		months[date.getMonth()] +
		' ' +
		date.getDate() +
		', ' +
		date.getFullYear()
	);
}
