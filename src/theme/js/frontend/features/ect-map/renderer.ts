/*
	Map generation codes provided by the client.
	Changes applied to the original codes:
	- Removed Highcharts.data usage
		- Extracted hardcoded google sheets credentials and moved to BE
		- Extracted parsed data processor for states and municipalities data
	- Moved Google Sheets request from Highcharts.data to WP AJAX
	- Updated null equations which are now empty strings due to AJAX implementation
	- Replaced #container selector string with an HTMLElement
	- On chart load, use viewport width instead of chartWidth to determine legend layout
	- Extract exporting logic to integrate with custom button (Download Map)
*/
import $ from 'jquery';
const Highcharts = window.Highcharts;

const renderer = async (
	mapContainer: HTMLElement,
	rawStatesData: any[],
	rawMunicipalitiesData: any[],
) => {
	return new Promise((resolve) => {
		/*
			CLIENT CODE STARTS HERE

			NOTE:
			Only minimal changes should be made from the client codes
			in order to maintain the fewest possible changes as possible
			for future updates. Necessary changes should be listed in the
			top most comment of this file.
		*/

		let chart, updatedDate;
		$(document).ready(function () {
			let mapData = Highcharts.maps['countries/us/us-all'],
				data1 = [],
				data2 = [],
				data3 = [];

			let align = 'left',
				verticalAlign = 'bottom',
				floating = false,
				layout = 'horizontal',
				tooltipW = $(window).width() - 60,
				yoffset = -100;

			if ($(window).width() > 560) {
				align = 'left';
				verticalAlign = 'bottom';
				floating = false;
				layout = 'vertical';
				tooltipW = 550;
				yoffset = 0;
			}

			// Process states data
			// Extracted from client codes
			((columns) => {
				$.each(columns[0], function (i, code) {
					let legType = columns[2][i];
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
					if (columns[2][i] == '') {
						data1[i].valueName = 'No Legislation';
						data1[i].value = 0;
						data1[i].explainer =
							'offers no state-wide protections for LGBTQ young people and they can be subjected to conversion therapy.';
					}
					if (columns[3][i] != '') {
						data1[i].yearPassed = 'in ' + columns[3][i];
					}
				});
				let options = {
					weekday: 'long',
					year: 'numeric',
					month: 'long',
					day: 'numeric',
				};
				let updated = data1[52]['note'];
				updatedDate = formatDate(updated);
			})(rawStatesData);

			// Process municipalities data
			// Extracted from client codes
			((columns2) => {
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
			})(rawMunicipalitiesData);

			function createChart() {
				$(mapContainer).highcharts('Map', {
					title: {
						text: '',
					},
					credits: {
						enabled: false,
					},
					chart: {
						backgroundColor: 'transparent',
						padding: 0,
						margin: 0,
						animation: false,
						events: {
							render: renderLabel,
							load() {
								let chart = this,
									legend = chart.legend;

								if (window.innerWidth < 400) {
									legend.update({
										layout: 'horizontal',
									});
								}

								// Allow external logic to consume
								// chart extracted data
								resolve([chart, [data1, data2, data3], updatedDate]);
							},
						},
						style: {
							fontFamily: 'Manrope',
						},
					},
					defs: {
						patterns: [
							{
								id: 'introduced',
								path: {
									d: 'm0 0 11 11m-7-12 7 7m-12-2 7 7',
									stroke: '#003A48',
									strokeWidth: 2,
								},
								width: 10,
								height: 10,
							},
							{
								id: 'regulation',
								path: {
									d: 'm0 0 10 10m0-10-10 10',
									stroke: '#005E67',
									strokeWidth: 2,
								},
								width: 10,
								height: 10,
							},
						],
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
					},
					legend: {
						margin: 0,
						title: {
							text: '<i>Click to Toggle Visibility</i>',
							style: {
								color: '#003A48',
								fontWeight: 'normal',
							},
						},
						itemStyle: {
							color: '#003A48',
							fontSize: '14px',
						},
						padding: 10,
						valueDecimals: 0,
						backgroundColor: 'rgba(243,243,247,0.8)',
						symbolRadius: 2,
						align: align,
						verticalAlign: verticalAlign,
						floating: false,
						layout: layout,
					},
					colorAxis: {
						dataClasses: [
							{
								from: 2,
								to: 2,
								name: 'Protected',
								color: '#7B989F', //rgba(0,58,72,0.5)
							},
							{
								from: 3,
								to: 3,
								name: 'Partially Protected',
								color: '#B9CBE0', //rgba(0,71,157,0.25
								// color: "url(#regulation)"
							},
							{
								from: 1,
								to: 1,
								color: '#B9C8CB', //rgba(0,58,72,0.25)
								// color: "url(#introduced)",
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
						className: 'hover',
						followTouchMove: false,
						backgroundColor: '#003A48',
						borderWidth: 0,
						borderColor: '#777',
						padding: 15,
						borderRadius: 10,
						style: {
							fontSize: '12px',
							color: 'white',
							zIndex: 10,
							width: tooltipW,
						},
						useHTML: true,
					},
					navigation: {
						buttonOptions: {
							align: 'right',
							theme: {
								fill: 'white',
								padding: 10,
								style: {
									fontWeight: 'bold',
									color: '#005E67',
									fontSize: '17px',
								},
							},
						},
					},
					exporting: {
						filename: 'TTP CT Map ' + updatedDate,
						buttons: {
							contextButton: {
								enabled: false,
							},
						},
						chartOptions: {
							legend: {
								title: {
									text: '',
								},
							},
							title: {
								text: 'States and local municipalities where licensed medical professionals are prohibited from practicing conversion therapy on minors.',
							},
							chart: {
								width: 1200,
								height: 630,
								style: {
									fontFamily: 'Arial',
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
							color: 'rgba(0,96,104,0.5)',
							showInLegend: true,
							tooltip: {
								pointFormat:
									"<h2 style='font-size:16px;'><b>{point.locale}</b>, {point.ST}</h2><div class='explainer'>{point.locale} {point.explainer}</div><div class='note'>{point.note2}</div>",
							},
						},
						{
							data: data3,
							zIndex: 4,
							type: 'mappoint',
							colorAxis: true,
							allAreas: false,
							name: 'Blocked by Courts',
							color: 'rgba(0,71,157,0.5)',
							showInLegend: true,
							tooltip: {
								pointFormat:
									"<h2 style='font-size:16px;'><b>{point.locale}</b>, {point.ST}</h2><div class='explainer'>{point.locale} {point.explainer}</div><div class='note'>{point.note2}</div>",
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
							},
							tooltip: {
								pointFormat:
									"<h2><b>{point.stateName}</b> — {point.valueName} {point.yearPassed}</h2><div class='explainer'>{point.stateName} {point.explainer}</div><div class='note'>{point.note}</div>",
							},
						},
					],
				});
			}
			function renderLabel() {
				let label = this.renderer
					.label(
						'<b>TheTrevorProject.org/CTMap</b><br>Last Updated: <i>' +
							updatedDate +
							'</i>'
					)
					.css({
						color: '#003B4A',
						fontSize: '9px',
					})
					.attr({
						padding: 0,
						align: 'left',
					})
					.add();

				label.align(
					Highcharts.extend(label.getBBox(), {
						align: 'right',
						textAlign: 'right',
						useHTML: true,
						x: -10, // offset
						verticalAlign: 'bottom',
						y: yoffset, // offset
					}),
					null,
					'spacingBox'
				);
			}

			createChart();
		});

		let formatDate = function (timestamp) {
			let date = new Date(timestamp);
			let months = [
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
		};
	});
};

export default renderer;
