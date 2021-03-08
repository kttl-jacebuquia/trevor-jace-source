/* https://codepen.io/jsilff/pen/ZEpdjqB */
import $ from 'jquery';
import proj4 from 'proj4';
import Highcharts from 'highcharts/highmaps';
import mapData from '@highcharts/map-collection/countries/us/us-all.geo.json';
// Load required modules
require('highcharts/modules/data').default(Highcharts);
require('highcharts/modules/map').default(Highcharts);
require('highcharts/modules/exporting').default(Highcharts);
require('highcharts/modules/offline-exporting').default(Highcharts);
require('highcharts/modules/pattern-fill').default(Highcharts);
window.proj4 = window.proj4 || proj4;

/*
Fixme: Do not hardcode keys
Fixme: Get elements via arguments, do not use hardcoded id selectors
 */
export default function () {
	const regulationPattern = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAIAAAC0Ujn1AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAARhJREFUeNqs1MkKwjAQBuD2t2rBi6KIeih48f3fxbMIggvuKMW11gFFSrSTmdg55JDlYxLa3x+OJl5OlYBB1KXRE1R8voxny+wMdyx5PBabvcSlnfP1zpi0dLQ7xtPV1upSv+frTUdb9TxXRDM640rpnzrvKmhDt7pUgacp0mnsNOtW902HlXK31Zgs1tSLRD/EJ8lOkNvvtWthlUbh3yFxqfx7knxEuiPdVHiSL+rYT9M0O1WI/noJ/JwVvgzjkgBm7R8397t2041TYPZFnZbcJdHoBqqcVCUwtHkmzxkU5X7rKNA19KBYN5tiUOWkSg9UOamqQJWTqgSGKidVCQy3/1iSBHDLB4kON1eiw9m1ruIfl9/zFGAA/6QAGrgarkQAAAAASUVORK5CYII=';
	const introducedPattern = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAIAAAC0Ujn1AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAANxJREFUeNq01ssOwiAQBdBCWttqXFgf0YX//1kuXPhKNDGpj1qCEE21BCiFmbtkcXIDyQyEcx4Zci1v+/MlckuaJOvFnFLSnFA4d/bvGmkvV6U09P1Zhbsa+lXX2+Mp3FVp4W52BxC3RcO6IjHgu2laY7iSRnIljeSKxhTJFY0pkmubIYFuN+3tdtAhro0OdI10uKunQVwNDeWqNKDbomHdHw3ufmkMV66CPB2I/4PjkHpUPTYyYYyB9/3cBLH8nrzd8TBfFpMYw11NC6eh6ud2073cUZY1rshbgAEADd+9Bu55XrUAAAAASUVORK5CYII=';
	let updatedDate, data1 = [],
		data2 = [], data3 = [], chart,
		align, verticalAlign, floating, layout, tooltipW;

	if ($(window).width() > 560) {
		align = 'left';
		verticalAlign = 'bottom';
		floating = true;
		layout = 'vertical';
		tooltipW = 550;
	} else {
		align = 'center';
		verticalAlign = 'top';
		floating = false;
		layout = 'horizontal';
		tooltipW = $(window).width() - 60;
	}

	const statesData = Highcharts.data({
		googleSpreadsheetKey: '1L0pIBS2QJOKz1nMkHzo_ZvdB8oOYkXVEPKHpm2kswDo',
		googleSpreadsheetWorksheet: 1,
		parsed: function (columns) {
			$.each(columns[0], function (i, code) {
				var legType = columns[2][i];
				data1.push({
					code: "us-" + code.toLowerCase(),
					valueName: columns[2][i],
					stateName: columns[1][i],
					note: columns[4][i],
					id: "us-" + code.toLowerCase(),
				});
				if (legType === "Protected") {
					data1[i].value = 2;
					data1[i].explainer = 'protects LGBTQ young people from the dangers of conversion therapy.';
				} else if (legType === "Introduced") {
					data1[i].value = 1;
					data1[i].explainer = 'has legislation introduced in the state house that would protect LGBTQ young people from conversion therapy, if it passes.';
				} else if (legType === "Partially Protected") {
					data1[i].value = 3;
					data1[i].explainer = 'partially protects LGBTQ young people from conversion therapy.';
				}
				if (columns[2][i] == null) {
					data1[i].valueName = "No Legislation";
					data1[i].value = 0;
					data1[i].explainer = 'offers no state-wide protections for LGBTQ young people and they can be subjected to conversion therapy.';
				}
				if (columns[3][i] != null) {
					data1[i].yearPassed = "in " + columns[3][i];
				}
			});
			var updated = data1[52]['note'];
			updatedDate = formatDate(updated);
			complete: createChart();
		},
		error: function () {
			$('#container').html('<div class="loading">' +
				'<i class="icon-frown icon-large"></i> ' +
				'Error loading data; please try again or check back later.' +
				'</div>');
		},
	});
	const municiplaityData = Highcharts.data({
		googleSpreadsheetKey: '1L0pIBS2QJOKz1nMkHzo_ZvdB8oOYkXVEPKHpm2kswDo',
		googleSpreadsheetWorksheet: 2,
		parsed: function (columns2) {
			$.each(columns2[0], function (i, code) {
				if (columns2[2][i] === 'Local Protections') {
					data2.push({
						lat: columns2[11][i],
						lon: columns2[12][i],
						ST: columns2[0][i],
						locale: columns2[1][i],
						note2: columns2[4][i],
						explainer: "protects LGBTQ young people who live in the community from the dangers of conversion therapy.",
					});
				}
				if (columns2[2][i] === 'Blocked by Courts') {
					data3.push({
						lat: columns2[11][i],
						lon: columns2[12][i],
						ST: columns2[0][i],
						locale: columns2[1][i],
						note2: columns2[4][i],
						explainer: "has laws to protect LGBTQ young people from the dangers of conversion therapy but they are currently not in effect by order of the courts.",
					});
				}

			});
			complete: createChart()
		},
		error: function () {
			$('#container').html('<div class="loading">' +
				'<i class="icon-frown icon-large"></i> ' +
				'Error loading data from Google Spreadsheets' +
				'</div>');
		},
	});

	function createChart() {
		chart = $('#container').highcharts('Map', {
			title: {
				text: ''
			},
			credits: {
				enabled: false
			},
			chart: {
				backgroundColor: 'transparent',
				padding: 0,
				animation: false,
				events: {
					render: renderLabel
				},
				style: {
					fontFamily: 'Manrope',
				}
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
							fontWeight: "bold",
						}
					},
				}
			},
			mapNavigation: {
				enabled: true
			},
			legend: {
				margin: 0,
				title: {
					style: {
						color: '#003A48',
						fontWeight: "bold",
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
				dataClasses: [{
					from: 2,
					to: 2,
					name: 'Protected',
					color: '#D3DDE2',
				}, {
					from: 3,
					to: 3,
					name: 'Partially Protected',
					color: {
						pattern: {
							image: regulationPattern,
							width: 6,
							height: 6
						},
					},
				}, {
					from: 1,
					to: 1,
					name: 'Introduced',
					color: {
						pattern: {
							image: introducedPattern,
							width: 6,
							height: 6
						},
					},
				}, {
					from: 0,
					to: 0,
					color: '#FFF',
					name: 'No Legislation'
				},],
			},
			tooltip: {
				backgroundColor: '#003A48',
				borderWidth: 0,
				borderColor: '#777',
				padding: 15,
				borderRadius: 10,
				style: {
					fontSize: "16px",
					color: "white",
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
							window.open('https://docs.google.com/spreadsheets/d/1L0pIBS2QJOKz1nMkHzo_ZvdB8oOYkXVEPKHpm2kswDo/view')
						},
						text: 'View Data in Table View'
					}
				},
				buttons: {
					contextButton: {
						menuItems: ['tabular', 'separator', 'downloadPNG', 'downloadSVG', 'downloadPDF',]
					}
				},
				chartOptions: {
					chart: {
						width: 1200,
						height: 630,
						style: {
							fontFamily: 'Helvetica',
						}
					},
				},
			},
			series: [{
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
						pointFormat: '{point.locale}, {point.ST}<br>' +
							'<div style="font-size:.8em;"><div class="explainer">{point.locale} {point.explainer}</div><div class="note">{point.note2}</div></div>',
					},
				}, {
					data: data3,
					zIndex: 4,
					type: 'mappoint',
					colorAxis: true,
					allAreas: false,
					name: 'Blocked by Courts',
					color: '#FFD215',
					showInLegend: true,
					tooltip: {
						pointFormat: '{point.locale}, {point.ST}<br>' +
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
							color: 'rgba(0,0,0,0.1)'
						}
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
						pointFormat: '{point.stateName} — {point.valueName} {point.yearPassed}<div style="font-size:.8em;"><div class="explainer">{point.stateName} {point.explainer}</div><div class="note">{point.note}</div></div>'
					},
				},
			],
		});
	}

	function renderLabel() {
		const label = this.renderer.label("<b>TheTrevorProject.org/CTMap</b><br>Last Updated: <i>" + updatedDate + "</i>")
			.css({
				'color': '#003B4A',
			})
			.attr({
				'padding': 0,
				'font-size': '0.8em',
				'text-align': 'right',
			})
			.add();

		label.align(Highcharts.extend(label.getBBox(), {
			align: 'right',
			textAlign: 'right',
			useHTML: true,
			x: 0, // offset
			verticalAlign: 'bottom',
			y: 0, // offset
		}), null, 'spacingBox');
	}
}

function formatDate(timestamp) {
	const date = new Date(timestamp);
	const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
	return months[date.getMonth()] + ' ' + date.getDate() + ', ' + date.getFullYear();
}
